<form method="post" action="">
<input type="text" name="value">
<input type="submit">
</form>

<?php
include_once(__DIR__ . '/GetLowestOfferListingsForASIN.php');

$asin = array(
    array(
        "ASIN" => $_POST['value'],
        "Condition" => "UsedAcceptable"
    )
);
parseOffers($asin, $request);

