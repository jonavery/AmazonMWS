<?php

/************************************************************************
 * This script combines functions from the Inbound Shipment API to
 * update information on a shipment after it is sent to Amazon.
 ************************************************************************/
// Initialize files
require_once(__DIR__ . '/../../MarketplaceWebService/Functions/SubmitFeed.php');
$serviceMWS = $service;
require_once(__DIR__ . '/GetUniquePackageLabels.php');
require_once(__DIR__ . '/UpdateInboundShipment.php');

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
invokeSubmitFeed($serviceMWS, $requestFeed);
@fclose($feedHandle);

/*****************************************************************************
*  Call GetUniquePackageLabels to retrieve shipment label  images from Amazon.
*****************************************************************************/

// Initialize label script
$items = new SimpleXMLElement($feed);

$requestCount = 0;
$itemArray = [];
foreach ($items->Message as $message) {
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

    print_r($parameters);
    
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

    // Cache pdf label as a base64-encoded data string
    $label64 = $label->GetUniquePackageLabelsResult->TransportDocument->PdfDocument; 
    // Get File content from txt file
    $pdf_base64_handler = fopen($pdf_base64,'r');
    $pdf_content = fread ($pdf_base64_handler,filesize($pdf_base64));
    fclose ($pdf_base64_handler);
    // Decode pdf content
    $pdf_decoded = base64_decode ($pdf_content);
    // Write data back to pdf file
    $pdf = fopen ($shipmentId . '-labels.pdf','w');
    fwrite ($pdf,$pdf_decoded);
    // Close output file
    fclose ($pdf);
}

/*************************************************************
*  Call UpdateInboundShipment to change shipment status to
*  'SHIPPED' for all shipments picked up by UPS.
*************************************************************/

// Initialize update script

// $requestUpdate = new FBAInboundServiceMWS_Model_UpdateInboundShipmentRequest($parameters);
// unset($parameters);
// $xmlUpdate = invokeUpdateInboundShipment($service, $requestUpdate);

?>
