<?php
/** 
 *  PHP Version 5
 *
 *  @category    Amazon
 *  @package     MarketplaceWebService
 *  @copyright   Copyright 2009 Amazon Technologies, Inc.
 *  @link        http://aws.amazon.com
 *  @license     http://aws.amazon.com/apache2.0  Apache License, Version 2.0
 *  @version     2009-01-01
 */
/******************************************************************************* 
 *  Marketplace Web Service PHP5 Library
 *  Generated: Thu May 07 13:07:36 PDT 2009
 * 
 */

/**
 * Cancel Reports and returns canceled report count
 */

include_once ('../../includes.php'); 

// IMPORTANT: Uncomment the approiate line for the country you wish to
// sell in:
// United States:
$serviceUrl = "https://mws.amazonservices.com";

$config = array (
  'ServiceURL' => $serviceUrl,
  'ProxyHost' => null,
  'ProxyPort' => -1,
  'MaxErrorRetry' => 3,
);

/************************************************************************
 * Instantiate Implementation of MarketplaceWebService
 * 
 * AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY constants 
 * are defined in the ../../includes.php located in the same 
 * directory as this sample
 ***********************************************************************/
 $service = new MarketplaceWebService_Client(
     AWS_ACCESS_KEY_ID, 
     AWS_SECRET_ACCESS_KEY, 
     $config,
     APPLICATION_NAME,
     APPLICATION_VERSION);

//invokeCancelReportRequests($service, $request);
 
/**
  * Cancel Report Requests Action
  * cancels report requests - by default all of the submissions of the
  * last 30 days that have not started processing
  *   
  * @param MarketplaceWebService_Interface $service instance of MarketplaceWebService_Interface
  * @param mixed $request MarketplaceWebService_Model_CancelFeedSubmissions or array of parameters
  */
  function invokeCancelReportRequests(MarketplaceWebService_Interface $service, $request) 
  {
      try {
              $response = $service->cancelReportRequests($request);
              
                echo ("Service Response\n");
                echo ("=============================================================================\n");

                echo("        CancelReportRequestsResponse\n");
                if ($response->isSetCancelReportRequestsResult()) { 
                    echo("            CancelReportRequestsResult\n");
                    $cancelReportRequestsResult = $response->getCancelReportRequestsResult();
                    if ($cancelReportRequestsResult->isSetCount()) 
                    {
                        echo("                Count\n");
                        echo("                    " . $cancelReportRequestsResult->getCount() . "\n");
                    }
                    $reportRequestInfoList = $cancelReportRequestsResult->getReportRequestInfoList();
                    foreach ($reportRequestInfoList as $reportRequestInfo) {
                        echo("                ReportRequestInfo\n");
                        if ($reportRequestInfo->isSetReportRequestId()) 
                        {
                            echo("                    ReportRequestId\n");
                            echo("                        " . $reportRequestInfo->getReportRequestId() . "\n");
                        }
                        if ($reportRequestInfo->isSetReportType()) 
                        {
                            echo("                    ReportType\n");
                            echo("                        " . $reportRequestInfo->getReportType() . "\n");
                        }
                        if ($reportRequestInfo->isSetStartDate()) 
                        {
                            echo("                    StartDate\n");
                            echo("                        " . $reportRequestInfo->getStartDate()->format(DATE_FORMAT) . "\n");
                        }
                        if ($reportRequestInfo->isSetEndDate()) 
                        {
                            echo("                    EndDate\n");
                            echo("                        " . $reportRequestInfo->getEndDate()->format(DATE_FORMAT) . "\n");
                        }
                        if ($reportRequestInfo->isSetSubmittedDate()) 
                        {
                            echo("                    SubmittedDate\n");
                            echo("                        " . $reportRequestInfo->getSubmittedDate()->format(DATE_FORMAT) . "\n");
                        }
                        if ($reportRequestInfo->isSetReportProcessingStatus()) 
                        {
                            echo("                    ReportProcessingStatus\n");
                            echo("                        " . $reportRequestInfo->getReportProcessingStatus() . "\n");
                        }
                    }
                } 
                if ($response->isSetResponseMetadata()) { 
                    echo("            ResponseMetadata\n");
                    $responseMetadata = $response->getResponseMetadata();
                    if ($responseMetadata->isSetRequestId()) 
                    {
                        echo("                RequestId\n");
                        echo("                    " . $responseMetadata->getRequestId() . "\n");
                    }
                } 

                echo("            ResponseHeaderMetadata: " . $response->getResponseHeaderMetadata() . "\n");
     } catch (MarketplaceWebService_Exception $ex) {
         echo("Caught Exception: " . $ex->getMessage() . "\n");
         echo("Response Status Code: " . $ex->getStatusCode() . "\n");
         echo("Error Code: " . $ex->getErrorCode() . "\n");
         echo("Error Type: " . $ex->getErrorType() . "\n");
         echo("Request ID: " . $ex->getRequestId() . "\n");
         echo("XML: " . $ex->getXML() . "\n");
         echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
     }
 }
 
?>
