<?php

/************************************************************************
 * This script combines functions from the Inbound Shipment API to
 * create a shipment to be sent to Amazon.
 ************************************************************************/

// Initialize files
require_once(__DIR__ . '/GetPrepInstructionsForSKU.php');
require_once(__DIR__ . '/CreateInboundShipmentPlan.php');
require_once(__DIR__ . '/CreateInboundShipment.php');
require_once(__DIR__ . '/UpdateInboundShipment.php');
require_once(__DIR__ . '/PutTransportContent.php');

// Cache URL 
$urlShip = "https://script.google.com/macros/s/AKfycbxBN9iOFmN5fJH5_iEPwEMK36a98SX7xFF4bfHaBfD0y29Ff7zN/exec";

// Parse XML file and create member array
$itemsXML = file_get_contents($urlShip);
$items = new SimpleXMLElement($itemsXML);

$memberArray = array();
$skuArray = array();
foreach ($items->Member as $member) {
    $memberArray['member'][] = array(
        "SellerSKU"=>(string)$member->SellerSKU,
        "Quantity"=>(string)$member->Quantity,
    );
    $skuArray[] = (string)$member->SellerSKU;
}

/****************************************************************
* Call GetPrepInstructionsForSKU to retrieve prep instructions
* for all items to be included in shipment.
****************************************************************/

$i = 0;
// Chunk $skuArray into 50-item pieces
$chunkedSKUs = array_chunk($skuArray, 50);

// Pass chunks through GetPrepInstructionsForSKU
foreach($chunkedSKUs as $chunk) {
    $parameters = array (
        'SellerId' => MERCHANT_ID,
        'SellerSKUList' => array('Id' => $chunk),
        'ShipToCountryCode' => 'US'
    );

    $requestPrep = new FBAInboundServiceMWS_Model_GetPrepInstructionsForSKURequest($parameters);
    $xmlPrep = invokeGetPrepInstructionsForSKU($service, $requestPrep);
    unset($parameters);
    $prep = new SimpleXMLElement($xmlPrep);

    // Add prep instructions to member array
    foreach ($prep->GetPrepInstructionsForSKUResult->SKUPrepInstructionsList->SKUPrepInstructions as $instructions) {
        foreach ($instructions->PrepInstructionList->PrepInstruction as $instruction) {
            $memberArray['member'][$i]['PrepDetailsList']['member'][] = array(
                'PrepInstruction' => (string)$instruction,
                'PrepOwner' => 'AMAZON'
            );
        }
        $i++;
    }
}
    
/****************************************************************
* Call CreateInboundShipmentPlan to create shipment plans
* and create ShipmentId's and Destinations for each shipment.
****************************************************************/

// Create address array to be passed into parameters
$ShipFromAddress = array (
    'Name' => 'KLAS1000',
    'AddressLine1' => '51 N Pecos Rd #103',
    'City' => 'Las Vegas',
    'StateOrProvinceCode' => 'NV',
    'PostalCode' => '89101',
    'CountryCode' => 'US'
);

// Initialize counter variable and shipment array
$n = 0;
$shipmentArray = array();
$shipmentSKU = array();

// Chunk $memberArray into 200-item pieces
$chunkedMember = array_chunk($memberArray, 200);

foreach($chunkedMember as $key => $chunk) {
    // Enter parameters to be passed into CreateInboundShipmentPlan
    $parameters = array (
        'SellerId' => MERCHANT_ID,
        'LabelPrepPreference' => 'SELLER_LABEL',
        'ShipFromAddress' => $ShipFromAddress,
        'InboundShipmentPlanRequestItems' => array('member' => $chunk[$key])
    );

    // Create new Inbound Shipment Plan $request
    $requestPlan = new FBAInboundServiceMWS_Model_CreateInboundShipmentPlanRequest($parameters);
    unset($parameters);
    $xmlPlan = invokeCreateInboundShipmentPlan($service, $requestPlan);
    $plan = new SimpleXMLElement($xmlPlan);

    // Send plan to Amazon and cache shipment information
    $shipments = $plan->CreateInboundShipmentPlanResult->InboundShipmentPlans;
    $nameDate = date('n/j/y g:i A');
    foreach($shipments->member as $member) {
        $n++;
        $shipmentArray[] = array(
            'Destination' => (string)$member->DestinationFulfillmentCenterId,
            'ShipmentId' => (string)$member->ShipmentId,
            'ShipmentName' => 'FBA (' . $nameDate . ") - " . $n
        );
        $shipmentId = (string)$member->ShipmentId;
        foreach($member->Items->member as $item) {
            $shipmentSKU[$shipmentId][] = (string)$item->SellerSKU;
        }
    } 
}

/****************************************************************
* Call CreateInboundShipment to create shipments for each unique
* combination of Destination and LabelPrepType
****************************************************************/

//Initialize arrays for shipment filtering
$destinations = array();
$skipShips = array();

