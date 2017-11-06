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
 *  @see FBAInboundServiceMWS_Interface
 */
require_once (dirname(__FILE__) . '/Interface.php');

/**
 * FBAInboundServiceMWS_Client is an implementation of FBAInboundServiceMWS
 *
 */
class FBAInboundServiceMWS_Client implements FBAInboundServiceMWS_Interface
{

    const SERVICE_VERSION = '2010-10-01';
    const MWS_CLIENT_VERSION = '2016-10-05';

    /** @var string */
    private  $_awsAccessKeyId = null;

    /** @var string */
    private  $_awsSecretAccessKey = null;

    /** @var array */
    private  $_config = array ('ServiceURL' => null,
                               'UserAgent' => 'FBAInboundServiceMWS PHP5 Library',
                               'SignatureVersion' => 2,
                               'SignatureMethod' => 'HmacSHA256',
                               'ProxyHost' => null,
                               'ProxyPort' => -1,
                               'ProxyUsername' => null,
                               'ProxyPassword' => null,
                               'MaxErrorRetry' => 3,
                               'Headers' => array()
                               );


    /**
     * Confirm Preorder
     * Given a shipment id. and date as input, this API confirms a shipment as a
     * pre-order.
     * This date must be the same as the NeedByDate (NBD) that is returned in the
     * GetPreorderInfo API. Any other date will result in an appropriate error code.
     * All items in the shipment with a release date on or after the
     * ConfirmedFulfillableDate ( also returned by the GetPreorderInfo  API) would
     * be pre-orderable on the website and would be fulfilled without promise breaks,
     * if the NBD is met.
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_ConfirmPreorder request or FBAInboundServiceMWS_Model_ConfirmPreorder object itself
     * @see FBAInboundServiceMWS_Model_ConfirmPreorderRequest
     * @return FBAInboundServiceMWS_Model_ConfirmPreorderResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function confirmPreorder($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_ConfirmPreorderRequest)) {
            require_once (dirname(__FILE__) . '/Model/ConfirmPreorderRequest.php');
            $request = new FBAInboundServiceMWS_Model_ConfirmPreorderRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'ConfirmPreorder';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/ConfirmPreorderResponse.php');
        $response = FBAInboundServiceMWS_Model_ConfirmPreorderResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert ConfirmPreorderRequest to name value pairs
     */
    private function _convertConfirmPreorder($request) {

        $parameters = array();
        $parameters['Action'] = 'ConfirmPreorder';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplace()) {
            $parameters['Marketplace'] =  $request->getMarketplace();
        }
        if ($request->isSetShipmentId()) {
            $parameters['ShipmentId'] =  $request->getShipmentId();
        }
        if ($request->isSetNeedByDate()) {
            $parameters['NeedByDate'] =  $request->getNeedByDate();
        }

