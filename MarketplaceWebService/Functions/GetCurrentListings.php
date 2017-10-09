<?php

/***********************************************************
 * GetCurrentListings.php calls Amazon and gets a report of
 * all items currently listed for sale by Klasrun.
 **********************************************************/

// Initialize needed files.
require_once('RequestReport.php'); 
require_once('GetReportRequestList.php');

// Request a listings data report.
$parameters = array(
    'ReportType' => '_GET_MERCHANT_LISTINGS_DATA_LITE_',
    'SellerId' => MERCHANT_ID,
    'MarketplaceIdList' => $marketplaceIdArray
);
$requestReport = new MarketplaceWebService_Model_RequestReportRequest($parameters);
unset($parameters);
$reportRequestId = invokeRequestReport($service, $requestReport);

// Check the status of the report.
$parameters = array(
    'ReportRequestIdList' => array(
        'Id' => array($reportRequestId)
    )
);
$requestStatus = new MarketplaceWebService_Model_GetReportRequestListRequest($parameters);
unset($parameters);
$reportStatus = invokeGetReportRequestList($service, $request);