foreach($shipmentArray as $shipment) {

    // Filter out redundant Destinations so those shipments
    // are never created
    $shipmentId = $shipment['ShipmentId'];
    $shipDest = $shipment['Destination'];
    if (in_array($shipDest, $destinations)) {
        $skipShips[] = $shipmentId;
        continue;
    }
    $destinations[] = $shipDest;

    // Filter item array to only include items from this shipment
    $shipmentItems = array();
    foreach($shipmentSKU[$shipmentId] as $sku) {
        $item = array_filter($memberArray['member'], function ($var) use ($sku) {
            return ($var['SellerSKU'] == $sku);
        });
        $shipmentItems[] = $item[array_keys($item)[0]];
    }

    // Rename Quantity to QuantityShipped
    $shipmentItems = array_map(function($shipItems) {
        return array(
            'SellerSKU' => $shipItems['SellerSKU'],
            'QuantityShipped' => $shipItems['Quantity'],
            'PrepDetailsList' => $shipItems['PrepDetailsList']
        );
    }, $shipmentItems);

    // Enter parameters to be passed into CreateInboundShipment
    $parameters = array (
        'SellerId' => MERCHANT_ID,
        'ShipmentId' => $shipmentId,
        'InboundShipmentHeader' => array(
            'ShipmentName' => $shipment['ShipmentName'],
            'ShipFromAddress' => $ShipFromAddress,
            'DestinationFulfillmentCenterId' => $shipDest,
            'ShipmentStatus' => 'WORKING',
            'LabelPrepPreference' => 'SELLER_LABEL',
	    'IntendedBoxContentsSource' => 'FEED'
        ),
        'InboundShipmentItems' => array('member' => $shipmentItems)
    );
    
    // Send parameters to Amazon to create shipment
    $requestShip = new FBAInboundServiceMWS_Model_CreateInboundShipmentRequest($parameters);
    unset($parameters);
    $xmlShip = invokeCreateInboundShipment($service, $requestShip);
}


/*************************************************************
*  Call UpdateInboundShipment to merge duplicate combinations
*  of Destination and LabelPrepType into single shipments.
*
*  tested using sandbox: http://sandbox.onlinephpfunctions.com/code/9bc0ac26f13595b077854234b13d0d0538021b4a
*************************************************************/

$mergedShipments = array();
foreach($skipShips as $shipmentId) {
    
    // Find parent of skipped shipment
    $key = array_search($skip['Destination'], array_column($shipmentArray, 'Destination'));
    $parent = $shipmentArray[$key]['ShipmentId'];
    $child = $skip['ShipmentId'];

    // Merge child with parent in $shipmentSKU array
    $shipmentSKU[$parent] = array_merge($shipmentSKU[$parent], $shipmentSKU[$child]);
    unset($shipmentSKU[$child]);

    // Add parent shipment to $mergedShipments
    if(!in_array($shipmentArray[$key], $mergedShipments)) {
        $mergedShipments[] = $shipmentArray[$key];
    }
}

foreach($mergedShipments as $shipment) {

    // Create array of updated shipment items
    $shipmentId = $shipment['ShipmentId'];
    $shipmentItems = array();
    foreach($shipmentSKU[$shipmentId] as $sku) {
        $item = array_filter($memberArray['member'], function ($var) use ($sku) {
            return ($var['SellerSKU'] == $sku);
        });
        $shipmentItems[] = $item[array_keys($item)[0]];
    }

    // Rename Quantity to QuantityShipped
    $shipmentItems = array_map(function($shipItems) {
        return array(
            'SellerSKU' => $shipItems['SellerSKU'],
            'QuantityShipped' => $shipItems['Quantity'],
            'PrepDetailsList' => $shipItems['PrepDetailsList']
        );
    }, $shipmentItems);

    // Enter parameters to be passed into UpdateInboundShipment
    $parameters = array (
        'SellerId' => MERCHANT_ID,
        'ShipmentId' => $shipmentId,
        'InboundShipmentHeader' => array(
            'ShipmentName' => $shipment['ShipmentName'],
            'ShipFromAddress' => $ShipFromAddress,
            'DestinationFulfillmentCenterId' => $shipment['Destination'],
            'ShipmentStatus' => 'WORKING',
            'LabelPrepPreference' => 'SELLER_LABEL',
        ),
        'InboundShipmentItems' => array('member' => $shipmentItems)
    );

    // Pass updated shipment information to Amazon
    $requestUpdate = new FBAInboundServiceMWS_Model_UpdateInboundShipmentRequest($parameters);
    unset($parameters);
    $xmlUpdate = invokeUpdateInboundShipment($service, $requestUpdate);
}

/*************************************************************
*  Call PutTransportContent to add dimensions to all items in
*  each shipment.
*************************************************************/

// Create dimension array of all items from XML data
$memberDimensionArray = array();
foreach ($items->Member as $member) {
    $memberDimensionArray[(string)$member->SellerSKU] = array(
        'Weight' => array(
            'Value'=>(string)$member->Dimensions->Weight,
            'Unit' => 'pounds'
        ),
        'Dimensions' => array(
            'Length'=>(string)$member->Dimensions->Length,
            'Width'=>(string)$member->Dimensions->Width,
            'Height'=>(string)$member->Dimensions->Height,
            'Unit' => 'inches'
        )
    );
}

foreach($shipmentArray as $shipment) {

    // Create array of dimensions in shipment
    $shipmentId = $shipment['ShipmentId'];
    $shipmentDimensions = array();
    foreach($shipmentSKU[$shipmentId] as $sku) {
        $shipmentDimensions[] = $memberDimensionArray[$sku];
    }

    // Enter parameters to be passed into PutTransportContent
    $parameters = array (
        'SellerId' => MERCHANT_ID,
        'ShipmentId' => $shipment['ShipmentId'],
        'IsPartnered' => 'true',
        'ShipmentType' => 'SP',
        'TransportDetails' => array(
            'PartneredSmallParcelData' => array(
                'CarrierName' => 'UNITED_PARCEL_SERVICE_INC',
                'PackageList' => array( 'member' => $shipmentDimensions)
            )
        )
    );

    // Send dimensions to Amazon
    $requestPut = new FBAInboundServiceMWS_Model_PutTransportContentRequest($parameters);
    unset($parameters);
    $xmlPut = invokePutTransportContent($service, $requestPut);
}
$shipJSON = json_encode($shipmentSKU);
file_put_contents("shipID.json", $shipJSON);

echo "Success! Shipments have been created. Go to SellerCentral to view shipments and print labels.";
?>
