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
$reportArray = parseReport('test-report.txt');
print_r(array_slice($reportArray, -20));
/******
 * $reportArray key names:
['item-name']
['seller-sku']
['price']
['quantity']
['open-date']
['item-note']
['item-condition']
['product-id']
['pending-quantity']
['fulfillment-channel']

item-condition:
1 - Used - Like New
2 - Used - Very Good
3 - Used - Good
4 - Used - Acceptable
5 - Collectible - Like New
6 - Collectible - Very Good
11- New
 ******/







?>
