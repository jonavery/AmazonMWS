<?php
/************************************************************************
 * This script combines functions from the Inbound Shipment API to
 * create a shipment of small items to be sent to Amazon.
 ************************************************************************/


/*************************************************************
*  Call PutTransportContent to add dimensions to all items in
*  each shipment.
*************************************************************/


function electronicPutTransport($shipmentArray, $items, $service) {
foreach ($shipmentArray as $shipment) {
    $shipmentId = $shipment['ShipmentId'];

    // Create placeholder dimensions for shipment box
    $shipmentDimensions = array(
        'Weight' => array(
            'Value'=>1,
            'Unit' => 'pounds'
        ),
        'Dimensions' => array(
            'Length'=>1,
            'Width'=>1,
            'Height'=>1,
            'Unit' => 'inches'
        )
    );

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
