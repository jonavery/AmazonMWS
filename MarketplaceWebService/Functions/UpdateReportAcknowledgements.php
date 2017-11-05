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
 * Update Report Acknowledgement status 
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
                                    
/**
  * Update Report Acknowledgements Action
  * The UpdateReportAcknowledgements operation updates the acknowledged status of one or more reports.
  *   
  * @param MarketplaceWebService_Interface $service instance of MarketplaceWebService_Interface
  * @param mixed $request MarketplaceWebService_Model_UpdateReportAcknowledgements or array of parameters
  */
  function invokeUpdateReportAcknowledgements(MarketplaceWebService_Interface $service, $request) 
  {
      try {
              $response = $service->updateReportAcknowledgements($request);
              
                echo ("Service Response\n");
                echo ("=============================================================================\n");

                echo("        UpdateReportAcknowledgementsResponse\n");
                if ($response->isSetUpdateReportAcknowledgementsResult()) { 
                    echo("            UpdateReportAcknowledgementsResult\n");
                    $updateReportAcknowledgementsResult = $response->getUpdateReportAcknowledgementsResult();
                    if ($updateReportAcknowledgementsResult->isSetCount()) 
                    {
                        echo("                Count\n");
                        echo("                    " . $updateReportAcknowledgementsResult->getCount() . "\n");
                    }
                    $reportInfoList = $updateReportAcknowledgementsResult->getReportInfo();
                    foreach ($reportInfoList as $reportInfo) {
                        echo("                ReportInfo\n");
                        if ($reportInfo->isSetReportId()) 
                        {
                            echo("                    ReportId\n");
                            echo("                        " . $reportInfo->getReportId() . "\n");
                        }
                        if ($reportInfo->isSetReportType()) 
                        {
                            echo("                    ReportType\n");
                            echo("                        " . $reportInfo->getReportType() . "\n");
                        }
                        if ($reportInfo->isSetReportRequestId()) 
                        {
                            echo("                    ReportRequestId\n");
                            echo("                        " . $reportInfo->getReportRequestId() . "\n");
                        }
                        if ($reportInfo->isSetAvailableDate()) 
                        {
                            echo("                    AvailableDate\n");
                            echo("                        " . $reportInfo->getAvailableDate()->format(DATE_FORMAT) . "\n");
                        }
                        if ($reportInfo->isSetAcknowledged()) 
                        {
                            echo("                    Acknowledged\n");
                            echo("                        " . $reportInfo->getAcknowledged() . "\n");
                        }
                        if ($reportInfo->isSetAcknowledgedDate()) 
                        {
                            echo("                    AcknowledgedDate\n");
                            echo("                        " . $reportInfo->getAcknowledgedDate()->format(DATE_FORMAT) . "\n");
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
                                                                    
