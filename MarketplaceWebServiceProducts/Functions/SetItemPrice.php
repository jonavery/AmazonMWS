<?php

/***********************************************************
 * SetItemPrice.php uses multiple input parameters to determine
 * the optimal pricing for an item and outputs that pricing.
 *
 * @param {*} $listPrice - the price of the offer being matched
 * @param {int} $listCond - the condition of the offer being matched
 * @param {int} $itemCond - the condition of the item being priced
 * **********************************************************/

function pricer($listPrice, $listCond, $itemCond) {
    // Stop function if lowest offer
    // listing condition matches condition of our item,
    // no price is set, or no list condition is set.
    if ($listPrice == "") {return;}
    if ($ListCond == "" || $ListCond == "" ) {return;}
    if ($ListCond == $Condition) {return;}

    // Set the price of the item.
    $itemPrice = $listPrice*(1-(.08*($listCond - $itemCond)));
    return $itemPrice;
}

function numCond($condition) {
    // @param {string} condition - Acceptable, Good, VeryGood, LikeNew, New 
    // Convert string condition into integer condition.
    switch ($condition) {
        case "Acceptable":
            return 1;
        case "Good":
            return 2;
        case "VeryGood":
            return 3;
        case "LikeNew":
            return 4;
        case "New":
            return 5;
        default:
            return;
    }
}