        return $parameters;
    }


    /**
     * Confirm Transport Request
     * Confirms the estimate returned by the EstimateTransportRequest operation.
     *     Once this operation has been called successfully, the seller agrees to allow Amazon to charge
     *     their account the amount returned in the estimate.
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_ConfirmTransportRequest request or FBAInboundServiceMWS_Model_ConfirmTransportRequest object itself
     * @see FBAInboundServiceMWS_Model_ConfirmTransportInputRequest
     * @return FBAInboundServiceMWS_Model_ConfirmTransportRequestResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function confirmTransportRequest($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_ConfirmTransportInputRequest)) {
            require_once (dirname(__FILE__) . '/Model/ConfirmTransportInputRequest.php');
            $request = new FBAInboundServiceMWS_Model_ConfirmTransportInputRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'ConfirmTransportRequest';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/ConfirmTransportRequestResponse.php');
        $response = FBAInboundServiceMWS_Model_ConfirmTransportRequestResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert ConfirmTransportInputRequest to name value pairs
     */
    private function _convertConfirmTransportRequest($request) {

        $parameters = array();
        $parameters['Action'] = 'ConfirmTransportRequest';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetShipmentId()) {
            $parameters['ShipmentId'] =  $request->getShipmentId();
        }

        return $parameters;
    }


    /**
     * Create Inbound Shipment
     * Creates an inbound shipment. It may include up to 200 items. 
     * The initial status of a shipment will be set to 'Working'.
     * This operation will simply return a shipment Id upon success,
     * otherwise an explicit error will be returned.
     * More items may be added using the Update call.
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_CreateInboundShipment request or FBAInboundServiceMWS_Model_CreateInboundShipment object itself
     * @see FBAInboundServiceMWS_Model_CreateInboundShipmentRequest
     * @return FBAInboundServiceMWS_Model_CreateInboundShipmentResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function createInboundShipment($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_CreateInboundShipmentRequest)) {
            require_once (dirname(__FILE__) . '/Model/CreateInboundShipmentRequest.php');
            $request = new FBAInboundServiceMWS_Model_CreateInboundShipmentRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'CreateInboundShipment';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/CreateInboundShipmentResponse.php');
        $response = FBAInboundServiceMWS_Model_CreateInboundShipmentResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert CreateInboundShipmentRequest to name value pairs
     */
    private function _convertCreateInboundShipment($request) {

        $parameters = array();
        $parameters['Action'] = 'CreateInboundShipment';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplace()) {
            $parameters['Marketplace'] =  $request->getMarketplace();
        }
        if ($request->isSetShipmentId()) {
            $parameters['ShipmentId'] =  $request->getShipmentId();
        }
        if ($request->isSetInboundShipmentHeader()) {
            $InboundShipmentHeaderCreateInboundShipmentRequest = $request->getInboundShipmentHeader();
            foreach  ($InboundShipmentHeaderCreateInboundShipmentRequest->getShipmentName() as $ShipmentNameInboundShipmentHeaderIndex => $ShipmentNameInboundShipmentHeader) {
                $parameters['InboundShipmentHeader' . '.' . 'ShipmentName' . '.'  . ($ShipmentNameInboundShipmentHeaderIndex + 1)] =  $ShipmentNameInboundShipmentHeader;
            }
        }
        if ($request->isSetInboundShipmentItems()) {
            $InboundShipmentItemsCreateInboundShipmentRequest = $request->getInboundShipmentItems();
            foreach  ($InboundShipmentItemsCreateInboundShipmentRequest->getmember() as $memberInboundShipmentItemsIndex => $memberInboundShipmentItems) {
                $parameters['InboundShipmentItems' . '.' . 'member' . '.'  . ($memberInboundShipmentItemsIndex + 1)] =  $memberInboundShipmentItems;
            }
        }

        return $parameters;
    }


    /**
     * Create Inbound Shipment Plan
     * Plans inbound shipments for a set of items.  Registers identifiers if needed,
     * and assigns ShipmentIds for planned shipments.
     * When all the items are not all in the same category (e.g. some sortable, some 
     * non-sortable) it may be necessary to create multiple shipments (one for each 
     * of the shipment groups returned).
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_CreateInboundShipmentPlan request or FBAInboundServiceMWS_Model_CreateInboundShipmentPlan object itself
     * @see FBAInboundServiceMWS_Model_CreateInboundShipmentPlanRequest
     * @return FBAInboundServiceMWS_Model_CreateInboundShipmentPlanResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function createInboundShipmentPlan($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_CreateInboundShipmentPlanRequest)) {
            require_once (dirname(__FILE__) . '/Model/CreateInboundShipmentPlanRequest.php');
            $request = new FBAInboundServiceMWS_Model_CreateInboundShipmentPlanRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'CreateInboundShipmentPlan';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/CreateInboundShipmentPlanResponse.php');
        $response = FBAInboundServiceMWS_Model_CreateInboundShipmentPlanResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert CreateInboundShipmentPlanRequest to name value pairs
     */
    private function _convertCreateInboundShipmentPlan($request) {

        $parameters = array();
        $parameters['Action'] = 'CreateInboundShipmentPlan';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplace()) {
            $parameters['Marketplace'] =  $request->getMarketplace();
        }
        if ($request->isSetShipFromAddress()) {
            $ShipFromAddressCreateInboundShipmentPlanRequest = $request->getShipFromAddress();
            foreach  ($ShipFromAddressCreateInboundShipmentPlanRequest->getName() as $NameShipFromAddressIndex => $NameShipFromAddress) {
                $parameters['ShipFromAddress' . '.' . 'Name' . '.'  . ($NameShipFromAddressIndex + 1)] =  $NameShipFromAddress;
            }
        }
        if ($request->isSetLabelPrepPreference()) {
            $parameters['LabelPrepPreference'] =  $request->getLabelPrepPreference();
        }
        if ($request->isSetShipToCountryCode()) {
            $parameters['ShipToCountryCode'] =  $request->getShipToCountryCode();
        }
        if ($request->isSetShipToCountrySubdivisionCode()) {
            $parameters['ShipToCountrySubdivisionCode'] =  $request->getShipToCountrySubdivisionCode();
        }
        if ($request->isSetInboundShipmentPlanRequestItems()) {
            $InboundShipmentPlanRequestItemsCreateInboundShipmentPlanRequest = $request->getInboundShipmentPlanRequestItems();
            foreach  ($InboundShipmentPlanRequestItemsCreateInboundShipmentPlanRequest->getmember() as $memberInboundShipmentPlanRequestItemsIndex => $memberInboundShipmentPlanRequestItems) {
                $parameters['InboundShipmentPlanRequestItems' . '.' . 'member' . '.'  . ($memberInboundShipmentPlanRequestItemsIndex + 1)] =  $memberInboundShipmentPlanRequestItems;
            }
        }

        return $parameters;
    }


    /**
     * Estimate Transport Request
     * Initiates the process for requesting an estimated shipping cost based-on the shipment
     *     for which the request is being made, whether or not the carrier shipment is partnered/non-partnered
     *     and the carrier type.
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_EstimateTransportRequest request or FBAInboundServiceMWS_Model_EstimateTransportRequest object itself
     * @see FBAInboundServiceMWS_Model_EstimateTransportInputRequest
     * @return FBAInboundServiceMWS_Model_EstimateTransportRequestResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function estimateTransportRequest($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_EstimateTransportInputRequest)) {
            require_once (dirname(__FILE__) . '/Model/EstimateTransportInputRequest.php');
            $request = new FBAInboundServiceMWS_Model_EstimateTransportInputRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'EstimateTransportRequest';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/EstimateTransportRequestResponse.php');
        $response = FBAInboundServiceMWS_Model_EstimateTransportRequestResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert EstimateTransportInputRequest to name value pairs
     */
    private function _convertEstimateTransportRequest($request) {

        $parameters = array();
        $parameters['Action'] = 'EstimateTransportRequest';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetShipmentId()) {
            $parameters['ShipmentId'] =  $request->getShipmentId();
        }

        return $parameters;
    }


    /**
     * Get Bill Of Lading
     * Retrieves the PDF-formatted BOL data for a partnered LTL shipment.
     *     This PDF data will be ZIP'd and then it will be encoded as a Base64 string, and a
     *     MD5 hash is included with the response to validate the BOL data which will be encoded as Base64.
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_GetBillOfLading request or FBAInboundServiceMWS_Model_GetBillOfLading object itself
     * @see FBAInboundServiceMWS_Model_GetBillOfLadingRequest
     * @return FBAInboundServiceMWS_Model_GetBillOfLadingResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function getBillOfLading($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_GetBillOfLadingRequest)) {
            require_once (dirname(__FILE__) . '/Model/GetBillOfLadingRequest.php');
            $request = new FBAInboundServiceMWS_Model_GetBillOfLadingRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetBillOfLading';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetBillOfLadingResponse.php');
        $response = FBAInboundServiceMWS_Model_GetBillOfLadingResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetBillOfLadingRequest to name value pairs
     */
    private function _convertGetBillOfLading($request) {

        $parameters = array();
        $parameters['Action'] = 'GetBillOfLading';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetShipmentId()) {
            $parameters['ShipmentId'] =  $request->getShipmentId();
        }

        return $parameters;
    }


    /**
     * Get Inbound Guidance For ASIN
     * Given a list of ASINs and shipToCountryCode, this API returns Inbound
     *      guidance to ASINs in request with optional reason code if applicable.
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_GetInboundGuidanceForASIN request or FBAInboundServiceMWS_Model_GetInboundGuidanceForASIN object itself
     * @see FBAInboundServiceMWS_Model_GetInboundGuidanceForASINRequest
     * @return FBAInboundServiceMWS_Model_GetInboundGuidanceForASINResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function getInboundGuidanceForASIN($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_GetInboundGuidanceForASINRequest)) {
            require_once (dirname(__FILE__) . '/Model/GetInboundGuidanceForASINRequest.php');
            $request = new FBAInboundServiceMWS_Model_GetInboundGuidanceForASINRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetInboundGuidanceForASIN';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetInboundGuidanceForASINResponse.php');
        $response = FBAInboundServiceMWS_Model_GetInboundGuidanceForASINResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetInboundGuidanceForASINRequest to name value pairs
     */
    private function _convertGetInboundGuidanceForASIN($request) {

        $parameters = array();
        $parameters['Action'] = 'GetInboundGuidanceForASIN';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetASINList()) {
            $ASINListGetInboundGuidanceForASINRequest = $request->getASINList();
            foreach  ($ASINListGetInboundGuidanceForASINRequest->getId() as $IdASINListIndex => $IdASINList) {
                $parameters['ASINList' . '.' . 'Id' . '.'  . ($IdASINListIndex + 1)] =  $IdASINList;
            }
        }
        if ($request->isSetMarketplaceId()) {
            $parameters['MarketplaceId'] =  $request->getMarketplaceId();
        }

        return $parameters;
    }


    /**
     * Get Inbound Guidance For SKU
     * Given a list of SKUs and shipToCountryCode, this API returns Inbound
     *      guidance to SKUs in request with optional reason code if applicable.
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_GetInboundGuidanceForSKU request or FBAInboundServiceMWS_Model_GetInboundGuidanceForSKU object itself
     * @see FBAInboundServiceMWS_Model_GetInboundGuidanceForSKURequest
     * @return FBAInboundServiceMWS_Model_GetInboundGuidanceForSKUResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function getInboundGuidanceForSKU($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_GetInboundGuidanceForSKURequest)) {
            require_once (dirname(__FILE__) . '/Model/GetInboundGuidanceForSKURequest.php');
            $request = new FBAInboundServiceMWS_Model_GetInboundGuidanceForSKURequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetInboundGuidanceForSKU';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetInboundGuidanceForSKUResponse.php');
        $response = FBAInboundServiceMWS_Model_GetInboundGuidanceForSKUResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetInboundGuidanceForSKURequest to name value pairs
     */
    private function _convertGetInboundGuidanceForSKU($request) {

        $parameters = array();
        $parameters['Action'] = 'GetInboundGuidanceForSKU';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetSellerSKUList()) {
            $SellerSKUListGetInboundGuidanceForSKURequest = $request->getSellerSKUList();
            foreach  ($SellerSKUListGetInboundGuidanceForSKURequest->getId() as $IdSellerSKUListIndex => $IdSellerSKUList) {
                $parameters['SellerSKUList' . '.' . 'Id' . '.'  . ($IdSellerSKUListIndex + 1)] =  $IdSellerSKUList;
            }
        }
        if ($request->isSetMarketplaceId()) {
            $parameters['MarketplaceId'] =  $request->getMarketplaceId();
        }

        return $parameters;
    }


    /**
     * Get Package Labels
     * Retrieves the PDF-formatted package label data for the packages of the
     *     shipment. These labels will include relevant data for shipments utilizing 
     *     Amazon-partnered carriers. The PDF data will be ZIP'd and then it will be encoded as a Base64 string, and
     *     MD5 hash is included with the response to validate the label data which will be encoded as Base64.
     *     The language of the address and FC prep instructions sections of the labels are
     *     determined by the marketplace in which the request is being made and the marketplace of
     *     the destination FC, respectively.
     *     
     *     Only select PageTypes are supported in each marketplace. By marketplace, the
     *     supported types are:
     *       * US non-partnered UPS: PackageLabel_Letter_6
     *       * US partnered-UPS: PackageLabel_Letter_2
     *       * GB, DE, FR, IT, ES: PackageLabel_A4_4, PackageLabel_Plain_Paper
     *       * Partnered EU: PackageLabel_A4_2
     *       * JP/CN: PackageLabel_Plain_Paper
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_GetPackageLabels request or FBAInboundServiceMWS_Model_GetPackageLabels object itself
     * @see FBAInboundServiceMWS_Model_GetPackageLabelsRequest
     * @return FBAInboundServiceMWS_Model_GetPackageLabelsResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function getPackageLabels($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_GetPackageLabelsRequest)) {
            require_once (dirname(__FILE__) . '/Model/GetPackageLabelsRequest.php');
            $request = new FBAInboundServiceMWS_Model_GetPackageLabelsRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetPackageLabels';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetPackageLabelsResponse.php');
        $response = FBAInboundServiceMWS_Model_GetPackageLabelsResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetPackageLabelsRequest to name value pairs
     */
    private function _convertGetPackageLabels($request) {

        $parameters = array();
        $parameters['Action'] = 'GetPackageLabels';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetShipmentId()) {
            $parameters['ShipmentId'] =  $request->getShipmentId();
        }
        if ($request->isSetPageType()) {
            $parameters['PageType'] =  $request->getPageType();
        }
        if ($request->isSetNumberOfPackages()) {
            $parameters['NumberOfPackages'] =  $request->getNumberOfPackages();
        }

        return $parameters;
    }


    /**
     * Get Pallet Labels
     * Retrieves the PDF-formatted pallet label data for the pallets in an LTL shipment. These labels
     *     include relevant data for shipments being sent to Amazon Fulfillment Centers. The PDF data will be 
     *     ZIP'd and then it will be encoded as a Base64 string, and MD5 hash is included with the response to 
     *     validate the label data which will be encoded as Base64. The language of the address and FC prep 
     *     instructions sections of the labels are determined by the marketplace in which the request is being 
     *     made and the marketplace of the destination FC, respectively.
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_GetPalletLabels request or FBAInboundServiceMWS_Model_GetPalletLabels object itself
     * @see FBAInboundServiceMWS_Model_GetPalletLabelsRequest
     * @return FBAInboundServiceMWS_Model_GetPalletLabelsResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function getPalletLabels($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_GetPalletLabelsRequest)) {
            require_once (dirname(__FILE__) . '/Model/GetPalletLabelsRequest.php');
            $request = new FBAInboundServiceMWS_Model_GetPalletLabelsRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetPalletLabels';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetPalletLabelsResponse.php');
        $response = FBAInboundServiceMWS_Model_GetPalletLabelsResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetPalletLabelsRequest to name value pairs
     */
    private function _convertGetPalletLabels($request) {

        $parameters = array();
        $parameters['Action'] = 'GetPalletLabels';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetShipmentId()) {
            $parameters['ShipmentId'] =  $request->getShipmentId();
        }
        if ($request->isSetPageType()) {
            $parameters['PageType'] =  $request->getPageType();
        }
        if ($request->isSetNumberOfPallets()) {
            $parameters['NumberOfPallets'] =  $request->getNumberOfPallets();
        }

        return $parameters;
    }


    /**
     * Get Preorder Info
     * Given a shipment id. as input, based on the release date of the items in the
     * shipment, this API returns the suggested need By Date that the shipment items
     * must reach Amazon FC to successfully fulfill Pre-Orders without any promise
     * breaks.
     * This API also returns the confirmed Fullfillable date. All items in the
     * shipment that have a release date on or after this date would have the
     * pre-order buy box show up on the detail page if this shipment is marked as a
     * pre-orderable.
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_GetPreorderInfo request or FBAInboundServiceMWS_Model_GetPreorderInfo object itself
     * @see FBAInboundServiceMWS_Model_GetPreorderInfoRequest
     * @return FBAInboundServiceMWS_Model_GetPreorderInfoResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function getPreorderInfo($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_GetPreorderInfoRequest)) {
            require_once (dirname(__FILE__) . '/Model/GetPreorderInfoRequest.php');
            $request = new FBAInboundServiceMWS_Model_GetPreorderInfoRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetPreorderInfo';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetPreorderInfoResponse.php');
        $response = FBAInboundServiceMWS_Model_GetPreorderInfoResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetPreorderInfoRequest to name value pairs
     */
    private function _convertGetPreorderInfo($request) {

        $parameters = array();
        $parameters['Action'] = 'GetPreorderInfo';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplace()) {
            $parameters['Marketplace'] =  $request->getMarketplace();
        }
        if ($request->isSetShipmentId()) {
            $parameters['ShipmentId'] =  $request->getShipmentId();
        }

        return $parameters;
    }


    /**
     * Get Prep Instructions For ASIN
     * Returns the required prep that must be performed for a set of items, identified
     * by ASINs, that will be sent to Amazon. It returns guidance for the seller
     * on how to prepare the items to be sent in to Amazon's Fulfillment Centers, and
     * identifies the labeling required for the items, and gives the seller a list
     * of additional required prep that must be performed.
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_GetPrepInstructionsForASIN request or FBAInboundServiceMWS_Model_GetPrepInstructionsForASIN object itself
     * @see FBAInboundServiceMWS_Model_GetPrepInstructionsForASINRequest
     * @return FBAInboundServiceMWS_Model_GetPrepInstructionsForASINResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function getPrepInstructionsForASIN($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_GetPrepInstructionsForASINRequest)) {
            require_once (dirname(__FILE__) . '/Model/GetPrepInstructionsForASINRequest.php');
            $request = new FBAInboundServiceMWS_Model_GetPrepInstructionsForASINRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetPrepInstructionsForASIN';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetPrepInstructionsForASINResponse.php');
        $response = FBAInboundServiceMWS_Model_GetPrepInstructionsForASINResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetPrepInstructionsForASINRequest to name value pairs
     */
    private function _convertGetPrepInstructionsForASIN($request) {

        $parameters = array();
        $parameters['Action'] = 'GetPrepInstructionsForASIN';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetAsinList()) {
            $AsinListGetPrepInstructionsForASINRequest = $request->getAsinList();
            foreach  ($AsinListGetPrepInstructionsForASINRequest->getId() as $IdAsinListIndex => $IdAsinList) {
                $parameters['AsinList' . '.' . 'Id' . '.'  . ($IdAsinListIndex + 1)] =  $IdAsinList;
            }
        }
        if ($request->isSetShipToCountryCode()) {
            $parameters['ShipToCountryCode'] =  $request->getShipToCountryCode();
        }

        return $parameters;
    }


    /**
     * Get Prep Instructions For SKU
     * Returns the required prep that must be performed for a set of items, identified
     * by SellerSKUs, that will be sent to Amazon. It returns guidance for the seller
     * on how to prepare the items to be sent in to Amazon's Fulfillment Centers, and
     * identifies the labeling required for the items, and gives the seller a list
     * of additional required prep that must be performed.
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_GetPrepInstructionsForSKU request or FBAInboundServiceMWS_Model_GetPrepInstructionsForSKU object itself
     * @see FBAInboundServiceMWS_Model_GetPrepInstructionsForSKURequest
     * @return FBAInboundServiceMWS_Model_GetPrepInstructionsForSKUResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function getPrepInstructionsForSKU($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_GetPrepInstructionsForSKURequest)) {
            require_once (dirname(__FILE__) . '/Model/GetPrepInstructionsForSKURequest.php');
            $request = new FBAInboundServiceMWS_Model_GetPrepInstructionsForSKURequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetPrepInstructionsForSKU';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetPrepInstructionsForSKUResponse.php');
        $response = FBAInboundServiceMWS_Model_GetPrepInstructionsForSKUResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetPrepInstructionsForSKURequest to name value pairs
     */
    private function _convertGetPrepInstructionsForSKU($request) {

        $parameters = array();
        $parameters['Action'] = 'GetPrepInstructionsForSKU';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetSellerSKUList()) {
            $SellerSKUListGetPrepInstructionsForSKURequest = $request->getSellerSKUList();
            foreach  ($SellerSKUListGetPrepInstructionsForSKURequest->getId() as $IdSellerSKUListIndex => $IdSellerSKUList) {
                $parameters['SellerSKUList' . '.' . 'Id' . '.'  . ($IdSellerSKUListIndex + 1)] =  $IdSellerSKUList;
            }
        }
        if ($request->isSetShipToCountryCode()) {
            $parameters['ShipToCountryCode'] =  $request->getShipToCountryCode();
        }

        return $parameters;
    }


    /**
     * Get Service Status
     * Gets the status of the service.
     *   Status is one of GREEN, RED representing:
     *   GREEN: The service section is operating normally.
     *   RED: The service section disruption.
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_GetServiceStatus request or FBAInboundServiceMWS_Model_GetServiceStatus object itself
     * @see FBAInboundServiceMWS_Model_GetServiceStatusRequest
     * @return FBAInboundServiceMWS_Model_GetServiceStatusResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function getServiceStatus($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_GetServiceStatusRequest)) {
            require_once (dirname(__FILE__) . '/Model/GetServiceStatusRequest.php');
            $request = new FBAInboundServiceMWS_Model_GetServiceStatusRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetServiceStatus';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetServiceStatusResponse.php');
        $response = FBAInboundServiceMWS_Model_GetServiceStatusResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetServiceStatusRequest to name value pairs
     */
    private function _convertGetServiceStatus($request) {

        $parameters = array();
        $parameters['Action'] = 'GetServiceStatus';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplace()) {
            $parameters['Marketplace'] =  $request->getMarketplace();
        }

        return $parameters;
    }


    /**
     * Get Transport Content
     * A read-only operation which sellers use to retrieve the current
     *     details about the transportation of an inbound shipment, including status of the
     *     partnered carrier workflow and status of individual packages when they arrive at our FCs.
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_GetTransportContent request or FBAInboundServiceMWS_Model_GetTransportContent object itself
     * @see FBAInboundServiceMWS_Model_GetTransportContentRequest
     * @return FBAInboundServiceMWS_Model_GetTransportContentResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function getTransportContent($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_GetTransportContentRequest)) {
            require_once (dirname(__FILE__) . '/Model/GetTransportContentRequest.php');
            $request = new FBAInboundServiceMWS_Model_GetTransportContentRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetTransportContent';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetTransportContentResponse.php');
        $response = FBAInboundServiceMWS_Model_GetTransportContentResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetTransportContentRequest to name value pairs
     */
    private function _convertGetTransportContent($request) {

        $parameters = array();
        $parameters['Action'] = 'GetTransportContent';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetShipmentId()) {
            $parameters['ShipmentId'] =  $request->getShipmentId();
        }

        return $parameters;
    }


    /**
     * Get Unique Package Labels
     * Retrieves the PDF-formatted package label data for the packages of the
     *     shipment. These labels will include relevant data for shipments utilizing 
     *     Amazon-partnered carriers. Each label contains a unique package identifier that represents the mapping between
     *     physical and virtual packages. This API requires that Carton Information has been provided for all packages in the
     *     shipment. The PDF data will be ZIP'd and then it will be encoded as a Base64 string, and
     *     MD5 hash is included with the response to validate the label data which will be encoded as Base64.
     *     The language of the address and FC prep instructions sections of the labels are
     *     determined by the marketplace in which the request is being made and the marketplace of
     *     the destination FC, respectively.
     *     
     *     Only select PageTypes are supported in each marketplace. By marketplace, the
     *     supported types are:
     *       * US non-partnered UPS: PackageLabel_Letter_6
     *       * US partnered-UPS: PackageLabel_Letter_2
     *       * GB, DE, FR, IT, ES: PackageLabel_A4_4, PackageLabel_Plain_Paper
     *       * Partnered EU: PackageLabel_A4_2
     *       * JP/CN: PackageLabel_Plain_Paper
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_GetUniquePackageLabels request or FBAInboundServiceMWS_Model_GetUniquePackageLabels object itself
     * @see FBAInboundServiceMWS_Model_GetUniquePackageLabelsRequest
     * @return FBAInboundServiceMWS_Model_GetUniquePackageLabelsResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function getUniquePackageLabels($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_GetUniquePackageLabelsRequest)) {
            require_once (dirname(__FILE__) . '/Model/GetUniquePackageLabelsRequest.php');
            $request = new FBAInboundServiceMWS_Model_GetUniquePackageLabelsRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetUniquePackageLabels';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetUniquePackageLabelsResponse.php');
        $response = FBAInboundServiceMWS_Model_GetUniquePackageLabelsResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetUniquePackageLabelsRequest to name value pairs
     */
    private function _convertGetUniquePackageLabels($request) {

        $parameters = array();
        $parameters['Action'] = 'GetUniquePackageLabels';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetShipmentId()) {
            $parameters['ShipmentId'] =  $request->getShipmentId();
        }
        if ($request->isSetPageType()) {
            $parameters['PageType'] =  $request->getPageType();
        }
        if ($request->isSetPackageLabelsToPrint()) {
            $PackageLabelsToPrintGetUniquePackageLabelsRequest = $request->getPackageLabelsToPrint();
            foreach  ($PackageLabelsToPrintGetUniquePackageLabelsRequest->getmember() as $memberPackageLabelsToPrintIndex => $memberPackageLabelsToPrint) {
                $parameters['PackageLabelsToPrint' . '.' . 'member' . '.'  . ($memberPackageLabelsToPrintIndex + 1)] =  $memberPackageLabelsToPrint;
            }
        }

        return $parameters;
    }


    /**
     * List Inbound Shipment Items
     * Gets the first set of inbound shipment items for the given ShipmentId or 
     * all inbound shipment items updated between the given date range. 
     * A NextToken is also returned to further iterate through the Seller's 
     * remaining inbound shipment items. To get the next set of inbound 
     * shipment items, you must call ListInboundShipmentItemsByNextToken and 
     * pass in the 'NextToken' this call returned. If a NextToken is not 
     * returned, it indicates the end-of-data. Use LastUpdatedBefore 
     * and LastUpdatedAfter to filter results based on last updated time. 
     * Either the ShipmentId or a pair of LastUpdatedBefore and LastUpdatedAfter 
     * must be passed in. if ShipmentId is set, the LastUpdatedBefore and 
     * LastUpdatedAfter will be ignored.
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_ListInboundShipmentItems request or FBAInboundServiceMWS_Model_ListInboundShipmentItems object itself
     * @see FBAInboundServiceMWS_Model_ListInboundShipmentItemsRequest
     * @return FBAInboundServiceMWS_Model_ListInboundShipmentItemsResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function listInboundShipmentItems($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_ListInboundShipmentItemsRequest)) {
            require_once (dirname(__FILE__) . '/Model/ListInboundShipmentItemsRequest.php');
            $request = new FBAInboundServiceMWS_Model_ListInboundShipmentItemsRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'ListInboundShipmentItems';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/ListInboundShipmentItemsResponse.php');
        $response = FBAInboundServiceMWS_Model_ListInboundShipmentItemsResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert ListInboundShipmentItemsRequest to name value pairs
     */
    private function _convertListInboundShipmentItems($request) {

        $parameters = array();
        $parameters['Action'] = 'ListInboundShipmentItems';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplace()) {
            $parameters['Marketplace'] =  $request->getMarketplace();
        }
        if ($request->isSetShipmentId()) {
            $parameters['ShipmentId'] =  $request->getShipmentId();
        }
        if ($request->isSetLastUpdatedBefore()) {
            $parameters['LastUpdatedBefore'] =  $request->getLastUpdatedBefore();
        }
        if ($request->isSetLastUpdatedAfter()) {
            $parameters['LastUpdatedAfter'] =  $request->getLastUpdatedAfter();
        }

        return $parameters;
    }


    /**
     * List Inbound Shipment Items By Next Token
     * Gets the next set of inbound shipment items with the NextToken 
     * which can be used to iterate through the remaining inbound shipment 
     * items. If a NextToken is not returned, it indicates the end-of-data. 
     * You must first call ListInboundShipmentItems to get a 'NextToken'.
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_ListInboundShipmentItemsByNextToken request or FBAInboundServiceMWS_Model_ListInboundShipmentItemsByNextToken object itself
     * @see FBAInboundServiceMWS_Model_ListInboundShipmentItemsByNextTokenRequest
     * @return FBAInboundServiceMWS_Model_ListInboundShipmentItemsByNextTokenResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function listInboundShipmentItemsByNextToken($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_ListInboundShipmentItemsByNextTokenRequest)) {
            require_once (dirname(__FILE__) . '/Model/ListInboundShipmentItemsByNextTokenRequest.php');
            $request = new FBAInboundServiceMWS_Model_ListInboundShipmentItemsByNextTokenRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'ListInboundShipmentItemsByNextToken';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/ListInboundShipmentItemsByNextTokenResponse.php');
        $response = FBAInboundServiceMWS_Model_ListInboundShipmentItemsByNextTokenResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert ListInboundShipmentItemsByNextTokenRequest to name value pairs
     */
    private function _convertListInboundShipmentItemsByNextToken($request) {

        $parameters = array();
        $parameters['Action'] = 'ListInboundShipmentItemsByNextToken';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplace()) {
            $parameters['Marketplace'] =  $request->getMarketplace();
        }
        if ($request->isSetNextToken()) {
            $parameters['NextToken'] =  $request->getNextToken();
        }

        return $parameters;
    }


    /**
     * List Inbound Shipments
     * Get the first set of inbound shipments created by a Seller according to 
     * the specified shipment status or the specified shipment Id. A NextToken 
     * is also returned to further iterate through the Seller's remaining 
     * shipments. If a NextToken is not returned, it indicates the end-of-data.
     * At least one of ShipmentStatusList and ShipmentIdList must be passed in. 
     * if both are passed in, then only shipments that match the specified 
     * shipment Id and specified shipment status will be returned.
     * the LastUpdatedBefore and LastUpdatedAfter are optional, they are used 
     * to filter results based on last update time of the shipment.
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_ListInboundShipments request or FBAInboundServiceMWS_Model_ListInboundShipments object itself
     * @see FBAInboundServiceMWS_Model_ListInboundShipmentsRequest
     * @return FBAInboundServiceMWS_Model_ListInboundShipmentsResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function listInboundShipments($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_ListInboundShipmentsRequest)) {
            require_once (dirname(__FILE__) . '/Model/ListInboundShipmentsRequest.php');
            $request = new FBAInboundServiceMWS_Model_ListInboundShipmentsRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'ListInboundShipments';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/ListInboundShipmentsResponse.php');
        $response = FBAInboundServiceMWS_Model_ListInboundShipmentsResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert ListInboundShipmentsRequest to name value pairs
     */
    private function _convertListInboundShipments($request) {

        $parameters = array();
        $parameters['Action'] = 'ListInboundShipments';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplace()) {
            $parameters['Marketplace'] =  $request->getMarketplace();
        }
        if ($request->isSetShipmentStatusList()) {
            $ShipmentStatusListListInboundShipmentsRequest = $request->getShipmentStatusList();
            foreach  ($ShipmentStatusListListInboundShipmentsRequest->getmember() as $memberShipmentStatusListIndex => $memberShipmentStatusList) {
                $parameters['ShipmentStatusList' . '.' . 'member' . '.'  . ($memberShipmentStatusListIndex + 1)] =  $memberShipmentStatusList;
            }
        }
        if ($request->isSetShipmentIdList()) {
            $ShipmentIdListListInboundShipmentsRequest = $request->getShipmentIdList();
            foreach  ($ShipmentIdListListInboundShipmentsRequest->getmember() as $memberShipmentIdListIndex => $memberShipmentIdList) {
                $parameters['ShipmentIdList' . '.' . 'member' . '.'  . ($memberShipmentIdListIndex + 1)] =  $memberShipmentIdList;
            }
        }
        if ($request->isSetLastUpdatedBefore()) {
            $parameters['LastUpdatedBefore'] =  $request->getLastUpdatedBefore();
        }
        if ($request->isSetLastUpdatedAfter()) {
            $parameters['LastUpdatedAfter'] =  $request->getLastUpdatedAfter();
        }

        return $parameters;
    }


    /**
     * List Inbound Shipments By Next Token
     * Gets the next set of inbound shipments created by a Seller with the 
     * NextToken which can be used to iterate through the remaining inbound 
     * shipments. If a NextToken is not returned, it indicates the end-of-data.
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_ListInboundShipmentsByNextToken request or FBAInboundServiceMWS_Model_ListInboundShipmentsByNextToken object itself
     * @see FBAInboundServiceMWS_Model_ListInboundShipmentsByNextTokenRequest
     * @return FBAInboundServiceMWS_Model_ListInboundShipmentsByNextTokenResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function listInboundShipmentsByNextToken($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_ListInboundShipmentsByNextTokenRequest)) {
            require_once (dirname(__FILE__) . '/Model/ListInboundShipmentsByNextTokenRequest.php');
            $request = new FBAInboundServiceMWS_Model_ListInboundShipmentsByNextTokenRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'ListInboundShipmentsByNextToken';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/ListInboundShipmentsByNextTokenResponse.php');
        $response = FBAInboundServiceMWS_Model_ListInboundShipmentsByNextTokenResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert ListInboundShipmentsByNextTokenRequest to name value pairs
     */
    private function _convertListInboundShipmentsByNextToken($request) {

        $parameters = array();
        $parameters['Action'] = 'ListInboundShipmentsByNextToken';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplace()) {
            $parameters['Marketplace'] =  $request->getMarketplace();
        }
        if ($request->isSetNextToken()) {
            $parameters['NextToken'] =  $request->getNextToken();
        }

        return $parameters;
    }


    /**
     * Put Transport Content
     * A write operation which sellers use to provide transportation details regarding
     *     how an inbound shipment will arrive at Amazon's Fulfillment Centers.
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_PutTransportContent request or FBAInboundServiceMWS_Model_PutTransportContent object itself
     * @see FBAInboundServiceMWS_Model_PutTransportContentRequest
     * @return FBAInboundServiceMWS_Model_PutTransportContentResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function putTransportContent($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_PutTransportContentRequest)) {
            require_once (dirname(__FILE__) . '/Model/PutTransportContentRequest.php');
            $request = new FBAInboundServiceMWS_Model_PutTransportContentRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'PutTransportContent';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/PutTransportContentResponse.php');
        $response = FBAInboundServiceMWS_Model_PutTransportContentResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert PutTransportContentRequest to name value pairs
     */
    private function _convertPutTransportContent($request) {

        $parameters = array();
        $parameters['Action'] = 'PutTransportContent';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetShipmentId()) {
            $parameters['ShipmentId'] =  $request->getShipmentId();
        }
        if ($request->isSetIsPartnered()) {
            $parameters['IsPartnered'] =  $request->getIsPartnered() ? "true" : "false";
        }
        if ($request->isSetShipmentType()) {
            $parameters['ShipmentType'] =  $request->getShipmentType();
        }
        if ($request->isSetTransportDetails()) {
            $TransportDetailsPutTransportContentRequest = $request->getTransportDetails();
            foreach  ($TransportDetailsPutTransportContentRequest->getPartneredSmallParcelData() as $PartneredSmallParcelDataTransportDetailsIndex => $PartneredSmallParcelDataTransportDetails) {
                $parameters['TransportDetails' . '.' . 'PartneredSmallParcelData' . '.'  . ($PartneredSmallParcelDataTransportDetailsIndex + 1)] =  $PartneredSmallParcelDataTransportDetails;
            }
        }

        return $parameters;
    }


    /**
     * Update Inbound Shipment
     * Updates an pre-existing inbound shipment specified by the 
     * ShipmentId. It may include up to 200 items.
     * If InboundShipmentHeader is set. it replaces the header information 
     * for the given shipment.
     * If InboundShipmentItems is set. it adds, replaces and removes 
     * the line time to inbound shipment.
     * For non-existing item, it will add the item for new line item; 
     * For existing line items, it will replace the QuantityShipped for the item.
     * For QuantityShipped = 0, it indicates the item should be removed from the shipment
     * 
     * This operation will simply return a shipment Id upon success,
     * otherwise an explicit error will be returned.
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_UpdateInboundShipment request or FBAInboundServiceMWS_Model_UpdateInboundShipment object itself
     * @see FBAInboundServiceMWS_Model_UpdateInboundShipmentRequest
     * @return FBAInboundServiceMWS_Model_UpdateInboundShipmentResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function updateInboundShipment($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_UpdateInboundShipmentRequest)) {
            require_once (dirname(__FILE__) . '/Model/UpdateInboundShipmentRequest.php');
            $request = new FBAInboundServiceMWS_Model_UpdateInboundShipmentRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'UpdateInboundShipment';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/UpdateInboundShipmentResponse.php');
        $response = FBAInboundServiceMWS_Model_UpdateInboundShipmentResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert UpdateInboundShipmentRequest to name value pairs
     */
    private function _convertUpdateInboundShipment($request) {

        $parameters = array();
        $parameters['Action'] = 'UpdateInboundShipment';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplace()) {
            $parameters['Marketplace'] =  $request->getMarketplace();
        }
        if ($request->isSetShipmentId()) {
            $parameters['ShipmentId'] =  $request->getShipmentId();
        }
        if ($request->isSetInboundShipmentHeader()) {
            $InboundShipmentHeaderUpdateInboundShipmentRequest = $request->getInboundShipmentHeader();
            foreach  ($InboundShipmentHeaderUpdateInboundShipmentRequest->getShipmentName() as $ShipmentNameInboundShipmentHeaderIndex => $ShipmentNameInboundShipmentHeader) {
                $parameters['InboundShipmentHeader' . '.' . 'ShipmentName' . '.'  . ($ShipmentNameInboundShipmentHeaderIndex + 1)] =  $ShipmentNameInboundShipmentHeader;
            }
        }
        if ($request->isSetInboundShipmentItems()) {
            $InboundShipmentItemsUpdateInboundShipmentRequest = $request->getInboundShipmentItems();
            foreach  ($InboundShipmentItemsUpdateInboundShipmentRequest->getmember() as $memberInboundShipmentItemsIndex => $memberInboundShipmentItems) {
                $parameters['InboundShipmentItems' . '.' . 'member' . '.'  . ($memberInboundShipmentItemsIndex + 1)] =  $memberInboundShipmentItems;
            }
        }

        return $parameters;
    }


    /**
     * Void Transport Request
     * Voids a previously-confirmed transport request. It only succeeds for requests
     *     made by the VoidDeadline provided in the PartneredEstimate component of the
     *     response of the GetTransportContent operation for a shipment. Currently this
     *     deadline is 24 hours after confirming a transport request for a partnered small parcel
     *     request and 1 hour after confirming a transport request for a partnered LTL/TL
     *     request, though this is subject to change at any time without notice.
     *
     * @param mixed $request array of parameters for FBAInboundServiceMWS_Model_VoidTransportRequest request or FBAInboundServiceMWS_Model_VoidTransportRequest object itself
     * @see FBAInboundServiceMWS_Model_VoidTransportInputRequest
     * @return FBAInboundServiceMWS_Model_VoidTransportRequestResponse
     *
     * @throws FBAInboundServiceMWS_Exception
     */
    public function voidTransportRequest($request)
    {
        if (!($request instanceof FBAInboundServiceMWS_Model_VoidTransportInputRequest)) {
            require_once (dirname(__FILE__) . '/Model/VoidTransportInputRequest.php');
            $request = new FBAInboundServiceMWS_Model_VoidTransportInputRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'VoidTransportRequest';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/VoidTransportRequestResponse.php');
        $response = FBAInboundServiceMWS_Model_VoidTransportRequestResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert VoidTransportInputRequest to name value pairs
     */
    private function _convertVoidTransportRequest($request) {

        $parameters = array();
        $parameters['Action'] = 'VoidTransportRequest';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetShipmentId()) {
            $parameters['ShipmentId'] =  $request->getShipmentId();
        }

        return $parameters;
    }



    /**
     * Construct new Client
     *
     * @param string $awsAccessKeyId AWS Access Key ID
     * @param string $awsSecretAccessKey AWS Secret Access Key
     * @param array $config configuration options.
     * Valid configuration options are:
     * <ul>
     * <li>ServiceURL</li>
     * <li>UserAgent</li>
     * <li>SignatureVersion</li>
     * <li>TimesRetryOnError</li>
     * <li>ProxyHost</li>
     * <li>ProxyPort</li>
     * <li>ProxyUsername<li>
     * <li>ProxyPassword<li>
     * <li>MaxErrorRetry</li>
     * </ul>
     */
    public function __construct($awsAccessKeyId, $awsSecretAccessKey, $applicationName, $applicationVersion, $config = null)
    {
        // iconv_set_encoding('output_encoding', 'UTF-8');
        // iconv_set_encoding('input_encoding', 'UTF-8');
        // iconv_set_encoding('internal_encoding', 'UTF-8');

        $this->_awsAccessKeyId = $awsAccessKeyId;
        $this->_awsSecretAccessKey = $awsSecretAccessKey;
        if (!is_null($config)) $this->_config = array_merge($this->_config, $config);
        $this->setUserAgentHeader($applicationName, $applicationVersion);
    }

    private function setUserAgentHeader(
        $applicationName,
        $applicationVersion,
        $attributes = null) {

        if (is_null($attributes)) {
            $attributes = array ();
        }

        $this->_config['UserAgent'] = 
            $this->constructUserAgentHeader($applicationName, $applicationVersion, $attributes);
    }

    private function constructUserAgentHeader($applicationName, $applicationVersion, $attributes = null) {
        if (is_null($applicationName) || $applicationName === "") {
            throw new InvalidArgumentException('$applicationName cannot be null');
        }

        if (is_null($applicationVersion) || $applicationVersion === "") {
            throw new InvalidArgumentException('$applicationVersion cannot be null');
        }

        $userAgent = 
            $this->quoteApplicationName($applicationName)
            . '/'
            . $this->quoteApplicationVersion($applicationVersion);

        $userAgent .= ' (';
        $userAgent .= 'Language=PHP/' . phpversion();
        $userAgent .= '; ';
        $userAgent .= 'Platform=' . php_uname('s') . '/' . php_uname('m') . '/' . php_uname('r');
        $userAgent .= '; ';
        $userAgent .= 'MWSClientVersion=' . self::MWS_CLIENT_VERSION;

        foreach ($attributes as $key => $value) {
            if (empty($value)) {
                throw new InvalidArgumentException("Value for $key cannot be null or empty.");
            }

            $userAgent .= '; '
                . $this->quoteAttributeName($key)
                . '='
                . $this->quoteAttributeValue($value);
        }

        $userAgent .= ')';

        return $userAgent;
    }

   /**
    * Collapse multiple whitespace characters into a single ' ' character.
    * @param $s
    * @return string
    */
   private function collapseWhitespace($s) {
       return preg_replace('/ {2,}|\s/', ' ', $s);
   }

    /**
     * Collapse multiple whitespace characters into a single ' ' and backslash escape '\',
     * and '/' characters from a string.
     * @param $s
     * @return string
     */
    private function quoteApplicationName($s) {
        $quotedString = $this->collapseWhitespace($s);
        $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
        $quotedString = preg_replace('/\//', '\\/', $quotedString);

        return $quotedString;
    }

    /**
     * Collapse multiple whitespace characters into a single ' ' and backslash escape '\',
     * and '(' characters from a string.
     *
     * @param $s
     * @return string
     */
    private function quoteApplicationVersion($s) {
        $quotedString = $this->collapseWhitespace($s);
        $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
        $quotedString = preg_replace('/\\(/', '\\(', $quotedString);

        return $quotedString;
    }

    /**
     * Collapse multiple whitespace characters into a single ' ' and backslash escape '\',
     * and '=' characters from a string.
     *
     * @param $s
     * @return unknown_type
     */
    private function quoteAttributeName($s) {
        $quotedString = $this->collapseWhitespace($s);
        $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
        $quotedString = preg_replace('/\\=/', '\\=', $quotedString);

        return $quotedString;
    }

    /**
     * Collapse multiple whitespace characters into a single ' ' and backslash escape ';', '\',
     * and ')' characters from a string.
     *
     * @param $s
     * @return unknown_type
     */
    private function quoteAttributeValue($s) {
        $quotedString = $this->collapseWhitespace($s);
        $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
        $quotedString = preg_replace('/\\;/', '\\;', $quotedString);
        $quotedString = preg_replace('/\\)/', '\\)', $quotedString);

        return $quotedString;
    }


    // Private API ------------------------------------------------------------//

    /**
     * Invoke request and return response
     */
    private function _invoke(array $parameters)
    {
        try {
            if (empty($this->_config['ServiceURL'])) {
                require_once (dirname(__FILE__) . '/Exception.php');
                throw new FBAInboundServiceMWS_Exception(
                    array ('ErrorCode' => 'InvalidServiceURL',
                           'Message' => "Missing serviceUrl configuration value. You may obtain a list of valid MWS URLs by consulting the MWS Developer's Guide, or reviewing the sample code published along side this library."));
            }
            $parameters = $this->_addRequiredParameters($parameters);
            $retries = 0;
            for (;;) {
                $response = $this->_httpPost($parameters);
                $status = $response['Status'];
                if ($status == 200) {
                    return array('ResponseBody' => $response['ResponseBody'],
                      'ResponseHeaderMetadata' => $response['ResponseHeaderMetadata']);
                }
                if ($status == 500 && $this->_pauseOnRetry(++$retries)) {
                    continue;
                }
                throw $this->_reportAnyErrors($response['ResponseBody'],
                    $status, $response['ResponseHeaderMetadata']);
            }
        } catch (FBAInboundServiceMWS_Exception $se) {
            throw $se;
        } catch (Exception $t) {
            require_once (dirname(__FILE__) . '/Exception.php');
            throw new FBAInboundServiceMWS_Exception(array('Exception' => $t, 'Message' => $t->getMessage()));
        }
    }

    /**
     * Look for additional error strings in the response and return formatted exception
     */
    private function _reportAnyErrors($responseBody, $status, $responseHeaderMetadata, Exception $e =  null)
    {
        $exProps = array();
        $exProps["StatusCode"] = $status;
        $exProps["ResponseHeaderMetadata"] = $responseHeaderMetadata;

        libxml_use_internal_errors(true);  // Silence XML parsing errors
        $xmlBody = simplexml_load_string($responseBody);

        if ($xmlBody !== false) {  // Check XML loaded without errors
            $exProps["XML"] = $responseBody;
            $exProps["ErrorCode"] = $xmlBody->Error->Code;
            $exProps["Message"] = $xmlBody->Error->Message;
            $exProps["ErrorType"] = !empty($xmlBody->Error->Type) ? $xmlBody->Error->Type : "Unknown";
            $exProps["RequestId"] = !empty($xmlBody->RequestID) ? $xmlBody->RequestID : $xmlBody->RequestId; // 'd' in RequestId is sometimes capitalized
        } else { // We got bad XML in response, just throw a generic exception
            $exProps["Message"] = "Internal Error";
        }

        require_once (dirname(__FILE__) . '/Exception.php');
        return new FBAInboundServiceMWS_Exception($exProps);
    }



    /**
     * Perform HTTP post with exponential retries on error 500 and 503
     *
     */
    private function _httpPost(array $parameters)
    {
        $config = $this->_config;
        $query = $this->_getParametersAsString($parameters);
        $url = parse_url ($config['ServiceURL']);
        $uri = array_key_exists('path', $url) ? $url['path'] : null;
        if (!isset ($uri)) {
                $uri = "/";
        }

        switch ($url['scheme']) {
            case 'https':
                $scheme = 'https://';
                $port = isset($url['port']) ? $url['port'] : 443;
                break;
            default:
                $scheme = 'http://';
                $port = isset($url['port']) ? $url['port'] : 80;
        }

        $allHeaders = $config['Headers'];
        $allHeaders['Content-Type'] = "application/x-www-form-urlencoded; charset=utf-8"; // We need to make sure to set utf-8 encoding here
        $allHeaders['Expect'] = null; // Don't expect 100 Continue
        $allHeadersStr = array();
        foreach($allHeaders as $name => $val) {
            $str = $name . ": ";
            if(isset($val)) {
                $str = $str . $val;
            }
            $allHeadersStr[] = $str;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $scheme . $url['host'] . $uri);
        curl_setopt($ch, CURLOPT_PORT, $port);
        $this->setSSLCurlOptions($ch);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->_config['UserAgent']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $allHeadersStr);
        curl_setopt($ch, CURLOPT_HEADER, true); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($config['ProxyHost'] != null && $config['ProxyPort'] != -1)
        {
            curl_setopt($ch, CURLOPT_PROXY, $config['ProxyHost'] . ':' . $config['ProxyPort']);
        }
        if ($config['ProxyUsername'] != null && $config['ProxyPassword'] != null)
        {
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $config['ProxyUsername'] . ':' . $config['ProxyPassword']);
        }

        $response = "";
        $response = curl_exec($ch);

        if($response === false) {
            require_once (dirname(__FILE__) . '/Exception.php');
            $exProps["Message"] = curl_error($ch);
            $exProps["ErrorType"] = "HTTP";
            curl_close($ch);
            throw new FBAInboundServiceMWS_Exception($exProps);
        }

        curl_close($ch);
        return $this->_extractHeadersAndBody($response);
    }
    
    /**
     * This method will attempt to extract the headers and body of our response.
     * We need to split the raw response string by 2 'CRLF's.  2 'CRLF's should indicate the separation of the response header
     * from the response body.  However in our case we have some circumstances (certain client proxies) that result in 
     * multiple responses concatenated.  We could encounter a response like
     *
     * HTTP/1.1 100 Continue
     *
     * HTTP/1.1 200 OK
     * Date: Tue, 01 Apr 2014 13:02:51 GMT
     * Content-Type: text/html; charset=iso-8859-1
     * Content-Length: 12605
     *
     * ... body ..
     *
     * This method will throw away extra response status lines and attempt to find the first full response headers and body
     *
     * return [status, body, ResponseHeaderMetadata]
     */
    private function _extractHeadersAndBody($response){
        //First split by 2 'CRLF'
        $responseComponents = preg_split("/(?:\r?\n){2}/", $response, 2);
        $body = null;
        for ($count = 0; 
                $count < count($responseComponents) && $body == null; 
                $count++) {
            
            $headers = $responseComponents[$count];
            $responseStatus = $this->_extractHttpStatusCode($headers);
            
            if($responseStatus != null && 
                    $this->_httpHeadersHaveContent($headers)){
                
                $responseHeaderMetadata = $this->_extractResponseHeaderMetadata($headers);
                //The body will be the next item in the responseComponents array
                $body = $responseComponents[++$count];
            }
        }
        
        //If the body is null here then we were unable to parse the response and will throw an exception
        if($body == null){
            require_once (dirname(__FILE__) . '/Exception.php');
            $exProps["Message"] = "Failed to parse valid HTTP response (" . $response . ")";
            $exProps["ErrorType"] = "HTTP";
            throw new FBAInboundServiceMWS_Exception($exProps);
        }

        return array(
                'Status' => $responseStatus, 
                'ResponseBody' => $body, 
                'ResponseHeaderMetadata' => $responseHeaderMetadata);
    }
    
    /**
     * parse the status line of a header string for the proper format and
     * return the status code
     *
     * Example: HTTP/1.1 200 OK
     * ...
     * returns String statusCode or null if the status line can't be parsed
     */
    private function _extractHttpStatusCode($headers){
    	$statusCode = null; 
        if (1 === preg_match("/(\\S+) +(\\d+) +([^\n\r]+)(?:\r?\n|\r)/", $headers, $matches)) {
        	//The matches array [entireMatchString, protocol, statusCode, the rest]
            $statusCode = $matches[2]; 
        }
        return $statusCode;
    }
    
    /**
     * Tries to determine some valid headers indicating this response
     * has content.  In this case
     * return true if there is a valid "Content-Length" or "Transfer-Encoding" header
     */
    private function _httpHeadersHaveContent($headers){
        return (1 === preg_match("/[cC]ontent-[lL]ength: +(?:\\d+)(?:\\r?\\n|\\r|$)/", $headers) ||
                1 === preg_match("/Transfer-Encoding: +(?!identity[\r\n;= ])(?:[^\r\n]+)(?:\r?\n|\r|$)/i", $headers));
    }
    
    /**
    *  extract a ResponseHeaderMetadata object from the raw headers
    */
    private function _extractResponseHeaderMetadata($rawHeaders){
        $inputHeaders = preg_split("/\r\n|\n|\r/", $rawHeaders);
        $headers = array();
        $headers['x-mws-request-id'] = null;
        $headers['x-mws-response-context'] = null;
        $headers['x-mws-timestamp'] = null;
        $headers['x-mws-quota-max'] = null;
        $headers['x-mws-quota-remaining'] = null;
        $headers['x-mws-quota-resetsOn'] = null;

        foreach ($inputHeaders as $currentHeader) {
            $keyValue = explode (': ', $currentHeader);
            if (isset($keyValue[1])) {
                list ($key, $value) = $keyValue;
                if (isset($headers[$key]) && $headers[$key]!==null) {
                    $headers[$key] = $headers[$key] . "," . $value;
                } else {
                    $headers[$key] = $value;
                }
            }
        }
 
        require_once(dirname(__FILE__) . '/Model/ResponseHeaderMetadata.php');
        return new FBAInboundServiceMWS_Model_ResponseHeaderMetadata(
          $headers['x-mws-request-id'],
          $headers['x-mws-response-context'],
          $headers['x-mws-timestamp'],
          $headers['x-mws-quota-max'],
          $headers['x-mws-quota-remaining'],
          $headers['x-mws-quota-resetsOn']);
    }

    /**
     * Set curl options relating to SSL. Protected to allow overriding.
     * @param $ch curl handle
     */
    protected function setSSLCurlOptions($ch) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }

    /**
     * Exponential sleep on failed request
     *
     * @param retries current retry
     */
    private function _pauseOnRetry($retries)
    {
        if ($retries <= $this->_config['MaxErrorRetry']) {
            $delay = (int) (pow(4, $retries) * 100000);
            usleep($delay);
            return true;
        } 
        return false;
    }

    /**
     * Add authentication related and version parameters
     */
    private function _addRequiredParameters(array $parameters)
    {
        $parameters['AWSAccessKeyId'] = $this->_awsAccessKeyId;
        $parameters['Timestamp'] = $this->_getFormattedTimestamp();
        $parameters['Version'] = self::SERVICE_VERSION;
        $parameters['SignatureVersion'] = $this->_config['SignatureVersion'];
        if ($parameters['SignatureVersion'] > 1) {
            $parameters['SignatureMethod'] = $this->_config['SignatureMethod'];
        }
        $parameters['Signature'] = $this->_signParameters($parameters, $this->_awsSecretAccessKey);

        return $parameters;
    }

    /**
     * Convert paremeters to Url encoded query string
     */
    private function _getParametersAsString(array $parameters)
    {
        $queryParameters = array();
        foreach ($parameters as $key => $value) {
            $queryParameters[] = $key . '=' . $this->_urlencode($value);
        }
        return implode('&', $queryParameters);
    }


    /**
     * Computes RFC 2104-compliant HMAC signature for request parameters
     * Implements AWS Signature, as per following spec:
     *
     * If Signature Version is 0, it signs concatenated Action and Timestamp
     *
     * If Signature Version is 1, it performs the following:
     *
     * Sorts all  parameters (including SignatureVersion and excluding Signature,
     * the value of which is being created), ignoring case.
     *
     * Iterate over the sorted list and append the parameter name (in original case)
     * and then its value. It will not URL-encode the parameter values before
     * constructing this string. There are no separators.
     *
     * If Signature Version is 2, string to sign is based on following:
     *
     *    1. The HTTP Request Method followed by an ASCII newline (%0A)
     *    2. The HTTP Host header in the form of lowercase host, followed by an ASCII newline.
     *    3. The URL encoded HTTP absolute path component of the URI
     *       (up to but not including the query string parameters);
     *       if this is empty use a forward '/'. This parameter is followed by an ASCII newline.
     *    4. The concatenation of all query string components (names and values)
     *       as UTF-8 characters which are URL encoded as per RFC 3986
     *       (hex characters MUST be uppercase), sorted using lexicographic byte ordering.
     *       Parameter names are separated from their values by the '=' character
     *       (ASCII character 61), even if the value is empty.
     *       Pairs of parameter and values are separated by the '&' character (ASCII code 38).
     *
     */
    private function _signParameters(array $parameters, $key) {
        $signatureVersion = $parameters['SignatureVersion'];
        $algorithm = "HmacSHA1";
        $stringToSign = null;
        if (2 == $signatureVersion) {
            $algorithm = $this->_config['SignatureMethod'];
            $parameters['SignatureMethod'] = $algorithm;
            $stringToSign = $this->_calculateStringToSignV2($parameters);
        } else {
            throw new Exception("Invalid Signature Version specified");
        }
        return $this->_sign($stringToSign, $key, $algorithm);
    }

    /**
     * Calculate String to Sign for SignatureVersion 2
     * @param array $parameters request parameters
     * @return String to Sign
     */
    private function _calculateStringToSignV2(array $parameters) {
        $data = 'POST';
        $data .= "\n";
        $endpoint = parse_url ($this->_config['ServiceURL']);
        $data .= $endpoint['host'];
        $data .= "\n";
        $uri = array_key_exists('path', $endpoint) ? $endpoint['path'] : null;
        if (!isset ($uri)) {
            $uri = "/";
        }
        $uriencoded = implode("/", array_map(array($this, "_urlencode"), explode("/", $uri)));
        $data .= $uriencoded;
        $data .= "\n";
        uksort($parameters, 'strcmp');
        $data .= $this->_getParametersAsString($parameters);
        return $data;
    }

    private function _urlencode($value) {
        return str_replace('%7E', '~', rawurlencode($value));
    }


    /**
     * Computes RFC 2104-compliant HMAC signature.
     */
    private function _sign($data, $key, $algorithm)
    {
        if ($algorithm === 'HmacSHA1') {
            $hash = 'sha1';
        } else if ($algorithm === 'HmacSHA256') {
            $hash = 'sha256';
        } else {
            throw new Exception ("Non-supported signing method specified");
        }
        return base64_encode(
            hash_hmac($hash, $data, $key, true)
        );
    }


    /**
     * Formats date as ISO 8601 timestamp
     */
    private function _getFormattedTimestamp()
    {
        return gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
    }

    /**
     * Formats date as ISO 8601 timestamp
     */
    private function getFormattedTimestamp($dateTime)
    {
        return $dateTime->format(DATE_ISO8601);
    }

}

