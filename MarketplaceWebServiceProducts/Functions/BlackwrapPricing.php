<?php

/***********************************************************
 * BlackwrapPricing.php queries the database and creates an
 * array containing prices for items in the order.
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
$url = "https://script.google.com/macros/s/AKfycbwFxIlDhKpBIkJywpzz9iSbkWeO50EXLS5Oj7xS7IYzCoK-jxND/exec";

// Set throttling parameter to zero.
$requestCount = 0;

// Parse data from XML into an array.
$itemsXML = file_get_contents($url);
$items = new SimpleXMLElement($itemsXML);
$itemArray = array();
foreach ($items->item as $key => $item) {
    switch (strlen((string)$item->UPC)) {
        case 11:
            $upc = "0".(string)$item->UPC;
            $asin = "";
            break;
        case 12:
            $upc = (string)$item->UPC;
            $asin = "";
            break;
        default:
            $upc = "";
            if (substr((string)$item->Title, 1, 6) == "ssorted") {
                $upc = "";
                $asin = "";
                break;
            }
            if ((string)$item->ASIN == "") {
                // Sleep for required time to avoid throttling.
                $requestCount++; 
                $time_end = microtime(true);
                if ($requestCount > 19 && ($time_end - $time_start) < 5000000) {
                    usleep(5000000 - ($time_end - $time_start));
                }
                $time_start = microtime(true);

                $requestMatch->setQuery((string)$item->Title);
                $xmlMatch = invokeListMatchingProducts($service, $requestMatch);
                $match = new SimpleXMLElement($xmlMatch);
                $asin = (string)$match->ListMatchingProductsResult->Products->Product->Identifiers->MarketplaceASIN->ASIN;

                $upc = "";
                $asin = "";
                break;
            }
            $asin = (string)$item->ASIN;
            break;
    }
    $itemArray[] = array(
        "Title"=>(string)$item->Title,
        "UPC"=>$upc,
        "ASIN"=>$asin
    );
}

print_r($itemArray);

// Set throttling parameter to zero.
$requestCount = 0;

// Pass item array to Amazon and cache ASIN.
foreach($itemArray as $key => &$item) {
    if ($item["UPC"] == "") {continue;}
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
    if ($requestCount > 19 && ($time_end - $time_start) > 200000) {
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
        break;
    }

    // Sleep for required time to avoid throttling.
    $time_end = microtime(true);
    if ($requestCount > 19 && ($time_end - $time_start) > 200000) {
        usleep(200000 - ($time_end - $time_start));
    }
    $time_start = microtime(true);
}

echo $itemJSON = json_encode($itemArray);
file_put_contents("blackwrap.json", $itemJSON);

?>
