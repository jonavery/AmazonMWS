<?php

/************************************************************************
 * This script combines functions from the Inbound Shipment API to
 * create a shipment to be sent to Amazon.
 ************************************************************************/

// Initialize configuration file
require_once(__DIR__ . '/../../MarketplaceWebService/Functions/.config.inc.php');

// Cache URLs 
$urlShip = "https://script.google.com/macros/s/AKfycbxBN9iOFmN5fJH5_iEPwEMK36a98SX7xFF4bfHaBfD0y29Ff7zN/exec";
$urlFeed = "https://script.google.com/macros/s/AKfycbxozOUDpHwr0-szEtn2J8luT7D7cImDevIjSRyZf72ODKGy0H0O/exec"; 

require_once('GetPrepInstructionsForSKU.php');
require_once('CreateInboundShipmentPlan.php');

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
    

// Create address array to be passed into parameters
$ShipFromAddress = array (
    'Name' => 'Kriss Sweeney',
    'AddressLine1' => '51 N Pecos Rd #103',
    'City' => 'Las Vegas',
    'StateOrProvinceCode' => 'NV',
    'PostalCode' => '89101',
    'CountryCode' => 'US'
);

// Initialize counter variable and shipment array
$n = 0;
$shipmentArray = array();

// Chunk $memberArray into 200-item pieces
$chunkedSKUs = array_chunk($skuArray, 200);

foreach($memberChunked as $chunk) {
    // Enter parameters to be passed into CreateInboundShipmentPlan
    $parameters = array (
        'SellerId' => MERCHANT_ID,
        'LabelPrepPreference' => 'SELLER_LABEL',
        'ShipFromAddress' => $ShipFromAddress,
        'InboundShipmentPlanRequestItems' => $chunk
    );

    // Create new Inbound Shipment Plan $request
    $requestPlan = new FBAInboundServiceMWS_Model_CreateInboundShipmentPlanRequest($parameters);
    unset($parameters);
    $xmlPlan = invokeCreateInboundShipmentPlan($service, $requestPlan);
    $plan = new SimpleXMLElement($xmlPlan);

    // Send plan to Amazon and cache shipment information
    $shipments = $plan->CreateInboundShipmentPlanResult->InboundShipmentPlans;
    foreach($shipments->member as $member) {
        $n++;
        $shipmentArray[] = array(
            'Destination' => (string)$member->DestinationFullfillmentCenterId,
            'ShipmentId' => (string)$member->ShipmentId,
            'ShipmentName' => 'FBA (' . date('Y-m-d') . ") - " . $n
        );
    }
}

// Call CreateInboundShipment to create shipments for each unique
// combination of Destination and LabelPrepType

// Enter parameters to be passed into CreateInboundShipment
$parameters = array (
    'SellerId' => MERCHANT_ID,
    'ShipmentId' => $shipmentId,
    'InboundShipmentHeader' => array(
        'ShipmentName' => $shipmentArray[0]['ShipmentName'],
        'ShipFromAddress' => $ShipFromAddress,
        'DestinationFulfillmentCenterId' => $shipmentArray[0]['Destination'],
        'ShipmentStatus' => 'WORKING',
        'LabelPrepPreference' => 'SELLER_LABEL',
    ),
    'InboundShipmentItems' => $memberArray
);

require_once('CreateInboundShipment.php');
$requestShip = new FBAInboundServiceMWS_Model_CreateInboundShipmentRequest($parameters);
unset($parameters);
$xmlShip = invokeCreateInboundShipment($service, $requestShip);
$shipment = new SimpleXMLElement($xmlShip);

// Call PutTransportContent to add dimensions to all items in
// each shipment.

// @TODO: Create dimension array for each shipment.
$memberDimensionArray = array();
foreach ($items->Member as $member) {
    $memberDimensionArray[] = array(
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

// Enter parameters to be passed into PutTransportContent
$parameters = array (
    'SellerId' => MERCHANT_ID,
    'ShipmentId' => $shipmentId,
    'IsPartnered' => 'true',
    'ShipmentType' => 'SP',
    'TransportDetails' => array(
        'PartneredSmallParcelData' => array(
            'CarrierName' => 'UNITED_PARCEL_SERVICE_INC',
            'PackageList' => $memberDimensionArray 
        )
    )
);

// Send dimensions to Amazon
$requestPut = new FBAInboundServiceMWS_Model_PutTransportContentRequest($parameters);
unset($parameters);
$xmlPut = invokePutTransportContent($service, $requestShip);

// Call UpdateInboundShipment to merge duplicate combinations
// of Destination and LabelPrepType into single shipments

$requestUpdate = new FBAInboundServiceMWS_Model_UpdateInboundShipmentRequest($parameters);
unset($parameters);
$xmlUpdate = invokeUpdateInboundShipment($service, $requestUpdate);

require_once(__DIR__ . '/../../MarketplaceWebService/Functions/SubmitFeed.php');
$feed = file_get_contents($urlShip);
$requestFeed = makeRequest($feed);
$requestFeed->setFeedType('_POST_FBA_INBOUND_CARTON_CONTENTS_');
invokeSubmitFeed($service, $requestFeed);
@fclose($feedHandle);
unset($request);
    
require_once('PutTransportContent.php');
$requestPut = $request;
$xmlTransport = invokePutTransportContent($service, $requestPut);
$transport = new SimpleXMLElement($xmlTransport);

?>
