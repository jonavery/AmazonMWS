<?php

/***********************************************************
 * UpdatePricing.php uses a flat file obtained from
 * GetCurrentListings.php to get a list of all SKUs that
 * have active listings.
 *
 * UpdatePricing.php then gets prices for the ASINs of those
 * SKUs from the prices database and updates their pricing
 * accordingly on Amazon.
 **********************************************************/

require_once(__DIR__ . '/../../MarketplaceWebServiceProducts/Functions/SetItemPrice.php');

function parseReport($file) {
    $result = array();
    $fp = fopen($file,'r');
    if (($headers = fgetcsv($fp, 0, "\t")) !== FALSE)
      if ($headers)
        while (($line = fgetcsv($fp, 0, "\t")) !== FALSE) 
          if ($line)
            if (sizeof($line)==sizeof($headers))
              $result[] = array_combine($headers,$line);
    fclose($fp);
    return $result;
}

// Open report and parse as array.
echo "Loading listing report... ";
$reportArray = parseReport('AFN-report.txt');
echo "Success! \n\n";

// Create ASIN array to be passed to SQL SELECT statement.
$asinArray = array_column($reportArray, 'asin');
$inArray = "?" . str_repeat(",?", count($asinArray) - 1);

/******
 * $reportArray key names:
['seller-sku']
['fulfillment-channel-sku']
['asin']
['condition-type']
['Warehouse-Condition-code']
['Quantity Available']

item-condition:
1 - Used - Like New
2 - Used - Very Good
3 - Used - Good
4 - Used - Acceptable
5 - Collectible - Like New
6 - Collectible - Very Good
11- New
 ******/

// Define database parameters.
$host = "localhost";
$db = "inventory";
$user = "php";
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


// Select all ASINs from price table that are in ASIN array.
$stmt = $db->prepare("
    SELECT ASIN, SalePrice
    FROM prices
    WHERE ASIN IN ($inArray)
");
$stmt->execute($asinArray);
$asinPDOS = $stmt->fetchAll(PDO::FETCH_ASSOC);

// NOTE: Does not function with tabs and newlines in values
function arrayToTab($array) {
    $tabCSV = implode("\t", array_keys($array[0])); 
    foreach($array as $row) {
        $tabCSV = implode("\n",array($tabCSV, implode("\t", $row)));
    }
    return $tabCSV;
}

print_r($asinPDOS);
$tabCSV = arrayToTab($asinPDOS);
file_put_contents("prices.txt", $tabCSV);

?>