/*******************************************************************************
 *
 *  Marketplace Web Service PHP5 Library
 *  Generated: Thu May 07 13:07:36 PDT 2009
 *
 */

/**
 *  @see MarketplaceWebService_Interface
 */
require_once ('MarketplaceWebService/Interface.php');
require_once ('RequestType.php');

define('CONVERTED_PARAMETERS_KEY', 'PARAMETERS');
define('CONVERTED_HEADERS_KEY', 'HEADERS');

/**
 * The Amazon Marketplace Web Service contain APIs for inventory and order management.
 *
 * MarketplaceWebService_Client is an implementation of MarketplaceWebService
 *
 */
class MarketplaceWebService_Client implements MarketplaceWebService_Interface
{

  /** @var string */
  private  $awsAccessKeyId = null;

  /** @var string */
  private  $awsSecretAccessKey = null;

  /** @var array */
  private  $config = array ('ServiceURL' => null,
                            'UserAgent' => 'PHP Client Library/2016-09-21 (Language=PHP5)',
                            'SignatureVersion' => 2,
                            'SignatureMethod' => 'HmacSHA256',
                            'ProxyHost' => null,
                            'ProxyPort' => -1,
                            'MaxErrorRetry' => 3,
  );

  const SERVICE_VERSION = '2009-01-01';

  const REQUEST_TYPE = "POST";

  const MWS_CLIENT_VERSION = '2016-09-21';
  
  private $defaultHeaders = array();

  private $responseBodyContents;
  
  // "streaming" responses that are errors will be send to this handle;
  private $errorResponseBody;

  private $headerContents;
  
  private $curlClient;

  /**
   * Construct new Client
   *
   * @param string $awsAccessKeyId AWS Access Key ID
   * @param string $awsSecretAccessKey AWS Secret Access Key
   * @param array $config configuration options.
   * @param string $applicationName application name.
   * @param string $applicationVersion application version.
   * @param array $attributes user-agent attributes
   * @return unknown_type
   * Valid configuration options are:
   * <ul>
   * <li>ServiceURL</li>
   * <li>SignatureVersion</li>
   * <li>TimesRetryOnError</li>
   * <li>ProxyHost</li>
   * <li>ProxyPort</li>
   * <li>MaxErrorRetry</li>
   * </ul>
   */
  public function __construct(
  $awsAccessKeyId, $awsSecretAccessKey, $config, $applicationName, $applicationVersion, $attributes = null) {
    $this->awsAccessKeyId = $awsAccessKeyId;
    $this->awsSecretAccessKey = $awsSecretAccessKey;
    if (!is_null($config)) 
      $this->config = array_merge($this->config, $config);
     
    $this->setUserAgentHeader($applicationName, $applicationVersion, $attributes);
  }

  /**
   * Sets a MWS compliant HTTP User-Agent Header value.
   * $attributeNameValuePairs is an associative array.
   *
   * @param $applicationName
   * @param $applicationVersion
   * @param $attributes
   * @return unknown_type
   */
  public function setUserAgentHeader(
      $applicationName,
      $applicationVersion,
      $attributes = null) {

    if (is_null($attributes)) {
      $attributes = array ();
    }

    $this->config['UserAgent'] =
        $this->constructUserAgentHeader($applicationName, $applicationVersion, $attributes);
  }

  /**
   * Construct a valid MWS compliant HTTP User-Agent Header. From the MWS Developer's Guide, this
   * entails:
   * "To meet the requirements, begin with the name of your application, followed by a forward
   * slash, followed by the version of the application, followed by a space, an opening
   * parenthesis, the Language name value pair, and a closing paranthesis. The Language parameter
   * is a required attribute, but you can add additional attributes separated by semi-colons."
   *
   * @param $applicationName
   * @param $applicationVersion
   * @param $additionalNameValuePairs
   * @return unknown_type
   */
  private function constructUserAgentHeader($applicationName, $applicationVersion, $attributes = null) {

    if (is_null($applicationName) || $applicationName === "") {
      throw new InvalidArgumentException('$applicationName cannot be null.');
    }
     
    if (is_null($applicationVersion) || $applicationVersion === "") {
      throw new InvalidArgumentException('$applicationVersion cannot be null.');
    }
     
    $userAgent =
    $this->quoteApplicationName($applicationName)
        . '/'
        . $this->quoteApplicationVersion($applicationVersion);

    $userAgent .= ' (';

    $userAgent .= 'Language=PHP/' . phpversion();
    $userAgent .= '; ';
    $userAgent .= 'Platform=' . php_uname('s') . '/' . php_uname('m') . '/' . php_uname('r');
    $userAgent .= '; ';
    $userAgent .= 'MWSClientVersion=' . self::MWS_CLIENT_VERSION;

    foreach ($attributes as $key => $value) {
      if (is_null($value) || $value === '') {
        throw new InvalidArgumentException("Value for $key cannot be null or empty.");
      }
        
      $userAgent .= '; '
        . $this->quoteAttributeName($key)
        . '='
        . $this->quoteAttributeValue($value);
    }
    $userAgent .= ')';

    return $userAgent;
  }

