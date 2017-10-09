<?php

/***********************************************************
 * GetCurrentListings.php calls Amazon and gets a report of
 * all items currently listed for sale by Klasrun.
 **********************************************************/

require_once('RequestReport.php'); 
$parameters = array(
    'ReportType' => '_GET_MERCHANT_LISTINGS_DATA_LITE_',
    'SellerId' => MERCHANT_ID,
    'MarketplaceIdList' => $marketplaceIdArray
);
$requestReport = new MarketplaceWebService_Model_RequestReportRequest($parameters);
$reportRequestId = invokeRequestReport($service, $requestReport);


