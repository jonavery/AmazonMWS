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

// Cache throttling parameter.
$requestCount = 0;
 
// Construct parameters to be sent to Amazon
$parameters = array
(
    'SellerId' => MERCHANT_ID,
    'LastUpdatedAfter' => $updatedAfter,
    'LastUpdatedBefore' => $updatedBefore,
    'ShipmentStatusList' => array('member' => $statusList)
);

// Format parameters into MWS request and send to Amazon
$requestShip = new FBAInboundServiceMWS_Model_ListInboundShipmentsRequest($parameters);
unset($parameters);
$shipmentXML = invokeListInboundShipments($service, $requestShip);
$requestCount++;

// Parse the new XML document.
$shipments = new SimpleXMLElement($shipmentXML);
$shipmentArray = array();

// Create array of all shipments.
foreach ($shipments->ListInboundShipmentsResult->ShipmentData->member as $member) 
{
    $shipmentArray[] = array
    (
        "ShipmentId"=>(string)$member->ShipmentId,
        "ShipmentStatus"=>(string)$member->ShipmentStatus,
        "ShipmentName"=>(string)$member->ShipmentName
    );
}

// Save token and run through ListInboundShipmentsByNextToken until
// it does not return a token.
$token = (string)$shipments->ListInboundShipmentsResult->NextToken;
$parameters = array ('SellerId' => MERCHANT_ID, 'NextToken' => $token);
$requestShipToken = new FBAInboundServiceMWS_Model_ListInboundShipmentsByNextTokenRequest($parameters);
unset($parameters);

while ($token != null) 
{
    $shipmentXML = invokeListInboundShipmentsByNextToken($service, $requestShipToken);
    $requestCount++;
    $shipments = new SimpleXMLElement($shipmentXML);
    foreach ($shipments->ListInboundShipmentsByNextTokenResult->ShipmentData->member as $member) {
        // Create array of all shipments.
        $shipmentArray[] = array
        (
            "ShipmentId"=>(string)$member->ShipmentId,
            "ShipmentStatus"=>(string)$member->ShipmentStatus,
            "ShipmentName"=>(string)$member->ShipmentName
        );
    }
    $token = (string)$shipments->ListInboundShipmentsByNextTokenResult->NextToken;
    $parameters = array ('SellerId' => MERCHANT_ID, 'NextToken' => $token);
    $requestShipToken = new FBAInboundServiceMWS_Model_ListInboundShipmentsByNextTokenRequest($parameters);
    unset($parameters);
            
    // Sleep for required time to avoid throttling.
    $end = microtime(true);
    if ($requestCount > 29 && ($end - $start) < 500000) {
        usleep(500000 - ($end - $start));
    }
    $start = microtime(true);
}

/************************************************************************
 * Parse each item contained in each shipment, caching all important information.
 * After this is done, run the function again using the token
 * until the fuction stops returning a token.
 ***********************************************************************/

$itemArray = array();
foreach($shipmentArray as $key => &$shipment) {
    $shipmentId = $shipment['ShipmentId'];
    $name = $shipment['ShipmentName'];
    $updated = date('m/d/Y', mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));
    
    // Cache throttling parameter.
    $requestCount = 0;
    
    // Sleep for required time to avoid throttling.
    $end = microtime(true);
    if (($end - $start) < 500000) {
        usleep(500000 - ($end - $start));
    }
    $start = microtime(true);

    // Format parameters to be sent to Amazon
    $parameters = array
    (
        'SellerId' => MERCHANT_ID,
        'LastUpdatedAfter' => $updatedAfter,
        'LastUpdatedBefore' => $updatedBefore,
        'ShipmentId' => $shipmentId
    );

    // Format parameters into MWS request and send to Amazon
    $requestItem = new FBAInboundServiceMWS_Model_ListInboundShipmentItemsRequest($parameters);
    unset($parameters);
    $itemsXML = invokeListInboundShipmentItems($service, $requestItem);
    $requestCount++;

    // Parse the new XML document.
    $items = new SimpleXMLElement($itemsXML);
    foreach ($items->ListInboundShipmentItemsResult->ItemData->member as $member) 
    {
        $itemArray[] = array
        (
            "ShipmentId" => $shipmentId,
            "SellerSKU" => (string)$member->SellerSKU,
            "Status" => $shipment['ShipmentStatus'],
            "Created" => substr($name, 5, strcspn($name," ",5)),
            "Updated" => $updated,
            "QuantityShipped" => (string)$member->QuantityShipped,
            "QuantityReceived" => (string)$member->QuantityReceived
        );
    }

    // Save token and run through ListInboundShipmentItemsByNextToken until
    // it does not return a token.
    $token = (string)$items->ListInboundShipmentItemsResult->NextToken;
    $parameters = array('SellerId' => MERCHANT_ID, 'NextToken' => $token);
    $requestItemToken = new FBAInboundServiceMWS_Model_ListInboundShipmentItemsByNextTokenRequest($parameters);
    unset($parameters);

    while ($token != null) 
    {
        $itemsXML = invokeListInboundShipmentItemsByNextToken($service, $requestItemToken);
        $requestCount++;
        $items = new SimpleXMLElement($shipmentXML);
        foreach ($items->ListInboundShipmentItemsByNextTokenResult->ItemData->member as $member) 
        {
            // Create array of all shipments.
            $itemArray[] = array
            (
                "ShipmentId" => $shipmentId,
                "SellerSKU" => (string)$member->SellerSKU,
                "Status" => $shipment['ShipmentStatus'],
                "Created" => substr($name, 5, strcspn($name," ",5)),
                "Updated" => $updated,
                "QuantityShipped" => (string)$member->QuantityShipped,
                "QuantityReceived" => (string)$member->QuantityReceived
            );
        }
        $token = (string)$items->ListInboundShipmentsResult->NextToken;
        $parameters = array('SellerId' => MERCHANT_ID, 'NextToken' => $token);
        $requestItemToken = new FBAInboundServiceMWS_Model_ListInboundShipmentItemsByNextTokenRequest($parameters);
        unset($parameters);


        // Sleep for required time to avoid throttling.
        $end = microtime(true);
        if (($end - $start) < 500000) {
            usleep(500000 - ($end - $start));
        }
        $start = microtime(true);
    }
}

$itemJSON = json_encode($itemArray);
file_put_contents("FBA.json", $itemJSON);

echo "Success! FBA.json has been created. Now run 'Import FBA Shipment Report' to move it into the sheet.";
?>
