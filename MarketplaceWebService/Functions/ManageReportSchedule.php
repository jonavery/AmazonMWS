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
 */

/**
 * Manage Report Schedule
 * Creates, updates, or deletes a report schedule
 * for a given report type, such as order reports in particular.
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

//invokeManageReportSchedule($service, $request);
                                                                                            
/**
  * Manage Report Schedule Action
  * Creates, updates, or deletes a report schedule
  * for a given report type, such as order reports in particular.
  *   
  * @param MarketplaceWebService_Interface $service instance of MarketplaceWebService_Interface
  * @param mixed $request MarketplaceWebService_Model_ManageReportSchedule or array of parameters
  */
  function invokeManageReportSchedule(MarketplaceWebService_Interface $service, $request) 
  {
      try {
              $response = $service->manageReportSchedule($request);
              
                echo ("Service Response\n");
                echo ("=============================================================================\n");

                echo("        ManageReportScheduleResponse\n");
                if ($response->isSetManageReportScheduleResult()) { 
                    echo("            ManageReportScheduleResult\n");
                    $manageReportScheduleResult = $response->getManageReportScheduleResult();
                    if ($manageReportScheduleResult->isSetCount()) 
                    {
                        echo("                Count\n");
                        echo("                    " . $manageReportScheduleResult->getCount() . "\n");
                    }
                    $reportScheduleList = $manageReportScheduleResult->getReportScheduleList();
                    foreach ($reportScheduleList as $reportSchedule) {
                        echo("                ReportSchedule\n");
                        if ($reportSchedule->isSetReportType()) 
                        {
                            echo("                    ReportType\n");
                            echo("                        " . $reportSchedule->getReportType() . "\n");
                        }
                        if ($reportSchedule->isSetSchedule()) 
                        {
                            echo("                    Schedule\n");
                            echo("                        " . $reportSchedule->getSchedule() . "\n");
                        }
                        if ($reportSchedule->isSetScheduledDate()) 
                        {
                            echo("                    ScheduledDate\n");
                            echo("                        " . $reportSchedule->getScheduledDate()->format(DATE_FORMAT) . "\n");
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
            
