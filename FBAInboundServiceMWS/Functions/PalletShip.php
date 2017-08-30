<?php

/************************************************************************
 * This script combines functions from the Inbound Shipment API to
 * create a shipment to be sent to Amazon.
 ************************************************************************/

// Initialize files
require_once(__DIR__ . '/../../MarketplaceWebService/Functions/SubmitFeed.php');
$serviceMWS = $service;
require_once(__DIR__ . '/GetPrepInstructionsForSKU.php');
require_once(__DIR__ . '/CreateInboundShipmentPlan.php');
require_once(__DIR__ . '/CreateInboundShipment.php');
require_once(__DIR__ . '/UpdateInboundShipment.php');
require_once(__DIR__ . '/PutTransportContent.php');
require_once(__DIR__ . '/EstimateTransportRequest.php');
require_once(__DIR__ . '/GetTransportContent.php');
require_once(__DIR__ . '/ConfirmTransportRequest.php');
require_once(__DIR__ . '/GetUniquePackageLabels.php');

// Cache URL 
$urlShip = "https://script.google.com/macros/s/AKfycbxBN9iOFmN5fJH5_iEPwEMK36a98SX7xFF4bfHaBfD0y29Ff7zN/exec";

// Parse XML file and create member array
$itemsXML = file_get_contents($urlShip);
$items = new SimpleXMLElement($itemsXML);

$memberArray = array();
$skuArray = array();
foreach ($items->Member as $member) {
    $memberArray['member'][] = array(
        "SellerSKU"=>(string)$member->SellerSKU,
        "Quantity"=>(string)$member->Quantity,
    );
    $skuArray[] = (string)$member->SellerSKU;
}

/****************************************************************
* Call GetPrepInstructionsForSKU to retrieve prep instructions
* for all items to be included in shipment.
****************************************************************/

$i = 0;
// Chunk $skuArray into 50-item pieces
$chunkedSKUs = array_chunk($skuArray, 50);

// Pass chunks through GetPrepInstructionsForSKU
foreach($chunkedSKUs as $chunk) {
    $parameters = array (
        'SellerId' => MERCHANT_ID,
        'SellerSKUList' => array('Id' => $chunk),
        'ShipToCountryCode' => 'US'
    );

    $requestPrep = new FBAInboundServiceMWS_Model_GetPrepInstructionsForSKURequest($parameters);
    $xmlPrep = invokeGetPrepInstructionsForSKU($service, $requestPrep);
    unset($parameters);
    $prep = new SimpleXMLElement($xmlPrep);

    // Add prep instructions to member array
    foreach ($prep->GetPrepInstructionsForSKUResult->SKUPrepInstructionsList->SKUPrepInstructions as $instructions) {
        foreach ($instructions->PrepInstructionList->PrepInstruction as $instruction) {
            $memberArray['member'][$i]['PrepDetailsList']['member'][] = array(
                'PrepInstruction' => (string)$instruction,
                'PrepOwner' => 'AMAZON'
            );
        }
        $i++;
    }
}
    
/****************************************************************
* Call CreateInboundShipmentPlan to create shipment plans
* and create ShipmentId's and Destinations for each shipment.
****************************************************************/

// Create address array to be passed into parameters
$ShipFromAddress = array (
    'Name' => 'KLAS1000',
    'AddressLine1' => '61 N Pecos Rd #104',
    'City' => 'Las Vegas',
    'StateOrProvinceCode' => 'NV',
    'PostalCode' => '89101',
    'CountryCode' => 'US'
);

// Initialize counter variable and shipment array
$n = 0;
$shipmentArray = array();
$shipmentSKU = array();

// Chunk $memberArray into 200-item pieces
$chunkedMember = array_chunk($memberArray, 200);

foreach($chunkedMember as $key => $chunk) {
    // Enter parameters to be passed into CreateInboundShipmentPlan
    $parameters = array (
        'SellerId' => MERCHANT_ID,
        'LabelPrepPreference' => 'SELLER_LABEL',
        'ShipFromAddress' => $ShipFromAddress,
        'InboundShipmentPlanRequestItems' => array('member' => $chunk[$key])
    );

    // Create new Inbound Shipment Plan $request
    $requestPlan = new FBAInboundServiceMWS_Model_CreateInboundShipmentPlanRequest($parameters);
    unset($parameters);
    $xmlPlan = invokeCreateInboundShipmentPlan($service, $requestPlan);
    $plan = new SimpleXMLElement($xmlPlan);

    // Send plan to Amazon and cache shipment information
    $shipments = $plan->CreateInboundShipmentPlanResult->InboundShipmentPlans;
    $nameDate = date('n/j/y g:i A');
    foreach($shipments->member as $member) {
        $n++;
        $shipmentArray[] = array(
            'Destination' => (string)$member->DestinationFulfillmentCenterId,
            'ShipmentId' => (string)$member->ShipmentId,
            'ShipmentName' => 'FBA (' . $nameDate . ") - " . $n
        );
        $shipmentId = (string)$member->ShipmentId;
        foreach($member->Items->member as $item) {
            $shipmentSKU[$shipmentId][] = (string)$item->SellerSKU;
        }
    } 
}

