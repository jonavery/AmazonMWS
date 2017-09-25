<?php

/***********************************************************
 * RepriceASINs.php grabs items from the database and queries
 * Amazon for the most up-to-date pricing for each item.
 *
 * These prices are then updated in the database.
 **********************************************************/

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
echo "Connected successfully \n";

// Select all ASINs from price table that have not been updated in the last hour.
$updated = date('Y-m-d H:i:s', strtotime('-1 hour'));
$stmt = $db->prepare('SELECT ASIN FROM prices WHERE LastUpdated < ? LIMIT 5');
$stmt->execute([$updated]);
$asinPDOS = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Call GetLowestOfferListingsForASIN to get price, list condition, and fulfillment channel.
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
print_r($itemArray);
exit;

// Run info through algorithm to set pricing.
foreach($itemArray as $key => &$item) {
    // Convert conditions to number form.
    $itemCond = numCond(subStr($item["Condition"], 4));
    $listCond = numCond($item["ListCond"]);

    // Set price of item.
    $item["Price"] = pricer($item["Price"], $listCond, $itemCond, $item["FeedbackCount"]);
}

// Save price in database.
$stmt = $db->prepare('UPDATE prices SET ListPrice = :price WHERE ASIN = :asin');
foreach ($asinArray as $asin => $price) {
    $stmt->execute([$price, $asin]);
}


?>
