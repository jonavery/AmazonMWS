<?php

/********************************************************************
 * This script takes the item XML file from CreateItemArray
 * and submits it to Amazon via the Feed API.
 *
 * This is done using the following feed types:
 * 	_POST_PRODUCT_DATA_
 * 	_POST_PRODUCT_PRICING_DATA_
 * 	_POST_INVENTORY_AVAILABILITY_DATA_
 *
 * For each feed, SubmitFeed is called to send the feed to Amazon.
 * Then GetFeedSubmissionList is used to check the feed's status.
 * Finally, GetFeedSubmissionResult is used to confirm successful
 * processing.
 *******************************************************************/

// @TODO: Create new tab in Work that includes information
// @TODO: obtained through the Products API and load that instead.
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
	    "Height"=>(string)$item->Dimensions->Height));
}







?>
