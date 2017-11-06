<?php
/*******************************************************************************
 * Copyright 2009-2016 Amazon Services. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License"); 
 *
 * You may not use this file except in compliance with the License. 
 * You may obtain a copy of the License at: http://aws.amazon.com/apache2.0
 * This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR 
 * CONDITIONS OF ANY KIND, either express or implied. See the License for the 
 * specific language governing permissions and limitations under the License.
 *******************************************************************************
 * PHP Version 5
 * @category Amazon
 * @package  FBA Inbound Service MWS
 * @version  2010-10-01
 * Library Version: 2016-10-05
 * Generated: Wed Oct 05 06:15:45 PDT 2016
 */


/**
 * FBA Inbound Service MWS Exception provides details of errors
 * returned by FBA Inbound Service MWS service
 *
 */
class FBAInboundServiceMWS_Exception extends Exception

{
    /** @var string */
    private $_message = null;
    /** @var int */
    private $_statusCode = -1;
    /** @var string */
    private $_errorCode = null;
    /** @var string */
    private $_errorType = null;
    /** @var string */
    private $_requestId = null;
    /** @var string */
    private $_xml = null;

    private $_responseHeaderMetadata = null;

    /**
     * Constructs FBAInboundServiceMWS_Exception
     * @param array $errorInfo details of exception.
     * Keys are:
     * <ul>
     * <li>Message - (string) text message for an exception</li>
     * <li>StatusCode - (int) HTTP status code at the time of exception</li>
     * <li>ErrorCode - (string) specific error code returned by the service</li>
     * <li>ErrorType - (string) Possible types:  Sender, Receiver or Unknown</li>
     * <li>RequestId - (string) request id returned by the service</li>
     * <li>XML - (string) compete xml response at the time of exception</li>
     * <li>Exception - (Exception) inner exception if any</li>
     * </ul>
     *
     */
    public function __construct(array $errorInfo = array())
    {
        $this->_message = $errorInfo["Message"];
        parent::__construct($this->_message);
        if (array_key_exists("Exception", $errorInfo)) {
            $exception = $errorInfo["Exception"];
            if ($exception instanceof FBAInboundServiceMWS_Exception) {
                $this->_statusCode = $exception->getStatusCode();
                $this->_errorCode = $exception->getErrorCode();
                $this->_errorType = $exception->getErrorType();
                $this->_requestId = $exception->getRequestId();
                $this->_xml= $exception->getXML();
                $this->_responseHeaderMetadata = $exception->getResponseHeaderMetadata();
            }
        } else {
            $this->_statusCode = $this->arr_val($errorInfo, "StatusCode");
            $this->_errorCode = $this->arr_val($errorInfo, "ErrorCode");
            $this->_errorType = $this->arr_val($errorInfo, "ErrorType");
            $this->_requestId = $this->arr_val($errorInfo, "RequestId");
            $this->_xml = $this->arr_val($errorInfo, "XML");
            $this->_responseHeaderMetadata = $this->arr_val($errorInfo, "ResponseHeaderMetadata");
        }
    }

    private function arr_val($arr, $key) {
        if(array_key_exists($key, $arr)) {
            return $arr[$key];
        } else {
            return null;
        }
    }

    /**
     * Gets error type returned by the service if available.
     *
     * @return string Error Code returned by the service
     */
    public function getErrorCode(){
        return $this->_errorCode;
    }

    /**
     * Gets error type returned by the service.
     *
     * @return string Error Type returned by the service.
     * Possible types:  Sender, Receiver or Unknown
     */
    public function getErrorType(){
        return $this->_errorType;
    }

    /**
     * Gets error message
     *
     * @return string Error message
     */
    public function getErrorMessage() {
        return $this->_message;
    }

    /**
     * Gets status code returned by the service if available. If status
     * code is set to -1, it means that status code was unavailable at the
     * time exception was thrown
     *
     * @return int status code returned by the service
     */
    public function getStatusCode() {
        return $this->_statusCode;
    }

