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
require_once(__DIR__ . '/SubmitFeed.php');


// Open report and parse as array.
echo "Loading listing report... ";
$reportArray = parseReport('AFN-report.txt');
echo "Success! \n\n";

// Filter out all out-of-stock items
$reportArray = arrayFind($reportArray, 'Quantity Available', 1);

// Create ASIN array to be passed to SQL SELECT statement.
$asinArray = array_column($reportArray, 'asin');
$inArray = "?" . str_repeat(",?", count($asinArray) - 1);

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
$arrayPDOS = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Filter report array to only contain items to be updated.
$asinPDOS = array_column($arrayPDOS, 'asin');
$inter = array_intersect($asinArray, $asinPDOS);
$updatePrep = array_intersect_key($reportArray, $inter);

// Load list of SKU's to be skipped.
echo "Loading list of SKU's to be skipped... ";
$skipURL = "https://script.google.com/macros/s/AKfycbxDydTVlIpT5NEitTxMekuuuMX0eJABrcML3PigN8R4lF-Wm02e/exec";
$skipJSON = file_get_contents($skipURL);
$skipArrayMulti = array_slice(json_decode($skipJSON, true), 1);
$skipArray = array_merge(...$skipArrayMulti);
echo "Success! \n\n";
print_r($skipArray);

// Rename/set fields, skipping pre-designated SKU's when appropriate.
echo "Creating flat file...";
$updateFinal = array();
foreach ($updatePrep as $key => &$row) {
    if (in_array($row['seller-sku'], $skipArray)) {continue;}
    $asin = $row['asin'];
    $price = $arrayPDOS[array_search($asin, $asinPDOS)]['sale_price'];
    if ($price == 0) {continue;}
    $cond = substr($row['condition-type'], 4);
    $updateFinal[] = array(
        'sku' => $row['seller-sku'],
        'price' => pricer($price, 2, numCond($cond)),
        'fulfillment-channel' => "amazon"
    );
}

// Convert array into flat, tab-delimited text file.
$handle = fopen("prices.txt", 'w+');
fputcsv($handle, array_keys($updateFinal[0]), "\t");
foreach ($updateFinal as $row) {
    fputcsv($handle, $row, "\t");
}
fclose($handle);
echo "Success! \n\n";

// Submit flat file to Amazon feed.
$feed = file_get_contents("prices.txt");
$request = makeRequest($feed);
$request->setFeedType('_POST_FLAT_FILE_PRICEANDQUANTITYONLY_UPDATE_DATA_');
invokeSubmitFeed($service, $request);
@fclose($feedHandle);
echo "Feed submitted to Amazon. \n\n";

/***
 * parseReport parses a tab-delimited text file and returns it
 * as a PHP array object.
 *
 * @param $file {string} - string path/to/file.txt to be converted
 ***/
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

/***
 * arrayFind looks through a multi-dimensional array and returns all elements where
 * one field equals a certain value.
 *
 * @param $arr {array} - array object to be searched
 * @param $key {int}||{string} - integer or string index to search in each element
 * @param $val {*} - value to find
 ***/
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
