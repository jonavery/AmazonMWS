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

// Parse XML file and create member array
$itemsXML = file_get_contents($urlShip);
$items = new SimpleXMLElement($itemsXML);

$memberArray = array();
$skuArray = array();
foreach ($items->Member as $member) {
    $memberArray[] = array(
        'member' => array(
            "SellerSKU"=>(string)$member->SellerSKU,
            "Quantity"=>(string)$member->Quantity,
        )
    );
    $skuArray[] = (string)$member->SellerSKU;
}

$i = 0;
// Chunk $memberArray into 50-item pieces
$chunkedSKUs = array_chunk($skuArray, 50);

// Pass chunks through GetPrepInstructionsForSKU
require_once('GetPrepInstructionsForSKU.php');
foreach($chunkedSKUs as $chunk) {
    $parameters = array (
        'SellerId' => MERCHANT_ID,
        'SellerSKUList' => array('Id' => $chunk),
        'ShipToCountryCode' => 'US'
    );

    $request = new FBAInboundServiceMWS_Model_GetPrepInstructionsForSKURequest($parameters);
    $requestPrep = $request;
    $xmlPrep = invokeGetPrepInstructionsForSKU($service, $requestPrep);
    unset($request, $parameters);
    $prep = new SimpleXMLElement($xmlPrep);

    // Add prep instructions to member array
    foreach ($prep->GetPrepInstructionsForSKUResult->SKUPrepInstructionsList->SKUPrepInstructions as $instructions) {
        foreach ($instructions->PrepInstructionList->PrepInstruction as $instruction) {
            $memberArray[$i]['PrepDetailsList']['PrepDetails'][] = array(
                'PrepInstruction' => $instruction,
                'PrepOwner' => 'AMAZON'
            );
        }
        $i++;
    }
}
    
print_r($memberArray);

// Create address array to be passed into parameters
$ShipFromAddress = array (
    'Name' => 'Kriss Sweeney',
    'AddressLine1' => '51 N Pecos Rd #103',
    'City' => 'Las Vegas',
    'StateOrProvinceCode' => 'NV',
    'PostalCode' => '89101',
    'CountryCode' => 'US'
);

// Enter parameters to be passed into CreateInboundShipmentPlan
$parameters = array (
    'LabelPrepPreference' => 'SELLER_LABEL',
    'ShipFromAddress' => $ShipFromAddress,
    'InboundShipmentPlanRequestItems' => $memberArray
    
);

// print_r($parameters);

require_once('CreateInboundShipmentPlan.php');
$requestPlan = $request;
unset($request, $parameters);
$xmlPlan = invokeCreateInboundShipmentPlan($service, $requestPlan);
$plan = new SimpleXMLElement($xmlPlan);

$shipmentArray = array();
$shipments = $plan->CreateInboundShipmentPlanResult->InboundShipmentPlans;
$n = 0;
foreach($shipments->member as $member) {
    $n++;
    $shipmentArray[] = array(
        'Destination' => $member->DestinationFullfillmentCenterId,
        'ShipmentId' => $member->ShipmentId,
        'ShipmentName' => 'FBA (' . date('Y-m-d') . ") - " . $n
    );
}

// @TODO: Create Google Script to import ShipmentIds into MWS tab.

// Enter parameters to be passed into CreateInboundShipment
$parameters = array (
    'Merchant' => MERCHANT_ID,
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
$requestShip = $request;
unset($request, $parameters);
$xmlShip = invokeCreateInboundShipment($service, $requestShip);
$shipment = new SimpleXMLElement($xmlShip);

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
    'Merchant' => MERCHANT_ID,
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