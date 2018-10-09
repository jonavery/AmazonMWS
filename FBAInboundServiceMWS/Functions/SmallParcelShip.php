<?php
/************************************************************************
 * This script combines functions from the Inbound Shipment API
 * with Klasrun-specific functions to create a shipment to be sent to Amazon.
 ************************************************************************/


/*************************************************************
*  Call PutTransportContent to add dimensions to all items in
*  each shipment.
*************************************************************/

function spPutTransport($member, $key, $service) {
    
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
