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
require_once(__DIR__ . '/.config.inc.php');

// Open report and parse as array.
echo "Loading listing report... ";
$reportArray = parseReport('AFN-report.txt');
echo "Success! \n\n";

// Filter out all out-of-stock items
$reportArray = arrayFind($reportArray, 'Quantity Available', 1);
print_r($reportArray);

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

// Create and check database connection
$pdo = createPDO("inventory");
if ($pdo->connect_errno) {
    die("Connection failed: " . $pdo->connect_error);
} 
echo "Connected successfully to MySQL database. \n";


// Select all ASINs from price table that are in ASIN array.
$stmt = $pdo->prepare("
    SELECT asin, sale_price
    FROM prices
    WHERE asin IN ($inArray)
    AND sale_price > 0
");
$stmt->execute($asinArray);
$asinPDOS = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Convert array into flat, tab-delimited text file.
$handle = fopen("prices.txt", 'w+');
$tabCSV = arrayToTab($asinPDOS);
fputcsv($handle, array_keys($asinPDOS[1]), "\t");
foreach ($asinPDOS as $row) {
    fputcsv($handle, $row, "\t");
}
fclose($handle);

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

function arrayToTab($array) {
    $tabCSV = implode("\t", array_keys($array[0])); 
    foreach($array as $row) {
        $tabCSV = implode("\n",array($tabCSV, implode("\t", $row)));
    }
    return $tabCSV;
}

function arrayFind($arr, $key, $val) {
    $ret = [];
    foreach($arr as $elem) {
        if($elem[$key] == $val) {
            $ret[]= $elem;
        }
    }
    return $ret;
}

?>
