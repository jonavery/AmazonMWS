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

/************************************************************************
 * Get a list of all inbound shipments from Amazon and
 * cache the list in an array.
 ***********************************************************************/
// Initialize and run ListInboundShipments.
require 'ListInboundShipments.php';
// Set status array and timeframe criteria for filtering shipments
$shipmentStatusList = new FBAInboundServiceMWS_Model_ShipmentStatusList();
$shipmentStatusList->setMember(array('WORKING','SHIPPED','IN_TRANSIT','DELIVERED','CHECKED_IN','RECEIVING','CLOSED','CANCELLED','DELETED','ERROR'));
$updatedAfter = date('Y-m-d', mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));
$updatedBefore = date('Y-m-d');
 
// Pass filter criteria into $request array
$request->setShipmentStatusList($shipmentStatusList);
$request->setLastUpdatedAfter($updatedAfter);
$request->setLastUpdatedBefore($updatedBefore);
$shipmentXML = invokeListInboundShipments($service, $request);

// Parse the new XML document.
$shipments = new SimpleXMLElement($shipmentXML);
$shipmentArray = array();
print_r($shipments);
foreach ($shipments->ListInboundShipmentsResult->ShipmentData->member as $member) {
//    echo 'ID: ', $member->ShipmentId, PHP_EOL,
//            'Status: ', $member->ShipmentStatus, PHP_EOL;
    // Create array of all shipments.
    $shipmentArray[] = array(
        "ShipmentId"=>$member->ShipmentId,
        "ShipmentStatus"=>$member->ShipmentStatus
    );
}

// Destroy variables to get a clean slate.
unset($service); unset($request);

// Save token and run through ListInboundShipmentsByNextToken until
// it does not return a token.
$token = array("NextToken" => (string)$shipments->ListInboundShipmentsResult->NextToken);
require 'ListInboundShipmentsByNextToken.php';

while ($token != null) {
    $request->setNextToken($token);
    print_r($request);
    $shipmentXML = invokeListInboundShipmentsByNextToken($service, $request);
    $shipments = new SimpleXMLElement($shipmentXML);
    foreach ($shipments->ListInboundShipmentsResult->ShipmentData->member as $member) {
//        echo 'ID: ', $member->ShipmentId, PHP_EOL,
//            'Status: ', $member->ShipmentStatus, PHP_EOL;
        // Create array of all shipments.
        $shipmentArray[] = array(
            "ShipmentId"=>$member->ShipmentId,
            "ShipmentStatus"=>$member->ShipmentStatus
        );
    }
    $token = array("NextToken" => (string)$shipments->ListInboundShipmentsResult->NextToken);
}

/************************************************************************
 * Parse each item contained in each shipment, caching all important information.
 * After this is done, run the function again using the token
 * until the fuction stops returning a token.
 ***********************************************************************/
// Destroy variables to get a clean slate.
unset($service); unset($request);

// Initialize and run ListInboundShipmentItems.
require 'ListInboundShipmentItems.php';

// @TODO: Use shipmentId for filtering shipment items
//$inboundShipments = array('FBA4BH80BS','FBA4BQ3V9K','FBA4C2XLRT','FBA4DWLRWN');
//$shipmentId = new FBAInboundServiceMWS_Model_ShipmentIdList();
//$shipmentId->setmember($inboundShipments);
//$request->setShipmentId($shipmentId);

$updatedAfter = date('Y-m-d', mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));
$updatedBefore = date('Y-m-d');

$request->setLastUpdatedAfter($updatedAfter);
$request->setLastUpdatedBefore($updatedBefore);

echo is_array($request);
$itemsXML = invokeListInboundShipmentItems($service, $request);

// Parse the new XML document.
$items = new SimpleXMLElement($itemsXML);
$itemArray = array();
foreach ($items->ListInboundShipmentItemsResult->ItemData->member as $member) {
    echo 'SKU: ', $member->SellerSKU, PHP_EOL,
            'ASIN: ', $member->FulfillmentNetworkSKU, PHP_EOL,
            'QShipped: ', $member->QuantityShipped, PHP_EOL,
            'QReceived: ', $member->QuantityReceived, PHP_EOL;
    $itemArray[] = array(
        "SellerSKU"=>$member->SellerSKU,
        "QuantityShipped"=>$member->QuantityShipped,
        "QuantityReceived"=>$member->QuantityReceived
    );
}

// @TODO: Save token and run through ListInboundShipmentItemsByNextToken until
// @TODO: it does not return a token.
