<?php

/***********************************************************
 * RepriceASINs.php grabs items from the database and queries
 * Amazon for the most up-to-date pricing for each item.
 *
 * These prices are then updated in the database.
 **********************************************************/

require_once(__DIR__ . '/GetLowestOfferListingsForASIN.php');
$requestPrice = $request;

// Create and check database connection
$pdo = createPDO("inventory");
if ($pdo->connect_errno) {
    die("Connection failed: " . $pdo->connect_error);
} 
echo "Connected successfully to MySQL database. \n";

// Select all ASINs from price table that have not been updated in the last four hours.
$updated = date('Y-m-d H:i:s', strtotime('-4 hours'));
$stmt = $pdo->prepare('
    SELECT asin
    FROM prices
    WHERE last_updated < ?
    AND aer_designation = "A"
    ORDER BY last_updated DESC
    LIMIT 400 
');
$stmt->execute([$updated]);
$asinPDOS = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Call GetLowestOfferListingsForASIN to get price, list condition, and fulfillment channel.
echo "Updating prices... \n";
$asinArray = [];
foreach ($asinPDOS as $row) {
    // Cache ASINs into array.
    $asinArray[] = array(
        "ASIN" => $row['asin'],
        "Condition" => "UsedGood"
    );
}

// Generate prices.
$itemArray = parseOffers($asinArray, $request);

// Run info through algorithm to set pricing.
foreach($itemArray as $key => &$item) {
    // Default Klasrun item to Good condition.
    $itemCond = 2;
    // Convert list condition to number form.
    $listCond = numCond($item["ListCond"]);
    $price = $item["ListPrice"];
    $asin = $item["ASIN"];

    // Set price of item.
    $price = pricer($price, $listCond, $itemCond, $item["FeedbackCount"]);

    // Save price in database.
    $stmt = $pdo->prepare('
        UPDATE prices
        SET sale_price = ?
        WHERE asin = ?
    ');
    $stmt->execute([$price, $asin]);
}
echo "Database update complete.";

?>
