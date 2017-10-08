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
echo "Connected successfully to MySQL database. \n";

echo "Getting ASINs from Google Sheets database... ";
// Call the Google DB and convert JSON to an array.
$url = "https://script.google.com/macros/s/AKfycbwKZalsFAweoZtHoFEzmz-W505BN4P7sQ6zDP4HSI4AXK8Tsdw/exec";
$googleJSON = file_get_contents($url);
$googleArray = array_slice(json_decode($googleJSON, true), 1);
echo "ASINs retrieved successfully. \n\n";

// Create ASIN array to be passed to SQL SELECT statement.
$asinArray = array_column($googleArray, 1);
$inArray = "?" . str_repeat(",?", count($asinArray) - 1);

// Select all ASINs from price table that are in ASIN array.
$stmt = $db->prepare("
    SELECT ASIN
    FROM prices
    WHERE ASIN IN ($inArray)
");
$stmt->execute($asinArray);
$asinPDOS = $stmt->fetchAll(PDO::FETCH_COLUMN,0);

// Compute difference between Google DB and MySQL.
$diff = array_diff($asinArray, $asinPDOS);

// Prepare new ASINs to be inserted into MySQL.
$valueArray = array_intersect_key($googleArray, $diff);

echo "Comparing ASINs in MySQL to those in GoogleDB... \n";
// Insert all ASINs not in prices table.
$stmt = $db->prepare("
    INSERT INTO prices (Title, ASIN, AERdesignation, SalePrice, FeeTotal, NetProfit, SaleRank)
    VALUES (?,?,?,?,?,?,?)
");
foreach($valueArray as $row) {
    $stmt->execute($row);
}

echo "Database update complete.";

?>