  /**
   * Collapse multiple whitespace characters into a single ' ' character.
   * @param $s
   * @return string
   */
  private function collapseWhitespace($s) {
    return preg_replace('/ {2,}|\s/', ' ', $s);
  }

  /**
   * Collapse multiple whitespace characters into a single ' ' and backslash escape '\',
   * and '/' characters from a string.
   * @param $s
   * @return string
   */
  private function quoteApplicationName($s) {
    $quotedString = $this->collapseWhitespace($s);
    $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
    $quotedString = preg_replace('/\//', '\\/', $quotedString);

    return $quotedString;
  }

  /**
   * Collapse multiple whitespace characters into a single ' ' and backslash escape '\',
   * and '(' characters from a string.
   *
   * @param $s
   * @return string
   */
  private function quoteApplicationVersion($s) {
    $quotedString = $this->collapseWhitespace($s);
    $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
    $quotedString = preg_replace('/\\(/', '\\(', $quotedString);

    return $quotedString;
  }

  /**
   * Collapse multiple whitespace characters into a single ' ' and backslash escape '\',
   * and '=' characters from a string.
   *
   * @param $s
   * @return unknown_type
   */
  private function quoteAttributeName($s) {
    $quotedString = $this->collapseWhitespace($s);
    $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
    $quotedString = preg_replace('/\\=/', '\\=', $quotedString);

    return $quotedString;
  }

  /**
   * Collapse multiple whitespace characters into a single ' ' and backslash escape ';', '\',
   * and ')' characters from a string.
   *
   * @param $s
   * @return unknown_type
   */
  private function quoteAttributeValue($s) {
    $quotedString = $this->collapseWhitespace($s);
    $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
    $quotedString = preg_replace('/\\;/', '\\;', $quotedString);
    $quotedString = preg_replace('/\\)/', '\\)', $quotedString);

    return $quotedString;
  }

  // Public API ------------------------------------------------------------//

  /**
   * Get Report
   * The GetReport operation returns the contents of a report. Reports can potentially be
   * very large (>100MB) which is why we only return one report at a time, and in a
   * streaming fashion.
   *
   * @see http://docs.amazonwebservices.com/${docPath}GetReport.html
   * @param mixed $request array of parameters for MarketplaceWebService_Model_GetReportRequest request
   * or MarketplaceWebService_Model_GetReportRequest object itself
   * @see MarketplaceWebService_Model_GetReport
   * @return MarketplaceWebService_Model_GetReportResponse MarketplaceWebService_Model_GetReportResponse
   *
   * @throws MarketplaceWebService_Exception
   */
  public function getReport($request)
  {
    if (!$request instanceof MarketplaceWebService_Model_GetReportRequest) {
      require_once ('MarketplaceWebService/Model/GetReportRequest.php');
      $request = new MarketplaceWebService_Model_GetReportRequest($request);
    }
    require_once ('MarketplaceWebService/Model/GetReportResponse.php');

    $httpResponse = $this->invoke($this->convertGetReport($request), $request->getReport());
    $response = MarketplaceWebService_Model_GetReportResponse::fromXML($httpResponse['ResponseBody']);
    $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
    return $response;
  }

  /**
   * Get Report Schedule Count
   * returns the number of report schedules
   *
   * @see http://docs.amazonwebservices.com/${docPath}GetReportScheduleCount.html
   * @param mixed $request array of parameters for MarketplaceWebService_Model_GetReportScheduleCountRequest request
   * or MarketplaceWebService_Model_GetReportScheduleCountRequest object itself
   * @see MarketplaceWebService_Model_GetReportScheduleCount
   * @return MarketplaceWebService_Model_GetReportScheduleCountResponse MarketplaceWebService_Model_GetReportScheduleCountResponse
   *
   * @throws MarketplaceWebService_Exception
   */
  public function getReportScheduleCount($request)
  {
    if (!$request instanceof MarketplaceWebService_Model_GetReportScheduleCountRequest) {
      require_once ('MarketplaceWebService/Model/GetReportScheduleCountRequest.php');
      $request = new MarketplaceWebService_Model_GetReportScheduleCountRequest($request);
    }
    require_once ('MarketplaceWebService/Model/GetReportScheduleCountResponse.php');
    $httpResponse = $this->invoke($this->convertGetReportScheduleCount($request));
    $response = MarketplaceWebService_Model_GetReportScheduleCountResponse::fromXML($httpResponse['ResponseBody']);
    $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
    return $response;
  }

  /**
   * Get Report Request List By Next Token
   * retrieve the next batch of list items and if there are more items to retrieve
   *
   * @see http://docs.amazonwebservices.com/${docPath}GetReportRequestListByNextToken.html
   * @param mixed $request array of parameters for MarketplaceWebService_Model_GetReportRequestListByNextTokenRequest request
   * or MarketplaceWebService_Model_GetReportRequestListByNextTokenRequest object itself
   * @see MarketplaceWebService_Model_GetReportRequestListByNextToken
   * @return MarketplaceWebService_Model_GetReportRequestListByNextTokenResponse MarketplaceWebService_Model_GetReportRequestListByNextTokenResponse
   *
   * @throws MarketplaceWebService_Exception
   */
  public function getReportRequestListByNextToken($request)
  {
    if (!$request instanceof MarketplaceWebService_Model_GetReportRequestListByNextTokenRequest) {
      require_once ('MarketplaceWebService/Model/GetReportRequestListByNextTokenRequest.php');
      $request = new MarketplaceWebService_Model_GetReportRequestListByNextTokenRequest($request);
    }
    require_once ('MarketplaceWebService/Model/GetReportRequestListByNextTokenResponse.php');
    $httpResponse = $this->invoke($this->convertGetReportRequestListByNextToken($request));
    $response = MarketplaceWebService_Model_GetReportRequestListByNextTokenResponse::fromXML($httpResponse['ResponseBody']);
    $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
    return $response;
  }

  /**
   * Update Report Acknowledgements
   * The UpdateReportAcknowledgements operation updates the acknowledged status of one or more reports.
   *
   * @see http://docs.amazonwebservices.com/${docPath}UpdateReportAcknowledgements.html
   * @param mixed $request array of parameters for MarketplaceWebService_Model_UpdateReportAcknowledgementsRequest request
   * or MarketplaceWebService_Model_UpdateReportAcknowledgementsRequest object itself
   * @see MarketplaceWebService_Model_UpdateReportAcknowledgements
   * @return MarketplaceWebService_Model_UpdateReportAcknowledgementsResponse MarketplaceWebService_Model_UpdateReportAcknowledgementsResponse
   *
   * @throws MarketplaceWebService_Exception
   */
  public function updateReportAcknowledgements($request)
  {
    if (!$request instanceof MarketplaceWebService_Model_UpdateReportAcknowledgementsRequest) {
      require_once ('MarketplaceWebService/Model/UpdateReportAcknowledgementsRequest.php');
      $request = new MarketplaceWebService_Model_UpdateReportAcknowledgementsRequest($request);
    }
    require_once ('MarketplaceWebService/Model/UpdateReportAcknowledgementsResponse.php');
    $httpResponse = $this->invoke($this->convertUpdateReportAcknowledgements($request));
    $response = MarketplaceWebService_Model_UpdateReportAcknowledgementsResponse::fromXML($httpResponse['ResponseBody']);
    $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
    return $response;
  }

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
   * @see MarketplaceWebService_Model_SubmitFeed
   * @return MarketplaceWebService_Model_SubmitFeedResponse MarketplaceWebService_Model_SubmitFeedResponse
   *
   * @throws MarketplaceWebService_Exception
   */
  public function submitFeed($request)
  {
    if (!$request instanceof MarketplaceWebService_Model_SubmitFeedRequest) {
      require_once ('MarketplaceWebService/Model/SubmitFeedRequest.php');
      $request = new MarketplaceWebService_Model_SubmitFeedRequest($request);
    }
    require_once ('MarketplaceWebService/Model/SubmitFeedResponse.php');
    $httpResponse = $this->invoke($this->convertSubmitFeed($request), $request->getFeedContent());
    $response = MarketplaceWebService_Model_SubmitFeedResponse::fromXML($httpResponse['ResponseBody']);
    $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
    return $response;
  }

  /**
   * Get Report Count
   * returns a count of reports matching your criteria;
   * by default, the number of reports generated in the last 90 days,
   * regardless of acknowledgement status
   *
   * @see http://docs.amazonwebservices.com/${docPath}GetReportCount.html
   * @param mixed $request array of parameters for MarketplaceWebService_Model_GetReportCountRequest request
   * or MarketplaceWebService_Model_GetReportCountRequest object itself
   * @see MarketplaceWebService_Model_GetReportCount
   * @return MarketplaceWebService_Model_GetReportCountResponse MarketplaceWebService_Model_GetReportCountResponse
   *
   * @throws MarketplaceWebService_Exception
   */
  public function getReportCount($request)
  {
    if (!$request instanceof MarketplaceWebService_Model_GetReportCountRequest) {
      require_once ('MarketplaceWebService/Model/GetReportCountRequest.php');
      $request = new MarketplaceWebService_Model_GetReportCountRequest($request);
    }
    require_once ('MarketplaceWebService/Model/GetReportCountResponse.php');
    $httpResponse = $this->invoke($this->convertGetReportCount($request));
    $response = MarketplaceWebService_Model_GetReportCountResponse::fromXML($httpResponse['ResponseBody']);
    $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
    return $response;
  }

  /**
   * Get Feed Submission List By Next Token
   * retrieve the next batch of list items and if there are more items to retrieve
   *
   * @see http://docs.amazonwebservices.com/${docPath}GetFeedSubmissionListByNextToken.html
   * @param mixed $request array of parameters for MarketplaceWebService_Model_GetFeedSubmissionListByNextTokenRequest request
   * or MarketplaceWebService_Model_GetFeedSubmissionListByNextTokenRequest object itself
   * @see MarketplaceWebService_Model_GetFeedSubmissionListByNextToken
   * @return MarketplaceWebService_Model_GetFeedSubmissionListByNextTokenResponse MarketplaceWebService_Model_GetFeedSubmissionListByNextTokenResponse
   *
   * @throws MarketplaceWebService_Exception
   */
  public function getFeedSubmissionListByNextToken($request)
  {
    if (!$request instanceof MarketplaceWebService_Model_GetFeedSubmissionListByNextTokenRequest) {
      require_once ('MarketplaceWebService/Model/GetFeedSubmissionListByNextTokenRequest.php');
      $request = new MarketplaceWebService_Model_GetFeedSubmissionListByNextTokenRequest($request);
    }
    require_once ('MarketplaceWebService/Model/GetFeedSubmissionListByNextTokenResponse.php');
    $httpResponse = $this->invoke($this->convertGetFeedSubmissionListByNextToken($request));
    $response = MarketplaceWebService_Model_GetFeedSubmissionListByNextTokenResponse::fromXML($httpResponse['ResponseBody']);
    $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
    return $response;
  }

  /**
   * Cancel Feed Submissions
   * cancels feed submissions - by default all of the submissions of the
   * last 30 days that have not started processing
   *
   * @see http://docs.amazonwebservices.com/${docPath}CancelFeedSubmissions.html
   * @param mixed $request array of parameters for MarketplaceWebService_Model_CancelFeedSubmissionsRequest request
   * or MarketplaceWebService_Model_CancelFeedSubmissionsRequest object itself
   * @see MarketplaceWebService_Model_CancelFeedSubmissions
   * @return MarketplaceWebService_Model_CancelFeedSubmissionsResponse MarketplaceWebService_Model_CancelFeedSubmissionsResponse
   *
   * @throws MarketplaceWebService_Exception
   */
  public function cancelFeedSubmissions($request)
  {
    if (!$request instanceof MarketplaceWebService_Model_CancelFeedSubmissionsRequest) {
      require_once ('MarketplaceWebService/Model/CancelFeedSubmissionsRequest.php');
      $request = new MarketplaceWebService_Model_CancelFeedSubmissionsRequest($request);
    }
    require_once ('MarketplaceWebService/Model/CancelFeedSubmissionsResponse.php');
    $httpResponse = $this->invoke($this->convertCancelFeedSubmissions($request));
    $response = MarketplaceWebService_Model_CancelFeedSubmissionsResponse::fromXML($httpResponse['ResponseBody']);
    $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
    return $response;
  }

  /**
   * Request Report
   * requests the generation of a report
   *
   * @see http://docs.amazonwebservices.com/${docPath}RequestReport.html
   * @param mixed $request array of parameters for MarketplaceWebService_Model_RequestReportRequest request
   * or MarketplaceWebService_Model_RequestReportRequest object itself
   * @see MarketplaceWebService_Model_RequestReport
   * @return MarketplaceWebService_Model_RequestReportResponse MarketplaceWebService_Model_RequestReportResponse
   *
   * @throws MarketplaceWebService_Exception
   */
  public function requestReport($request)
  {
    if (!$request instanceof MarketplaceWebService_Model_RequestReportRequest) {
      require_once ('MarketplaceWebService/Model/RequestReportRequest.php');
      $request = new MarketplaceWebService_Model_RequestReportRequest($request);
    }
    require_once ('MarketplaceWebService/Model/RequestReportResponse.php');
    $httpResponse = $this->invoke($this->convertRequestReport($request));
    $response = MarketplaceWebService_Model_RequestReportResponse::fromXML($httpResponse['ResponseBody']);
    $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
    return $response;
  }

  /**
   * Get Feed Submission Count
   * returns the number of feeds matching all of the specified criteria
   *
   * @see http://docs.amazonwebservices.com/${docPath}GetFeedSubmissionCount.html
   * @param mixed $request array of parameters for MarketplaceWebService_Model_GetFeedSubmissionCountRequest request
   * or MarketplaceWebService_Model_GetFeedSubmissionCountRequest object itself
   * @see MarketplaceWebService_Model_GetFeedSubmissionCount
   * @return MarketplaceWebService_Model_GetFeedSubmissionCountResponse MarketplaceWebService_Model_GetFeedSubmissionCountResponse
   *
   * @throws MarketplaceWebService_Exception
   */
  public function getFeedSubmissionCount($request)
  {
    if (!$request instanceof MarketplaceWebService_Model_GetFeedSubmissionCountRequest) {
      require_once ('MarketplaceWebService/Model/GetFeedSubmissionCountRequest.php');
      $request = new MarketplaceWebService_Model_GetFeedSubmissionCountRequest($request);
    }
    require_once ('MarketplaceWebService/Model/GetFeedSubmissionCountResponse.php');
    $httpResponse = $this->invoke($this->convertGetFeedSubmissionCount($request));
    $response = MarketplaceWebService_Model_GetFeedSubmissionCountResponse::fromXML($httpResponse['ResponseBody']);
    $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
    return $response;
  }

  /**
   * Cancel Report Requests
   * cancels report requests that have not yet started processing,
   * by default all those within the last 90 days
   *
   * @see http://docs.amazonwebservices.com/${docPath}CancelReportRequests.html
   * @param mixed $request array of parameters for MarketplaceWebService_Model_CancelReportRequestsRequest request
   * or MarketplaceWebService_Model_CancelReportRequestsRequest object itself
   * @see MarketplaceWebService_Model_CancelReportRequests
   * @return MarketplaceWebService_Model_CancelReportRequestsResponse MarketplaceWebService_Model_CancelReportRequestsResponse
   *
   * @throws MarketplaceWebService_Exception
   */
  public function cancelReportRequests($request)
  {
    if (!$request instanceof MarketplaceWebService_Model_CancelReportRequestsRequest) {
      require_once ('MarketplaceWebService/Model/CancelReportRequestsRequest.php');
      $request = new MarketplaceWebService_Model_CancelReportRequestsRequest($request);
    }
    require_once ('MarketplaceWebService/Model/CancelReportRequestsResponse.php');
    $httpResponse = $this->invoke($this->convertCancelReportRequests($request));
    $response = MarketplaceWebService_Model_CancelReportRequestsResponse::fromXML($httpResponse['ResponseBody']);
    $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
    return $response;
  }

  /**
   * Get Report List
   * returns a list of reports; by default the most recent ten reports,
   * regardless of their acknowledgement status
   *
   * @see http://docs.amazonwebservices.com/${docPath}GetReportList.html
   * @param mixed $request array of parameters for MarketplaceWebService_Model_GetReportListRequest request
   * or MarketplaceWebService_Model_GetReportListRequest object itself
   * @see MarketplaceWebService_Model_GetReportList
   * @return MarketplaceWebService_Model_GetReportListResponse MarketplaceWebService_Model_GetReportListResponse
   *
   * @throws MarketplaceWebService_Exception
   */
  public function getReportList($request)
  {
    if (!$request instanceof MarketplaceWebService_Model_GetReportListRequest) {
      require_once ('MarketplaceWebService/Model/GetReportListRequest.php');
      $request = new MarketplaceWebService_Model_GetReportListRequest($request);
    }
    require_once ('MarketplaceWebService/Model/GetReportListResponse.php');
    $httpResponse = $this->invoke($this->convertGetReportList($request));
    $response = MarketplaceWebService_Model_GetReportListResponse::fromXML($httpResponse['ResponseBody']);
    $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
    return $response;
  }

  /**
   * Get Feed Submission Result
   * retrieves the feed processing report
   *
   * @see http://docs.amazonwebservices.com/${docPath}GetFeedSubmissionResult.html
   * @param mixed $request array of parameters for MarketplaceWebService_Model_GetFeedSubmissionResultRequest request
   * or MarketplaceWebService_Model_GetFeedSubmissionResultRequest object itself
   * @see MarketplaceWebService_Model_GetFeedSubmissionResult
   * @return MarketplaceWebService_Model_GetFeedSubmissionResultResponse MarketplaceWebService_Model_GetFeedSubmissionResultResponse
   *
   * @throws MarketplaceWebService_Exception
   */
  public function getFeedSubmissionResult($request)
  {
    if (!$request instanceof MarketplaceWebService_Model_GetFeedSubmissionResultRequest) {
      require_once ('MarketplaceWebService/Model/GetFeedSubmissionResultRequest.php');
      $request = new MarketplaceWebService_Model_GetFeedSubmissionResultRequest($request);
    }
    require_once ('MarketplaceWebService/Model/GetFeedSubmissionResultResponse.php');
    $httpResponse = $this->invoke($this->convertGetFeedSubmissionResult($request), $request->getFeedSubmissionResult());
    $response = MarketplaceWebService_Model_GetFeedSubmissionResultResponse::fromXML($httpResponse['ResponseBody']);
    $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
    return $response;
  }

  /**
   * Get Feed Submission List
   * returns a list of feed submission identifiers and their associated metadata
   *
   * @see http://docs.amazonwebservices.com/${docPath}GetFeedSubmissionList.html
   * @param mixed $request array of parameters for MarketplaceWebService_Model_GetFeedSubmissionListRequest request
   * or MarketplaceWebService_Model_GetFeedSubmissionListRequest object itself
   * @see MarketplaceWebService_Model_GetFeedSubmissionList
   * @return MarketplaceWebService_Model_GetFeedSubmissionListResponse MarketplaceWebService_Model_GetFeedSubmissionListResponse
   *
   * @throws MarketplaceWebService_Exception
   */
  public function getFeedSubmissionList($request)
  {
    if (!$request instanceof MarketplaceWebService_Model_GetFeedSubmissionListRequest) {
      require_once ('MarketplaceWebService/Model/GetFeedSubmissionListRequest.php');
      $request = new MarketplaceWebService_Model_GetFeedSubmissionListRequest($request);
    }
    require_once ('MarketplaceWebService/Model/GetFeedSubmissionListResponse.php');
    $httpResponse = $this->invoke($this->convertGetFeedSubmissionList($request));
    $response = MarketplaceWebService_Model_GetFeedSubmissionListResponse::fromXML($httpResponse['ResponseBody']);
    $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
    return $response;
  }

  /**
   * Get Report Request List
   * returns a list of report requests ids and their associated metadata
   *
   * @see http://docs.amazonwebservices.com/${docPath}GetReportRequestList.html
   * @param mixed $request array of parameters for MarketplaceWebService_Model_GetReportRequestListRequest request
   * or MarketplaceWebService_Model_GetReportRequestListRequest object itself
   * @see MarketplaceWebService_Model_GetReportRequestList
   * @return MarketplaceWebService_Model_GetReportRequestListResponse MarketplaceWebService_Model_GetReportRequestListResponse
   *
   * @throws MarketplaceWebService_Exception
   */
  public function getReportRequestList($request)
  {
    if (!$request instanceof MarketplaceWebService_Model_GetReportRequestListRequest) {
      require_once ('MarketplaceWebService/Model/GetReportRequestListRequest.php');
      $request = new MarketplaceWebService_Model_GetReportRequestListRequest($request);
    }
    require_once ('MarketplaceWebService/Model/GetReportRequestListResponse.php');
    $httpResponse = $this->invoke($this->convertGetReportRequestList($request));
    $response = MarketplaceWebService_Model_GetReportRequestListResponse::fromXML($httpResponse['ResponseBody']);
    $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
    return $response;
  }

  /**
   * Get Report Schedule List By Next Token
   * retrieve the next batch of list items and if there are more items to retrieve
   *
   * @see http://docs.amazonwebservices.com/${docPath}GetReportScheduleListByNextToken.html
   * @param mixed $request array of parameters for MarketplaceWebService_Model_GetReportScheduleListByNextTokenRequest request
   * or MarketplaceWebService_Model_GetReportScheduleListByNextTokenRequest object itself
   * @see MarketplaceWebService_Model_GetReportScheduleListByNextToken
   * @return MarketplaceWebService_Model_GetReportScheduleListByNextTokenResponse MarketplaceWebService_Model_GetReportScheduleListByNextTokenResponse
   *
   * @throws MarketplaceWebService_Exception
   */
  public function getReportScheduleListByNextToken($request)
  {
    if (!$request instanceof MarketplaceWebService_Model_GetReportScheduleListByNextTokenRequest) {
      require_once ('MarketplaceWebService/Model/GetReportScheduleListByNextTokenRequest.php');
      $request = new MarketplaceWebService_Model_GetReportScheduleListByNextTokenRequest($request);
    }
    require_once ('MarketplaceWebService/Model/GetReportScheduleListByNextTokenResponse.php');
    $httpResponse = $this->invoke($this->convertGetReportScheduleListByNextToken($request));
    $response = MarketplaceWebService_Model_GetReportScheduleListByNextTokenResponse::fromXML($httpResponse['ResponseBody']);
    $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
    return $response;
  }

