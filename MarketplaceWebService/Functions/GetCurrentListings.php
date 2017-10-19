<?php

/***********************************************************
 * GetCurrentListings.php calls Amazon and gets a report of
 * all items currently listed for sale by Klasrun.
 **********************************************************/

// Initialize needed files.
require_once(__DIR__ . '/RequestReport.php'); 
require_once(__DIR__ . '/GetReportRequestList.php');
require_once(__DIR__ . '/GetReport.php');


// Request a listings data report.
$parameters = array(
    'ReportType' => '_GET_AFN_INVENTORY_DATA_',
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
$filename = 'report_'.date('Y-m-d_H:i:s').'txt';
$handle = fopen($filename, 'x+');

$requestGetReport = new MarketplaceWebService_Model_GetReportRequest();
$requestGetReport->setMerchant(MERCHANT_ID);
$requestGetReport->setReport($handle);
$requestGetReport->setReportId($reportStatus[1]);

echo invokeGetReport($service, $requestGetReport);
fwrite($handle, $response);
fclose($handle);