    /**
     * Gets XML returned by the service if available.
     *
     * @return string XML returned by the service
     */
    public function getXML() {
        return $this->_xml;
    }

    /**
     * Gets Request ID returned by the service if available.
     *
     * @return string Request ID returned by the service
     */
    public function getRequestId() {
        return $this->_requestId;
    }

    public function getResponseHeaderMetadata() {
      return $this->_responseHeaderMetadata;
    }
}

/******************************************************************************* 

 *  Marketplace Web Service PHP5 Library
 *  Generated: Thu May 07 13:07:36 PDT 2009
 * 
 */
/**
 * The Amazon Marketplace Web Service contain APIs for inventory and order management.
 * 
 */

interface  MarketplaceWebService_Interface 
{
    

            
    /**
     * Get Report 
     * The GetReport operation returns the contents of a report. Reports can potentially be
     * very large (>100MB) which is why we only return one report at a time, and in a
     * streaming fashion.
     *   
     * @see http://docs.amazonwebservices.com/${docPath}GetReport.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_GetReportRequest request
     * or MarketplaceWebService_Model_GetReportRequest object itself
     * @see MarketplaceWebService_Model_GetReportRequest
     * @return MarketplaceWebService_Model_GetReportResponse MarketplaceWebService_Model_GetReportResponse
     *
     * @throws MarketplaceWebService_Exception
     */
    public function getReport($request);


            
    /**
     * Get Report Schedule Count 
     * returns the number of report schedules
     *   
     * @see http://docs.amazonwebservices.com/${docPath}GetReportScheduleCount.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_GetReportScheduleCountRequest request
     * or MarketplaceWebService_Model_GetReportScheduleCountRequest object itself
     * @see MarketplaceWebService_Model_GetReportScheduleCountRequest
     * @return MarketplaceWebService_Model_GetReportScheduleCountResponse MarketplaceWebService_Model_GetReportScheduleCountResponse
     *
     * @throws MarketplaceWebService_Exception
     */
    public function getReportScheduleCount($request);


            
    /**
     * Get Report Request List By Next Token 
     * retrieve the next batch of list items and if there are more items to retrieve
     *   
     * @see http://docs.amazonwebservices.com/${docPath}GetReportRequestListByNextToken.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_GetReportRequestListByNextTokenRequest request
     * or MarketplaceWebService_Model_GetReportRequestListByNextTokenRequest object itself
     * @see MarketplaceWebService_Model_GetReportRequestListByNextTokenRequest
     * @return MarketplaceWebService_Model_GetReportRequestListByNextTokenResponse MarketplaceWebService_Model_GetReportRequestListByNextTokenResponse
     *
     * @throws MarketplaceWebService_Exception
     */
    public function getReportRequestListByNextToken($request);


            
    /**
     * Update Report Acknowledgements 
     * The UpdateReportAcknowledgements operation updates the acknowledged status of one or more reports.
     *   
     * @see http://docs.amazonwebservices.com/${docPath}UpdateReportAcknowledgements.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_UpdateReportAcknowledgementsRequest request
     * or MarketplaceWebService_Model_UpdateReportAcknowledgementsRequest object itself
     * @see MarketplaceWebService_Model_UpdateReportAcknowledgementsRequest
     * @return MarketplaceWebService_Model_UpdateReportAcknowledgementsResponse MarketplaceWebService_Model_UpdateReportAcknowledgementsResponse
     *
     * @throws MarketplaceWebService_Exception
     */
    public function updateReportAcknowledgements($request);


            
    /**
     * Submit Feed 
     * Uploads a file for processing together with the necessary
     * metadata to process the file, such as which type of feed it is.
     * PurgeAndReplace if true means that your existing e.g. inventory is
     * wiped out and replace with the contents of this feed - use with
     * caution (the default is false).
     *   
     * @see http://docs.amazonwebservices.com/${docPath}SubmitFeed.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_SubmitFeedRequest request
     * or MarketplaceWebService_Model_SubmitFeedRequest object itself
     * @see MarketplaceWebService_Model_SubmitFeedRequest
     * @return MarketplaceWebService_Model_SubmitFeedResponse MarketplaceWebService_Model_SubmitFeedResponse
     *
     * @throws MarketplaceWebService_Exception
     */
    public function submitFeed($request);


            
    /**
     * Get Report Count 
     * returns a count of reports matching your criteria;
     * by default, the number of reports generated in the last 90 days,
     * regardless of acknowledgement status
     *   
     * @see http://docs.amazonwebservices.com/${docPath}GetReportCount.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_GetReportCountRequest request
     * or MarketplaceWebService_Model_GetReportCountRequest object itself
     * @see MarketplaceWebService_Model_GetReportCountRequest
     * @return MarketplaceWebService_Model_GetReportCountResponse MarketplaceWebService_Model_GetReportCountResponse
     *
     * @throws MarketplaceWebService_Exception
     */
    public function getReportCount($request);


            
    /**
     * Get Feed Submission List By Next Token 
     * retrieve the next batch of list items and if there are more items to retrieve
     *   
     * @see http://docs.amazonwebservices.com/${docPath}GetFeedSubmissionListByNextToken.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_GetFeedSubmissionListByNextTokenRequest request
     * or MarketplaceWebService_Model_GetFeedSubmissionListByNextTokenRequest object itself
     * @see MarketplaceWebService_Model_GetFeedSubmissionListByNextTokenRequest
     * @return MarketplaceWebService_Model_GetFeedSubmissionListByNextTokenResponse MarketplaceWebService_Model_GetFeedSubmissionListByNextTokenResponse
     *
     * @throws MarketplaceWebService_Exception
     */
    public function getFeedSubmissionListByNextToken($request);


            
    /**
     * Cancel Feed Submissions 
     * cancels feed submissions - by default all of the submissions of the
     * last 30 days that have not started processing
     *   
     * @see http://docs.amazonwebservices.com/${docPath}CancelFeedSubmissions.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_CancelFeedSubmissionsRequest request
     * or MarketplaceWebService_Model_CancelFeedSubmissionsRequest object itself
     * @see MarketplaceWebService_Model_CancelFeedSubmissionsRequest
     * @return MarketplaceWebService_Model_CancelFeedSubmissionsResponse MarketplaceWebService_Model_CancelFeedSubmissionsResponse
     *
     * @throws MarketplaceWebService_Exception
     */
    public function cancelFeedSubmissions($request);


            
    /**
     * Request Report 
     * requests the generation of a report
     *   
     * @see http://docs.amazonwebservices.com/${docPath}RequestReport.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_RequestReportRequest request
     * or MarketplaceWebService_Model_RequestReportRequest object itself
     * @see MarketplaceWebService_Model_RequestReportRequest
     * @return MarketplaceWebService_Model_RequestReportResponse MarketplaceWebService_Model_RequestReportResponse
     *
     * @throws MarketplaceWebService_Exception
     */
    public function requestReport($request);


            
    /**
     * Get Feed Submission Count 
     * returns the number of feeds matching all of the specified criteria
     *   
     * @see http://docs.amazonwebservices.com/${docPath}GetFeedSubmissionCount.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_GetFeedSubmissionCountRequest request
     * or MarketplaceWebService_Model_GetFeedSubmissionCountRequest object itself
     * @see MarketplaceWebService_Model_GetFeedSubmissionCountRequest
     * @return MarketplaceWebService_Model_GetFeedSubmissionCountResponse MarketplaceWebService_Model_GetFeedSubmissionCountResponse
     *
     * @throws MarketplaceWebService_Exception
     */
    public function getFeedSubmissionCount($request);


            
    /**
     * Cancel Report Requests 
     * cancels report requests that have not yet started processing,
     * by default all those within the last 90 days
     *   
     * @see http://docs.amazonwebservices.com/${docPath}CancelReportRequests.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_CancelReportRequestsRequest request
     * or MarketplaceWebService_Model_CancelReportRequestsRequest object itself
     * @see MarketplaceWebService_Model_CancelReportRequestsRequest
     * @return MarketplaceWebService_Model_CancelReportRequestsResponse MarketplaceWebService_Model_CancelReportRequestsResponse
     *
     * @throws MarketplaceWebService_Exception
     */
    public function cancelReportRequests($request);


            
    /**
     * Get Report List 
     * returns a list of reports; by default the most recent ten reports,
     * regardless of their acknowledgement status
     *   
     * @see http://docs.amazonwebservices.com/${docPath}GetReportList.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_GetReportListRequest request
     * or MarketplaceWebService_Model_GetReportListRequest object itself
     * @see MarketplaceWebService_Model_GetReportListRequest
     * @return MarketplaceWebService_Model_GetReportListResponse MarketplaceWebService_Model_GetReportListResponse
     *
     * @throws MarketplaceWebService_Exception
     */
    public function getReportList($request);


            
    /**
     * Get Feed Submission Result 
     * retrieves the feed processing report
     *   
     * @see http://docs.amazonwebservices.com/${docPath}GetFeedSubmissionResult.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_GetFeedSubmissionResultRequest request
     * or MarketplaceWebService_Model_GetFeedSubmissionResultRequest object itself
     * @see MarketplaceWebService_Model_GetFeedSubmissionResultRequest
     * @return MarketplaceWebService_Model_GetFeedSubmissionResultResponse MarketplaceWebService_Model_GetFeedSubmissionResultResponse
     *
     * @throws MarketplaceWebService_Exception
     */
    public function getFeedSubmissionResult($request);


            
    /**
     * Get Feed Submission List 
     * returns a list of feed submission identifiers and their associated metadata
     *   
     * @see http://docs.amazonwebservices.com/${docPath}GetFeedSubmissionList.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_GetFeedSubmissionListRequest request
     * or MarketplaceWebService_Model_GetFeedSubmissionListRequest object itself
     * @see MarketplaceWebService_Model_GetFeedSubmissionListRequest
     * @return MarketplaceWebService_Model_GetFeedSubmissionListResponse MarketplaceWebService_Model_GetFeedSubmissionListResponse
     *
     * @throws MarketplaceWebService_Exception
     */
    public function getFeedSubmissionList($request);


            
    /**
     * Get Report Request List 
     * returns a list of report requests ids and their associated metadata
     *   
     * @see http://docs.amazonwebservices.com/${docPath}GetReportRequestList.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_GetReportRequestListRequest request
     * or MarketplaceWebService_Model_GetReportRequestListRequest object itself
     * @see MarketplaceWebService_Model_GetReportRequestListRequest
     * @return MarketplaceWebService_Model_GetReportRequestListResponse MarketplaceWebService_Model_GetReportRequestListResponse
     *
     * @throws MarketplaceWebService_Exception
     */
    public function getReportRequestList($request);


            
    /**
     * Get Report Schedule List By Next Token 
     * retrieve the next batch of list items and if there are more items to retrieve
     *   
     * @see http://docs.amazonwebservices.com/${docPath}GetReportScheduleListByNextToken.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_GetReportScheduleListByNextTokenRequest request
     * or MarketplaceWebService_Model_GetReportScheduleListByNextTokenRequest object itself
     * @see MarketplaceWebService_Model_GetReportScheduleListByNextTokenRequest
     * @return MarketplaceWebService_Model_GetReportScheduleListByNextTokenResponse MarketplaceWebService_Model_GetReportScheduleListByNextTokenResponse
     *
     * @throws MarketplaceWebService_Exception
     */
    public function getReportScheduleListByNextToken($request);


            
    /**
     * Get Report List By Next Token 
     * retrieve the next batch of list items and if there are more items to retrieve
     *   
     * @see http://docs.amazonwebservices.com/${docPath}GetReportListByNextToken.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_GetReportListByNextTokenRequest request
     * or MarketplaceWebService_Model_GetReportListByNextTokenRequest object itself
     * @see MarketplaceWebService_Model_GetReportListByNextTokenRequest
     * @return MarketplaceWebService_Model_GetReportListByNextTokenResponse MarketplaceWebService_Model_GetReportListByNextTokenResponse
     *
     * @throws MarketplaceWebService_Exception
     */
    public function getReportListByNextToken($request);


            
    /**
     * Manage Report Schedule 
     * Creates, updates, or deletes a report schedule
     * for a given report type, such as order reports in particular.
     *   
     * @see http://docs.amazonwebservices.com/${docPath}ManageReportSchedule.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_ManageReportScheduleRequest request
     * or MarketplaceWebService_Model_ManageReportScheduleRequest object itself
     * @see MarketplaceWebService_Model_ManageReportScheduleRequest
     * @return MarketplaceWebService_Model_ManageReportScheduleResponse MarketplaceWebService_Model_ManageReportScheduleResponse
     *
     * @throws MarketplaceWebService_Exception
     */
    public function manageReportSchedule($request);


            
    /**
     * Get Report Request Count 
     * returns a count of report requests; by default all the report
     * requests in the last 90 days
     *   
     * @see http://docs.amazonwebservices.com/${docPath}GetReportRequestCount.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_GetReportRequestCountRequest request
     * or MarketplaceWebService_Model_GetReportRequestCountRequest object itself
     * @see MarketplaceWebService_Model_GetReportRequestCountRequest
     * @return MarketplaceWebService_Model_GetReportRequestCountResponse MarketplaceWebService_Model_GetReportRequestCountResponse
     *
     * @throws MarketplaceWebService_Exception
     */
    public function getReportRequestCount($request);


            
    /**
     * Get Report Schedule List 
     * returns the list of report schedules
     *   
     * @see http://docs.amazonwebservices.com/${docPath}GetReportScheduleList.html      
     * @param mixed $request array of parameters for MarketplaceWebService_Model_GetReportScheduleListRequest request
     * or MarketplaceWebService_Model_GetReportScheduleListRequest object itself
     * @see MarketplaceWebService_Model_GetReportScheduleListRequest
     * @return MarketplaceWebService_Model_GetReportScheduleListResponse MarketplaceWebService_Model_GetReportScheduleListResponse
     *
     * @throws MarketplaceWebService_Exception
     */
    public function getReportScheduleList($request);

}

