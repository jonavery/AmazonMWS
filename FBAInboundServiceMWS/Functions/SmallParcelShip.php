<?php
/************************************************************************
 * This script combines functions from the Inbound Shipment API
 * with Klasrun-specific functions to create a shipment to be sent to Amazon.
 ************************************************************************/


/*************************************************************
*  Call PutTransportContent to add dimensions to all items in
*  each shipment.
*************************************************************/

function spPutTransport($memberDimensionArray, $items, $service) {
    // Create dimension array of all items from XML data
    $memberDimensionArray = array();
    foreach ($items->Member as $member) {
        $memberDimensionArray[(string)$member->ShipmentId][(string)$member->SellerSKU] = array(
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

    foreach ($memberDimensionArray as $key => $member) {
        $shipmentId = $key;

        // Create array of dimensions in shipment
        $shipmentDimensions = array();
        foreach ($member as $value) {
            $shipmentDimensions[] = $value;
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
                    'PackageList' => array( 'member' => $shipmentDimensions)
                )
            )
        );

        // Send dimensions to Amazon
        $requestPut = new FBAInboundServiceMWS_Model_PutTransportContentRequest($parameters);
        unset($parameters);
        $xmlPut = invokePutTransportContent($service, $requestPut);
    }
}
