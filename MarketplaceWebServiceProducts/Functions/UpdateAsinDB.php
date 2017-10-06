<?php

/***********************************************************
 * UpdateAsinDB.php checks the MySQL ASIN table and updates
 * its values against the Google Sheets AsinDB.
 *
 **********************************************************/

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

// Select all ASINs from price table and cache as array.
$stmt = $db->prepare('
    SELECT ASIN
    FROM prices
');
$stmt->execute();
$asinPDOS = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Call the Google DB and convert JSON to an array.
$url = "https://script.google.com/macros/s/AKfycbwKZalsFAweoZtHoFEzmz-W505BN4P7sQ6zDP4HSI4AXK8Tsdw/exec";
$asinJSON = file_get_contents($url);
$asinArray = json_decode($asinJSON, true);

echo "Comparing ASINs in MySQL to those in GoogleDB. \n";
// Check each ASIN from the Google DB to see if it's in the prices table.
foreach ($asinArray as $asinRow) {
    // If the ASIN is not in the prices table, insert it.
    // @TODO: Change query to an insert with the item to be added.
    $stmt = $db->prepare('UPDATE prices SET ListPrice = :price WHERE ASIN = :asin');
    $stmt->execute([$item["Price"], $item["ASIN"]]);
}

// Run info through algorithm to set pricing.

echo "Database update complete.";

?>
