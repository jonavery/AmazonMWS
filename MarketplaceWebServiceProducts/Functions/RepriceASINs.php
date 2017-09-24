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
foreach ($asinPDOS as $row) {
    echo $row['ASIN'] . "\n";
    $asin = $row['ASIN'];
}

exit;
// Call GetLowestOfferListingsForASIN to get price, list condition, and fulfillment channel.


// Run info through algorithm to set pricing.


// Save price in database.
$stmt = $db->prepare('UPDATE prices SET ListPrice = :price WHERE ASIN = :asin');
foreach ($asinArray as $asin => $price) {
    $stmt->execute([$price, $asin]);
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
        $item["ListCond"] = (string)$listing->Qualifiers->ItemSubcondition;
        $item["FulfilledBy"] = (string)$listing->Qualifiers->FulfillmentChannel;
        break;
    }

    // Sleep for required time to avoid throttling.
    $time_end = microtime(true);
    if ($requestCount > 19 && ($time_end - $time_start) < 200000) {
        usleep(200000 - ($time_end - $time_start));
    }
    $time_start = microtime(true);
}

foreach($itemArray as $key => &$item) {
    // Stop current loop iteration if lowest offer
    // listing condition matches condition of our item,
    // no price is set, or no list condition is set.
    if ($item["Price"] == "") {continue;}
    if ($item["ListCond"] == "" || $item["ListCond"] == "" ) {continue;}
    if ($item["ListCond"] == $item["Condition"]) {continue;}

    // Cache conditions as numbers.
    $itemCond = numCond(subStr($item["Condition"], 4));
    $listCond = numCond($item["ListCond"]);
    
    // Adjust price by condition.
    echo $item["SellerSKU"]." -> ".$item["Price"]."*(1-(.05*(".$listCond." - ".$itemCond.")))\n\n";
    $item["Price"] = $item["Price"]*(1-(.05*($listCond - $itemCond)));
}

function numCond($condition) {
    // Convert string condition into numerical condition.
    switch ($condition) {
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

?>