/*******************************************************************************
 * PHP Version 5
 * @category Amazon
 * @package  Marketplace Web Service Products
 * @version  2011-10-01
 * Library Version: 2017-03-22
 * Generated: Wed Mar 22 23:24:40 UTC 2017
 */

interface  MarketplaceWebServiceProducts_Interface
{

    /**
     * Get Competitive Pricing For ASIN
     * Gets competitive pricing and related information for a product identified by
     * the MarketplaceId and ASIN.
     *
     * @param mixed $request array of parameters for MarketplaceWebServiceProducts_Model_GetCompetitivePricingForASIN request or MarketplaceWebServiceProducts_Model_GetCompetitivePricingForASIN object itself
     * @see MarketplaceWebServiceProducts_Model_GetCompetitivePricingForASINRequest
     * @return MarketplaceWebServiceProducts_Model_GetCompetitivePricingForASINResponse
     *
     * @throws MarketplaceWebServiceProducts_Exception
     */
    public function getCompetitivePricingForASIN($request);


    /**
     * Get Competitive Pricing For SKU
     * Gets competitive pricing and related information for a product identified by
     * the SellerId and SKU.
     *
     * @param mixed $request array of parameters for MarketplaceWebServiceProducts_Model_GetCompetitivePricingForSKU request or MarketplaceWebServiceProducts_Model_GetCompetitivePricingForSKU object itself
     * @see MarketplaceWebServiceProducts_Model_GetCompetitivePricingForSKURequest
     * @return MarketplaceWebServiceProducts_Model_GetCompetitivePricingForSKUResponse
     *
     * @throws MarketplaceWebServiceProducts_Exception
     */
    public function getCompetitivePricingForSKU($request);


