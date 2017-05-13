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

require_once('.config.inc.php');

/************************************************************************
 * Instantiate Implementation of MarketplaceWebServiceProducts
 *
 * AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY constants
 * are defined in the .config.inc.php located in the same
 * directory as this sample
 ***********************************************************************/
// More endpoints are listed in the MWS Developer Guide
// North America:
$serviceUrl = "https://mws.amazonservices.com/Products/2011-10-01";
// Europe
//$serviceUrl = "https://mws-eu.amazonservices.com/Products/2011-10-01";
// Japan
//$serviceUrl = "https://mws.amazonservices.jp/Products/2011-10-01";
// China
//$serviceUrl = "https://mws.amazonservices.com.cn/Products/2011-10-01";


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

/************************************************************************
 * Setup request parameters and uncomment invoke to try out
 * sample for Get Matching Product For Id Action
 ***********************************************************************/
 // @TODO: set request. Action can be passed as MarketplaceWebServiceProducts_Model_GetMatchingProductForId
 // object or array of parameters
 
// Load XML file.
$url = "https://script.google.com/macros/s/AKfycbxoNDu7BM4PRE1DEDVyCTd5lkMK1cGPLV0C8KujXDgc3CKNqljU/exec";

// Parse data from XML into an array.
$itemsXML = file_get_contents($url);
$items = new SimpleXMLElement($itemsXML);
$itemArray = array();
foreach ($items->item as $item) {
    if ((string)$item->Title == "") {
	continue;
    }
    $itemArray[] = array(
	"SellerSKU"=>(string)$item->SKU,
	"Title"=>(string)$item->Title,
	"UPC"=>(string)$item->ASIN,
	"Condition"=>(string)$item->Condition,
	"Comment"=>(string)$item->Comment,
	"Dimensions"=>array(
	    "Weight"=>(string)$item->Dimensions->Weight,
	    "Length"=>(string)$item->Dimensions->Length,
	    "Width"=>(string)$item->Dimensions->Width,
	    "Height"=>(string)$item->Dimensions->Height)
	);
}

// Create array to hold UPC's
$upcList = array();

// Pass item array through for loop and format UPC.
foreach($itemArray as &$item) {
    switch(strlen($item["UPC"])) {
        case 11:
            $item["UPC"] = "0".$item["UPC"];
            break;
        case 12:
            $item["UPC"] = $item["UPC"];
            break;
        case 10:
            $item["ASIN"] = $item["UPC"];
            continue 2;
        default:
            $item["ASIN"] = "Error: Improper UPC";
            continue 2;
    }
    // Add UPC to the array if the array has less than
    // 5 items and new UPC is not a duplicate value.
    
    
    
    // If either condition fails, run array through
    // GetMatchingProductId and add ASIN's to item array.
    

    // Add UPC to new empty array.
    
}
/*
// Create array of properly formatted UPC's.
$upcList = array();
foreach ($itemArray as $item) {
    switch(strlen($item["UPC"])) {
        case 11:
            $upcList[] = "0".$item["UPC"];
            break;
        case 12:
            $upcList[] = $item["UPC"];
            break;
        case 10:
            $upcList[] = "ASIN";
            break;
        default:
            $upcList[] = "error";
            break;
    }
}

// Remove duplicate UPC's from array.
$dups = array();
$upcUnique = array_unique($upcList);
foreach(array_keys($upcList) as $key) {
    if(!array_key_exists($key, $upcUnique)) {
        $dups[] = $key;
    }
}
$upcUnique = array_merge($upcUnique);
// Uncomment for testing purposes.
// print_r($upcList);
// print_r($upcUnique);
// print_r($dups);

// Create request to be sent to Amazon.
$request = new MarketplaceWebServiceProducts_Model_GetMatchingProductForIdRequest();
$request->setSellerId(MERCHANT_ID);
$request->setMarketplaceId(MARKETPLACE_ID);


// Convert UPC's into ASIN's.
for($i=0; $i<ceil(count($upcUnique)/5); $i++) {
    if ($i == 0) $offset = 0;
    else $offset = 5*$i - 1;
    $upcSlice = array_slice($upcUnique, $offset, 5);
	$request->setIdType('UPC');

    $upcObject = new MarketplaceWebServiceProducts_Model_IdListType();
    $upcObject->setId($upcSlice);
	$request->setIdList($upcObject);

    $xml = invokeGetMatchingProductForId($service, $request);
    sleep(1);

    $asins = new SimpleXMLElement($xml);    
    $j = 0;
    foreach ($asins->GetMatchingProductForIdResult as $result) {
        if (@count($result->Products)) {
            $product = $result->Products->Product->children();
            $itemArray[$offset+$j]["ASIN"] = (string)$product->Identifiers->MarketplaceASIN->ASIN;
            // $ns2 = $product->AttributeSets->children("http://mws.amazonservices.com/schema/Products/2011-10-01/default.xsd");
            // $itemArray[$offset+$j]["Title"] = (string)$ns2->Title;
            // $itemArray[$offset+$j]["ListPrice"] = (string)$ns2->ListPrice;
        }
        $j++;
    }
}

// Send all the dups back through the function one-by one.
foreach($dups as $key => $value) { 
	$request->setIdType('UPC');

    $upcObject = new MarketplaceWebServiceProducts_Model_IdListType();
    $upcObject->setId($upcList[$value]);
	$request->setIdList($upcObject);

    $xml = invokeGetMatchingProductForId($service, $request);
    usleep(200000);

    $asins = new SimpleXMLElement($xml);    
    $j = 0;
    foreach ($asins->GetMatchingProductForIdResult as $result) {
        if (@count($result->Products)) {
            $product = $result->Products->Product->children();
            $itemArray[$value]["ASIN"] = (string)$product->Identifiers->MarketplaceASIN->ASIN;
            // $ns2 = $product->AttributeSets->children("http://mws.amazonservices.com/schema/Products/2011-10-01/default.xsd");
            // $itemArray[$offset+$j]["Title"] = (string)$ns2->Title;
            // $itemArray[$offset+$j]["ListPrice"] = (string)$ns2->ListPrice;
        }
        $j++;
    }
}
 */
print_r($itemArray);

/**
  * Get Get Matching Product For Id Action Sample
  * Gets competitive pricing and related information for a product identified by
  * the MarketplaceId and ASIN.
  *
  * @param MarketplaceWebServiceProducts_Interface $service instance of MarketplaceWebServiceProducts_Interface
  * @param mixed $request MarketplaceWebServiceProducts_Model_GetMatchingProductForId or array of parameters
  */

  function invokeGetMatchingProductForId(MarketplaceWebServiceProducts_Interface $service, $request)
  {
      try {
        $response = $service->GetMatchingProductForId($request);

        $dom = new DOMDocument();
        $dom->loadXML($response->toXML());
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $response->getResponseHeaderMetadata();
        return $dom->saveXML();

     } catch (FBAInboundServiceMWS_Exception $ex) {
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
