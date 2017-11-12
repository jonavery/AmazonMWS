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
 * Returns a list of all feed submissions submitted in the previous 90 days.  
 */
require_once (__DIR__ . '/../../FBAInboundServiceMWS/Functions/../../includes.php');

// IMPORTANT: Uncomment the appropriate line for the country you wish to
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

$parameters = array (
    'Merchant' => MERCHANT_ID,
    'MWSAuthToken' => MWS_AUTH_TOKEN,
#    'SubmittedFromDate' => date('Y-m-d', mktime(0, 0, 0, date("m"), date("d")-2,   date("Y"))),
#    'SubmittedToDate' => date('Y-m-d')
);
$request = new MarketplaceWebService_Model_GetFeedSubmissionListRequest($parameters);
invokeGetFeedSubmissionList($service, $request);
                                                                            
/**
  * Get Feed Submission List Action 
  * returns a list of feed submission identifiers and their associated metadata
  *   
  * @param MarketplaceWebService_Interface $service instance of MarketplaceWebService_Interface
  * @param mixed $request MarketplaceWebService_Model_GetFeedSubmissionList or array of parameters
  */
 function invokeGetFeedSubmissionList(MarketplaceWebService_Interface $service, $request, $id = Null) 
 {
     try {
             $response = $service->getFeedSubmissionList($request);
             
               echo ("Service Response\n");
               echo ("=============================================================================\n");
               echo("        GetFeedSubmissionListResponse\n");
               if ($response->isSetGetFeedSubmissionListResult()) { 
                   echo("            GetFeedSubmissionListResult\n");
                   $getFeedSubmissionListResult = $response->getGetFeedSubmissionListResult();
                   if ($getFeedSubmissionListResult->isSetNextToken()) 
                   {
                       echo("                NextToken\n");
                       echo("                    " . $getFeedSubmissionListResult->getNextToken() . "\n");
                   }
                   if ($getFeedSubmissionListResult->isSetHasNext()) 
                   {
                       echo("                HasNext\n");
                       echo("                    " . $getFeedSubmissionListResult->getHasNext() . "\n");
                   }
                   $feedSubmissionInfoList = $getFeedSubmissionListResult->getFeedSubmissionInfoList();
                   foreach ($feedSubmissionInfoList as $feedSubmissionInfo) {
                       echo("                FeedSubmissionInfo\n");
                       if ($feedSubmissionInfo->isSetFeedSubmissionId()) 
                       {
                           echo("                    FeedSubmissionId\n");
                           echo("                        " . $feedSubmissionInfo->getFeedSubmissionId() . "\n");
                       }
                       if ($feedSubmissionInfo->isSetFeedType()) 
                       {
                           echo("                    FeedType\n");
                           echo("                        " . $feedSubmissionInfo->getFeedType() . "\n");
                           if ($id == $feedSubmissionInfo->getFeedSubmissionId())
                           {
                               $type = $feedSubmissionInfo->getFeedProcessingStatus();
                           }
                       }
                       if ($feedSubmissionInfo->isSetSubmittedDate()) 
                       {
                           echo("                    SubmittedDate\n");
                           echo("                        " . $feedSubmissionInfo->getSubmittedDate()->format(DATE_FORMAT) . "\n");
                       }
                       if ($feedSubmissionInfo->isSetFeedProcessingStatus()) 
                       {
                           echo("                    FeedProcessingStatus\n");
                           echo("                        " . $feedSubmissionInfo->getFeedProcessingStatus() . "\n");
                           if ($id == $feedSubmissionInfo->getFeedSubmissionId())
                           {
                               $status = $feedSubmissionInfo->getFeedProcessingStatus();
                           }
                       }
                       if ($feedSubmissionInfo->isSetStartedProcessingDate()) 
                       {
                           echo("                    StartedProcessingDate\n");
                           echo("                        " . $feedSubmissionInfo->getStartedProcessingDate()->format(DATE_FORMAT) . "\n");
                       }
                       if ($feedSubmissionInfo->isSetCompletedProcessingDate()) 
                       {
                           echo("                    CompletedProcessingDate\n");
                           echo("                        " . $feedSubmissionInfo->getCompletedProcessingDate()->format(DATE_FORMAT) . "\n");
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
               return $status;
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