    /**
     * Get Lowest Offer Listings For ASIN
     * Gets some of the lowest prices based on the product identified by the given
     * MarketplaceId and ASIN.
     *
     * @param mixed $request array of parameters for MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForASIN request or MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForASIN object itself
     * @see MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForASINRequest
     * @return MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForASINResponse
     *
     * @throws MarketplaceWebServiceProducts_Exception
     */
    public function getLowestOfferListingsForASIN($request);


    /**
     * Get Lowest Offer Listings For SKU
     * Gets some of the lowest prices based on the product identified by the given
     * SellerId and SKU.
     *
     * @param mixed $request array of parameters for MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForSKU request or MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForSKU object itself
     * @see MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForSKURequest
     * @return MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForSKUResponse
     *
     * @throws MarketplaceWebServiceProducts_Exception
     */
    public function getLowestOfferListingsForSKU($request);


    /**
     * Get Lowest Priced Offers For ASIN
     * Retrieves the lowest priced offers based on the product identified by the given
     *     ASIN.
     *
     * @param mixed $request array of parameters for MarketplaceWebServiceProducts_Model_GetLowestPricedOffersForASIN request or MarketplaceWebServiceProducts_Model_GetLowestPricedOffersForASIN object itself
     * @see MarketplaceWebServiceProducts_Model_GetLowestPricedOffersForASINRequest
     * @return MarketplaceWebServiceProducts_Model_GetLowestPricedOffersForASINResponse
     *
     * @throws MarketplaceWebServiceProducts_Exception
     */
    public function getLowestPricedOffersForASIN($request);


