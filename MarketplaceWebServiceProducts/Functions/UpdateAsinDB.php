<?php

/***********************************************************
 * UpdateAsinDB.php checks the MySQL ASIN table and updates
 * its values against the Google Sheets AsinDB.
 *
 **********************************************************/

require_once('SetItemPrice.php');
require_once('GetLowestOfferListingsForASIN.php');
$requestPrice = $request;

// Define database parameters.
$host = "localhost";
$db = "klasrunc_inventory";
$user = "klasrunc_php";
$pass = "K1@srun";
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
echo "Updating ASINs... \n";

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
