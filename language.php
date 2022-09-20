<?php
define( 'ROOT_DIR', '/var/www/html');

if(isset($_COOKIE["language"])) {
    $cookie_val = $_COOKIE["language"];

    if($cookie_val == "EN") {
        $user_lan = $cookie_val;
    } else if($cookie_val == "DE") {
        $user_lan = $cookie_val;
    } else {
        $user_lan = "DA";
    }
} else {
    $user_lan = "DA";
}

if($user_lan == "DA") {
    include ROOT_DIR . "/language/da.php";
    $html_lan = "da";
    $flag_img_lan = "denmark";
} else if($user_lan == "EN") {
    include ROOT_DIR . "/language/en.php";
    $html_lan = "en";
    $flag_img_lan = "united states";
} else if($user_lan == "DE") {
    include ROOT_DIR . "/language/de.php";
    $html_lan = "de";
    $flag_img_lan = "germany";
}

?>