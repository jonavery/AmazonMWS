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
    if ($listCond == "" || $listCond == "" ) {return;}
    if ($listCond == $itemCond) {return;}

    // Set the price of the item.
    //$itemPrice = $listPrice*(1-(.08*($listCond - $itemCond)));
    //return $itemPrice;

    // Set price using Klasrun algorithm.
    $priceMatrix = array(
       [1, .86, .77, .57, .5],
       [1.16, 1, .89, .663, .58],
       [1.3, 1.12, 1, .743, .65],
       [1.75, 1.51, 1.35, 1, .875]
    );
    $itemPrice = $listPrice * $priceMatrix[$itemCond-1][$listCond-1];
    return $itemPrice;

    // Set price using multivariable algorithm.
    //$p = $listPrice;
    //$c = $listCond;
}

function numCond($condition) {
    // @param {string} condition - Acceptable, Good, VeryGood, LikeNew, New 
    // Convert string condition into integer condition.
    switch ($condition) {
        case "Acceptable":
            return 1;
        case "Good":
            return 2;
        case "Refurbished":
        case "VeryGood":
            return 3;
        case "LikeNew":
            return 4;
        case "New":
            return 5;
        default:
            return $condition;
    }
}
