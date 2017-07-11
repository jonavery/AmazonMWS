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
//    echo 'ID: ', $member->ShipmentId, PHP_EOL,
//            'Status: ', $member->ShipmentStatus, PHP_EOL;
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
$requestShipToken = new FBAInboundServiceMWS_Model_ListInboundShipmentsByNextTokenRequest($parameters);
unset($parameters);

while ($token != null) 
{
    $requestShipToken->setNextToken($token);
    $shipmentXML = invokeListInboundShipmentsByNextToken($service, $requestShipToken);
    $shipments = new SimpleXMLElement($shipmentXML);
    foreach ($shipments->ListInboundShipmentsByNextTokenResult->ShipmentData->member as $member) {
//        echo 'ID: ', $member->ShipmentId, PHP_EOL,
//            'Status: ', $member->ShipmentStatus, PHP_EOL;
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
/*
// Destroy variables to get a clean slate.
unset($service); unset($request);

// @TODO: Use shipmentId for filtering shipment items
$inboundShipments = array('FBA4BH80BS','FBA4BQ3V9K','FBA4C2XLRT','FBA4DWLRWN');
$shipmentId = new FBAInboundServiceMWS_Model_ShipmentIdList();
$shipmentId->setMember($inboundShipments);

$parameters = array
(
    'SellerId' => MERCHANT_ID,
    'LastUpdatedAfter' => $updatedAfter,
    'LastUpdatedBefore' => $updatedBefore,

);

$requestItem = new FBAInboundServiceMWS_Model_ListInboundShipmentItemsRequest($parameters);
unset($parameters);
$request->setShipmentId($shipmentId);
$request->setLastUpdatedAfter($updatedAfter);
$request->setLastUpdatedBefore($updatedBefore);
$request->setSellerId(MERCHANT_ID);

echo is_array($request);
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
$requestItemToken = new FBAInboundServiceMWS_Model_ListInboundShipmentItemsByNextTokenRequest($parameters);
unset($parameters);
$request->setSellerId(MERCHANT_ID);

while ($token != null) 
{
    $request->setNextToken($token);
    $itemsXML = invokeListInboundShipmentItemsByNextToken($service, $requestItemToken);
    $items = new SimpleXMLElement($shipmentXML);
    foreach ($shipments->ListInboundShipmentItemsByNextTokenResult->ItemData->member as $member) 
    {
//        echo 'ID: ', $member->ShipmentId, PHP_EOL,
//            'Status: ', $member->ShipmentStatus, PHP_EOL;
        // Create array of all shipments.
        $itemArray[] = array
        (
            "ShipmentId"=>(string)$member->ShipmentId,
            "ShipmentStatus"=>(string)$member->ShipmentStatus
        );
    }
    $token = array("NextToken" => (string)$shipments->ListInboundShipmentsResult->NextToken);
}
 */

?>
