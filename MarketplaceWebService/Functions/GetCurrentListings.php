<?php

/***********************************************************
 * GetCurrentListings.php calls Amazon and gets a report of
 * all items currently listed for sale by Klasrun.
 **********************************************************/

// Initialize needed files.
require_once('RequestReport.php'); 
require_once('GetReportRequestList.php');
require_once('GetReport.php');


// Request a listings data report.
$parameters = array(
    'ReportType' => '_GET_MERCHANT_LISTINGS_DATA_',
);
$requestReport = new MarketplaceWebService_Model_RequestReportRequest($parameters);
$requestReport->setMerchant(MERCHANT_ID);
unset($parameters);
$reportRequestId = invokeRequestReport($service, $requestReport);

// Check the status of the report.
$parameters = array(
    'ReportRequestIdList' => array(
        'Id' => array($reportRequestId)
    )
);
$requestStatus = new MarketplaceWebService_Model_GetReportRequestListRequest($parameters);
$requestStatus->setMerchant(MERCHANT_ID);
unset($parameters);
$reportStatus = invokeGetReportRequestList($service, $requestStatus, $reportRequestId);

// Continue to check status of report until it is finished.
while ($reportStatus[0] != '_DONE_') {
    echo "Waiting for Amazon to process report...\n";
    sleep(45);
    $reportStatus = invokeGetReportRequestList($service, $requestStatus, $reportRequestId);
}
echo "\nReport complete!\n\n";

// Get the completed report from Amazon.
$requestGetReport = new MarketplaceWebService_Model_GetReportRequest();
$requestGetReport->setMerchant(MERCHANT_ID);
$requestGetReport->setReportId($reportStatus[1]);
print_r($requestGetReport);

echo invokeGetReport($service, $requestGetReport);
