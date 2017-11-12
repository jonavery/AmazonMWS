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
 * Get Report Request List 
 */

include_once (__DIR__ . '/../../includes.php'); 

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
 
/**
  * Get Report List Action 
  * returns a list of reports; by default the most recent ten reports,
  * regardless of their acknowledgement status
  *   
  * @param MarketplaceWebService_Interface $service instance of MarketplaceWebService_Interface
  * @param mixed $request MarketplaceWebService_Model_GetReportList or array of parameters
  * @param string [$id] ReportRequestId of the report the user would like to check on
  */
  function invokeGetReportRequestList(MarketplaceWebService_Interface $service, $request, $id = Null) 
  {
      try {
              $response = $service->getReportRequestList($request);
              
                echo ("Service Response\n");
                echo ("=============================================================================\n");

                echo("        GetReportRequestListResponse\n");
                if ($response->isSetGetReportRequestListResult()) { 
                    echo("            GetReportRequestListResult\n");
                    $getReportRequestListResult = $response->getGetReportRequestListResult();
                    if ($getReportRequestListResult->isSetNextToken()) 
                    {
                        echo("                NextToken\n");
                        echo("                    " . $getReportRequestListResult->getNextToken() . "\n");
                    }
                    if ($getReportRequestListResult->isSetHasNext()) 
                    {
                        echo("                HasNext\n");
                        echo("                    " . $getReportRequestListResult->getHasNext() . "\n");
                    }
                    $reportRequestInfoList = $getReportRequestListResult->getReportRequestInfoList();
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
                        // add start
                        if ($reportRequestInfo->isSetScheduled()) 
                        {
                            echo("                    Scheduled\n");
                            echo("                        " . $reportRequestInfo->getScheduled() . "\n");
                        }
                        // add end
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
                        // add start
                        if ($reportRequestInfo->isSetGeneratedReportId()) 
                        {
                            echo("                    GeneratedReportId\n");
                            echo("                        " . $reportRequestInfo->getGeneratedReportId() . "\n");
                            if ($id == $reportRequestInfo->getReportRequestId()) 
                            {
                                $generatedId = $reportRequestInfo->getGeneratedReportId();
                            }
                        }
                        if ($reportRequestInfo->isSetStartedProcessingDate()) 
                        {
                            echo("                    StartedProcessingDate\n");
                            echo("                        " . $reportRequestInfo->getStartedProcessingDate()->format(DATE_FORMAT) . "\n");
                        }
                        if ($reportRequestInfo->isSetCompletedDate()) 
                        {
                            echo("                    CompletedDate\n");
                            echo("                        " . $reportRequestInfo->getCompletedDate()->format(DATE_FORMAT) . "\n");
                        }
                        // add end
                        if ($id == $reportRequestInfo->getReportRequestId()) 
                        {
                            $status = $reportRequestInfo->getReportProcessingStatus();
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
                return array ($status, $generatedId);
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
