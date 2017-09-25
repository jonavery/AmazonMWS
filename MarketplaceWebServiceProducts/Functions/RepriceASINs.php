<?php

/***********************************************************
 * RepriceASINs.php grabs items from the database and queries
 * Amazon for the most up-to-date pricing for each item.
 *
 * These prices are then updated in the database.
 **********************************************************/

require_once('SetItemPrice.php');
require_once('GetLowestOfferListingsForASIN.php');
$requestPrice = $request;

// Define database parameters.
$host = "localhost";
$db = "klasrunc_pricingTEST";
$user = "klasrunc_test";
$pass = "P@ssw0rd";
$char = "utf8";
$dsn = "mysql:host=$host;dbname=$db;charset=$char";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Create and check database connection
$db = new PDO($dsn, $user, $pass, $opt);
if ($db->connect_errno) {
    die("Connection failed: " . $db->connect_error);
} 
echo "Connected successfully. \n";

// Select all ASINs from price table that have not been updated in the last hour.
$updated = date('Y-m-d H:i:s', strtotime('-1 hour'));
$stmt = $db->prepare('
    SELECT ASIN
    FROM prices
    WHERE LastUpdated < ?
    ORDER BY LastUpdated ASC
    LIMIT 250
');
$stmt->execute([$updated]);
$asinPDOS = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Call GetLowestOfferListingsForASIN to get price, list condition, and fulfillment channel.
echo "Updating prices... \n";
$itemArray = [];
foreach ($asinPDOS as $row) {
    // Cache ASIN.
    $asin = $row['ASIN'];

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
    $stmt = $db->prepare('UPDATE prices SET ListPrice = :price WHERE ASIN = :asin');
    $stmt->execute([$item["Price"], $item["ASIN"]]);
}
echo "Database update complete.";

?>
