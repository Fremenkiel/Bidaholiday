<?php

if(!isset($_COOKIE["bid_currency"]) || $_COOKIE["bid_currency"] != "DKK") {
    $cookieexpire = time() + 60*60*24*365;
    setcookie("bid_currency", "DKK", $cookieexpire);
}

?>