    /**
     * Get Lowest Priced Offers For SKU
     * Retrieves the lowest priced offers based on the product identified by the given
     *     SellerId and SKU.
     *
     * @param mixed $request array of parameters for MarketplaceWebServiceProducts_Model_GetLowestPricedOffersForSKU request or MarketplaceWebServiceProducts_Model_GetLowestPricedOffersForSKU object itself
     * @see MarketplaceWebServiceProducts_Model_GetLowestPricedOffersForSKURequest
     * @return MarketplaceWebServiceProducts_Model_GetLowestPricedOffersForSKUResponse
     *
     * @throws MarketplaceWebServiceProducts_Exception
     */
    public function getLowestPricedOffersForSKU($request);


    /**
     * Get Matching Product
     * GetMatchingProduct will return the details (attributes) for the
     * given ASIN.
     *
     * @param mixed $request array of parameters for MarketplaceWebServiceProducts_Model_GetMatchingProduct request or MarketplaceWebServiceProducts_Model_GetMatchingProduct object itself
     * @see MarketplaceWebServiceProducts_Model_GetMatchingProductRequest
     * @return MarketplaceWebServiceProducts_Model_GetMatchingProductResponse
     *
     * @throws MarketplaceWebServiceProducts_Exception
     */
    public function getMatchingProduct($request);


