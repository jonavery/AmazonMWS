<?php
/*******************************************************************************
 * Copyright 2009-2016 Amazon Services. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License"); 
 *
 * You may not use this file except in compliance with the License. 
 * You may obtain a copy of the License at: http://aws.amazon.com/apache2.0
 * This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR 
 * CONDITIONS OF ANY KIND, either express or implied. See the License for the 
 * specific language governing permissions and limitations under the License.
 *******************************************************************************
 * PHP Version 5
 * @category Amazon
 * @package  FBA Inbound Service MWS
 * @version  2010-10-01
 * Library Version: 2016-10-05
 * Generated: Wed Oct 05 06:15:45 PDT 2016
 */

/**
 * Get Unique Package Labels
 */

require_once(__DIR__ . '/.config.inc.php');

/************************************************************************
 * Instantiate Implementation of FBAInboundServiceMWS
 *
 * AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY constants
 * are defined in the .config.inc.php located in the same
 * directory as this sample
 ***********************************************************************/
// More endpoints are listed in the MWS Developer Guide
// North America:
$serviceUrl = "https://mws.amazonservices.com/FulfillmentInboundShipment/2010-10-01";

$config = array (
  'ServiceURL' => $serviceUrl,
  'ProxyHost' => null,
  'ProxyPort' => -1,
  'ProxyUsername' => null,
  'ProxyPassword' => null,
  'MaxErrorRetry' => 3,
);

$service = new FBAInboundServiceMWS_Client(
       AWS_ACCESS_KEY_ID,
       AWS_SECRET_ACCESS_KEY,
       APPLICATION_NAME,
       APPLICATION_VERSION,
       $config);

/**
  * Get Get Unique Package Labels Action
  * @param FBAInboundServiceMWS_Interface $service instance of FBAInboundServiceMWS_Interface
  * @param mixed $request FBAInboundServiceMWS_Model_GetUniquePackageLabels or array of parameters
  */

  function invokeGetUniquePackageLabels(FBAInboundServiceMWS_Interface $service, $request)
  {
      try {
        $response = $service->GetUniquePackageLabels($request);

        $dom = new DOMDocument();
        $dom->loadXML($response->toXML());
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $response->getResponseHeaderMetadata();
        return $dom->saveXML();

     } catch (FBAInboundServiceMWS_Exception $ex) {
        echo("Caught Exception: " . $ex->getMessage() . "\n");
        echo("Response Status Code: " . $ex->getStatusCode() . "\n");
        echo("Error Code: " . $ex->getErrorCode() . "\n");
        echo("Error Type: " . $ex->getErrorType() . "\n");
        echo("Request ID: " . $ex->getRequestId() . "\n");
        echo("XML: " . $ex->getXML() . "\n");
        echo("ResponseHeaderMetadata: " . $ex->getResponseHeaderMetadata() . "\n");
     }
 }