  /**
   * Get Report List By Next Token
   * retrieve the next batch of list items and if there are more items to retrieve
   *
   * @see http://docs.amazonwebservices.com/${docPath}GetReportListByNextToken.html
   * @param mixed $request array of parameters for MarketplaceWebService_Model_GetReportListByNextTokenRequest request
   * or MarketplaceWebService_Model_GetReportListByNextTokenRequest object itself
   * @see MarketplaceWebService_Model_GetReportListByNextToken
   * @return MarketplaceWebService_Model_GetReportListByNextTokenResponse MarketplaceWebService_Model_GetReportListByNextTokenResponse
   *
   * @throws MarketplaceWebService_Exception
   */
  public function getReportListByNextToken($request)
  {
    if (!$request instanceof MarketplaceWebService_Model_GetReportListByNextTokenRequest) {
      require_once ('MarketplaceWebService/Model/GetReportListByNextTokenRequest.php');
      $request = new MarketplaceWebService_Model_GetReportListByNextTokenRequest($request);
    }
    require_once ('MarketplaceWebService/Model/GetReportListByNextTokenResponse.php');
    $httpResponse = $this->invoke($this->convertGetReportListByNextToken($request));
    $response = MarketplaceWebService_Model_GetReportListByNextTokenResponse::fromXML($httpResponse['ResponseBody']);
    $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
    return $response;
  }

  /**
   * Manage Report Schedule
   * Creates, updates, or deletes a report schedule
   * for a given report type, such as order reports in particular.
   *
   * @see http://docs.amazonwebservices.com/${docPath}ManageReportSchedule.html
   * @param mixed $request array of parameters for MarketplaceWebService_Model_ManageReportScheduleRequest request
   * or MarketplaceWebService_Model_ManageReportScheduleRequest object itself
   * @see MarketplaceWebService_Model_ManageReportSchedule
   * @return MarketplaceWebService_Model_ManageReportScheduleResponse MarketplaceWebService_Model_ManageReportScheduleResponse
   *
   * @throws MarketplaceWebService_Exception
   */
  public function manageReportSchedule($request)
  {
    if (!$request instanceof MarketplaceWebService_Model_ManageReportScheduleRequest) {
      require_once ('MarketplaceWebService/Model/ManageReportScheduleRequest.php');
      $request = new MarketplaceWebService_Model_ManageReportScheduleRequest($request);
    }
    require_once ('MarketplaceWebService/Model/ManageReportScheduleResponse.php');
    $httpResponse = $this->invoke($this->convertManageReportSchedule($request));
    $response = MarketplaceWebService_Model_ManageReportScheduleResponse::fromXML($httpResponse['ResponseBody']);
    $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
    return $response;
  }

  /**
   * Get Report Request Count
   * returns a count of report requests; by default all the report
   * requests in the last 90 days
   *
   * @see http://docs.amazonwebservices.com/${docPath}GetReportRequestCount.html
   * @param mixed $request array of parameters for MarketplaceWebService_Model_GetReportRequestCountRequest request
   * or MarketplaceWebService_Model_GetReportRequestCountRequest object itself
   * @see MarketplaceWebService_Model_GetReportRequestCount
   * @return MarketplaceWebService_Model_GetReportRequestCountResponse MarketplaceWebService_Model_GetReportRequestCountResponse
   *
   * @throws MarketplaceWebService_Exception
   */
  public function getReportRequestCount($request)
  {
    if (!$request instanceof MarketplaceWebService_Model_GetReportRequestCountRequest) {
      require_once ('MarketplaceWebService/Model/GetReportRequestCountRequest.php');
      $request = new MarketplaceWebService_Model_GetReportRequestCountRequest($request);
    }
    require_once ('MarketplaceWebService/Model/GetReportRequestCountResponse.php');
    $httpResponse = $this->invoke($this->convertGetReportRequestCount($request));
    $response = MarketplaceWebService_Model_GetReportRequestCountResponse::fromXML($httpResponse['ResponseBody']);
    $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
    return $response;
  }

  /**
   * Get Report Schedule List
   * returns the list of report schedules
   *
   * @see http://docs.amazonwebservices.com/${docPath}GetReportScheduleList.html
   * @param mixed $request array of parameters for MarketplaceWebService_Model_GetReportScheduleListRequest request
   * or MarketplaceWebService_Model_GetReportScheduleListRequest object itself
   * @see MarketplaceWebService_Model_GetReportScheduleList
   * @return MarketplaceWebService_Model_GetReportScheduleListResponse MarketplaceWebService_Model_GetReportScheduleListResponse
   *
   * @throws MarketplaceWebService_Exception
   */
  public function getReportScheduleList($request)
  {
    if (!$request instanceof MarketplaceWebService_Model_GetReportScheduleListRequest) {
      require_once ('MarketplaceWebService/Model/GetReportScheduleListRequest.php');
      $request = new MarketplaceWebService_Model_GetReportScheduleListRequest($request);
    }
    require_once ('MarketplaceWebService/Model/GetReportScheduleListResponse.php');
    $httpResponse = $this->invoke($this->convertGetReportScheduleList($request));
    $response = MarketplaceWebService_Model_GetReportScheduleListResponse::fromXML($httpResponse['ResponseBody']);
    $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
    return $response;
  }
  
  // Private API ------------------------------------------------------------//

  /**
   * Get the base64 encoded md5 value of $data. If $data is a memory or temp file stream, this 
   * method dumps the contents into a string before calculating the md5. Hence, this method
   * shouldn't be used for large memory streams.
   *
   * @param $data
   * @return unknown_type
   */
  private function getContentMd5($data) {
    $md5Hash = null;

    if (is_string($data)) {
      $md5Hash = md5($data, true);
    } else if (is_resource($data)) {
      // Assume $data is a stream.
      $streamMetadata = stream_get_meta_data($data);

      if ($streamMetadata['stream_type'] === 'MEMORY' || $streamMetadata['stream_type'] === 'TEMP') {
        $md5Hash = md5(stream_get_contents($data), true);
      } else {
        $md5Hash = md5_file($streamMetadata['uri'], true);
      }
    }

    return base64_encode($md5Hash);
  }

  /**
   * Invoke request and return response
   */
  private function invoke(array $converted, $dataHandle = null)
  {
  	
  	$parameters = $converted[CONVERTED_PARAMETERS_KEY];
    $actionName = $parameters["Action"];
    $response = array();
    $responseBody = null;
    $statusCode = 200;
    
    /* Submit the request and read response body */
    try {
    	
    // Ensure the endpoint URL is set.
    if (empty($this->config['ServiceURL'])) {
        throw new MarketplaceWebService_Exception(
            array('ErrorCode' => 'InvalidServiceUrl',
                  'Message' => "Missing serviceUrl configuration value. You may obtain a list of valid MWS URLs by consulting the MWS Developer's Guide, or reviewing the sample code published along side this library."));
    }

      /* Add required request parameters */
      $parameters = $this->addRequiredParameters($parameters);
      $converted[CONVERTED_PARAMETERS_KEY] = $parameters;

      $shouldRetry = false;
      $retries = 0;
      do {
        try {
          $response = $this->performRequest($actionName, $converted, $dataHandle);
          
          $httpStatus = $response['Status'];
          
          switch ($httpStatus) {
          	case 200:
          		$shouldRetry = false;
          		break;
          		
          	case 500:
          	case 503:
          		require_once('MarketplaceWebService/Model/ErrorResponse.php');
		          $errorResponse = MarketplaceWebService_Model_ErrorResponse::fromXML($response['ResponseBody']);
		          
		          // We will not retry throttling errors since this would just add to the throttling problem.
		          $shouldRetry = ($errorResponse->getError()->getCode() === 'RequestThrottled')
		            ? false : true;
		              
		          if ($shouldRetry && $retries <= $this->config['MaxErrorRetry']) {
		            $this->pauseOnRetry(++$retries); 
		          } else {
		            throw $this->reportAnyErrors($response['ResponseBody'], $response['Status'], $response['ResponseHeaderMetadata']);
		          }
          		break;
          		
          	default:
          		$shouldRetry = false;
          		throw $this->reportAnyErrors($response['ResponseBody'], $response['Status'], $response['ResponseHeaderMetadata']);
          		break;
          }
          
          /* Rethrow on deserializer error */
        } catch (Exception $e) {
          require_once ('MarketplaceWebService/Exception.php');
            throw new MarketplaceWebService_Exception(array('Exception' => $e, 'Message' => $e->getMessage()));
        }

      } while ($shouldRetry);

    } catch (MarketplaceWebService_Exception $se) {
      throw $se;
    } catch (Exception $t) {
      throw new MarketplaceWebService_Exception(array('Exception' => $t, 'Message' => $t->getMessage()));
    }
    return array('ResponseBody' => $response['ResponseBody'], 'ResponseHeaderMetadata' => $response['ResponseHeaderMetadata']);
  }

  /**
   * Look for additional error strings in the response and return formatted exception
   */
  private function reportAnyErrors($responseBody, $status, $responseHeaderMetadata)
  {
    $exProps = array();
    $exProps["StatusCode"] = $status;
    $exProps["ResponseHeaderMetadata"] = $responseHeaderMetadata;
    
    libxml_use_internal_errors(true);  // Silence XML parsing errors
    $xmlBody = simplexml_load_string($responseBody);
    
    if ($xmlBody !== false) {  // Check XML loaded without errors
      $exProps["XML"] = $responseBody;
      $exProps["ErrorCode"] = $xmlBody->Error->Code;
      $exProps["Message"] = $xmlBody->Error->Message;
      $exProps["ErrorType"] = !empty($xmlBody->Error->Type) ? $xmlBody->Error->Type : "Unknown";
      $exProps["RequestId"] = !empty($xmlBody->RequestID) ? $xmlBody->RequestID : $xmlBody->RequestId; // 'd' in RequestId is sometimes capitalized
    } else { // We got bad XML in response, just throw a generic exception
      $exProps["Message"] = "Internal Error";
    }
    
    require_once ('MarketplaceWebService/Exception.php');
    return new MarketplaceWebService_Exception($exProps);
  }

  /**
   * Setup and execute the request via cURL and return the server response.
   *
   * @param $action - the MWS action to perform.
   * @param $parameters - the MWS parameters for the Action.
   * @param $dataHandle - A stream handle to either a feed to upload, or a report/feed submission result to download.
   * @return array
   */
  private function performRequest($action, array $converted, $dataHandle = null) {

    $curlOptions = $this->configureCurlOptions($action, $converted, $dataHandle);

    if (is_null($curlOptions[CURLOPT_RETURNTRANSFER]) || !$curlOptions[CURLOPT_RETURNTRANSFER]) {
      $curlOptions[CURLOPT_RETURNTRANSFER] = true;
    }

    $this->curlClient = curl_init();
    curl_setopt_array($this->curlClient, $curlOptions);

    $this->headerContents = @fopen('php://memory', 'rw+');
    $this->errorResponseBody = @fopen('php://memory', 'rw+');

    $httpResponse = curl_exec($this->curlClient);

    rewind($this->headerContents);
    $header = stream_get_contents($this->headerContents);

    $parsedHeader = $this->parseHttpHeader($header);

    require_once('MarketplaceWebService/Model/ResponseHeaderMetadata.php');
    $responseHeaderMetadata = new MarketplaceWebService_Model_ResponseHeaderMetadata(
              $parsedHeader['x-mws-request-id'],
              $parsedHeader['x-mws-response-context'],
              $parsedHeader['x-mws-timestamp']);

    $code = (int) curl_getinfo($this->curlClient, CURLINFO_HTTP_CODE);
    
    // Only attempt to verify the Content-MD5 value if the request was successful.
    if (RequestType::getRequestType($action) === RequestType::POST_DOWNLOAD) {
    	if ($code != 200) {
    	  rewind($this->errorResponseBody);
        $httpResponse =  stream_get_contents($this->errorResponseBody);	
    	} else {
        $this->verifyContentMd5($this->getParsedHeader($parsedHeader,'Content-MD5'), $dataHandle);
        $httpResponse = $this->getDownloadResponseDocument($action, $parsedHeader);
    	}
    }
    
    // Cleanup open streams and cURL instance.
    @fclose($this->headerContents);
    @fclose($this->errorResponseBody);
    curl_close($this->curlClient);

    
    return array (
        'Status' => $code, 
        'ResponseBody' => $httpResponse,
        'ResponseHeaderMetadata' => $responseHeaderMetadata);
  }

  private function getParsedHeader($parsedHeader, $key) {
    return $parsedHeader[strtolower($key)];
  }

  /**
   * Compares the received Content-MD5 Hash value from the response with a locally calculated
   * value based on the contents of the response body. If the received hash value doesn't match
   * the locally calculated hash value, an exception is raised.
   *
   * @param $receivedMd5Hash
   * @param $streamHandle
   * @return unknown_type
   */
  private function verifyContentMd5($receivedMd5Hash, $streamHandle) {
    rewind($streamHandle);
    $expectedMd5Hash = $this->getContentMd5($streamHandle);
    rewind($streamHandle);

    if (!($receivedMd5Hash === $expectedMd5Hash)) {
      require_once ('MarketplaceWebService/Exception.php');
      throw new MarketplaceWebService_Exception(
          array(
            'Message' => 'Received Content-MD5 = ' . $receivedMd5Hash . ' but expected ' . $expectedMd5Hash, 
            'ErrorCode' => 'ContentMD5DoesNotMatch'));
    }
  }

  /**
   * Build an associative array of an HTTP Header lines. For requests, the HTTP request line
   * is not contained in the array, nor is the HTTP status line for response headers.
   *
   * @param $header
   * @return array
   */
  private function parseHttpHeader($header) {
    $parsedHeader = array ();
    foreach (explode("\n", $header) as $line) {
      $splitLine = preg_split('/:\s/', $line, 2, PREG_SPLIT_NO_EMPTY);

      if (sizeof($splitLine) == 2) {
        $k = strtolower(trim($splitLine[0]));
        $v = trim($splitLine[1]);
        if (array_key_exists($k, $parsedHeader)) {
          $parsedHeader[$k] = $parsedHeader[$k] . "," . $v;
        } else {
          $parsedHeader[$k] = $v;
        }
      }
    }

    return $parsedHeader;
  }

  /**
   * cURL callback to write the response HTTP body into a stream. This is only intended to be used
   * with RequestType::POST_DOWNLOAD request types, since the responses can potentially become
   * large.
   *
   * @param $ch - The curl handle.
   * @param $string - body portion to write.
   * @return int - number of byes written.
   */
  private function responseCallback($ch, $string) {
  	$httpStatusCode = (int) curl_getinfo($this->curlClient, CURLINFO_HTTP_CODE);
  	
  	// For unsuccessful responses, i.e. non-200 HTTP responses, we write the response body
  	// into a separate stream.
  	$responseHandle;
  	if ($httpStatusCode == 200) {
  		$responseHandle = $this->responseBodyContents;
  	} else {
  		$responseHandle = $this->errorResponseBody;
  	}
  	
    return fwrite($responseHandle, $string);
  }

  /**
   * cURL callback to write the response HTTP header into a stream.
   *
   * @param $ch - The curl handle.
   * @param $string - header portion to write.
   * @return int - number of bytes written to stream.
   */
  private function headerCallback($ch, $string) {
    $bytesWritten = fwrite($this->headerContents, $string);
    return $bytesWritten;
  }
  
  /**
   * Gets cURL options common to all MWS requests.
   * @return unknown_type
   */
  private function getDefaultCurlOptions() {
    return array (
      CURLOPT_POST => true,
      CURLOPT_USERAGENT => $this->config['UserAgent'],
      CURLOPT_VERBOSE => true,
      CURLOPT_HEADERFUNCTION => array ($this, 'headerCallback'),
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_SSL_VERIFYPEER => true,
      CURLOPT_SSL_VERIFYHOST => 2
    );
  }
  
  /**
   * Configures specific curl options based on the request type.
   *
   * @param $action
   * @param $parameters
   * @param $streamHandle
   * @return array
   */
  private function configureCurlOptions($action, array $converted, $streamHandle = null) {
    $curlOptions = $this->getDefaultCurlOptions();
    
    if (!is_null($this->config['ProxyHost'])) {
      $proxy = $this->config['ProxyHost'];
      $proxy .= ':' . ($this->config['ProxyPort'] == -1 ? '80' : $this->config['ProxyPort']);

      $curlOptions[CURLOPT_PROXY] = $proxy;
    }

    $serviceUrl = $this->config['ServiceURL'];

    // append the '/' character to the end of the service URL if it doesn't exist.
    if (!(substr($serviceUrl, strlen($serviceUrl) - 1) === '/')) {
      $serviceUrl .= '/';
    }

    $requestType = RequestType::getRequestType($action);

    if ($requestType == RequestType::POST_UPLOAD) {

      if (is_null($streamHandle) || !is_resource($streamHandle)) {
        require_once ('MarketplaceWebService/Exception.php');
        throw new MarketplaceWebService_Exception(
          array ('Message' => 'Missing stream resource.'));
      }

      $serviceUrl .= '?' . $this->getParametersAsString($converted[CONVERTED_PARAMETERS_KEY]);

      $curlOptions[CURLOPT_URL] = $serviceUrl;
      
      $header[] = 'Expect: ';
      $header[] = 'Accept: ';
      $header[] = 'Transfer-Encoding: chunked';
      
      $curlOptions[CURLOPT_HTTPHEADER] = array_merge($header, $converted[CONVERTED_HEADERS_KEY]);

      rewind($streamHandle);
      $curlOptions[CURLOPT_INFILE] = $streamHandle;

      $curlOptions[CURLOPT_UPLOAD] = true;

      $curlOptions[CURLOPT_CUSTOMREQUEST] = self::REQUEST_TYPE;

    } else if (!($requestType === RequestType::UNKNOWN)) {
      $curlOptions[CURLOPT_URL] = $this->config['ServiceURL'];
      $curlOptions[CURLOPT_POSTFIELDS] = $this->getParametersAsString($converted[CONVERTED_PARAMETERS_KEY]);

      if ($requestType == RequestType::POST_DOWNLOAD) {
        $this->responseBodyContents = $streamHandle;
        $curlOptions[CURLOPT_WRITEFUNCTION] = array ($this, 'responseCallback');
      }
    } else {
      throw new InvalidArgumentException("$action is not a valid request type.");
    }

    return $curlOptions;
  }

  /**
   * For RequestType::POST_DOWNLOAD actions, construct a response containing the Amazon Request ID
   * and Content MD5 header value.
   *
   * @param $responseType
   * @param $header
   * @return unknown_type
   */
  private function getDownloadResponseDocument($responseType, $header) {
    $md5 = $this->getParsedHeader($header, 'Content-MD5');
    $requestId = $this->getParsedHeader($header, 'x-amz-request-id');

    $response = '<' . $responseType . 'Response xmlns="http://mws.amazonaws.com/doc/2009-01-01/">';

    $response .= '<' . $responseType . 'Result>';
    $response .= '<ContentMd5>';
    $response .= $md5;
    $response .= '</ContentMd5>';
    $response .= '</' . $responseType . 'Result>';
    $response .= '<ResponseMetadata>';
    $response .= '<RequestId>';
    $response .= $requestId;
    $response .= '</RequestId>';
    $response .= '</ResponseMetadata>';
    $response .= '</' . $responseType . 'Response>';
    
    return $response;
  }

  /**
   * Exponential sleep on failed request
   * @param retries current retry
   */
  private function pauseOnRetry($retries)
  {
    $delay = (int) (pow(4, $retries) * 100000) ;
    usleep($delay);
  }

  /**
   * Add authentication related and version parameters
   */
  private function addRequiredParameters(array $parameters)
  {
    $parameters['AWSAccessKeyId'] = $this->awsAccessKeyId;
    $parameters['Timestamp'] = $this->getFormattedTimestamp(new DateTime('now', new DateTimeZone('UTC')));
    $parameters['Version'] = self::SERVICE_VERSION;
    $parameters['SignatureVersion'] = $this->config['SignatureVersion'];
    if ($parameters['SignatureVersion'] > 1) {
      $parameters['SignatureMethod'] = $this->config['SignatureMethod'];
    }
    $parameters['Signature'] = $this->signParameters($parameters, $this->awsSecretAccessKey);

    return $parameters;
  }

  /**
   * Convert paremeters to Url encoded query string
   */
  private function getParametersAsString(array $parameters)
  {
    $queryParameters = array();
    foreach ($parameters as $key => $value) {
      $queryParameters[] = $key . '=' . $this->urlencode($value);
    }
    return implode('&', $queryParameters);
  }


  /**
   * Computes RFC 2104-compliant HMAC signature for request parameters
   * Implements AWS Signature, as per following spec:
   *
   * Signature Version 0: This is not supported in the Marketplace Web Service.
   *
   * Signature Version 1: This is not supported in the Marketplace Web Service.
   *
   * Signature Version is 2, string to sign is based on following:
   *
   *    1. The HTTP Request Method followed by an ASCII newline (%0A)
   *    2. The HTTP Host header in the form of lowercase host, followed by an ASCII newline.
   *    3. The URL encoded HTTP absolute path component of the URI
   *       (up to but not including the query string parameters);
   *       if this is empty use a forward '/'. This parameter is followed by an ASCII newline.
   *    4. The concatenation of all query string components (names and values)
   *       as UTF-8 characters which are URL encoded as per RFC 3986
   *       (hex characters MUST be uppercase), sorted using lexicographic byte ordering.
   *       Parameter names are separated from their values by the '=' character
   *       (ASCII character 61), even if the value is empty.
   *       Pairs of parameter and values are separated by the '&' character (ASCII code 38).
   *
   */
  private function signParameters(array $parameters, $key) {
    $signatureVersion = $parameters['SignatureVersion'];
    $algorithm = "HmacSHA1";
    $stringToSign = null;
    if (0 === $signatureVersion) {
      throw new InvalidArgumentException(
        'Signature Version 0 is no longer supported. Only Signature Version 2 is supported.');
    } else if (1 === $signatureVersion) {
      throw new InvalidArgumentException(
        'Signature Version 1 is no longer supported. Only Signature Version 2 is supported.');
    } else if (2 === $signatureVersion) {
      $algorithm = $this->config['SignatureMethod'];
      $parameters['SignatureMethod'] = $algorithm;
      $stringToSign = $this->calculateStringToSignV2($parameters);
    } else {
      throw new Exception("Invalid Signature Version specified");
    }
    return $this->sign($stringToSign, $key, $algorithm);
  }