/****************************************************************
* Call CreateInboundShipment to create shipments for each unique
* combination of Destination and LabelPrepType
****************************************************************/

// // Initialize arrays for shipment filtering
// $destinations = array();
// $skipShips = array();

foreach($shipmentArray as $shipment) {

    // Filter out redundant Destinations so those shipments
    // are never created
    $shipmentId = $shipment['ShipmentId'];
    $shipDest = $shipment['Destination'];
//    if (in_array($shipDest, $destinations)) {
//        $skipShips[] = array (
//            'ShipmentId' => $shipmentId,
//            'Destination' => $shipDest
//        );
//        continue;
//    }
//    $destinations[] = $shipDest;

    // Filter item array to only include items from this shipment
    $shipmentItems = array();
    foreach($shipmentSKU[$shipmentId] as $sku) {
        $item = array_filter($memberArray['member'], function ($var) use ($sku) {
            return ($var['SellerSKU'] == $sku);
        });
        $shipmentItems[] = $item[array_keys($item)[0]];
    }

    // Rename Quantity to QuantityShipped
    $shipmentItems = array_map(function($shipItems) {
        return array(
            'SellerSKU' => $shipItems['SellerSKU'],
            'QuantityShipped' => $shipItems['Quantity'],
            'PrepDetailsList' => $shipItems['PrepDetailsList']
        );
    }, $shipmentItems);

    // Enter parameters to be passed into CreateInboundShipment
    $parameters = array (
        'SellerId' => MERCHANT_ID,
        'ShipmentId' => $shipmentId,
        'InboundShipmentHeader' => array(
            'ShipmentName' => $shipment['ShipmentName'],
            'ShipFromAddress' => $ShipFromAddress,
            'DestinationFulfillmentCenterId' => $shipDest,
            'ShipmentStatus' => 'WORKING',
            'LabelPrepPreference' => 'SELLER_LABEL',
	    'IntendedBoxContentsSource' => 'FEED'
        ),
        'InboundShipmentItems' => array('member' => $shipmentItems)
    );
    
    // Send parameters to Amazon to create shipment
    $requestShip = new FBAInboundServiceMWS_Model_CreateInboundShipmentRequest($parameters);
    unset($parameters);
    $xmlShip = invokeCreateInboundShipment($service, $requestShip);
}


/*************************************************************
*  Call UpdateInboundShipment to merge duplicate combinations
*  of Destination and LabelPrepType into single shipments.
*
*  tested using sandbox: http://sandbox.onlinephpfunctions.com/code/9bc0ac26f13595b077854234b13d0d0538021b4a
*************************************************************/

