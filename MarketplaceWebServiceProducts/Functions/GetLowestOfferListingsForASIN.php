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
 * Get Lowest Offer Listings For ASIN
 */

// Increase max runtime to 5 minutes - the max time allowed by Apache
ini_set('max_execution_time', 300);

require_once(__DIR__ . '/../../includes.php');
require_once(__DIR__ . '/SetItemPrice.php');

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


/***********************************************************************
 * Get Lowest Offer Listings For ASIN Action
 ***********************************************************************/
// Create request.
$request = new MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForASINRequest();
$request->setSellerId(MERCHANT_ID);
$request->setMarketplaceId(MARKETPLACE_ID);

/***********************************************************
 * parseOffers takes an item array, grabs prices for the items
 * in the array, and returns the array with the new information.
 *
 * @param {Array} $itemArray - assoc. array of items and their ASINs
 * @param {mixed} $requestPrice MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForASIN or array of parameters
 ************************************************************/

function parseOffers($itemArray, $requestPrice) {
    $requestCount = 0;
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

    foreach($itemArray as $key => &$item) {
        // Stop current loop iteration if no ASIN set.
        if (!array_key_exists("ASIN", $item)) {continue;}

        // Setup request to be passed to Amazon and increment counter.
        $asinObject = new MarketplaceWebServiceProducts_Model_ASINListType();
        $asinObject->setASIN($item["ASIN"]);
        $requestPrice->setASINList($asinObject);
        $requestCount++;

        // Query Amazon and store returned information.
        $xmlPrice = invokeGetLowestOfferListingsForASIN($service, $requestPrice);
        $price = new SimpleXMLElement($xmlPrice);
        $listings = $price->GetLowestOfferListingsForASINResult->Product->LowestOfferListings;
        foreach($listings->LowestOfferListing as $listing) {
            $item["FeedbackCount"] = (int)$listing->SellerFeedbackCount;
            if ($item["FeedbackCount"] < 3000000) {continue;}
            $item["ListPrice"] = (float)$listing->Price->LandedPrice->Amount;
            $item["ListCond"] = (string)$listing->Qualifiers->ItemSubcondition;
            $item["FulfilledBy"] = (string)$listing->Qualifiers->FulfillmentChannel;
            break;
        }

        // Sleep for required time to avoid throttling.
        $time_end = microtime(true);
        if ($requestCount > 19 && ($time_end - $time_start) < 200000) {
            usleep(200000 - ($time_end - $time_start));
        }
        $time_start = microtime(true);

        // Set all parameters to be sent to pricer.
        if (array_key_exists("Condition", $item)) {
            echo "\n\nItemCond: " . $itemCond = numCond(subStr($item["Condition"], 4));
        } else {echo "\n\nItemCond: " . $itemCond = "";}
        if (array_key_exists("ListCond", $item)) {
            echo "\tListCond: " . $listCond = numCond($item["ListCond"]);
        } else {echo "\tListCond: " . $listCond = "";}
        if (array_key_exists("ASIN", $item)) {
            echo "\tASIN: " . $item["ASIN"];
        } else {continue;}
        if (array_key_exists("ListPrice", $item)) {
            echo "\tPriceIn: $" . $item["ListPrice"];
        } else {echo "\tPriceIn: $" . $item["ListPrice"] = "";}
        if (array_key_exists("FeedbackCount", $item)) {
            echo "\tFeedbackCount: " . $item["FeedbackCount"];
        } else {echo "\tFeedbackCount: " . $item["FeedbackCount"] = 0;}

        // Set price of item.
        echo "\tPriceOut: $" . $item["ItemPrice"] = pricer($item["ListPrice"], $listCond, $itemCond, $item["FeedbackCount"]);
    }
    return $itemArray;
}

/**
  * Get Lowest Offer Listings For ASIN Action
  * Gets competitive pricing and related information for a product identified by
  * the MarketplaceId and ASIN.
  *
  * @param MarketplaceWebServiceProducts_Interface $service instance of MarketplaceWebServiceProducts_Interface
  * @param mixed $request MarketplaceWebServiceProducts_Model_GetLowestOfferListingsForASIN or array of parameters
  */

  function invokeGetLowestOfferListingsForASIN(MarketplaceWebServiceProducts_Interface $service, $request)
  {
      try {
        $response = $service->GetLowestOfferListingsForASIN($request);

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

