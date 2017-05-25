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
 * Then GetFeedSubmissionList is used to check the feed's status.
 *  + FeedProcessingStatus
 * Finally, GetFeedSubmissionResult is used to confirm successful
 * processing.
 *******************************************************************/

// @TODO: Create new tab in Work that includes information
// @TODO: obtained through the Products API and load that instead.
// Load XML file.
$productURL = "";
$priceURL = "";
$availURL = "";







?>
