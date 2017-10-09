<?php

/***********************************************************
 * GetCurrentListings.php calls Amazon and gets a report of
 * all items currently listed for sale by Klasrun.
 **********************************************************/

// Initialize needed files.
require_once('RequestReport.php'); 

// Request a listings data report.
$parameters = array(
    'SellerId' => MERCHANT_ID,
    'ReportType' => '_GET_MERCHANT_LISTINGS_DATA_LITE_',
    'MarketplaceIdList' => $marketplaceIdArray
);
$requestReport = new MarketplaceWebService_Model_RequestReportRequest($parameters);
unset($parameters);
$reportRequestId = invokeRequestReport($service, $requestReport);
print_r($reportRequestId);
exit;

require_once('GetReportRequestList.php');
// Check the status of the report.
$parameters = array(
    'SellerId' => MERCHANT_ID,
    'ReportRequestIdList' => array(
        'Id' => array($reportRequestId)
    )
);
$requestStatus = new MarketplaceWebService_Model_GetReportRequestListRequest($parameters);
unset($parameters);
$reportStatus = invokeGetReportRequestList($service, $requestStatus, $reportRequestId);

// Continue to check status of report until it is finished.
while ($reportStatus != '_DONE_') {
    echo "Waiting for Amazon to process report...\n";
    sleep(45);
    $reportStatus = invokeGetReportRequestList($service, $request, $reportRequestId);
}
echo "\nReport complete!\n\n";
