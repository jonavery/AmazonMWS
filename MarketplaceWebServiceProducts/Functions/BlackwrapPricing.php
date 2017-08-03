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
// require_once('GetMyFeesEstimate.php');
// $requestFees = $request;
// unset($request);

// Load XML file.
$url = "https://script.google.com/macros/s/AKfycbwFxIlDhKpBIkJywpzz9iSbkWeO50EXLS5Oj7xS7IYzCoK-jxND/exec";

// Parse data from XML into an array.
$itemsXML = file_get_contents($url);
$items = new SimpleXMLElement($itemsXML);
$itemArray = array();
foreach ($items->item as $key => $item) {
    switch (strlen((string)$item->UPC)) {
        case 11:
            $upc = "0".(string)$item->UPC;
            break;
        case 12:
            $upc = (string)$item->UPC;
            break;
        default:
            $upc = "";
            break;
    }
    $itemArray[] = array(
        "Title"=>(string)$item->Title,
        "UPC"=>$upc,
        "ASIN"=>(string)$item->ASIN
    );
}

// Set throttling parameters to zero.
$requestCount = 0;
$priceCount = 0;

// Pass item array to Amazon and cache ASIN.
foreach($itemArray as $key => &$item) {
    // Stop current loop iteration if no ASIN set.
    if ($item["ASIN"] == "") {continue;}
    $requestCount++;

    // Set the ID and ID type to be converted to an ASIN.
    $requestId->setIdType('ASIN');
    $asinObject = new MarketplaceWebServiceProducts_Model_IdListType();
    $asinObject->setId($item["ASIN"]);
    $requestId->setIdList($asinObject);

    $xmlId = invokeGetMatchingProductForId($service, $requestId);

    // Parse the XML response
    $asins = new SimpleXMLElement($xmlId);    
    $ns = $asins->GetNamespaces(true);
    
    // Add Rank and Weight to item array
    $result = $asins->GetMatchingProductForIdResult;
    if (@count($result->Products)) {
        $product = $result->Products->Product->children();
        $item["Rank"] = (string)$product->SalesRankings->SalesRank->Rank;
        $ns2 = $product->AttributeSets->children($ns["ns2"]);
        $item["Weight"] = (string)$ns2->ItemAttributes->ItemDimensions->Weight;
    }
    
    // Sleep for required time to avoid throttling.
    $match_end = microtime(true);
    if ($requestCount > 19 && ($match_end - $match_start) < 200000) {
        usleep(200000 - ($match_end - $match_start));
    }
    $match_start = microtime(true);
    
    // Setup request to be passed to Amazon and increment counter.
    $priceObject = new MarketplaceWebServiceProducts_Model_ASINListType();
    $priceObject->setASIN($item["ASIN"]);
    $requestPrice->setASINList($priceObject);
    $priceCount++;

    // Query Amazon and store returned information.
    $xmlPrice = invokeGetLowestOfferListingsForASIN($service, $requestPrice);
    $price = new SimpleXMLElement($xmlPrice);
    $listings = $price->GetLowestOfferListingsForASINResult->Product->LowestOfferListings;
    foreach($listings->LowestOfferListing as $listing) {
        $item["Price"] = (string)$listing->Price->LandedPrice->Amount;
        break;
    }

    // Sleep for required time to avoid throttling.
    $price_end = microtime(true);
    if ($priceCount > 19 && ($price_end - $price_start) < 200000) {
        usleep(200000 - ($price_end - $price_start));
    }
    $price_start = microtime(true);
}

$itemJSON = json_encode($itemArray);
file_put_contents("blackwrap.json", $itemJSON);

echo "Success! blackwrap.json has been created. Run 'Import Price Estimates' to import blackwrap item prices.";
?>
