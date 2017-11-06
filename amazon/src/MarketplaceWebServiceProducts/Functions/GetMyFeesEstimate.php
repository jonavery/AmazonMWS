<?php
/*******************************************************************************
 * Copyright 2009-2017 Amazon Services. All Rights Reserved.
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
 * @package  Marketplace Web Service Products
 * @version  2011-10-01
 * Library Version: 2017-03-22
 * Generated: Wed Mar 22 23:24:40 UTC 2017
 */

/**
 * Get My Fees Estimate
 */

require_once(__DIR__ . '/../../includes.php');

/************************************************************************
 * Instantiate Implementation of MarketplaceWebServiceProducts
 *
 * AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY constants
 * are defined in the ../../includes.php located in the same
 * directory as this sample
 ***********************************************************************/
// More endpoints are listed in the MWS Developer Guide
// North America:
$serviceUrl = "https://mws.amazonservices.com/Products/2011-10-01";

$config = array (
  'ServiceURL' => $serviceUrl,
  'ProxyHost' => null,
  'ProxyPort' => -1,
  'ProxyUsername' => null,
  'ProxyPassword' => null,
  'MaxErrorRetry' => 3,
);

$service = new MarketplaceWebServiceProducts_Client(
       AWS_ACCESS_KEY_ID,
       AWS_SECRET_ACCESS_KEY,
       APPLICATION_NAME,
       APPLICATION_VERSION,
       $config);

/**
  * Get My Fees Estimate Action
  * Gets competitive pricing and related information for a product identified by
  * the MarketplaceId and ASIN.
  *
  * @param MarketplaceWebServiceProducts_Interface $service instance of MarketplaceWebServiceProducts_Interface
  * @param mixed $request MarketplaceWebServiceProducts_Model_GetMyFeesEstimate or array of parameters
  */

 function invokeGetMyFeesEstimate(MarketplaceWebServiceProducts_Interface $service, $request)
 {
     try {
       $response = $service->GetMyFeesEstimate($request);

       $dom = new DOMDocument();
       $dom->loadXML($response->toXML());
       $dom->preserveWhiteSpace = false;
       $dom->formatOutput = true;
       $response->getResponseHeaderMetadata();
       return $dom->saveXML();

    } catch (MarketplaceWebServiceProducts_Exception $ex) {
       echo("Caught Exception: " . $ex->getMessage() . "\n");
       echo("Response Status Code: " . $ex->getStatusCode() . "\n");
       echo("Error Code: " . $ex->getErrorCode() . "\n");
       echo("Error Type: " . $ex->getErrorType() . "\n");
       echo("Request ID: " . $ex->getRequestId() . "\n");
       echo("XML: " . $ex->getXML() . "\n");
       echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
    }
}

