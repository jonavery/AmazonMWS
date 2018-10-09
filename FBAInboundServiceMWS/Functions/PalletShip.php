<?php
/************************************************************************
 * This script combines functions from the Inbound Shipment API to
 * create a shipment to be sent to Amazon.
 ************************************************************************/

/*************************************************************
*  Call PutTransportContent to add dimensions to all items in
*  each shipment.
*************************************************************/

// Estimate freight class
function freightClass($height, $weight) {
    $volume = 40 * 48 * $height / 1728;
    $density = $weight/$volume;
    switch (true) {
        case $density >= 50:
            return '50';
        case $density >= 35:
            return '55';
        case $density >= 30:
            return '60';
        case $density >= 22:
            return '65';
        case $density >= 15:
            return '70';
        case $density >= 13:
            return '77.5';
        case $density >= 12:
            return '85';
        case $density >= 10:
            return '92.5';
        case $density >= 9:
            return '100';
        case $density >= 8:
            return '110';
        case $density >= 7:
            return '175';
        case $density >= 4:
            return '200';
        case $density >= 3:
            return '250';
        case $density >= 2:
            return '300';
        case $density >= 1:
            return '400';
        case $density < 1:
            return '500';
        default:
            return '110';
    }
}

function palletPutTransport($member, $key, $totalWeight, $boxCount, $service) {

    // Calculate pallet count, and shipping date of shipment
    $palletList = array();
    $palletCount = ceil($boxCount/6);
    $palletList = array(
        'Dimensions' => array(
            'Unit' => 'inches',
            'Length' => '40',
            'Width' => '48',
            'Height' => '72'
        ),
        'IsStacked' => false,
        'Weight' => array(
            'Unit' => 'pounds',
            'Value' => ($totalWeight/$palletCount)+35
        )
    );
    $shipDate = date('Y-m-d', strtotime(date('Y-m-d') . ' +2 Weekday'));

    // Enter parameters to be passed into PutTransportContent
    $parameters = array (
        'SellerId' => MERCHANT_ID,
        'ShipmentId' => $shipmentId,
        'IsPartnered' => true,
        'ShipmentType' => 'LTL',
        'TransportDetails' => array(
            'PartneredLtlData' => array(
                'Contact' => array(
                    'Name' => 'Kevin Carozza',
                    'Phone' => '(914)217-7622',
                    'Email' => 'klasrunbooks4000@gmail.com',
                    'Fax' => 'n/a'),
                'BoxCount' => $boxCount,
                // 'SellerFreightClass' => freightClass('72',$totalWeight),
                'FreightReadyDate' => $shipDate,
                'PalletList' => array('member' => $palletList),
                'TotalWeight' => array(
                    'Unit' => 'pounds',
                    'Value' => $totalWeight + 35*sizeof($palletList)
                )
            )
        )
    );

    // Send pallet information to Amazon
    $requestPut = new FBAInboundServiceMWS_Model_PutTransportContentRequest($parameters);
    unset($parameters);
    $xmlPut = invokePutTransportContent($service, $requestPut);
}