  /**
   * Calculate String to Sign for SignatureVersion 2
   * @param array $parameters request parameters
   * @return String to Sign
   */
  private function calculateStringToSignV2(array $parameters, $queuepath = null) {

    $parsedUrl = parse_url($this->config['ServiceURL']);
    $endpoint = $parsedUrl['host'];
    if (isset($parsedUrl['port']) && !is_null($parsedUrl['port'])) {
      $endpoint .= ':' . $parsedUrl['port'];
    }

    $data = 'POST';
    $data .= "\n";
    $data .= $endpoint;
    $data .= "\n";
    if ($queuepath) {
      $uri  = $queuepath;
    } else {
      $uri = "/";
    }
    $uriencoded = implode("/", array_map(array($this, "urlencode"), explode("/", $uri)));
    $data .= $uriencoded;
    $data .= "\n";
    uksort($parameters, 'strcmp');
    $data .= $this->getParametersAsString($parameters);
    
    return $data;
  }

  private function urlencode($value) {
    return str_replace('%7E', '~', rawurlencode($value));
  }


  /**
   * Computes RFC 2104-compliant HMAC signature
   */
  private function sign($data, $key, $algorithm)
  {
    if ($algorithm === 'HmacSHA1') {
      $hash = 'sha1';
    } else if ($algorithm === 'HmacSHA256') {
      $hash = 'sha256';
    } else {
      throw new Exception ("Non-supported signing method specified");
    }
    return base64_encode(
    hash_hmac($hash, $data, $key, true)
    );
  }

  /**
   * Returns a ISO 8601 formatted string from a DateTime instance.
   */
  private function getFormattedTimestamp($dateTime) {
    if (!is_object($dateTime)) {
      if (is_string($dateTime)) {
        $dateTime = new DateTime($dateTime);
      } else {
        throw new Exception("Invalid date value.");
      }
    } else {
      if (!($dateTime instanceof DateTime)) {
        throw new Exception("Invalid date value.");
      }
    }
    
    return $dateTime->format(DATE_ISO8601);  
  }

    /**
     * Convert GetReportRequest to name value pairs
     */
    private function convertGetReport($request) {

      $parameters = array();
      $parameters['Action'] = 'GetReport';
      if ($request->isSetMarketplace()) {
        $parameters['Marketplace'] =  $request->getMarketplace();
      }
      if ($request->isSetMerchant()) {
        $parameters['Merchant'] =  $request->getMerchant();
      }
      if ($request->isSetReportId()) {
        $parameters['ReportId'] =  $request->getReportId();
      }
      if ($request->isSetMWSAuthToken()) {
        $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
      }

      return array(CONVERTED_PARAMETERS_KEY => $parameters, CONVERTED_HEADERS_KEY => $this->defaultHeaders);
    }


    /**
     * Convert GetReportScheduleCountRequest to name value pairs
     */
    private function convertGetReportScheduleCount($request) {

      $parameters = array();
      $parameters['Action'] = 'GetReportScheduleCount';
      if ($request->isSetMarketplace()) {
        $parameters['Marketplace'] =  $request->getMarketplace();
      }
      if ($request->isSetMerchant()) {
        $parameters['Merchant'] =  $request->getMerchant();
      }
      if ($request->isSetReportTypeList()) {
        $reportTypeList = $request->getReportTypeList();
        foreach  ($reportTypeList->getType() as $typeIndex => $type) {
          $parameters['ReportTypeList' . '.' . 'Type' . '.'  . ($typeIndex + 1)] =  $type;
        }
      }
      if ($request->isSetMWSAuthToken()) {
        $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
      }

      return array(CONVERTED_PARAMETERS_KEY => $parameters, CONVERTED_HEADERS_KEY => $this->defaultHeaders);
    }


    /**
     * Convert GetReportRequestListByNextTokenRequest to name value pairs
     */
    private function convertGetReportRequestListByNextToken($request) {

      $parameters = array();
      $parameters['Action'] = 'GetReportRequestListByNextToken';
      if ($request->isSetMarketplace()) {
        $parameters['Marketplace'] =  $request->getMarketplace();
      }
      if ($request->isSetMerchant()) {
        $parameters['Merchant'] =  $request->getMerchant();
      }
      if ($request->isSetNextToken()) {
        $parameters['NextToken'] =  $request->getNextToken();
      }
      if ($request->isSetMWSAuthToken()) {
        $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
      }

      return array(CONVERTED_PARAMETERS_KEY => $parameters, CONVERTED_HEADERS_KEY => $this->defaultHeaders);
    }

    /**
     * Convert UpdateReportAcknowledgementsRequest to name value pairs
     */
    private function convertUpdateReportAcknowledgements($request) {

      $parameters = array();
      $parameters['Action'] = 'UpdateReportAcknowledgements';
      if ($request->isSetMarketplace()) {
        $parameters['Marketplace'] =  $request->getMarketplace();
      }
      if ($request->isSetMerchant()) {
        $parameters['Merchant'] =  $request->getMerchant();
      }
      if ($request->isSetReportIdList()) {
        $reportIdList = $request->getReportIdList();
        foreach  ($reportIdList->getId() as $idIndex => $id) {
          $parameters['ReportIdList' . '.' . 'Id' . '.'  . ($idIndex + 1)] =  $id;
        }
      }
      if ($request->isSetAcknowledged()) {
        $parameters['Acknowledged'] =  $request->getAcknowledged() ? "true" : "false";
      }
      if ($request->isSetMWSAuthToken()) {
        $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
      }

      return array(CONVERTED_PARAMETERS_KEY => $parameters, CONVERTED_HEADERS_KEY => $this->defaultHeaders);
    }


    /**
     * Convert SubmitFeedRequest to name value pairs
     */
    private function convertSubmitFeed($request) {

      $parameters = array();
      $parameters['Action'] = 'SubmitFeed';
      if ($request->isSetMarketplace()) {
        $parameters['Marketplace'] =  $request->getMarketplace();
      }
      if ($request->isSetMerchant()) {
        $parameters['Merchant'] =  $request->getMerchant();
      }
      if ($request->isSetMarketplaceIdList()) {
	$marketplaceIdList = $request->getMarketplaceIdList();
        foreach  ($marketplaceIdList->getId() as $idIndex => $id) {
          $parameters['MarketplaceIdList.Id.'.($idIndex + 1)] =  $id;
        }       
      }
      if ($request->isSetFeedType()) {
        $parameters['FeedType'] =  $request->getFeedType();
      }
      if ($request->isSetPurgeAndReplace()) {
        $parameters['PurgeAndReplace'] =  $request->getPurgeAndReplace() ? "true" : "false";
      }
      if ($request->isSetMWSAuthToken()) {
        $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
      }
      if ($request->isSetContentMd5()) {
              $parameters['ContentMD5Value'] = $request->getContentMd5();
      }

      $headers = array();
      array_push($headers, "Content-Type: " . $request->getContentType()->toString());

      return array(CONVERTED_PARAMETERS_KEY => $parameters, CONVERTED_HEADERS_KEY => $headers);
    }


    /**
     * Convert GetReportCountRequest to name value pairs
     */
    private function convertGetReportCount($request) {

      $parameters = array();
      $parameters['Action'] = 'GetReportCount';
      if ($request->isSetMarketplace()) {
        $parameters['Marketplace'] =  $request->getMarketplace();
      }
      if ($request->isSetMerchant()) {
        $parameters['Merchant'] =  $request->getMerchant();
      }
      if ($request->isSetReportTypeList()) {
        $reportTypeList = $request->getReportTypeList();
        foreach  ($reportTypeList->getType() as $typeIndex => $type) {
          $parameters['ReportTypeList' . '.' . 'Type' . '.'  . ($typeIndex + 1)] =  $type;
        }
      }
      if ($request->isSetAcknowledged()) {
        $parameters['Acknowledged'] =  $request->getAcknowledged() ? "true" : "false";
      }
      if ($request->isSetAvailableFromDate()) {
        $parameters['AvailableFromDate'] =
        $this->getFormattedTimestamp($request->getAvailableFromDate());
      }
      if ($request->isSetAvailableToDate()) {
        $parameters['AvailableToDate'] =
        $this->getFormattedTimestamp($request->getAvailableToDate());
      }
      if ($request->isSetMWSAuthToken()) {
        $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
      }

      return array(CONVERTED_PARAMETERS_KEY => $parameters, CONVERTED_HEADERS_KEY => $this->defaultHeaders);
    }


    /**
     * Convert GetFeedSubmissionListByNextTokenRequest to name value pairs
     */
    private function convertGetFeedSubmissionListByNextToken($request) {

      $parameters = array();
      $parameters['Action'] = 'GetFeedSubmissionListByNextToken';
      if ($request->isSetMarketplace()) {
        $parameters['Marketplace'] =  $request->getMarketplace();
      }
      if ($request->isSetMerchant()) {
        $parameters['Merchant'] =  $request->getMerchant();
      }
      if ($request->isSetNextToken()) {
        $parameters['NextToken'] =  $request->getNextToken();
      }
      if ($request->isSetMWSAuthToken()) {
        $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
      }

      return array(CONVERTED_PARAMETERS_KEY => $parameters, CONVERTED_HEADERS_KEY => $this->defaultHeaders);
    }


    /**
     * Convert CancelFeedSubmissionsRequest to name value pairs
     */
    private function convertCancelFeedSubmissions($request) {

      $parameters = array();
      $parameters['Action'] = 'CancelFeedSubmissions';
      if ($request->isSetMarketplace()) {
        $parameters['Marketplace'] =  $request->getMarketplace();
      }
      if ($request->isSetMerchant()) {
        $parameters['Merchant'] =  $request->getMerchant();
      }
      if ($request->isSetFeedSubmissionIdList()) {
        $feedSubmissionIdList = $request->getFeedSubmissionIdList();
        foreach  ($feedSubmissionIdList->getId() as $idIndex => $id) {
          $parameters['FeedSubmissionIdList' . '.' . 'Id' . '.'  . ($idIndex + 1)] =  $id;
        }
      }
      if ($request->isSetFeedTypeList()) {
        $feedTypeList = $request->getFeedTypeList();
        foreach  ($feedTypeList->getType() as $typeIndex => $type) {
          $parameters['FeedTypeList' . '.' . 'Type' . '.'  . ($typeIndex + 1)] =  $type;
        }
      }
      if ($request->isSetSubmittedFromDate()) {
        $parameters['SubmittedFromDate'] =
        $this->getFormattedTimestamp($request->getSubmittedFromDate());
      }
      if ($request->isSetSubmittedToDate()) {
        $parameters['SubmittedToDate'] =
        $this->getFormattedTimestamp($request->getSubmittedToDate());
      }
      if ($request->isSetMWSAuthToken()) {
        $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
      }

      return array(CONVERTED_PARAMETERS_KEY => $parameters, CONVERTED_HEADERS_KEY => $this->defaultHeaders);
    }


    /**
     * Convert RequestReportRequest to name value pairs
     */
    private function convertRequestReport($request) {

      $parameters = array();
      $parameters['Action'] = 'RequestReport';
      if ($request->isSetMarketplace()) {
        $parameters['Marketplace'] =  $request->getMarketplace();
      }
      if ($request->isSetMerchant()) {
        $parameters['Merchant'] =  $request->getMerchant();
      }
      if ($request->isSetMarketplaceIdList()) {
	$marketplaceIdList = $request->getMarketplaceIdList();
        foreach  ($marketplaceIdList->getId() as $idIndex => $id) {
          $parameters['MarketplaceIdList.Id.'.($idIndex + 1)] =  $id;
        }       
      }
      if ($request->isSetReportType()) {
        $parameters['ReportType'] =  $request->getReportType();
      }
      if ($request->isSetStartDate()) {
        $parameters['StartDate'] =
        $this->getFormattedTimestamp($request->getStartDate());
      }
      if ($request->isSetEndDate()) {
        $parameters['EndDate'] =
        $this->getFormattedTimestamp($request->getEndDate());
      }
      if ($request->isSetReportOptions()) {
        $parameters['ReportOptions'] =  $request->getReportOptions();
      }
      if ($request->isSetMWSAuthToken()) {
        $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
      }

      return array(CONVERTED_PARAMETERS_KEY => $parameters, CONVERTED_HEADERS_KEY => $this->defaultHeaders);
    }


    /**
     * Convert GetFeedSubmissionCountRequest to name value pairs
     */
    private function convertGetFeedSubmissionCount($request) {

      $parameters = array();
      $parameters['Action'] = 'GetFeedSubmissionCount';
      if ($request->isSetMarketplace()) {
        $parameters['Marketplace'] =  $request->getMarketplace();
      }
      if ($request->isSetMerchant()) {
        $parameters['Merchant'] =  $request->getMerchant();
      }
      if ($request->isSetFeedTypeList()) {
        $feedTypeList = $request->getFeedTypeList();
        foreach  ($feedTypeList->getType() as $typeIndex => $type) {
          $parameters['FeedTypeList' . '.' . 'Type' . '.'  . ($typeIndex + 1)] =  $type;
        }
      }
      if ($request->isSetFeedProcessingStatusList()) {
        $feedProcessingStatusList = $request->getFeedProcessingStatusList();
        foreach  ($feedProcessingStatusList->getStatus() as $statusIndex => $status) {
          $parameters['FeedProcessingStatusList' . '.' . 'Status' . '.'  . ($statusIndex + 1)] =  $status;
        }
      }
      if ($request->isSetSubmittedFromDate()) {
        $parameters['SubmittedFromDate'] =
        $this->getFormattedTimestamp($request->getSubmittedFromDate());
      }
      if ($request->isSetSubmittedToDate()) {
        $parameters['SubmittedToDate'] =
        $this->getFormattedTimestamp($request->getSubmittedToDate());
      }
      if ($request->isSetMWSAuthToken()) {
        $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
      }

      return array(CONVERTED_PARAMETERS_KEY => $parameters, CONVERTED_HEADERS_KEY => $this->defaultHeaders);
    }


    /**
     * Convert CancelReportRequestsRequest to name value pairs
     */
    private function convertCancelReportRequests($request) {

      $parameters = array();
      $parameters['Action'] = 'CancelReportRequests';
      if ($request->isSetMarketplace()) {
        $parameters['Marketplace'] =  $request->getMarketplace();
      }
      if ($request->isSetMerchant()) {
        $parameters['Merchant'] =  $request->getMerchant();
      }
      if ($request->isSetReportRequestIdList()) {
        $reportRequestIdList = $request->getReportRequestIdList();
        foreach  ($reportRequestIdList->getId() as $idIndex => $id) {
          $parameters['ReportRequestIdList' . '.' . 'Id' . '.'  . ($idIndex + 1)] =  $id;
        }
      }
      if ($request->isSetReportTypeList()) {
        $reportTypeList = $request->getReportTypeList();
        foreach  ($reportTypeList->getType() as $typeIndex => $type) {
          $parameters['ReportTypeList' . '.' . 'Type' . '.'  . ($typeIndex + 1)] =  $type;
        }
      }
      if ($request->isSetReportProcessingStatusList()) {
        $reportProcessingStatusList = $request->getReportProcessingStatusList();
        foreach  ($reportProcessingStatusList->getStatus() as $statusIndex => $status) {
          $parameters['ReportProcessingStatusList' . '.' . 'Status' . '.'  . ($statusIndex + 1)] =  $status;
        }
      }
      if ($request->isSetRequestedFromDate()) {
        $parameters['RequestedFromDate'] =
        $this->getFormattedTimestamp($request->getRequestedFromDate());
      }
      if ($request->isSetRequestedToDate()) {
        $parameters['RequestedToDate'] =
        $this->getFormattedTimestamp($request->getRequestedToDate());
      }
      if ($request->isSetMWSAuthToken()) {
        $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
      }

      return array(CONVERTED_PARAMETERS_KEY => $parameters, CONVERTED_HEADERS_KEY => $this->defaultHeaders);
    }


    /**
     * Convert GetReportListRequest to name value pairs
     */
    private function convertGetReportList($request) {

      $parameters = array();
      $parameters['Action'] = 'GetReportList';
      if ($request->isSetMarketplace()) {
        $parameters['Marketplace'] =  $request->getMarketplace();
      }
      if ($request->isSetMerchant()) {
        $parameters['Merchant'] =  $request->getMerchant();
      }
      if ($request->isSetMaxCount()) {
        $parameters['MaxCount'] =  $request->getMaxCount();
      }
      if ($request->isSetReportTypeList()) {
        $reportTypeList = $request->getReportTypeList();
        foreach  ($reportTypeList->getType() as $typeIndex => $type) {
          $parameters['ReportTypeList' . '.' . 'Type' . '.'  . ($typeIndex + 1)] =  $type;
        }
      }
      if ($request->isSetAcknowledged()) {
        $parameters['Acknowledged'] =  $request->getAcknowledged() ? "true" : "false";
      }
      if ($request->isSetAvailableFromDate()) {
        $parameters['AvailableFromDate'] =
        $this->getFormattedTimestamp($request->getAvailableFromDate());
      }
      if ($request->isSetAvailableToDate()) {
        $parameters['AvailableToDate'] =
        $this->getFormattedTimestamp($request->getAvailableToDate());
      }
      if ($request->isSetReportRequestIdList()) {
        $reportRequestIdList = $request->getReportRequestIdList();
        foreach  ($reportRequestIdList->getId() as $idIndex => $id) {
          $parameters['ReportRequestIdList' . '.' . 'Id' . '.'  . ($idIndex + 1)] =  $id;
        }
      }
      if ($request->isSetMWSAuthToken()) {
        $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
      }

      return array(CONVERTED_PARAMETERS_KEY => $parameters, CONVERTED_HEADERS_KEY => $this->defaultHeaders);
    }


    /**
     * Convert GetFeedSubmissionResultRequest to name value pairs
     */
    private function convertGetFeedSubmissionResult($request) {

      $parameters = array();
      $parameters['Action'] = 'GetFeedSubmissionResult';
      if ($request->isSetMarketplace()) {
        $parameters['Marketplace'] =  $request->getMarketplace();
      }
      if ($request->isSetMerchant()) {
        $parameters['Merchant'] =  $request->getMerchant();
      }
      if ($request->isSetFeedSubmissionId()) {
        $parameters['FeedSubmissionId'] =  $request->getFeedSubmissionId();
      }
      if ($request->isSetMWSAuthToken()) {
        $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
      }

      return array(CONVERTED_PARAMETERS_KEY => $parameters, CONVERTED_HEADERS_KEY => $this->defaultHeaders);
    }


    /**
     * Convert GetFeedSubmissionListRequest to name value pairs
     */
    private function convertGetFeedSubmissionList($request) {

      $parameters = array();
      $parameters['Action'] = 'GetFeedSubmissionList';
      if ($request->isSetMarketplace()) {
        $parameters['Marketplace'] =  $request->getMarketplace();
      }
      if ($request->isSetMerchant()) {
        $parameters['Merchant'] =  $request->getMerchant();
      }
      if ($request->isSetFeedSubmissionIdList()) {
        $feedSubmissionIdList = $request->getFeedSubmissionIdList();
        foreach  ($feedSubmissionIdList->getId() as $idIndex => $id) {
          $parameters['FeedSubmissionIdList' . '.' . 'Id' . '.'  . ($idIndex + 1)] =  $id;
        }
      }
      if ($request->isSetMaxCount()) {
        $parameters['MaxCount'] =  $request->getMaxCount();
      }
      if ($request->isSetFeedTypeList()) {
        $feedTypeList = $request->getFeedTypeList();
        foreach  ($feedTypeList->getType() as $typeIndex => $type) {
          $parameters['FeedTypeList' . '.' . 'Type' . '.'  . ($typeIndex + 1)] =  $type;
        }
      }
      if ($request->isSetFeedProcessingStatusList()) {
        $feedProcessingStatusList = $request->getFeedProcessingStatusList();
        foreach  ($feedProcessingStatusList->getStatus() as $statusIndex => $status) {
          $parameters['FeedProcessingStatusList' . '.' . 'Status' . '.'  . ($statusIndex + 1)] =  $status;
        }
      }
      if ($request->isSetSubmittedFromDate()) {
        $parameters['SubmittedFromDate'] =
        $this->getFormattedTimestamp($request->getSubmittedFromDate());
      }
      if ($request->isSetSubmittedToDate()) {
        $parameters['SubmittedToDate'] =
        $this->getFormattedTimestamp($request->getSubmittedToDate());
      }
      if ($request->isSetMWSAuthToken()) {
        $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
      }

      return array(CONVERTED_PARAMETERS_KEY => $parameters, CONVERTED_HEADERS_KEY => $this->defaultHeaders);
    }


    /**
     * Convert GetReportRequestListRequest to name value pairs
     */
    private function convertGetReportRequestList($request) {

      $parameters = array();
      $parameters['Action'] = 'GetReportRequestList';
      if ($request->isSetMarketplace()) {
        $parameters['Marketplace'] =  $request->getMarketplace();
      }
      if ($request->isSetMerchant()) {
        $parameters['Merchant'] =  $request->getMerchant();
      }
      if ($request->isSetReportRequestIdList()) {
        $reportRequestIdList = $request->getReportRequestIdList();
        foreach  ($reportRequestIdList->getId() as $idIndex => $id) {
          $parameters['ReportRequestIdList' . '.' . 'Id' . '.'  . ($idIndex + 1)] =  $id;
        }
      }
      if ($request->isSetReportTypeList()) {
        $reportTypeList = $request->getReportTypeList();
        foreach  ($reportTypeList->getType() as $typeIndex => $type) {
          $parameters['ReportTypeList' . '.' . 'Type' . '.'  . ($typeIndex + 1)] =  $type;
        }
      }
      if ($request->isSetReportProcessingStatusList()) {
        $reportProcessingStatusList = $request->getReportProcessingStatusList();
        foreach  ($reportProcessingStatusList->getStatus() as $statusIndex => $status) {
          $parameters['ReportProcessingStatusList' . '.' . 'Status' . '.'  . ($statusIndex + 1)] =  $status;
        }
      }
      if ($request->isSetMaxCount()) {
        $parameters['MaxCount'] =  $request->getMaxCount();
      }
      if ($request->isSetRequestedFromDate()) {
        $parameters['RequestedFromDate'] =
        $this->getFormattedTimestamp($request->getRequestedFromDate());
      }
      if ($request->isSetRequestedToDate()) {
        $parameters['RequestedToDate'] =
        $this->getFormattedTimestamp($request->getRequestedToDate());
      }
      if ($request->isSetMWSAuthToken()) {
        $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
      }

      return array(CONVERTED_PARAMETERS_KEY => $parameters, CONVERTED_HEADERS_KEY => $this->defaultHeaders);
    }


