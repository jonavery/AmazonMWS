<?php

/************************************************************************
 * This script combines functions from the Inbound Shipment API to
 * update information on a shipment after it is sent to Amazon.
 ************************************************************************/

/*************************************************************
*  Call SubmitFeed to send information to Amazon
*  about all shipped items.
*************************************************************/

// Cache URL containing feed XML file
$urlFeed = "https://script.google.com/macros/s/AKfycbxozOUDpHwr0-szEtn2J8luT7D7cImDevIjSRyZf72ODKGy0H0O/exec"; 

// Call SubmitFeed to send shipped item information to Amazon
require_once(__DIR__ . '/../../MarketplaceWebService/Functions/SubmitFeed.php');
$feed = file_get_contents($urlShip);
$requestFeed = makeRequest($feed);
$requestFeed->setFeedType('_POST_FBA_INBOUND_CARTON_CONTENTS_');
invokeSubmitFeed($service, $requestFeed);
@fclose($feedHandle);
unset($request);


/*************************************************************
*  Call GetUniquePackageLabels to retrieve shipment label
*  images from Amazon.
*************************************************************/

// Initialize label script
require_once(__DIR__ . '/GetUniquePackageLabels.php');


/*************************************************************
*  Call UpdateInboundShipment to change shipment status to
*  'SHIPPED' for all shipments picked up by UPS.
*************************************************************/

// Initialize update script
require_once(__DIR__ . '/UpdateInboundShipment.php');

$requestUpdate = new FBAInboundServiceMWS_Model_UpdateInboundShipmentRequest($parameters);
unset($parameters);
$xmlUpdate = invokeUpdateInboundShipment($service, $requestUpdate);

?>
