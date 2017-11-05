<?php

/***********************************************************
 * RepriceASINs.php grabs items from the database and queries
 * Amazon for the most up-to-date pricing for each item.
 *
 * These prices are then updated in the database.
 **********************************************************/

require_once(__DIR__ . '/GetLowestOfferListingsForASIN.php');
$requestPrice = $request;
require_once(__DIR__ . '/../../includes.php');

// Create and check database connection
$pdo = createPDO("inventory");
if ($pdo->connect_errno) {
    die("Connection failed: " . $pdo->connect_error);
} 
echo "Connected successfully to MySQL database. \n";

// Select all ASINs from price table that have not been updated in the last hour.
$updated = date('Y-m-d H:i:s', strtotime('-1 hour'));
$stmt = $pdo->prepare('
    SELECT asin
    FROM prices
    WHERE last_updated < ?
    ORDER BY last_updated ASC
    LIMIT 250
');
$stmt->execute([$updated]);
$asinPDOS = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Call GetLowestOfferListingsForASIN to get price, list condition, and fulfillment channel.
echo "Updating prices... \n";
$itemArray = [];
foreach ($asinPDOS as $row) {
    // Cache ASIN.
    $asin = $row['asin'];

    // Reset throttling parameter.
    $requestCount = 0;

    // Setup request to be passed to Amazon and increment counter.
    $asinObject = new MarketplaceWebServiceProducts_Model_ASINListType();
    $asinObject->setASIN($asin);
    $requestPrice->setASINList($asinObject);
    $requestCount++;

    // Query Amazon and store returned information.
    $xmlPrice = invokeGetLowestOfferListingsForASIN($service, $requestPrice);
    $price = new SimpleXMLElement($xmlPrice);
    $listings = $price->GetLowestOfferListingsForASINResult->Product->LowestOfferListings;
    foreach($listings->LowestOfferListing as $listing) {
        $itemArray[] = array(
            "ASIN" => $asin,
            "Price" => (string)$listing->Price->LandedPrice->Amount,
            "ListCond" => (string)$listing->Qualifiers->ItemSubcondition,
            "FulfilledBy" => (string)$listing->Qualifiers->FulfillmentChannel,
            "FeedbackCount" => (int)$listing->SellerFeedbackCount
        );
        echo "ASIN: $asin \t PRICE: " . (string)$listing->Price->LandedPrice->Amount . "\n";
        break;
    }

    // Sleep for required time to avoid throttling.
    $time_end = microtime(true);
    if ($requestCount > 19 && ($time_end - $time_start) < 200000) {
        usleep(200000 - ($time_end - $time_start));
    }
    $time_start = microtime(true);
}

// Run info through algorithm to set pricing.
foreach($itemArray as $key => &$item) {
    // Default Klasrun item to Good condition.
    $itemCond = 2;
    // Convert list condition to number form.
    $listCond = numCond($item["ListCond"]);

    // Set price of item.
    $item["Price"] = pricer($item["Price"], $listCond, $itemCond, $item["FeedbackCount"]);

    // Save price in database.
    $stmt = $pdo->prepare('UPDATE prices SET sale_price = :price WHERE asin = :asin');
    $stmt->execute([$item["Price"], $item["ASIN"]]);
}
echo "Database update complete.";

?>
