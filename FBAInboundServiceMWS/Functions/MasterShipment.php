<?php

/************************************************************************
 * This script combines functions from the Inbound Shipment API to
 * create a shipment to be sent to Amazon.
 ************************************************************************/


// Load XML file.
$urlShip = "https://script.google.com/macros/s/AKfycbxBN9iOFmN5fJH5_iEPwEMK36a98SX7xFF4bfHaBfD0y29Ff7zN/exec";
$urlFeed = "https://script.google.com/macros/s/AKfycbxozOUDpHwr0-szEtn2J8luT7D7cImDevIjSRyZf72ODKGy0H0O/exec"; 

// Parse XML file and create member array
$itemsXML = file_get_contents($urlShip);
$items = new SimpleXMLElement($itemsXML);
$memberArray = array();
foreach ($items->Member as $member) {
    $memberArray[] = array(
        "SellerSKU"=>(string)$member->SellerSKU,
        "Quantity"=>(string)$member->Quantity
    )
}

// Enter parameters to be passed into $request
$parameters = array (
    'Merchant' => MERCHANT_ID,
    'LabelPrepPreference' => 'SELLER_LABEL',
    'ShipFromAddress' => array (
        'Name' => 'Kriss Sweeney',
        'AddressLine1' => '51 N Pecos Rd #103',
        'City' => 'Las Vegas',
        'StateOrProvinceCode' => 'NV',
        'PostalCode' => '89101',
        'CountryCode' => 'US'
    ),
    'InboundShipmentPlanRequestItems' => $memberArray
)

require_once('CreateInboundShipmentPlan.php');
$requestPlan = $request;
unset($request);
require_once('CreateInboundShipment.php');
$requestShip = $request;
unset($request);
require_once(__DIR__ . '/../../../MarketplaceWebService/Functions/SubmitFeed.php');
$requestFeed = $request;
unset($request);

invokeCreateInboundShipmentPlan($service, $request);


?>