    /**
     * Convert GetReportScheduleListByNextTokenRequest to name value pairs
     */
    private function convertGetReportScheduleListByNextToken($request) {

      $parameters = array();
      $parameters['Action'] = 'GetReportScheduleListByNextToken';
      if ($request->isSetMarketplace()) {
        $parameters['Marketplace'] =  $request->getMarketplace();
      }
      if ($request->isSetMerchant()) {
        $parameters['Merchant'] =  $request->getMerchant();
      }
      if ($request->isSetNextToken()) {
        $parameters['NextToken'] =  $request->getNextToken();
      }
      if ($request->isSetMWSAuthToken()) {
        $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
      }

      return array(CONVERTED_PARAMETERS_KEY => $parameters, CONVERTED_HEADERS_KEY => $this->defaultHeaders);
    }


    /**
     * Convert GetReportListByNextTokenRequest to name value pairs
     */
    private function convertGetReportListByNextToken($request) {

      $parameters = array();
      $parameters['Action'] = 'GetReportListByNextToken';
      if ($request->isSetMarketplace()) {
        $parameters['Marketplace'] =  $request->getMarketplace();
      }
      if ($request->isSetMerchant()) {
        $parameters['Merchant'] =  $request->getMerchant();
      }
      if ($request->isSetNextToken()) {
        $parameters['NextToken'] =  $request->getNextToken();
      }
      if ($request->isSetMWSAuthToken()) {
        $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
      }

      return array(CONVERTED_PARAMETERS_KEY => $parameters, CONVERTED_HEADERS_KEY => $this->defaultHeaders);
    }


    /**
     * Convert ManageReportScheduleRequest to name value pairs
     */
    private function convertManageReportSchedule($request) {

      $parameters = array();
      $parameters['Action'] = 'ManageReportSchedule';
      if ($request->isSetMarketplace()) {
        $parameters['Marketplace'] =  $request->getMarketplace();
      }
      if ($request->isSetMerchant()) {
        $parameters['Merchant'] =  $request->getMerchant();
      }
      if ($request->isSetReportType()) {
        $parameters['ReportType'] =  $request->getReportType();
      }
      if ($request->isSetSchedule()) {
        $parameters['Schedule'] =  $request->getSchedule();
      }
      if ($request->isSetScheduleDate()) {
        $parameters['ScheduleDate'] =
        $this->getFormattedTimestamp($request->getScheduleDate());
      }
      if ($request->isSetMWSAuthToken()) {
        $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
      }
      
	  return array(CONVERTED_PARAMETERS_KEY => $parameters, CONVERTED_HEADERS_KEY => $this->defaultHeaders);
    }


    /**
     * Convert GetReportRequestCountRequest to name value pairs
     */
    private function convertGetReportRequestCount($request) {

      $parameters = array();
      $parameters['Action'] = 'GetReportRequestCount';
      if ($request->isSetMarketplace()) {
        $parameters['Marketplace'] =  $request->getMarketplace();
      }
      if ($request->isSetMerchant()) {
        $parameters['Merchant'] =  $request->getMerchant();
      }
      if ($request->isSetReportTypeList()) {
        $reportTypeList = $request->getReportTypeList();
        foreach  ($reportTypeList->getType() as $typeIndex => $type) {
          $parameters['ReportTypeList' . '.' . 'Type' . '.'  . ($typeIndex + 1)] =  $type;
        }
      }
      if ($request->isSetReportProcessingStatusList()) {
        $reportProcessingStatusList = $request->getReportProcessingStatusList();
        foreach  ($reportProcessingStatusList->getStatus() as $statusIndex => $status) {
          $parameters['ReportProcessingStatusList' . '.' . 'Status' . '.'  . ($statusIndex + 1)] =  $status;
        }
      }
      if ($request->isSetRequestedFromDate()) {
        $parameters['RequestedFromDate'] =
        $this->getFormattedTimestamp($request->getRequestedFromDate());
      }
      if ($request->isSetRequestedToDate()) {
        $parameters['RequestedToDate'] =
        $this->getFormattedTimestamp($request->getRequestedToDate());
      }
      if ($request->isSetMWSAuthToken()) {
        $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
      }

      return array(CONVERTED_PARAMETERS_KEY => $parameters, CONVERTED_HEADERS_KEY => $this->defaultHeaders);
    }


    /**
     * Convert GetReportScheduleListRequest to name value pairs
     */
    private function convertGetReportScheduleList($request) {

      $parameters = array();
      $parameters['Action'] = 'GetReportScheduleList';
      if ($request->isSetMarketplace()) {
        $parameters['Marketplace'] =  $request->getMarketplace();
      }
      if ($request->isSetMerchant()) {
        $parameters['Merchant'] =  $request->getMerchant();
      }
      if ($request->isSetReportTypeList()) {
        $reportTypeList = $request->getReportTypeList();
        foreach  ($reportTypeList->getType() as $typeIndex => $type) {
          $parameters['ReportTypeList' . '.' . 'Type' . '.'  . ($typeIndex + 1)] =  $type;
        }
      }
      if ($request->isSetMWSAuthToken()) {
        $parameters['MWSAuthToken'] = $request->getMWSAuthToken();
      }

      return array(CONVERTED_PARAMETERS_KEY => $parameters, CONVERTED_HEADERS_KEY => $this->defaultHeaders);
    }
  }

/*******************************************************************************
 * PHP Version 5
 * @category Amazon
 * @package  Marketplace Web Service Products
 * @version  2011-10-01
 * Library Version: 2017-03-22
 * Generated: Wed Mar 22 23:24:40 UTC 2017
 */

/**
 * Increase max runtime to 5 minutes - the max time allowed by Apache
 */
ini_set('max_execution_time', 300);

/**
 *  @see MarketplaceWebServiceProducts_Interface
 */
require_once (dirname(__FILE__) . '/Interface.php');

/**
 * MarketplaceWebServiceProducts_Client is an implementation of MarketplaceWebServiceProducts
 *
 */
class MarketplaceWebServiceProducts_Client implements MarketplaceWebServiceProducts_Interface
{

    const SERVICE_VERSION = '2011-10-01';
    const MWS_CLIENT_VERSION = '2017-03-22';

    /** @var string */
    private  $_awsAccessKeyId = null;

    /** @var string */
    private  $_awsSecretAccessKey = null;

    /** @var array */
    private  $_config = array ('ServiceURL' => null,
                               'UserAgent' => 'MarketplaceWebServiceProducts PHP5 Library',
                               'SignatureVersion' => 2,
                               'SignatureMethod' => 'HmacSHA256',
                               'ProxyHost' => null,
                               'ProxyPort' => -1,
                               'ProxyUsername' => null,
                               'ProxyPassword' => null,
                               'MaxErrorRetry' => 3,
                               'Headers' => array()
                               );


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
    public function getCompetitivePricingForASIN($request)
    {
        if (!($request instanceof MarketplaceWebServiceProducts_Model_GetCompetitivePricingForASINRequest)) {
            require_once (dirname(__FILE__) . '/Model/GetCompetitivePricingForASINRequest.php');
            $request = new MarketplaceWebServiceProducts_Model_GetCompetitivePricingForASINRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetCompetitivePricingForASIN';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetCompetitivePricingForASINResponse.php');
        $response = MarketplaceWebServiceProducts_Model_GetCompetitivePricingForASINResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetCompetitivePricingForASINRequest to name value pairs
     */
    private function _convertGetCompetitivePricingForASIN($request) {

        $parameters = array();
        $parameters['Action'] = 'GetCompetitivePricingForASIN';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplaceId()) {
            $parameters['MarketplaceId'] =  $request->getMarketplaceId();
        }
        if ($request->isSetASINList()) {
            $ASINListGetCompetitivePricingForASINRequest = $request->getASINList();
            foreach  ($ASINListGetCompetitivePricingForASINRequest->getASIN() as $ASINASINListIndex => $ASINASINList) {
                $parameters['ASINList' . '.' . 'ASIN' . '.'  . ($ASINASINListIndex + 1)] =  $ASINASINList;
            }
        }

        return $parameters;
    }


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
    public function getCompetitivePricingForSKU($request)
    {
        if (!($request instanceof MarketplaceWebServiceProducts_Model_GetCompetitivePricingForSKURequest)) {
            require_once (dirname(__FILE__) . '/Model/GetCompetitivePricingForSKURequest.php');
            $request = new MarketplaceWebServiceProducts_Model_GetCompetitivePricingForSKURequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetCompetitivePricingForSKU';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetCompetitivePricingForSKUResponse.php');
        $response = MarketplaceWebServiceProducts_Model_GetCompetitivePricingForSKUResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetCompetitivePricingForSKURequest to name value pairs
     */
    private function _convertGetCompetitivePricingForSKU($request) {

        $parameters = array();
        $parameters['Action'] = 'GetCompetitivePricingForSKU';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplaceId()) {
            $parameters['MarketplaceId'] =  $request->getMarketplaceId();
        }
        if ($request->isSetSellerSKUList()) {
            $SellerSKUListGetCompetitivePricingForSKURequest = $request->getSellerSKUList();
            foreach  ($SellerSKUListGetCompetitivePricingForSKURequest->getSellerSKU() as $SellerSKUSellerSKUListIndex => $SellerSKUSellerSKUList) {
                $parameters['SellerSKUList' . '.' . 'SellerSKU' . '.'  . ($SellerSKUSellerSKUListIndex + 1)] =  $SellerSKUSellerSKUList;
            }
        }

        return $parameters;
    }


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
    public function getLowestOfferListingsForASIN($request)
    {
        if (!($request instanceof MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForASINRequest)) {
            require_once (dirname(__FILE__) . '/Model/GetLowestOfferListingsForASINRequest.php');
            $request = new MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForASINRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetLowestOfferListingsForASIN';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetLowestOfferListingsForASINResponse.php');
        $response = MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForASINResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetLowestOfferListingsForASINRequest to name value pairs
     */
    private function _convertGetLowestOfferListingsForASIN($request) {

        $parameters = array();
        $parameters['Action'] = 'GetLowestOfferListingsForASIN';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplaceId()) {
            $parameters['MarketplaceId'] =  $request->getMarketplaceId();
        }
        if ($request->isSetASINList()) {
            $ASINListGetLowestOfferListingsForASINRequest = $request->getASINList();
            foreach  ($ASINListGetLowestOfferListingsForASINRequest->getASIN() as $ASINASINListIndex => $ASINASINList) {
                $parameters['ASINList' . '.' . 'ASIN' . '.'  . ($ASINASINListIndex + 1)] =  $ASINASINList;
            }
        }
        if ($request->isSetItemCondition()) {
            $parameters['ItemCondition'] =  $request->getItemCondition();
        }
        if ($request->isSetExcludeMe()) {
            $parameters['ExcludeMe'] =  $request->getExcludeMe() ? "true" : "false";
        }

        return $parameters;
    }


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
    public function getLowestOfferListingsForSKU($request)
    {
        if (!($request instanceof MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForSKURequest)) {
            require_once (dirname(__FILE__) . '/Model/GetLowestOfferListingsForSKURequest.php');
            $request = new MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForSKURequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetLowestOfferListingsForSKU';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetLowestOfferListingsForSKUResponse.php');
        $response = MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForSKUResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetLowestOfferListingsForSKURequest to name value pairs
     */
    private function _convertGetLowestOfferListingsForSKU($request) {

        $parameters = array();
        $parameters['Action'] = 'GetLowestOfferListingsForSKU';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplaceId()) {
            $parameters['MarketplaceId'] =  $request->getMarketplaceId();
        }
        if ($request->isSetSellerSKUList()) {
            $SellerSKUListGetLowestOfferListingsForSKURequest = $request->getSellerSKUList();
            foreach  ($SellerSKUListGetLowestOfferListingsForSKURequest->getSellerSKU() as $SellerSKUSellerSKUListIndex => $SellerSKUSellerSKUList) {
                $parameters['SellerSKUList' . '.' . 'SellerSKU' . '.'  . ($SellerSKUSellerSKUListIndex + 1)] =  $SellerSKUSellerSKUList;
            }
        }
        if ($request->isSetItemCondition()) {
            $parameters['ItemCondition'] =  $request->getItemCondition();
        }
        if ($request->isSetExcludeMe()) {
            $parameters['ExcludeMe'] =  $request->getExcludeMe() ? "true" : "false";
        }

        return $parameters;
    }


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
    public function getLowestPricedOffersForASIN($request)
    {
        if (!($request instanceof MarketplaceWebServiceProducts_Model_GetLowestPricedOffersForASINRequest)) {
            require_once (dirname(__FILE__) . '/Model/GetLowestPricedOffersForASINRequest.php');
            $request = new MarketplaceWebServiceProducts_Model_GetLowestPricedOffersForASINRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetLowestPricedOffersForASIN';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetLowestPricedOffersForASINResponse.php');
        $response = MarketplaceWebServiceProducts_Model_GetLowestPricedOffersForASINResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetLowestPricedOffersForASINRequest to name value pairs
     */
    private function _convertGetLowestPricedOffersForASIN($request) {

        $parameters = array();
        $parameters['Action'] = 'GetLowestPricedOffersForASIN';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplaceId()) {
            $parameters['MarketplaceId'] =  $request->getMarketplaceId();
        }
        if ($request->isSetASIN()) {
            $parameters['ASIN'] =  $request->getASIN();
        }
        if ($request->isSetItemCondition()) {
            $parameters['ItemCondition'] =  $request->getItemCondition();
        }

        return $parameters;
    }


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
    public function getLowestPricedOffersForSKU($request)
    {
        if (!($request instanceof MarketplaceWebServiceProducts_Model_GetLowestPricedOffersForSKURequest)) {
            require_once (dirname(__FILE__) . '/Model/GetLowestPricedOffersForSKURequest.php');
            $request = new MarketplaceWebServiceProducts_Model_GetLowestPricedOffersForSKURequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetLowestPricedOffersForSKU';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetLowestPricedOffersForSKUResponse.php');
        $response = MarketplaceWebServiceProducts_Model_GetLowestPricedOffersForSKUResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetLowestPricedOffersForSKURequest to name value pairs
     */
    private function _convertGetLowestPricedOffersForSKU($request) {

        $parameters = array();
        $parameters['Action'] = 'GetLowestPricedOffersForSKU';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplaceId()) {
            $parameters['MarketplaceId'] =  $request->getMarketplaceId();
        }
        if ($request->isSetSellerSKU()) {
            $parameters['SellerSKU'] =  $request->getSellerSKU();
        }
        if ($request->isSetItemCondition()) {
            $parameters['ItemCondition'] =  $request->getItemCondition();
        }

        return $parameters;
    }


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
    public function getMatchingProduct($request)
    {
        if (!($request instanceof MarketplaceWebServiceProducts_Model_GetMatchingProductRequest)) {
            require_once (dirname(__FILE__) . '/Model/GetMatchingProductRequest.php');
            $request = new MarketplaceWebServiceProducts_Model_GetMatchingProductRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetMatchingProduct';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetMatchingProductResponse.php');
        $response = MarketplaceWebServiceProducts_Model_GetMatchingProductResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetMatchingProductRequest to name value pairs
     */
    private function _convertGetMatchingProduct($request) {

        $parameters = array();
        $parameters['Action'] = 'GetMatchingProduct';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplaceId()) {
            $parameters['MarketplaceId'] =  $request->getMarketplaceId();
        }
        if ($request->isSetASINList()) {
            $ASINListGetMatchingProductRequest = $request->getASINList();
            foreach  ($ASINListGetMatchingProductRequest->getASIN() as $ASINASINListIndex => $ASINASINList) {
                $parameters['ASINList' . '.' . 'ASIN' . '.'  . ($ASINASINListIndex + 1)] =  $ASINASINList;
            }
        }

        return $parameters;
    }


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
    public function getMatchingProductForId($request)
    {
        if (!($request instanceof MarketplaceWebServiceProducts_Model_GetMatchingProductForIdRequest)) {
            require_once (dirname(__FILE__) . '/Model/GetMatchingProductForIdRequest.php');
            $request = new MarketplaceWebServiceProducts_Model_GetMatchingProductForIdRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetMatchingProductForId';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetMatchingProductForIdResponse.php');
        $response = MarketplaceWebServiceProducts_Model_GetMatchingProductForIdResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetMatchingProductForIdRequest to name value pairs
     */
    private function _convertGetMatchingProductForId($request) {

        $parameters = array();
        $parameters['Action'] = 'GetMatchingProductForId';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplaceId()) {
            $parameters['MarketplaceId'] =  $request->getMarketplaceId();
        }
        if ($request->isSetIdType()) {
            $parameters['IdType'] =  $request->getIdType();
        }
        if ($request->isSetIdList()) {
            $IdListGetMatchingProductForIdRequest = $request->getIdList();
            foreach  ($IdListGetMatchingProductForIdRequest->getId() as $IdIdListIndex => $IdIdList) {
                $parameters['IdList' . '.' . 'Id' . '.'  . ($IdIdListIndex + 1)] =  $IdIdList;
            }
        }

        return $parameters;
    }


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
    public function getMyFeesEstimate($request)
    {
        if (!($request instanceof MarketplaceWebServiceProducts_Model_GetMyFeesEstimateRequest)) {
            require_once (dirname(__FILE__) . '/Model/GetMyFeesEstimateRequest.php');
            $request = new MarketplaceWebServiceProducts_Model_GetMyFeesEstimateRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetMyFeesEstimate';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetMyFeesEstimateResponse.php');
        $response = MarketplaceWebServiceProducts_Model_GetMyFeesEstimateResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetMyFeesEstimateRequest to name value pairs
     */
    private function _convertGetMyFeesEstimate($request) {

        $parameters = array();
        $parameters['Action'] = 'GetMyFeesEstimate';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetFeesEstimateRequestList()) {
            $FeesEstimateRequestListGetMyFeesEstimateRequest = $request->getFeesEstimateRequestList();
            foreach  ($FeesEstimateRequestListGetMyFeesEstimateRequest->getFeesEstimateRequest() as $FeesEstimateRequestFeesEstimateRequestListIndex => $FeesEstimateRequestFeesEstimateRequestList) {
                $parameters['FeesEstimateRequestList' . '.' . 'FeesEstimateRequest' . '.'  . ($FeesEstimateRequestFeesEstimateRequestListIndex + 1)] =  $FeesEstimateRequestFeesEstimateRequestList;
            }
        }

        return $parameters;
    }


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
    public function getMyPriceForASIN($request)
    {
        if (!($request instanceof MarketplaceWebServiceProducts_Model_GetMyPriceForASINRequest)) {
            require_once (dirname(__FILE__) . '/Model/GetMyPriceForASINRequest.php');
            $request = new MarketplaceWebServiceProducts_Model_GetMyPriceForASINRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetMyPriceForASIN';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetMyPriceForASINResponse.php');
        $response = MarketplaceWebServiceProducts_Model_GetMyPriceForASINResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetMyPriceForASINRequest to name value pairs
     */
    private function _convertGetMyPriceForASIN($request) {

        $parameters = array();
        $parameters['Action'] = 'GetMyPriceForASIN';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplaceId()) {
            $parameters['MarketplaceId'] =  $request->getMarketplaceId();
        }
        if ($request->isSetASINList()) {
            $ASINListGetMyPriceForASINRequest = $request->getASINList();
            foreach  ($ASINListGetMyPriceForASINRequest->getASIN() as $ASINASINListIndex => $ASINASINList) {
                $parameters['ASINList' . '.' . 'ASIN' . '.'  . ($ASINASINListIndex + 1)] =  $ASINASINList;
            }
        }

        return $parameters;
    }


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
    public function getMyPriceForSKU($request)
    {
        if (!($request instanceof MarketplaceWebServiceProducts_Model_GetMyPriceForSKURequest)) {
            require_once (dirname(__FILE__) . '/Model/GetMyPriceForSKURequest.php');
            $request = new MarketplaceWebServiceProducts_Model_GetMyPriceForSKURequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetMyPriceForSKU';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetMyPriceForSKUResponse.php');
        $response = MarketplaceWebServiceProducts_Model_GetMyPriceForSKUResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetMyPriceForSKURequest to name value pairs
     */
    private function _convertGetMyPriceForSKU($request) {

        $parameters = array();
        $parameters['Action'] = 'GetMyPriceForSKU';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplaceId()) {
            $parameters['MarketplaceId'] =  $request->getMarketplaceId();
        }
        if ($request->isSetSellerSKUList()) {
            $SellerSKUListGetMyPriceForSKURequest = $request->getSellerSKUList();
            foreach  ($SellerSKUListGetMyPriceForSKURequest->getSellerSKU() as $SellerSKUSellerSKUListIndex => $SellerSKUSellerSKUList) {
                $parameters['SellerSKUList' . '.' . 'SellerSKU' . '.'  . ($SellerSKUSellerSKUListIndex + 1)] =  $SellerSKUSellerSKUList;
            }
        }

        return $parameters;
    }


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
    public function getProductCategoriesForASIN($request)
    {
        if (!($request instanceof MarketplaceWebServiceProducts_Model_GetProductCategoriesForASINRequest)) {
            require_once (dirname(__FILE__) . '/Model/GetProductCategoriesForASINRequest.php');
            $request = new MarketplaceWebServiceProducts_Model_GetProductCategoriesForASINRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetProductCategoriesForASIN';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetProductCategoriesForASINResponse.php');
        $response = MarketplaceWebServiceProducts_Model_GetProductCategoriesForASINResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetProductCategoriesForASINRequest to name value pairs
     */
    private function _convertGetProductCategoriesForASIN($request) {

        $parameters = array();
        $parameters['Action'] = 'GetProductCategoriesForASIN';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplaceId()) {
            $parameters['MarketplaceId'] =  $request->getMarketplaceId();
        }
        if ($request->isSetASIN()) {
            $parameters['ASIN'] =  $request->getASIN();
        }

        return $parameters;
    }


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
    public function getProductCategoriesForSKU($request)
    {
        if (!($request instanceof MarketplaceWebServiceProducts_Model_GetProductCategoriesForSKURequest)) {
            require_once (dirname(__FILE__) . '/Model/GetProductCategoriesForSKURequest.php');
            $request = new MarketplaceWebServiceProducts_Model_GetProductCategoriesForSKURequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetProductCategoriesForSKU';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetProductCategoriesForSKUResponse.php');
        $response = MarketplaceWebServiceProducts_Model_GetProductCategoriesForSKUResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetProductCategoriesForSKURequest to name value pairs
     */
    private function _convertGetProductCategoriesForSKU($request) {

        $parameters = array();
        $parameters['Action'] = 'GetProductCategoriesForSKU';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplaceId()) {
            $parameters['MarketplaceId'] =  $request->getMarketplaceId();
        }
        if ($request->isSetSellerSKU()) {
            $parameters['SellerSKU'] =  $request->getSellerSKU();
        }

        return $parameters;
    }


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
    public function getServiceStatus($request)
    {
        if (!($request instanceof MarketplaceWebServiceProducts_Model_GetServiceStatusRequest)) {
            require_once (dirname(__FILE__) . '/Model/GetServiceStatusRequest.php');
            $request = new MarketplaceWebServiceProducts_Model_GetServiceStatusRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'GetServiceStatus';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/GetServiceStatusResponse.php');
        $response = MarketplaceWebServiceProducts_Model_GetServiceStatusResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert GetServiceStatusRequest to name value pairs
     */
    private function _convertGetServiceStatus($request) {

        $parameters = array();
        $parameters['Action'] = 'GetServiceStatus';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }

        return $parameters;
    }


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
    public function listMatchingProducts($request)
    {
        if (!($request instanceof MarketplaceWebServiceProducts_Model_ListMatchingProductsRequest)) {
            require_once (dirname(__FILE__) . '/Model/ListMatchingProductsRequest.php');
            $request = new MarketplaceWebServiceProducts_Model_ListMatchingProductsRequest($request);
        }
        $parameters = $request->toQueryParameterArray();
        $parameters['Action'] = 'ListMatchingProducts';
        $httpResponse = $this->_invoke($parameters);

        require_once (dirname(__FILE__) . '/Model/ListMatchingProductsResponse.php');
        $response = MarketplaceWebServiceProducts_Model_ListMatchingProductsResponse::fromXML($httpResponse['ResponseBody']);
        $response->setResponseHeaderMetadata($httpResponse['ResponseHeaderMetadata']);
        return $response;
    }


    /**
     * Convert ListMatchingProductsRequest to name value pairs
     */
    private function _convertListMatchingProducts($request) {

        $parameters = array();
        $parameters['Action'] = 'ListMatchingProducts';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMWSAuthToken()) {
            $parameters['MWSAuthToken'] =  $request->getMWSAuthToken();
        }
        if ($request->isSetMarketplaceId()) {
            $parameters['MarketplaceId'] =  $request->getMarketplaceId();
        }
        if ($request->isSetQuery()) {
            $parameters['Query'] =  $request->getQuery();
        }
        if ($request->isSetQueryContextId()) {
            $parameters['QueryContextId'] =  $request->getQueryContextId();
        }

        return $parameters;
    }



