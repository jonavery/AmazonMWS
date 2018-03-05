<?php

/********************************************************************
 * This script takes the item XML file from CreateItemArray
 * and submits it to Amazon via the Feed API.
 *
 * This is done using the following feed types:
 * 	+ _POST_PRODUCT_DATA_
 * 	+ _POST_PRODUCT_PRICING_DATA_
 * 	+ _POST_INVENTORY_AVAILABILITY_DATA_
 *
 * For each feed, SubmitFeed is called to send the feed to Amazon.
 * Then GetFeedSubmissionList is used to check the feed's status:
 *  + FeedProcessingStatus
 * Finally, GetFeedSubmissionResult is used to confirm successful
 * processing.
 *******************************************************************/
// Load SubmitFeed file.
require_once(__DIR__ . '/SubmitFeed.php');

// Retrieve starting line from URL.
if (array_key_exists("pass", $_GET)) {
    $pass = htmlspecialchars($_GET["pass"]);
} else {
    echo "You are not authorized to run this code.\n\n";
    $pass = 'unauthorized';
}

// Check key against lock.
if ($pass !=PASSWORD) {
    echo "Entered password does not activate proper permission level to run this code.\n\n";
    exit;
} 

// Load urls to XML files.
$prdctURL = "https://script.google.com/macros/s/AKfycbxWIIZ7hy2GM77s1QP4D6qWU5ZJE-5WBT4CZPC3rbQaXHNfsZY/exec";
$priceURL = "https://script.google.com/macros/s/AKfycbxU5dLkzjzQhip47Mc34XEEKf9MoVkzsN26da5Zn85p6pGq7pM/exec";
$availURL = "https://script.google.com/macros/s/AKfycbx0by33ItcEbDrfcIm54VCoZRzHCyCI4kpWHfMODBb5-uoVgdx3/exec";

$feed = file_get_contents($prdctURL);
$request = makeRequest($feed);
$request->setFeedType('_POST_PRODUCT_DATA_');
invokeSubmitFeed($service, $request);
@fclose($feedHandle);
unset($request, $feed);

$feed = file_get_contents($priceURL);
$request = makeRequest($feed);
$request->setFeedType('_POST_PRODUCT_PRICING_DATA_');
invokeSubmitFeed($service, $request);
@fclose($feedHandle);
unset($request, $feed);

$feed = file_get_contents($availURL);
$request = makeRequest($feed);
$request->setFeedType('_POST_INVENTORY_AVAILABILITY_DATA_');
invokeSubmitFeed($service, $request);
@fclose($feedHandle);

?>