    /**
     * Get Matching Product For Id
     * GetMatchingProduct will return the details (attributes) for the
     * given Identifier list. Identifer type can be one of [SKU|ASIN|UPC|EAN|ISBN|GTIN|JAN]
     *
     * @param mixed $request array of parameters for MarketplaceWebServiceProducts_Model_GetMatchingProductForId request or MarketplaceWebServiceProducts_Model_GetMatchingProductForId object itself
     * @see MarketplaceWebServiceProducts_Model_GetMatchingProductForIdRequest
     * @return MarketplaceWebServiceProducts_Model_GetMatchingProductForIdResponse
     *
     * @throws MarketplaceWebServiceProducts_Exception
     */
    public function getMatchingProductForId($request);


    /**
     * Get My Fees Estimate
     * Retrieves the fees estimate for the
     *         products identified by the given
     *         ASIN/SKU list.
     *
     * @param mixed $request array of parameters for MarketplaceWebServiceProducts_Model_GetMyFeesEstimate request or MarketplaceWebServiceProducts_Model_GetMyFeesEstimate object itself
     * @see MarketplaceWebServiceProducts_Model_GetMyFeesEstimateRequest
     * @return MarketplaceWebServiceProducts_Model_GetMyFeesEstimateResponse
     *
     * @throws MarketplaceWebServiceProducts_Exception
     */
    public function getMyFeesEstimate($request);