    /**
     * Construct new Client
     *
     * @param string $awsAccessKeyId AWS Access Key ID
     * @param string $awsSecretAccessKey AWS Secret Access Key
     * @param array $config configuration options.
     * Valid configuration options are:
     * <ul>
     * <li>ServiceURL</li>
     * <li>UserAgent</li>
     * <li>SignatureVersion</li>
     * <li>TimesRetryOnError</li>
     * <li>ProxyHost</li>
     * <li>ProxyPort</li>
     * <li>ProxyUsername<li>
     * <li>ProxyPassword<li>
     * <li>MaxErrorRetry</li>
     * </ul>
     */
    public function __construct($awsAccessKeyId, $awsSecretAccessKey, $applicationName, $applicationVersion, $config = null)
    {
        $this->_awsAccessKeyId = $awsAccessKeyId;
        $this->_awsSecretAccessKey = $awsSecretAccessKey;
        if (!is_null($config)) $this->_config = array_merge($this->_config, $config);
        $this->setUserAgentHeader($applicationName, $applicationVersion);
    }

    private function setUserAgentHeader(
        $applicationName,
        $applicationVersion,
        $attributes = null) {

        if (is_null($attributes)) {
            $attributes = array ();
        }

        $this->_config['UserAgent'] = 
            $this->constructUserAgentHeader($applicationName, $applicationVersion, $attributes);
    }

    private function constructUserAgentHeader($applicationName, $applicationVersion, $attributes = null) {
        if (is_null($applicationName) || $applicationName === "") {
            throw new InvalidArgumentException('$applicationName cannot be null');
        }

        if (is_null($applicationVersion) || $applicationVersion === "") {
            throw new InvalidArgumentException('$applicationVersion cannot be null');
        }

        $userAgent = 
            $this->quoteApplicationName($applicationName)
            . '/'
            . $this->quoteApplicationVersion($applicationVersion);

        $userAgent .= ' (';
        $userAgent .= 'Language=PHP/' . phpversion();
        $userAgent .= '; ';
        $userAgent .= 'Platform=' . php_uname('s') . '/' . php_uname('m') . '/' . php_uname('r');
        $userAgent .= '; ';
        $userAgent .= 'MWSClientVersion=' . self::MWS_CLIENT_VERSION;

        foreach ($attributes as $key => $value) {
            if (empty($value)) {
                throw new InvalidArgumentException("Value for $key cannot be null or empty.");
            }

            $userAgent .= '; '
                . $this->quoteAttributeName($key)
                . '='
                . $this->quoteAttributeValue($value);
        }

        $userAgent .= ')';

        return $userAgent;
    }

   /**
    * Collapse multiple whitespace characters into a single ' ' character.
    * @param $s
    * @return string
    */
   private function collapseWhitespace($s) {
       return preg_replace('/ {2,}|\s/', ' ', $s);
   }

    /**
     * Collapse multiple whitespace characters into a single ' ' and backslash escape '\',
     * and '/' characters from a string.
     * @param $s
     * @return string
     */
    private function quoteApplicationName($s) {
        $quotedString = $this->collapseWhitespace($s);
        $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
        $quotedString = preg_replace('/\//', '\\/', $quotedString);

        return $quotedString;
    }

    /**
     * Collapse multiple whitespace characters into a single ' ' and backslash escape '\',
     * and '(' characters from a string.
     *
     * @param $s
     * @return string
     */
    private function quoteApplicationVersion($s) {
        $quotedString = $this->collapseWhitespace($s);
        $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
        $quotedString = preg_replace('/\\(/', '\\(', $quotedString);

        return $quotedString;
    }

    /**
     * Collapse multiple whitespace characters into a single ' ' and backslash escape '\',
     * and '=' characters from a string.
     *
     * @param $s
     * @return unknown_type
     */
    private function quoteAttributeName($s) {
        $quotedString = $this->collapseWhitespace($s);
        $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
        $quotedString = preg_replace('/\\=/', '\\=', $quotedString);

        return $quotedString;
    }

    /**
     * Collapse multiple whitespace characters into a single ' ' and backslash escape ';', '\',
     * and ')' characters from a string.
     *
     * @param $s
     * @return unknown_type
     */
    private function quoteAttributeValue($s) {
        $quotedString = $this->collapseWhitespace($s);
        $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
        $quotedString = preg_replace('/\\;/', '\\;', $quotedString);
        $quotedString = preg_replace('/\\)/', '\\)', $quotedString);

        return $quotedString;
    }


    // Private API ------------------------------------------------------------//

    /**
     * Invoke request and return response
     */
    private function _invoke(array $parameters)
    {
        try {
            if (empty($this->_config['ServiceURL'])) {
                require_once (dirname(__FILE__) . '/Exception.php');
                throw new MarketplaceWebServiceProducts_Exception(
                    array ('ErrorCode' => 'InvalidServiceURL',
                           'Message' => "Missing serviceUrl configuration value. You may obtain a list of valid MWS URLs by consulting the MWS Developer's Guide, or reviewing the sample code published along side this library."));
            }
            $parameters = $this->_addRequiredParameters($parameters);
            $retries = 0;
            for (;;) {
                $response = $this->_httpPost($parameters);
                $status = $response['Status'];
                if ($status == 200) {
                    return array('ResponseBody' => $response['ResponseBody'],
                      'ResponseHeaderMetadata' => $response['ResponseHeaderMetadata']);
                }
                if ($status == 500 && $this->_pauseOnRetry(++$retries)) {
                    continue;
                }
                throw $this->_reportAnyErrors($response['ResponseBody'],
                    $status, $response['ResponseHeaderMetadata']);
            }
        } catch (MarketplaceWebServiceProducts_Exception $se) {
            throw $se;
        } catch (Exception $t) {
            require_once (dirname(__FILE__) . '/Exception.php');
            throw new MarketplaceWebServiceProducts_Exception(array('Exception' => $t, 'Message' => $t->getMessage()));
        }
    }

    /**
     * Look for additional error strings in the response and return formatted exception
     */
    private function _reportAnyErrors($responseBody, $status, $responseHeaderMetadata, Exception $e =  null)
    {
        $exProps = array();
        $exProps["StatusCode"] = $status;
        $exProps["ResponseHeaderMetadata"] = $responseHeaderMetadata;

        libxml_use_internal_errors(true);  // Silence XML parsing errors
        $xmlBody = simplexml_load_string($responseBody);

        if ($xmlBody !== false) {  // Check XML loaded without errors
            $exProps["XML"] = $responseBody;
            $exProps["ErrorCode"] = $xmlBody->Error->Code;
            $exProps["Message"] = $xmlBody->Error->Message;
            $exProps["ErrorType"] = !empty($xmlBody->Error->Type) ? $xmlBody->Error->Type : "Unknown";
            $exProps["RequestId"] = !empty($xmlBody->RequestID) ? $xmlBody->RequestID : $xmlBody->RequestId; // 'd' in RequestId is sometimes capitalized
        } else { // We got bad XML in response, just throw a generic exception
            $exProps["Message"] = "Internal Error";
        }

        require_once (dirname(__FILE__) . '/Exception.php');
        return new MarketplaceWebServiceProducts_Exception($exProps);
    }



    /**
     * Perform HTTP post with exponential retries on error 500 and 503
     *
     */
    private function _httpPost(array $parameters)
    {
        $config = $this->_config;
        $query = $this->_getParametersAsString($parameters);
        $url = parse_url ($config['ServiceURL']);
        $uri = array_key_exists('path', $url) ? $url['path'] : null;
        if (!isset ($uri)) {
                $uri = "/";
        }

        switch ($url['scheme']) {
            case 'https':
                $scheme = 'https://';
                $port = isset($url['port']) ? $url['port'] : 443;
                break;
            default:
                $scheme = 'http://';
                $port = isset($url['port']) ? $url['port'] : 80;
        }

        $allHeaders = $config['Headers'];
        $allHeaders['Content-Type'] = "application/x-www-form-urlencoded; charset=utf-8"; // We need to make sure to set utf-8 encoding here
        $allHeaders['Expect'] = null; // Don't expect 100 Continue
        $allHeadersStr = array();
        foreach($allHeaders as $name => $val) {
            $str = $name . ": ";
            if(isset($val)) {
                $str = $str . $val;
            }
            $allHeadersStr[] = $str;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $scheme . $url['host'] . $uri);
        curl_setopt($ch, CURLOPT_PORT, $port);
        $this->setSSLCurlOptions($ch);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->_config['UserAgent']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $allHeadersStr);
        curl_setopt($ch, CURLOPT_HEADER, true); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($config['ProxyHost'] != null && $config['ProxyPort'] != -1)
        {
            curl_setopt($ch, CURLOPT_PROXY, $config['ProxyHost'] . ':' . $config['ProxyPort']);
        }
        if ($config['ProxyUsername'] != null && $config['ProxyPassword'] != null)
        {
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $config['ProxyUsername'] . ':' . $config['ProxyPassword']);
        }

        $response = "";
        $response = curl_exec($ch);

        if($response === false) {
            require_once (dirname(__FILE__) . '/Exception.php');
            $exProps["Message"] = curl_error($ch);
            $exProps["ErrorType"] = "HTTP";
            curl_close($ch);
            throw new MarketplaceWebServiceProducts_Exception($exProps);
        }

        curl_close($ch);
        return $this->_extractHeadersAndBody($response);
    }
    
    /**
     * This method will attempt to extract the headers and body of our response.
     * We need to split the raw response string by 2 'CRLF's.  2 'CRLF's should indicate the separation of the response header
     * from the response body.  However in our case we have some circumstances (certain client proxies) that result in 
     * multiple responses concatenated.  We could encounter a response like
     *
     * HTTP/1.1 100 Continue
     *
     * HTTP/1.1 200 OK
     * Date: Tue, 01 Apr 2014 13:02:51 GMT
     * Content-Type: text/html; charset=iso-8859-1
     * Content-Length: 12605
     *
     * ... body ..
     *
     * This method will throw away extra response status lines and attempt to find the first full response headers and body
     *
     * return [status, body, ResponseHeaderMetadata]
     */
    private function _extractHeadersAndBody($response){
        //First split by 2 'CRLF'
        $responseComponents = preg_split("/(?:\r?\n){2}/", $response, 2);
        $body = null;
        for ($count = 0; 
                $count < count($responseComponents) && $body == null; 
                $count++) {
            
            $headers = $responseComponents[$count];
            $responseStatus = $this->_extractHttpStatusCode($headers);
            
            if($responseStatus != null && 
                    $this->_httpHeadersHaveContent($headers)){
                
                $responseHeaderMetadata = $this->_extractResponseHeaderMetadata($headers);
                //The body will be the next item in the responseComponents array
                $body = $responseComponents[++$count];
            }
        }
        
        //If the body is null here then we were unable to parse the response and will throw an exception
        if($body == null){
            require_once (dirname(__FILE__) . '/Exception.php');
            $exProps["Message"] = "Failed to parse valid HTTP response (" . $response . ")";
            $exProps["ErrorType"] = "HTTP";
            throw new MarketplaceWebServiceProducts_Exception($exProps);
        }

        return array(
                'Status' => $responseStatus, 
                'ResponseBody' => $body, 
                'ResponseHeaderMetadata' => $responseHeaderMetadata);
    }
    
    /**
     * parse the status line of a header string for the proper format and
     * return the status code
     *
     * Example: HTTP/1.1 200 OK
     * ...
     * returns String statusCode or null if the status line can't be parsed
     */
    private function _extractHttpStatusCode($headers){
    	$statusCode = null; 
        if (1 === preg_match("/(\\S+) +(\\d+) +([^\n\r]+)(?:\r?\n|\r)/", $headers, $matches)) {
        	//The matches array [entireMatchString, protocol, statusCode, the rest]
            $statusCode = $matches[2]; 
        }
        return $statusCode;
    }
    
    /**
     * Tries to determine some valid headers indicating this response
     * has content.  In this case
     * return true if there is a valid "Content-Length" or "Transfer-Encoding" header
     */
    private function _httpHeadersHaveContent($headers){
        return (1 === preg_match("/[cC]ontent-[lL]ength: +(?:\\d+)(?:\\r?\\n|\\r|$)/", $headers) ||
                1 === preg_match("/Transfer-Encoding: +(?!identity[\r\n;= ])(?:[^\r\n]+)(?:\r?\n|\r|$)/i", $headers));
    }
    
    /**
    *  extract a ResponseHeaderMetadata object from the raw headers
    */
    private function _extractResponseHeaderMetadata($rawHeaders){
        $inputHeaders = preg_split("/\r\n|\n|\r/", $rawHeaders);
        $headers = array();
        $headers['x-mws-request-id'] = null;
        $headers['x-mws-response-context'] = null;
        $headers['x-mws-timestamp'] = null;
        $headers['x-mws-quota-max'] = null;
        $headers['x-mws-quota-remaining'] = null;
        $headers['x-mws-quota-resetsOn'] = null;

        foreach ($inputHeaders as $currentHeader) {
            $keyValue = explode (': ', $currentHeader);
            if (isset($keyValue[1])) {
                list ($key, $value) = $keyValue;
                if (isset($headers[$key]) && $headers[$key]!==null) {
                    $headers[$key] = $headers[$key] . "," . $value;
                } else {
                    $headers[$key] = $value;
                }
            }
        }
 
        require_once(dirname(__FILE__) . '/Model/ResponseHeaderMetadata.php');
        return new MarketplaceWebServiceProducts_Model_ResponseHeaderMetadata(
          $headers['x-mws-request-id'],
          $headers['x-mws-response-context'],
          $headers['x-mws-timestamp'],
          $headers['x-mws-quota-max'],
          $headers['x-mws-quota-remaining'],
          $headers['x-mws-quota-resetsOn']);
    }

    /**
     * Set curl options relating to SSL. Protected to allow overriding.
     * @param $ch curl handle
     */
    protected function setSSLCurlOptions($ch) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }

    /**
     * Exponential sleep on failed request
     *
     * @param retries current retry
     */
    private function _pauseOnRetry($retries)
    {
        if ($retries <= $this->_config['MaxErrorRetry']) {
            $delay = (int) (pow(4, $retries) * 100000);
            usleep($delay);
            return true;
        } 
        return false;
    }

    /**
     * Add authentication related and version parameters
     */
    private function _addRequiredParameters(array $parameters)
    {
        $parameters['AWSAccessKeyId'] = $this->_awsAccessKeyId;
        $parameters['Timestamp'] = $this->_getFormattedTimestamp();
        $parameters['Version'] = self::SERVICE_VERSION;
        $parameters['SignatureVersion'] = $this->_config['SignatureVersion'];
        if ($parameters['SignatureVersion'] > 1) {
            $parameters['SignatureMethod'] = $this->_config['SignatureMethod'];
        }
        $parameters['Signature'] = $this->_signParameters($parameters, $this->_awsSecretAccessKey);

        return $parameters;
    }

    /**
     * Convert paremeters to Url encoded query string
     */
    private function _getParametersAsString(array $parameters)
    {
        $queryParameters = array();
        foreach ($parameters as $key => $value) {
            $queryParameters[] = $key . '=' . $this->_urlencode($value);
        }
        return implode('&', $queryParameters);
    }


    /**
     * Computes RFC 2104-compliant HMAC signature for request parameters
     * Implements AWS Signature, as per following spec:
     *
     * If Signature Version is 0, it signs concatenated Action and Timestamp
     *
     * If Signature Version is 1, it performs the following:
     *
     * Sorts all  parameters (including SignatureVersion and excluding Signature,
     * the value of which is being created), ignoring case.
     *
     * Iterate over the sorted list and append the parameter name (in original case)
     * and then its value. It will not URL-encode the parameter values before
     * constructing this string. There are no separators.
     *
     * If Signature Version is 2, string to sign is based on following:
     *
     *    1. The HTTP Request Method followed by an ASCII newline (%0A)
     *    2. The HTTP Host header in the form of lowercase host, followed by an ASCII newline.
     *    3. The URL encoded HTTP absolute path component of the URI
     *       (up to but not including the query string parameters);
     *       if this is empty use a forward '/'. This parameter is followed by an ASCII newline.
     *    4. The concatenation of all query string components (names and values)
     *       as UTF-8 characters which are URL encoded as per RFC 3986
     *       (hex characters MUST be uppercase), sorted using lexicographic byte ordering.
     *       Parameter names are separated from their values by the '=' character
     *       (ASCII character 61), even if the value is empty.
     *       Pairs of parameter and values are separated by the '&' character (ASCII code 38).
     *
     */
    private function _signParameters(array $parameters, $key) {
        $signatureVersion = $parameters['SignatureVersion'];
        $algorithm = "HmacSHA1";
        $stringToSign = null;
        if (2 == $signatureVersion) {
            $algorithm = $this->_config['SignatureMethod'];
            $parameters['SignatureMethod'] = $algorithm;
            $stringToSign = $this->_calculateStringToSignV2($parameters);
        } else {
            throw new Exception("Invalid Signature Version specified");
        }
        return $this->_sign($stringToSign, $key, $algorithm);
    }

    /**
     * Calculate String to Sign for SignatureVersion 2
     * @param array $parameters request parameters
     * @return String to Sign
     */
    private function _calculateStringToSignV2(array $parameters) {
        $data = 'POST';
        $data .= "\n";
        $endpoint = parse_url ($this->_config['ServiceURL']);
        $data .= $endpoint['host'];
        $data .= "\n";
        $uri = array_key_exists('path', $endpoint) ? $endpoint['path'] : null;
        if (!isset ($uri)) {
            $uri = "/";
        }
        $uriencoded = implode("/", array_map(array($this, "_urlencode"), explode("/", $uri)));
        $data .= $uriencoded;
        $data .= "\n";
        uksort($parameters, 'strcmp');
        $data .= $this->_getParametersAsString($parameters);
        return $data;
    }

    private function _urlencode($value) {
        return str_replace('%7E', '~', rawurlencode($value));
    }


    /**
     * Computes RFC 2104-compliant HMAC signature.
     */
    private function _sign($data, $key, $algorithm)
    {
        if ($algorithm === 'HmacSHA1') {
            $hash = 'sha1';
        } else if ($algorithm === 'HmacSHA256') {
            $hash = 'sha256';
        } else {
            throw new Exception ("Non-supported signing method specified");
        }
        return base64_encode(
            hash_hmac($hash, $data, $key, true)
        );
    }


    /**
     * Formats date as ISO 8601 timestamp
     */
    private function _getFormattedTimestamp()
    {
        return gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
    }

    /**
     * Formats date as ISO 8601 timestamp
     */
    private function getFormattedTimestamp($dateTime)
    {
        return $dateTime->format(DATE_ISO8601);
    }

}
