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
require_once('GetCompetitivePricingForASIN.php');
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
	"Dimensions"=>array(
	    "Weight"=>(string)$item->Dimensions->Weight,
	    "Length"=>(string)$item->Dimensions->Length,
	    "Width"=>(string)$item->Dimensions->Width,
	    "Height"=>(string)$item->Dimensions->Height));
}


// Create request counter and an array to hold UPC's
$upcList = array();
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
            continue 2;
    }
    $requestCount++;
    $requestId->setIdType('UPC');

    $upcObject = new MarketplaceWebServiceProducts_Model_IdListType();
    $upcObject->setId($item["UPC"]);
    $requestId->setIdList($upcObject);

    $xmlId = invokeGetMatchingProductForId($service, $requestId);
    if ($requestCount > 19) {
        usleep(200000);
    }

    // Parse the XML response and add ASIN's to item array.
    $asins = new SimpleXMLElement($xmlId);    
    $result = $asins->GetMatchingProductForIdResult;
    if (@count($result->Products)) {
        $product = $result->Products->Product->children();
        $item["ASIN"] = (string)$product->Identifiers->MarketplaceASIN->ASIN;
    }

    // Stop current loop iteration if no ASIN set.
    if (!array_key_exists("ASIN", $item)) {continue;}

    // Use ASIN to query and cache competitive pricing.
    $asinObject = new MarketplaceWebServiceProducts_Model_ASINListType();
    $asinObject->setASIN($item["ASIN"]);
    $requestPrice->setASINList($asinObject);
    $xmlPrice = invokeGetCompetitivePricingForASIN($service, $requestPrice);
    $price = new SimpleXMLElement($xmlPrice);
    $compPrices = $price->GetCompetitivePricingForASINResult->Product->CompetitivePricing->CompetitivePrices;
    foreach($compPrices->CompetitivePrice as $compPrice) {
        foreach($compPrice->attributes() as $value) {
            if($value = "Used") {
                $item["Price"] = (string)$compPrice->Price->ListingPrice->Amount;
            }
        }
    }
}

print_r($itemArray);

?>
