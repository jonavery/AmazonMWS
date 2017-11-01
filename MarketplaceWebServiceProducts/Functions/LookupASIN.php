<?php
/***********************************************************
 * BlackwrapPricing.php queries the database and creates an
 * array containing prices for items in the order.
 **********************************************************/
// Increase max runtime to 5 minutes - the max time allowed by Apache
ini_set('max_execution_time', 100000);
require_once(__DIR__ . '/GetMatchingProductForId.php');
$requestId = $request;
unset($request);
require_once(__DIR__ . '/ListMatchingProducts.php');
$requestMatch = $request;
unset($request);

// Retrieve starting line from URL.
if (array_key_exists("line", $_GET)) {
    $offset = htmlspecialchars($_GET["line"]);
} else {
    $offset = 0;
}

// Load XML file.
$url = "https://script.google.com/macros/s/AKfycbwFxIlDhKpBIkJywpzz9iSbkWeO50EXLS5Oj7xS7IYzCoK-jxND/exec?line=$offset";
// Parse data from XML into an array.
$itemsXML = file_get_contents($url);
$items = new SimpleXMLElement($itemsXML);
$itemArray = array();
foreach ($items->item as $key => $item) {
    switch (strlen((string)$item->UPC)) {
        case 11:
            $asin = "";
            $upc = "0".(string)$item->UPC;
            break;
        case 12:
            $asin = "";
            $upc = (string)$item->UPC;
            break;
        default:
            // Search upc-less item by title.
            $upc = "";
            if ((string)$item->ASIN == "") {
                $requestMatch->setQuery($item["Title"]);
                $xmlMatch = invokeListMatchingProducts($service, $requestMatch);
                $match = new SimpleXMLElement($xmlMatch);
                $asin = (string)$match->ListMatchingProductsResult->Products->Product->Identifiers->MarketplaceASIN->ASIN;

                // Sleep for required time to avoid throttling.
                $match_end = microtime(true);
                if ($requestCount > 19 && ($match_end - $match_start) < 5000000) {
                    usleep(5000000 - ($match_end - $match_start));
                }
                $match_start = microtime(true);
            } else {
                $asin = (string)$item->ASIN;
            }

            break;
    }
    $itemArray[] = array(
        "Title"=>(string)$item->Title,
        "UPC"=>$upc,
        "ASIN"=>$asin
    );
}
// Set throttling parameters to zero.
$requestCount = 0;
// Pass item array to Amazon and cache ASIN.
foreach($itemArray as $key => &$item) {
    // Stop current loop iteration if no ASIN set.
    if ($item["ASIN"] != "") {continue;}
    $requestCount++;
    // Set the ID and ID type to be converted to an ASIN.
    $requestId->setIdType('UPC');
    $upcObject = new MarketplaceWebServiceProducts_Model_IdListType();
    $upcObject->setId($item["UPC"]);
    $requestId->setIdList($upcObject);
    $xmlId = invokeGetMatchingProductForId($service, $requestId);
    // Parse the XML response
    $asins = new SimpleXMLElement($xmlId);    
    $result = $asins->GetMatchingProductForIdResult;
    if (@count($result->Products)) {
        $product = $result->Products->Product->children();
        $item["ASIN"] = (string)$product->Identifiers->MarketplaceASIN->ASIN;
    }

    if ($item["ASIN"] == "") {
        $requestMatch->setQuery($item["Title"]);
        $xmlMatch = invokeListMatchingProducts($service, $requestMatch);
        $match = new SimpleXMLElement($xmlMatch);
        $item["ASIN"] = (string)$match->ListMatchingProductsResult->Products->Product->Identifiers->MarketplaceASIN->ASIN;
    }
    
    // Sleep for required time to avoid throttling.
    $match_end = microtime(true);
    if ($requestCount > 19 && ($match_end - $match_start) < 200000) {
        usleep(200000 - ($match_end - $match_start));
    }
    $match_start = microtime(true);
    
}
$itemJSON = json_encode($itemArray);
file_put_contents("asinLookup.json", $itemJSON);
echo "Success! asinLookup.json has been created.";

// Cache url for importer.
$url = "https://script.google.com/macros/s/AKfycbyyHJXvpy0waYFXvHzwiqIof0BkdCy69ybOgrB35iBICxnnh6lV/exec?line=$offset";
// Send ASINs back into google sheet.
file_get_contents($url);
?>