// $mergedShipments = array();
// foreach($skipShips as $skip) {
//     
//     // Find parent of skipped shipment
//     $key = array_search($skip['Destination'], array_column($shipmentArray, 'Destination'));
//     $parent = $shipmentArray[$key]['ShipmentId'];
//     $child = $skip['ShipmentId'];
// 
//     // Merge child with parent in $shipmentSKU array
//     $shipmentSKU[$parent] = array_merge($shipmentSKU[$parent], $shipmentSKU[$child]);
//     unset($shipmentSKU[$child]);
// 
//     // Add parent shipment to $mergedShipments
//     if(!in_array($shipmentArray[$key], $mergedShipments)) {
//         $mergedShipments[] = $shipmentArray[$key];
//     }
// }
// 
// foreach($mergedShipments as $shipment) { 
//     // Create array of updated shipment items
//     $shipmentId = $shipment['ShipmentId'];
//     $shipmentItems = array();
//     foreach($shipmentSKU[$shipmentId] as $sku) {
//         $item = array_filter($memberArray['member'], function ($var) use ($sku) {
//             return ($var['SellerSKU'] == $sku);
//         });
//         $shipmentItems[] = $item[array_keys($item)[0]];
//     }
// 
//     // Rename Quantity to QuantityShipped
//     $shipmentItems = array_map(function($shipItems) {
//         return array(
//             'SellerSKU' => $shipItems['SellerSKU'],
//             'QuantityShipped' => $shipItems['Quantity'],
//             'PrepDetailsList' => $shipItems['PrepDetailsList']
//         );
//     }, $shipmentItems);
// 
//     // Enter parameters to be passed into UpdateInboundShipment
//     $parameters = array (
//         'SellerId' => MERCHANT_ID,
//         'ShipmentId' => $shipmentId,
//         'InboundShipmentHeader' => array(
//             'ShipmentName' => $shipment['ShipmentName'],
//             'ShipFromAddress' => $ShipFromAddress,
//             'DestinationFulfillmentCenterId' => $shipment['Destination'],
//             'ShipmentStatus' => 'WORKING',
//             'LabelPrepPreference' => 'SELLER_LABEL',
//         ),
//         'InboundShipmentItems' => array('member' => $shipmentItems)
//     );
// 
//     // Pass updated shipment information to Amazon
//     $requestUpdate = new FBAInboundServiceMWS_Model_UpdateInboundShipmentRequest($parameters);
//     unset($parameters);
//     $xmlUpdate = invokeUpdateInboundShipment($service, $requestUpdate);
// }
// 
// Save shipment information as a JSON file
$shipJSON = json_encode($shipmentSKU);
file_put_contents("shipID.json", $shipJSON);

/*************************************************************
*  Call SubmitFeed to send information to Amazon
*  about all shipped items.
*************************************************************/

// Cache URL containing feed XML file
$urlFeed = "https://script.google.com/macros/s/AKfycbxozOUDpHwr0-szEtn2J8luT7D7cImDevIjSRyZf72ODKGy0H0O/exec"; 

// Call SubmitFeed to send shipped item information to Amazon
$feed = file_get_contents($urlFeed);
$requestFeed = makeRequest($feed);
$requestFeed->setFeedType('_POST_FBA_INBOUND_CARTON_CONTENTS_');
ob_start();
invokeSubmitFeed($serviceMWS, $requestFeed);
ob_end_clean();
@fclose($feedHandle);

/*************************************************************
*  Call PutTransportContent to add dimensions to all items in
*  each shipment.
*************************************************************/

// Cache URL 
$urlShip = "https://script.google.com/macros/s/AKfycbxBN9iOFmN5fJH5_iEPwEMK36a98SX7xFF4bfHaBfD0y29Ff7zN/exec";

// Parse XML file and create member array
$itemsXML = file_get_contents($urlShip);
$items = new SimpleXMLElement($itemsXML);

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

    // Calculate total weight, box count, pallet count, and shipping date of shipment
    $totalWeight = 0;
    $boxCount = 0;
    $palletList = array();
    foreach ($member as $value) {
        $totalWeight = (float)$totalWeight + $value['Weight']['Value'];
        $boxCount++;
    }
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
                return '125';
            case $density >= 6:
                return '150';
            case $density >= 5:
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
                'SellerFreightClass' => freightClass('72',$totalWeight),
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

/*************************************************************
*  Call EstimateTransportRequest operation to request that an 
*  estimate be generated for an Amazon-partnered carrier to 
*  ship your inbound shipment.
*************************************************************/
$requestCount = 0;
$itemShip = new SimpleXMLElement($feed);
foreach ($itemShip->Message as $message) {
    // Enter parameters to be passed into EstimateTransportRequest
    $shipmentId = (String)$message->CartonContentsRequest->ShipmentId;
    $parameters = array (
        'SellerId' => MERCHANT_ID,
        'ShipmentId' => $shipmentId
    );
    
    // Sleep for required time to avoid throttling.
    $end = microtime(true);
    if ($requestCount > 29 && ($end - $start) < 500000) {
        usleep(500000 - ($end - $start));
    }
    $start = microtime(true);

    // Call EstimateTransportRequest
    $requestCount++;
    $requestEsti = new FBAInboundServiceMWS_Model_EstimateTransportInputRequest($parameters);
    unset($parameters);
    $xmlEsti = invokeEstimateTransportRequest($service, $requestEsti);
}

