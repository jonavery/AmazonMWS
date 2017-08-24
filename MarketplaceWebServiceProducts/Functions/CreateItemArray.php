<?php

/***********************************************************
 * CreateItemArray.php queries the database and creates an
 * array containing all the items ready for listing.
 *
 * The script then converts that array into an XML file that
 * can be fed to Amazon via SubmitFeed calls.
 **********************************************************/

require_once('GetMatchingProductForId.php');
$requestId = $request;
unset($request);
require_once('GetLowestOfferListingsForASIN.php');
$requestPrice = $request;
unset($request);
require_once('ListMatchingProducts.php');
$requestMatch = $request;
unset($request);

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
    "Defect"=>(string)$item->Defect,
	"Dimensions"=>array(
	    "Weight"=>(string)$item->Dimensions->Weight,
	    "Length"=>(string)$item->Dimensions->Length,
	    "Width"=>(string)$item->Dimensions->Width,
	    "Height"=>(string)$item->Dimensions->Height));
}


// Create an array to hold UPC's.
$upcList = array();

// Cache throttling parameter.
$requestCount = 0;

// Pass item array through for loop and format UPC.
foreach($itemArray as $key => &$item) {
    switch(strlen($item["UPC"])) {
        case 11:
            $item["UPC"] = "0".$item["UPC"];
            break;
        case 12:
            break;
        case 10:
            $item["ASIN"] = $item["UPC"];
            continue 2;
        default:
            $requestMatch->setQuery($item["Title"]);
            $xmlMatch = invokeListMatchingProducts($service, $requestMatch);
            $match = new SimpleXMLElement($xmlMatch);
            $item["ASIN"] = (string)$match->ListMatchingProductsResult->Products->Product->Identifiers->MarketplaceASIN->ASIN;
            // Sleep for required time to avoid throttling.
    	    $match_end = microtime(true);
    	    if ($requestCount > 19 && ($match_end - $match_start) < 5000000) {
       	        usleep(5000000 - ($match_end - $match_start));
    	    }
    	    $match_start = microtime(true);
            continue 2;
    }
    $requestCount++;

    // Set the ID and ID type to be converted to an ASIN.
    $requestId->setIdType('UPC');
    $upcObject = new MarketplaceWebServiceProducts_Model_IdListType();
    $upcObject->setId($item["UPC"]);
    $requestId->setIdList($upcObject);

    $xmlId = invokeGetMatchingProductForId($service, $requestId);

    // Parse the XML response and add ASINs to item array.
    $asins = new SimpleXMLElement($xmlId);    
    $result = $asins->GetMatchingProductForIdResult;
    if (@count($result->Products)) {
        $product = $result->Products->Product->children();
        $item["ASIN"] = (string)$product->Identifiers->MarketplaceASIN->ASIN;
    }
    
    // Sleep for required time to avoid throttling.
    $time_end = microtime(true);
    if ($requestCount > 19 && ($time_end - $time_start) < 200000) {
        usleep(200000 - ($time_end - $time_start));
    }
    $time_start = microtime(true);
}

// Reset throttling parameter
$requestCount = 0;

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
        $item["Price"] = (string)$listing->Price->LandedPrice->Amount;
        $item["ListCond"] = (string)$listing->Qualifiers->ItemSubCondition;
        $item["FulfilledBy"] = (string)$listing->Qualifiers->FulfullmentChannel;
        break;
    }

    // Sleep for required time to avoid throttling.
    $time_end = microtime(true);
    if ($requestCount > 19 && ($time_end - $time_start) < 200000) {
        usleep(200000 - ($time_end - $time_start));
    }
    $time_start = microtime(true);
}

function numCond(condition) {
    // Convert string condition into numerical condition.
    switch (condition) {
        case "Acceptable":
            return 1;
        case "Good":
            return 2;
        case "VeryGood":
            return 3;
        case "LikeNew":
            return 4;
        case "New":
            return 5;
        default:
            return;
    }
}

foreach($itemArray as $key => &$item) {
    // Stop current loop iteration if lowest offer
    // listing condition matches condition of our item,
    // no price is set, or no list condition is set.
    if ($item["Price"] == "") {continue;}
    if ($item["ListCond"] == "") {continue;}
    if ($item["ListCond"] == $item["Condition"]) {continue;}

    // Cache conditions as numbers.
    $itemCond = numCond(subStr($item["Condition"], 4));
    $listCond = numCond($item["ListCond"]);
    
    // Adjust price by condition.
    $item["Price"] = $item["Price"]*1-(.05*($listCond - $itemCond));
}

$itemJSON = json_encode($itemArray);
file_put_contents("MWS.json", $itemJSON);

echo "Success! MWS.json has been created. Run 'Populate MWS Tab' and 'Post Listings' to list products on Amazon.";
?>
