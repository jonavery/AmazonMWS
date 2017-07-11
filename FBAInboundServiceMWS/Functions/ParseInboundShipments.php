<?php

/************************************************************************
 * This script combines ListInboundShipments, ListInboundShipmentItems,
 * and their respective token-based sister functions to generate
 * entries with: 
 *  ShipmentId
 *  ShipmentStatus
 *  SellerSKU
 *  QuantityShipped
 *  QuantityReceived
 *
 * and send these entries into a JSON file.
 ***********************************************************************/
// Initialize files
require_once(__DIR__ . '/ListInboundShipments.php');
require_once(__DIR__ . '/ListInboundShipmentsByNextToken.php');
require_once(__DIR__ . '/ListInboundShipmentItems.php');
require_once(__DIR__ . '/ListInboundShipmentItemsByNextToken.php');

/************************************************************************
 * Get a list of all inbound shipments from Amazon and
 * cache the list in an array.
 ***********************************************************************/

// Set status array and timeframe criteria for filtering shipments
$statusList = array('WORKING','SHIPPED','IN_TRANSIT','DELIVERED','CHECKED_IN','RECEIVING','CLOSED','CANCELLED','DELETED','ERROR');
$updatedAfter = date('Y-m-d', mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));
$updatedBefore = date('Y-m-d');
 
$parameters = array
(
    'SellerId' => MERCHANT_ID,
    'LastUpdatedAfter' => $updatedAfter,
    'LastUpdatedBefore' => $updatedBefore,
    'ShipmentStatusList' => array('member' => $statusList)
);

$requestShip = new FBAInboundServiceMWS_Model_ListInboundShipmentsRequest($parameters);
unset($parameters);
$shipmentXML = invokeListInboundShipments($service, $requestShip);

// Parse the new XML document.
$shipments = new SimpleXMLElement($shipmentXML);
$shipmentArray = array();
foreach ($shipments->ListInboundShipmentsResult->ShipmentData->member as $member) 
{
    echo 'ID: ', $member->ShipmentId, PHP_EOL,
            'Status: ', $member->ShipmentStatus, PHP_EOL;
    // Create array of all shipments.
    $shipmentArray[] = array
    (
        "ShipmentId"=>(string)$member->ShipmentId,
        "ShipmentStatus"=>(string)$member->ShipmentStatus
    );
}

// Destroy variables to get a clean slate.
unset($service); unset($request);

// Save token and run through ListInboundShipmentsByNextToken until
// it does not return a token.
$token = array("NextToken" => (string)$shipments->ListInboundShipmentsResult->NextToken);
$parameters = array('SellerId' => MERCHANT_ID);
$requestShipToken = new FBAInboundServiceMWS_Model_ListInboundShipmentsByNextTokenRequest($parameters);
unset($parameters);

while ($token != null) 
{
    $requestShipToken->setNextToken($token);
    $shipmentXML = invokeListInboundShipmentsByNextToken($service, $requestShipToken);
    $shipments = new SimpleXMLElement($shipmentXML);
    foreach ($shipments->ListInboundShipmentsByNextTokenResult->ShipmentData->member as $member) {
        echo 'ID: ', $member->ShipmentId, PHP_EOL,
            'Status: ', $member->ShipmentStatus, PHP_EOL;
        // Create array of all shipments.
        $shipmentArray[] = array
        (
            "ShipmentId"=>(string)$member->ShipmentId,
            "ShipmentStatus"=>(string)$member->ShipmentStatus
        );
    }
    $token = array("NextToken" => (string)$shipments->ListInboundShipmentsByNextTokenResult->NextToken);
}

/************************************************************************
 * Parse each item contained in each shipment, caching all important information.
 * After this is done, run the function again using the token
 * until the fuction stops returning a token.
 ***********************************************************************/

foreach($shipmentArray as $key => &$shipment) {
    $shipmentId = $shipment['ShipmentId'];

    $parameters = array
    (
        'SellerId' => MERCHANT_ID,
        'LastUpdatedAfter' => $updatedAfter,
        'LastUpdatedBefore' => $updatedBefore,
        'ShipmentId' => $shipmentId
    );

    $requestItem = new FBAInboundServiceMWS_Model_ListInboundShipmentItemsRequest($parameters);
    unset($parameters);
    $itemsXML = invokeListInboundShipmentItems($service, $requestItem);

    // Parse the new XML document.
    $items = new SimpleXMLElement($itemsXML);
    $itemArray = array();
    foreach ($items->ListInboundShipmentItemsResult->ItemData->member as $member) 
    {
        echo 'SKU: ', $member->SellerSKU, PHP_EOL,
                'ASIN: ', $member->FulfillmentNetworkSKU, PHP_EOL,
                'QShipped: ', $member->QuantityShipped, PHP_EOL,
                'QReceived: ', $member->QuantityReceived, PHP_EOL;
        $itemArray[] = array
        (
            "SellerSKU"=>(string)$member->SellerSKU,
            "QuantityShipped"=>(string)$member->QuantityShipped,
            "QuantityReceived"=>(string)$member->QuantityReceived
        );
    }

    // Save token and run through ListInboundShipmentItemsByNextToken until
    // it does not return a token.
    $token = array("NextToken" => (string)$shipments->ListInboundShipmentItemsResult->NextToken);
    $parameters = array('SellerId' => MERCHANT_ID);
    $requestItemToken = new FBAInboundServiceMWS_Model_ListInboundShipmentItemsByNextTokenRequest($parameters);
    unset($parameters);

    while ($token != null) 
    {
        $request->setNextToken($token);
        $itemsXML = invokeListInboundShipmentItemsByNextToken($service, $requestItemToken);
        $items = new SimpleXMLElement($shipmentXML);
        foreach ($shipments->ListInboundShipmentItemsByNextTokenResult->ItemData->member as $member) 
        {
            echo 'ID: ', $member->ShipmentId, PHP_EOL,
                'Status: ', $member->ShipmentStatus, PHP_EOL;
            // Create array of all shipments.
            $itemArray[] = array
            (
                "ShipmentId"=>(string)$member->ShipmentId,
                "ShipmentStatus"=>(string)$member->ShipmentStatus
            );
        }
        $token = array("NextToken" => (string)$shipments->ListInboundShipmentsResult->NextToken);
    }
}

?>
