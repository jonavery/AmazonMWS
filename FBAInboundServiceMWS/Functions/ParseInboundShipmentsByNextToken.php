<?php

/************************************************************************
 * Get a list of all inbound shipments from Amazon and
 * cache the list in an array.
 ***********************************************************************/

//require_once 'ParseInboundShipments.php';
$token = "_SMS_U2hpcG1lbnRTdGF0dXNlczpDSEVDS0VEX0lOLElOX1RSQU5TSVQsU0hJUFBFRCxXT1JLSU5HLENBTkNFTExFRCxDTE9TRUQsREVMRVRFRCxSRUNFSVZJTkcsREVMSVZFUkVELEVSUk9SfFRva2VuVHlwZTpMSVNUX0JZX1NUQVRVU3xQYWdlU3RhcnRJbmRleDo1MHxQYWdlU2l6ZTo1MHxVcGRhdGVkQWZ0ZXJEYXRlOjE0OTEyNjQwMDAwMDB8VXBkYXRlZEJlZm9yZURhdGU6MTQ5Mzg1NjAwMDAwMHxNZXJjaGFudEN1c3RvbWVySWQ6QTNGQTlXM0NESVdSOEY=";

while ($token != null) {
    require_once 'ListInboundShipmentsByNextToken.php';
    $request->setNextToken($token);
    echo print_r($request);
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
    $token = $shipments->ListInboundShipmentsResult->NextToken;
}