    /**
     * Get My Price For ASIN
     * <!-- Wrong doc in current code -->
     *
     * @param mixed $request array of parameters for MarketplaceWebServiceProducts_Model_GetMyPriceForASIN request or MarketplaceWebServiceProducts_Model_GetMyPriceForASIN object itself
     * @see MarketplaceWebServiceProducts_Model_GetMyPriceForASINRequest
     * @return MarketplaceWebServiceProducts_Model_GetMyPriceForASINResponse
     *
     * @throws MarketplaceWebServiceProducts_Exception
     */
    public function getMyPriceForASIN($request);


    /**
     * Get My Price For SKU
     * <!-- Wrong doc in current code -->
     *
     * @param mixed $request array of parameters for MarketplaceWebServiceProducts_Model_GetMyPriceForSKU request or MarketplaceWebServiceProducts_Model_GetMyPriceForSKU object itself
     * @see MarketplaceWebServiceProducts_Model_GetMyPriceForSKURequest
     * @return MarketplaceWebServiceProducts_Model_GetMyPriceForSKUResponse
     *
     * @throws MarketplaceWebServiceProducts_Exception
     */
    public function getMyPriceForSKU($request);


    /**
     * Get Product Categories For ASIN
     * Gets categories information for a product identified by
     * the MarketplaceId and ASIN.
     *
     * @param mixed $request array of parameters for MarketplaceWebServiceProducts_Model_GetProductCategoriesForASIN request or MarketplaceWebServiceProducts_Model_GetProductCategoriesForASIN object itself
     * @see MarketplaceWebServiceProducts_Model_GetProductCategoriesForASINRequest
     * @return MarketplaceWebServiceProducts_Model_GetProductCategoriesForASINResponse
     *
     * @throws MarketplaceWebServiceProducts_Exception
     */
    public function getProductCategoriesForASIN($request);


    /**
     * Get Product Categories For SKU
     * Gets categories information for a product identified by
     * the SellerId and SKU.
     *
     * @param mixed $request array of parameters for MarketplaceWebServiceProducts_Model_GetProductCategoriesForSKU request or MarketplaceWebServiceProducts_Model_GetProductCategoriesForSKU object itself
     * @see MarketplaceWebServiceProducts_Model_GetProductCategoriesForSKURequest
     * @return MarketplaceWebServiceProducts_Model_GetProductCategoriesForSKUResponse
     *
     * @throws MarketplaceWebServiceProducts_Exception
     */
    public function getProductCategoriesForSKU($request);


    /**
     * Get Service Status
     * Returns the service status of a particular MWS API section. The operation
     * takes no input.
     * All API sections within the API are required to implement this operation.
     *
     * @param mixed $request array of parameters for MarketplaceWebServiceProducts_Model_GetServiceStatus request or MarketplaceWebServiceProducts_Model_GetServiceStatus object itself
     * @see MarketplaceWebServiceProducts_Model_GetServiceStatusRequest
     * @return MarketplaceWebServiceProducts_Model_GetServiceStatusResponse
     *
     * @throws MarketplaceWebServiceProducts_Exception
     */
    public function getServiceStatus($request);


    /**
     * List Matching Products
     * ListMatchingProducts can be used to
     * find products that match the given criteria.
     *
     * @param mixed $request array of parameters for MarketplaceWebServiceProducts_Model_ListMatchingProducts request or MarketplaceWebServiceProducts_Model_ListMatchingProducts object itself
     * @see MarketplaceWebServiceProducts_Model_ListMatchingProductsRequest
     * @return MarketplaceWebServiceProducts_Model_ListMatchingProductsResponse
     *
     * @throws MarketplaceWebServiceProducts_Exception
     */
    public function listMatchingProducts($request);

}