/*************************************************************
*  Call GetTransportContent operation to get an estimate for 
*  the cost to ship your shipment with an Amazon-partnered 
*  carrier. The estimate is returned in the PartneredEstimate 
*  response element. Note that the estimate will not be returned 
*  until the TransportStatus value of the inbound shipment is 
*  ESTIMATED, CONFIRMING, or CONFIRMED. Because the 
*  GetTransportContent operation returns TransportStatus values, 
*  you can use this operation to monitor the progress of your 
*  inbound shipment. If a PartneredEstimate value is not yet 
*  available, retry the operation later.
*************************************************************/
$requestCount = 0;
foreach ($itemShip->Message as $message) {
    // Enter parameters to be passed into GetTransportContent
    $shipmentId = (String)$message->CartonContentsRequest->ShipmentId;
    $parameters = array (
        'SellerId' => MERCHANT_ID,
        'ShipmentId' => $shipmentId
    );
    
    // Sleep for required time to avoid throttling.
    $end = microtime(true);
    if ($requestCount > 29 && ($end - $start) < 500000) {
        usleep(500000 - ($end - $start));
    }
    $start = microtime(true);

    // Call GetTransportContent
    $requestCount++;
    $requestGet = new FBAInboundServiceMWS_Model_GetTransportContentRequest($parameters);
    unset($parameters);
    $xmlGet = invokeGetTransportContent($service, $requestGet);
}

/************************************************************
* Call the ConfirmTransportRequest operation to accept the 
* Amazon-partnered shipping estimate, agree to allow Amazon 
* to charge your account for the shipping cost, and request 
* that the Amazon-partnered carrier ship your inbound shipment.
*************************************************************/
/* Commented out to avoid danger of unintentionall incurring charges.
 * Uncomment when reinstating the GetUniquePackageLabels function below.
$requestCount = 0;
foreach ($itemShip->Message as $message) {
    // Enter parameters to be passed into ConfirmTransportRequest
    $shipmentId = (String)$message->CartonContentsRequest->ShipmentId;
    $parameters = array (
        'SellerId' => MERCHANT_ID,
        'ShipmentId' => $shipmentId
    );
    
    // Sleep for required time to avoid throttling.
    $end = microtime(true);
    if ($requestCount > 29 && ($end - $start) < 500000) {
        usleep(500000 - ($end - $start));
    }
    $start = microtime(true);

    // Call ConfirmTransportRequest
    $requestCount++;
    $requestConf = new FBAInboundServiceMWS_Model_ConfirmTransportInputRequest($parameters);
    unset($parameters);
    $xmlConf = invokeConfirmTransportRequest($service, $requestConf);
}
 */

/*************************************************************
*  Call GetUniquePackageLabels to retrieve shipment label
*  images from Amazon.
*************************************************************/
/* Commented out due to inability to get CartonId's working
// Initialize label script
$itemShip = new SimpleXMLElement($feed);

$requestCount = 0;
$itemArray = [];
foreach ($itemShip->Message as $message) {
    $shipmentId = (String)$message->CartonContentsRequest->ShipmentId;
    foreach ($message->CartonContentsRequest->Carton as $carton) {
        $itemArray[$shipmentId][] = (String)$carton->CartonId;
    }

    // Enter parameters to be passed into GetUniquePackageLabels
    $parameters = array(
        'SellerId' => MERCHANT_ID,
        'ShipmentId' => $shipmentId,
        'PageType' => 'PackageLabel_Plain_Paper',
        'PackageLabelsToPrint' => array('member' => $itemArray[$shipmentId])
    );

    // Sleep for required time to avoid throttling.
    $end = microtime(true);
    if ($requestCount > 29 && ($end - $start) < 500000) {
        usleep(500000 - ($end - $start));
    }
    $start = microtime(true);

    // Call GetUniquePackageLabels using the created parameters
    $requestLabel = new FBAInboundServiceMWS_Model_GetUniquePackageLabelsRequest($parameters);
    unset($parameters);
    $xmlLabel = invokeGetUniquePackageLabels($service, $requestLabel);
    $requestCount++;
    $label = new SimpleXMLElement($xmlLabel);

    // Retrive pdf label from xml as a base64-encoded data string
    $label64 = $label->GetUniquePackageLabelsResult->TransportDocument->PdfDocument; 
    // Get file content from txt file
    $pdf_base64_handler = fopen($label64, 'r');
    $pdf_content = fread ($pdf_base64_handler, filesize($label64));
    fclose ($pdf_base64_handler);
    // Decode pdf content from string
    $pdf_decoded = base64_decode ($pdf_content);
    // Write data to pdf file
    $pdf = fopen ($shipmentId . '-labels.pdf','w');
    fwrite ($pdf, $pdf_decoded);
    fclose ($pdf);
}
 */

echo "Success! Shipments and labels have been created. Go to SellerCentral to view shipments and print labels.";
?>
