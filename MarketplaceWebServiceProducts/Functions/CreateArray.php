<?php

/***************************************************************
* CreateArray.php queries the database and creates
* an array containing the items ready for listing
* based on the input small items, palleted items or electronics.
*
* The script then converts that array into an xml filee that
* can be fed to Amazon via the script "SubmitFeed"
****************************************************************/

// Match and request id for product
require_once(__DIR__ . '/GetMatchingProductForId.php');
$requestId = $request;
unset($request);

// Get the lowest price for product
require_once(__DIR__ . '/GetLowestOfferListingsForASIN.php');
$requestPrice = $request;
unset($request);

// Get the list
require_once(__DIR__ . '/ListMatchingProducts.php');
$requestMatch = $request;
unset ($request);

// List xml based on selection input (e, sp, o)

$type = htmlspecialchars($_GET["type"]);
	Switch($type) {
	case 'e':
		$url = "https://script.google.com/macros/s/AKfycbx8EuLlIqz8EPVXYV0kHDfgxLxUsoNL_4cZhtHlvcC0bl7IQG0/exec";
		break;
	case 'sp':
		$url = "https://script.google.com/macros/s/AKfycby1w0X4NSMGISn-IMeqCLclr7sXueZlhzTp84GPktgaQubNtI8/exec";
		break;
	case 'o':
		$url = "https://script.google.com/macros/s/AKfycby1w0X4NSMGISn-IMeqCLclr7sXueZlhzTp84GPktgaQubNtI8/exec";
		break;
	default:
		echo "No url entered. Please make sure you have entered the url correctly";
		exit;
}

// Parse data from XML into an array
$itemsXML = file_get_contents($url);
$items = new SimpleXMLElement($itemsXML);
$itemArray = array();
foreach ($items->item as $item) {
    if ((string)$item->Title == "") {
	continue;
    }
 	$itemArray[] = array(
		"SellerSKU"=>(string)$item->SKU,
		"Title"=>(string)$item->Title,
		"UPC"=>(string)$item->ASIN,
		"Condition"=>(string)$item->Condition,
		"Comment"=>(string)$item->Comment,
		"Defect"=>(string)$item->Defect,
		"Dimensions"=>array(
			"Weight"=>(string)$item->Dimensions->Weight,
			"Length"=>(string)$item->Dimensions->Length,
			"Width"=>(string)$item->Dimensions->Width,
			"Height"=>(string)$item->Dimensions->Height));
}
// Create an array to hold UPC's.
$upcList = array();

// Cache throttling parameter
$reqquestcount = 0;

// Pass item array through for loop and format UPC
echo "Creating array... \n";
foreach($itemArray as $key => &$item) {
    switch(strlen($item["UPC"])) {
        case 11:
            $item["UPC"] = "0".$item["UPC"];
            break;
	case 12:
            break;
        case 10:
            $item["ASIN"] = $item["UPC"];
            continue 2;
        default:
            $requestMatch->setQuery($item["Title"]);
            $xmlMatch = invokeListMatchingProducts($service, $requestMatch);
            $match = new SimpleXMLElement($xmlMatch);
            $item["ASIN"] = (string)$match->ListMatchingProductsResult->Products->Product->Identifiers->MarketplaceASIN->ASIN;
            // Sleep for required time to avoid throttling.
    	    $match_end = microtime(true);
    	    if ($requestCount > 19 && ($match_end - $match_start) < 5000000) {
       	        usleep(5000000 - ($match_end - $match_start));
    	    }
    	    $match_start = microtime(true);
            continue 2;
    }
    $requestCount++;

// Set the ID and ID type to be converted to an ASIN.
	$requestID->setIdType('UPC');
	$upcObject = new MarketplaceWebserviceProducts_Model_IdListType();
	$upcObject->SetId($item["UPC"]);
	$requestId->setIdList($upcobject);

	$xmlid = invokeGetMatchingProductForId($service, $requestId);

// Parse the XML response and add ASINS to item array.
	$asins = new SimpleXMLElement($xmlID);
	$result = $asins->GetMatchingProductforIdResult;
	if (@count($result->Products)) {
		$product = $result->Products->Product->children();
		$item["ASIN"] = (string)$product->Identifiers->MarketplaceASIN->ASIN;
	}

// Sleep for required time to avoid throttling.
	$time_end = microtime(true);
	if ($requestCount > 19 && ($time_end - $time_start) < 200000) {
		usleep(200000 - ($time_end - $time_start));
	}
	$time_start = microtime(true);
}
	echo "Generating prices... \n";
	$itemArray = parseOffers($itemArray, $requestPrice);
	print_r($itemArray);

		$itemJSON = json_encode($itemArray);
		file_put_contents("MWS.json", $itemJSON);

// Based on the selection input (e, sp, o) show the following message
 $case = htmlspecialchars($_GET["type"]);
	Switch($type) {
	case 'e':
		echo "Success! Electronics MWS.json has been created. Run 'Populate MWS Tab' and 'Post Listings' to list products on Amazon.";
		break;
	case 'sp':
		echo "Success! Small Parcel MWS.json has been created. Run 'Populate MWS Tab' and 'Post Listings' to list products on Amazon.";
		break;
	case 'o':
		echo "Success! Oversize MWS.json has been created. Run 'Populate MWS Tab' and 'Post Listings' to list products on Amazon.";
		break;
	default:
		echo "Nothing has been executed, Please check for errors and restart the process.";
		break;
}
?>
