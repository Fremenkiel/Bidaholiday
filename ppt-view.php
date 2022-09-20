<?php

include "https.php";
include_once("language.php");
include "currency.php";

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    /*header("location: index.php");
    exit;*/
    //$bidbtn = '<b class="logbid" style="text-align: center;">' . $language["ppt-view"]["button"][0] . '</b><input id="showbid" style="display: none;">';
    $bidbtn = '<input id="showbid" type="button" class="btn btn-primary logbid" value="' . $language["ppt-view"]["button"][1] . '">';
    } else {  
    $bidbtn = '<input id="showbid" type="button" class="btn btn-primary" onclick="showBidWindow()" value="' . $language["ppt-view"]["button"][1] . '">';
}

$startxt = $buynow_seller = "";

//echo date_default_timezone_get();

$id = $_SESSION["id"];
$firstname = $_SESSION["firstname"];
$lastname = $_SESSION["lastname"];
$email = $_SESSION["email"];
$ppt_id = $_GET["ppt-id"];

if(isset($_GET["auc-id"])) {
$auc_id = $_GET["auc-id"];
$clean = $_COOKIE["cleancookie".$auc_id];
} else {
    $auc_id = 0;
}
//$cur_user = $_COOKIE["bid_currency"];

include_once("config.php");
include "nordea.php";

if(!isset($_SESSION["id"])) {
    $id = 0;
}

if($clean == 1) {
    $cleanch = " checked";
} else {
    $cleanch = "";
}
$view_header_seller = "auto 10%";


//echo $ppt_id;

$ppt_data = "SELECT * FROM property WHERE id = $ppt_id";

$ppt_info = mysqli_query($link, $ppt_data);

while($row = mysqli_fetch_array($ppt_info)) {
    $ppt_userid = $row['userid'];
    $ppt_headline = $row['headline'];
    $ppt_type = $row['type'];
    $ppt_street = $row['street'];
    $ppt_number = $row['number'];
    $ppt_floor = $row['floor'];
    $ppt_postcode = $row['postcode'];
    $ppt_city = $row['city'];
    $ppt_contry = $row['contry'];
    $ppt_lat = $row['lat'];
    $ppt_lon = $row['lon'];
    $ppt_data = $row['data'];
    $ppt_room = $row['room'];
    $ppt_onebed = $row['onebed'];
    $ppt_twobed = $row['twobed'];
    $ppt_bed = $row['bed'];
    $ppt_area = $row['area'];
    $ppt_deposit = $row['deposit'];
    $ppt_animal = $row['animal'];
    $ppt_pool = $row['pool'];
    $ppt_bal = $row['bal'];
    $ppt_ter = $row['ter'];
    $ppt_fridge = $row['fridge'];
    $ppt_park = $row['park'];
    $ppt_wash = $row['wash'];
    $ppt_bace = $row['bace'];
    $ppt_dish = $row['dish'];
    $ppt_ele = $row['ele'];
    $ppt_gar = $row['gar'];
    $ppt_te = $row['te'];
    $ppt_tv = $row['tv'];
    $ppt_fit = $row['fit'];
    $ppt_smok = $row['smok'];
    $ppt_sau = $row['sau'];
    $ppt_wifi = $row['wifi'];
    $ppt_kic = $row['kic'];
    $ppt_elb = $row['elb'];
    $ppt_description = $row['description'];
    $ppt_link = $row['link'];
    $ppt_clean = $row['clean'];
    $ppt_min = $row['min'];
    $ppt_picture = $row['picture'];
    
 }

 $ratefile = file_get_contents('rating/' . $ppt_id . '.json');

 $ratefile = json_decode($ratefile, true);

 if(!empty($ratefile)) {
    $rate_count = count($ratefile);
    $ratefile = array_filter($ratefile);
    if(count($ratefile)) {
        $rate = array_sum($ratefile)/count($ratefile);
    }
    $rate = round($rate, 1);
    $rate = strval($rate);
    $startxt = '<div class="Stars" style="--rating: ' . $rate . ';"><div class="Startxt"><span>('.$rate_count.')</span></div></div>';
 }

if($ppt_userid == $id) {
    $seller = 1;
    $bidbtn = '<b style="text-align: center;">' . $language["ppt-view"]["button"][3] . '</b>';
    $buynow = "none";
    $buynow_txt = $language["ppt-view"]["textseller"][14];
    $buynow_btn = $language["ppt-view"]["buttonseller"][4];
    $buynow_seller = "seller";
    $buynow_ower = "$('#buy').css('display', 'block');";
} else {
    $seller = 0;
    $buynow = "none";
    $buynow_txt = $language["ppt-view"]["text"][14];
    $buynow_btn = $language["ppt-view"]["button"][4];
    $buynow_ower = "$('#buy').css('display', 'none');";
}

if($ppt_link != "") {
    $link_des = '<div class="ppt-view-link">
    <p class="ppt-view-link-title"><b>Link</b></p>
    <p></p>
    <div class="ppt-view-link-des">
        <div class="ppt-link-con"><i class="fas fa-link"></i><span><a href="'.$ppt_link.'" id="ppt-view-link">Website</a></span></div>
    </div>
</div>
<hr class="line">';
} else {
    $link_des = '';
}

if($ppt_clean != "" && $ppt_clean != 0) {
    $clean_price = '<div class="bid-grid-title cleanfee" style="margin-bottom: 1.5vw;">
    <input type="checkbox" id="clean" class="clean-checkbox" name="cleanch"' . $cleanch . '>
    <p class="clefeep" style="margin: 0; display: inline; padding-left: .85vw;"><b id="clefeetxt">' . $language["ppt-view"]["text"][0] . '</b><img src="graphics/ppt/info.svg" class="info-ppt"><span id="cletiptext" class="tiptext">' . $language["ppt-view"]["span"][0] . '</span></p>
    </div>
    <div class="bid-grid-price" style="text-align: right; margin-bottom: 0.7em;"><div class="cleanprice">' . number_format(round($ppt_clean * $cur_buy, 2),2,",",".") . ' ' . $cur_user . '</div></div>';
} elseif ($ppt_clean == 0) {
    $clean_price = '';
} else {
    $clean_price = '';
}

/*
if($ppt_type == "type1") {
    $ppt_type = "Sommerhus Type 1";
} elseif($ppt_type == "type2") {
    $ppt_type = "Sommerhus Type 2";
} elseif($ppt_type == "type3") {
    $ppt_type = "Sommerhus Type 3";
} elseif($ppt_type == "type4") {
    $ppt_type = "Sommerhus Type 4";
}
*/

/*
if($ppt_floor == "0") {
    $ppt_floor = "";
} else {
    $ppt_floor = " " . $ppt_floor;
}
*/

include_once("floor.php");

$ppt_floor = FormatFloor($ppt_floor);

include "country/dk/country.php";

$json = file_get_contents("$ppt_picture");

$array = json_decode($json, true);

//var_dump($array);

$total = count($array);

$imgs = array();
$dots = array();
$all_imgs = array();
$full_imgs = array();

for( $i=0 ; $i < $total ; $i++ ) {
    $arr_pic = "'" . $array[$i] . "'";
    $dot_num = $i + 1;
    $imgs[] = '<li class="splide__slide" onclick="showImg()">
            <img class="slide-img" src="' . $array[$i] . '">
        </li>';
    if($total == 1) {
        $all_imgs[] = '<div class="img-all pic" style="background:url(' . $arr_pic . ');"  onclick="currentSlide(' . $dot_num . ')"></div>';
        $dots_active = '';
    } else {
        $dots[] = '<span class="dot" onclick="currentSlide(' . $dot_num . ')"></span>';
        $all_imgs[] = '<li class="splide__slide" onclick="currentSlide(' . $dot_num . ')"><img src="' . $array[$i] . '"></li>';
        $dots_active = 'dots[slideIndex-1].className += " active";';
    }
    
    $full_imgs[] = '<li class="splide__slide"><img class="full-img-list" src="' . $array[$i] . '"></li>';

    //<img class="img-all" id="img-all' . $i . '" src="' . $array[$i] . '" onclick="currentSlide(' . $dot_num . ')">
}

$facts = array();

if($ppt_animal == 1) {
    $facts[] = '<div class="ppt-fact"><img src="graphics/ppt/animal.svg"><span> ' . $language["ppt-view"]["ch"][0] . '</span></div>';
}

if($ppt_pool == 1) {
    $facts[] = '<div class="ppt-fact"><img src="graphics/ppt/pool.svg"><span> ' . $language["ppt-view"]["ch"][1] . '</span></div>';
}

if($ppt_bal == 1) {
    $facts[] = '<div class="ppt-fact"><img src="graphics/ppt/bal.svg"><span> ' . $language["ppt-view"]["ch"][2] . '</span></div>';
}

if($ppt_ter == 1) {
    $facts[] = '<div class="ppt-fact"><img src="graphics/ppt/ter.svg"><span> ' . $language["ppt-view"]["ch"][3] . '</span></div>';
}

if($ppt_fridge == 1) {
    $facts[] = '<div class="ppt-fact"><img src="graphics/ppt/fridge.svg"><span> ' . $language["ppt-view"]["ch"][4] . '</span></div>';
}

if($ppt_park == 1) {
    $facts[] = '<div class="ppt-fact"><img src="graphics/ppt/park.svg"><span> ' . $language["ppt-view"]["ch"][5] . '</span></div>';
}

if($ppt_wash == 1) {
    $facts[] = '<div class="ppt-fact"><img src="graphics/ppt/wash.svg"><span> ' . $language["ppt-view"]["ch"][6] . '</span></div>';
}

if($ppt_bace == 1) {
    $facts[] = '<div class="ppt-fact"><img src="graphics/ppt/bace.svg"><span> ' . $language["ppt-view"]["ch"][7] . '</span></div>';
}

if($ppt_dish == 1) {
    $facts[] = '<div class="ppt-fact"><img src="graphics/ppt/dish.svg"><span> ' . $language["ppt-view"]["ch"][8] . '</span></div>';
}

if($ppt_ele == 1) {
    $facts[] = '<div class="ppt-fact"><img src="graphics/ppt/ele.svg"><span> ' . $language["ppt-view"]["ch"][9] . '</span></div>';
}

if($ppt_gar == 1) {
    $facts[] = '<div class="ppt-fact"><img src="graphics/ppt/gar.svg"><span> ' . $language["ppt-view"]["ch"][10] . '</span></div>';
}

if($ppt_te == 1) {
    $facts[] = '<div class="ppt-fact"><img src="graphics/ppt/te.svg"><span> ' . $language["ppt-view"]["ch"][11] . '</span></div>';
}

if($ppt_tv == 1) {
    $facts[] = '<div class="ppt-fact"><img src="graphics/ppt/tv.svg"><span> ' . $language["ppt-view"]["ch"][12] . '</span></div>';
}

if($ppt_fit == 1) {
    $facts[] = '<div class="ppt-fact"><img src="graphics/ppt/fit.svg"><span> ' . $language["ppt-view"]["ch"][13] . '</span></div>';
}

if($ppt_smok == 1) {
    $facts[] = '<div class="ppt-fact"><img src="graphics/ppt/smok.svg"><span> ' . $language["ppt-view"]["ch"][14] . '</span></div>';
}

if($ppt_sau == 1) {
    $facts[] = '<div class="ppt-fact"><img src="graphics/ppt/sau.svg"><span> ' . $language["ppt-view"]["ch"][15] . '</span></div>';
}

if($ppt_wifi == 1) {
    $facts[] = '<div class="ppt-fact"><img src="graphics/ppt/wifi.svg"><span> ' . $language["ppt-view"]["ch"][16] . '</span></div>';
}

if($ppt_kic == 1) {
    $facts[] = '<div class="ppt-fact"><img src="graphics/ppt/kic.svg"><span> ' . $language["ppt-view"]["ch"][17] . '</span></div>';
}

if($ppt_elb == 1) {
    $facts[] = '<div class="ppt-fact"><img src="graphics/ppt/elb.svg"><span> ' . $language["ppt-view"]["ch"][18] . '</span></div>';
}

//include "map.php";

if(isset($_GET["auc-id"])) {

$auc_data = "SELECT * FROM auction WHERE id = '$auc_id'";
    
$auc = mysqli_query($link, $auc_data);
    
if ($auc != "") {
    while($auc_array = mysqli_fetch_array($auc)) {
        date_default_timezone_set('Europe/Copenhagen');

        $date_1 = date('d-m-Y', strtotime($auc_array["date1"]));
        $date_2 = date('d-m-Y', strtotime($auc_array["date2"]));
        $date_end = date('d-m-Y', strtotime($auc_array["end"]));

        $date_1 = date_create($date_1);
        $date_2 = date_create($date_2);
        $date_end = date_create($date_end);

        $date_1_i = date('i', strtotime($auc_array["date1"]));
        $date_2_i = date('i', strtotime($auc_array["date2"]));
        $date_end_i = date('i', strtotime($auc_array["end"]));

        $date_1_h = date('H', strtotime($auc_array["date1"]));
        $date_2_h = date('H', strtotime($auc_array["date2"]));
        $date_end_h = date('H', strtotime($auc_array["end"]));

        $date_1_n = date('D', strtotime($auc_array["date1"]));
        $date_2_n = date('D', strtotime($auc_array["date2"]));
        $date_end_n = date('D', strtotime($auc_array["end"]));

        $date_1_d = date('d', strtotime($auc_array["date1"]));
        $date_2_d = date('d', strtotime($auc_array["date2"]));
        $date_end_d = date('d', strtotime($auc_array["end"]));
    
        $date_1_m_f = date('m', strtotime($auc_array["date1"]));
        $date_2_m_f = date('m', strtotime($auc_array["date2"]));
        $date_end_m = date('m', strtotime($auc_array["end"]));
    
        $date_1_y = date('Y', strtotime($auc_array["date1"]));
        $date_2_y = date('Y', strtotime($auc_array["date2"]));
        $date_end_y = date('Y', strtotime($auc_array["end"]));

        $date_diff_1 = date_diff($date_1, $date_2);
        $date_diff_1 = $date_diff_1->format('%a');

        $auc_id = $auc_array["id"];

        $auc_buy = $auc_array["buy"];

        $auc_bidsize = $auc_array["bidsize"];
        
        $ppt_value = $auc_array['value'];

        $auc_active = $auc_array['active'];
        $auc_removed = $auc_array['removed'];

        include "month.php";
        include "dayname.php";


    }
}
} else {
    $date_1 = 0;
    $date_2 = 0;
    $date_end = 0;
    $date_1 = 0;
    $date_2 = 0;
    $date_end = 0;
    $date_1_i = 0;
    $date_2_i = 0;
    $date_end_i = 0;
    $date_1_h = 0;
    $date_2_h = 0;
    $date_end_h = 0;
    $date_1_n = 0;
    $date_2_n = 0;
    $date_end_n = 0;
    $date_1_d = 0;
    $date_2_d = 0;
    $date_end_d = 0;
    $date_1_m_f = 0;
    $date_2_m_f = 0;
    $date_end_m = 0;
    $date_1_y = 0;
    $date_2_y = 0;
    $date_end_y = 0;
    $date_diff_1 = 0;
    $auc_id = 0;
    $auc_buy = 0;
    $auc_bidsize = 0;
    $ppt_value = 0;
}

if(isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == true) {
if($auc_active == 1 && $auc_removed == 0) {
if($id == $ppt_userid) {
    $ui_btn = '<a id="highlight-btn" href="checkout-highlight.php?aucid=' . $auc_id . '" class="btn">' . $language["ppt-view"]["seller-btn"][0] . '</a>' . '<a href="ppt-edit.php?ppt-id=' . $ppt_id . '" class="btn btn-primary-edit mobile-view-btn">' . $language["ppt-view"]["seller-btn"][1] . '</a>';
    $view_header_seller = "auto 18%";
} else {
    if(isset($_GET["auc-id"])) {
    $array = json_decode(file_get_contents("follow/" . $id . ".json"), true);

if(in_array($auc_id, $array)) {
    $ui_btn = '<a onclick="follow()" id="follow" class="btn btn-default" style="border: .1vw solid #aaa; display: none;">' . $language["ppt-view"]["follow"][0] . '</a>
    <a onclick="unfollow()" id="unfollow" class="btn btn-danger" style="display: block;">' . $language["ppt-view"]["follow"][1] . '</a>'; 
} else {
    $ui_btn = '<a onclick="follow()" id="follow" class="btn btn-default" style="border: .1vw solid #aaa; display: block;">' . $language["ppt-view"]["follow"][0] . '</a>
    <a onclick="unfollow()" id="unfollow" class="btn btn-danger" style="display: none;">' . $language["ppt-view"]["follow"][1] . '</a>'; 
}
}
}
} else {
    if($id != $ppt_userid) {
        $array = json_decode(file_get_contents("property/" . $ppt_id . ".json"), true);

if(in_array($id, $array)) {
    $ui_btn = '<a onclick="follow()" id="follow" class="btn btn-default" style="border: .1vw solid #aaa; display: none;">' . $language["ppt-view"]["follow"][2] . '</a>
    <a onclick="unfollow()" id="unfollow" class="btn btn-danger" style="display: block;">' . $language["ppt-view"]["follow"][1] . '</a>'; 
} else {
    $ui_btn = '<a onclick="follow()" id="follow" class="btn btn-default" style="border: .1vw solid #aaa; display: block;">' . $language["ppt-view"]["follow"][2] . '</a>
    <a onclick="unfollow()" id="unfollow" class="btn btn-danger" style="display: none;">' . $language["ppt-view"]["follow"][1] . '</a>'; 
}
    } else {
        $ui_btn = '<a style="margin-bottom: 0.4em;" href="ppt-edit.php?ppt-id=' . $ppt_id . '" class="btn btn-primary-edit mobile-view-btn">' . $language["ppt-view"]["seller-btn"][1] . '</a>'; 
    }
}

$admin = mysqli_fetch_array(mysqli_query($link, "SELECT admin FROM users WHERE id = $id"))["admin"];
    
if($admin == 1) {
    $array = json_decode(file_get_contents("follow/" . $id . ".json"), true);

/*
if(in_array($auc_id, $array)) {
    $ui_btn = '<a style="margin-bottom: 0.4em;" href="ppt-edit.php?ppt-id=' . $ppt_id . '" class="btn btn-primary-edit mobile-view-btn">' . $language["ppt-view"]["seller-btn"][1] . '</a><a onclick="follow()" id="follow" class="btn btn-default" style="border: .1vw solid #aaa; display: none;">' . $language["ppt-view"]["follow"][0] . '</a>
    <a onclick="unfollow()" id="unfollow" class="btn btn-danger" style="display: block;">' . $language["ppt-view"]["follow"][1] . '</a>'; 
} else {
    $ui_btn = '<a style="margin-bottom: 0.4em;" href="ppt-edit.php?ppt-id=' . $ppt_id . '" class="btn btn-primary-edit mobile-view-btn">' . $language["ppt-view"]["seller-btn"][1] . '</a><a onclick="follow()" id="follow" class="btn btn-default" style="border: .1vw solid #aaa; display: block;">' . $language["ppt-view"]["follow"][0] . '</a>
    <a onclick="unfollow()" id="unfollow" class="btn btn-danger" style="display: none;">' . $language["ppt-view"]["follow"][0] . '</a>'; 
}
*/
// $ui_btn = '<a style="margin-bottom: 0.4em;" href="ppt-edit.php?ppt-id=' . $ppt_id . '" class="btn btn-primary-edit mobile-view-btn">' . $language["ppt-view"]["seller-btn"][1] . '</a>'; 
}
} else {
    $ui_btn = '<a onclick="showLogin()" id="follow" class="btn btn-default" style="border: .1vw solid #aaa; display: block;">' . $language["ppt-view"]["follow"][0] . '</a>'; 

}

$all_auc = "";

$all_auc_data = "SELECT * FROM auction WHERE pptid = '$ppt_id' AND active = 1 AND removed = 0";
    
$all_auc_sql = mysqli_query($link, $all_auc_data);
    
if ($all_auc_sql != "") {
    while($all_auc_array = mysqli_fetch_array($all_auc_sql)) {
        date_default_timezone_set('Europe/Copenhagen');

        $all_date_1 = date('d-m-Y', strtotime($all_auc_array["date1"]));
        $all_date_2 = date('d-m-Y', strtotime($all_auc_array["date2"]));

        $all_date_1 = date_create($all_date_1);
        $all_date_2 = date_create($all_date_2);

        $all_date_1_i = date('i', strtotime($all_auc_array["date1"]));
        $all_date_2_i = date('i', strtotime($all_auc_array["date2"]));

        $all_date_1_h = date('H', strtotime($all_auc_array["date1"]));
        $all_date_2_h = date('H', strtotime($all_auc_array["date2"]));

        $all_date_1_n = date('D', strtotime($all_auc_array["date1"]));
        $all_date_2_n = date('D', strtotime($all_auc_array["date2"]));

        $all_date_1_d = date('d', strtotime($all_auc_array["date1"]));
        $all_date_2_d = date('d', strtotime($all_auc_array["date2"]));
    
        $all_date_1_m_f = date('m', strtotime($all_auc_array["date1"]));
        $all_date_2_m_f = date('m', strtotime($all_auc_array["date2"]));
        $all_date_end_m = date('m', strtotime($all_auc_array["end"]));
    
        $all_date_1_y = date('Y', strtotime($all_auc_array["date1"]));
        $all_date_2_y = date('Y', strtotime($all_auc_array["date2"]));

        $all_auc_bid = $all_auc_array["bid"];
        $all_auc_id = $all_auc_array["id"];

        include "month.php";
        include "dayname.php";

        if(!isset($_GET["auc-id"])) {
            $all_auc .= '<div class="all-auc-ppt" onclick="window.location.href=\'ppt-view.php?ppt-id=' . $ppt_id . '&auc-id=' . $all_all_auc_id . '\'"><div class="all-auc-div"><p>' . $all_date_1_d . ' ' . $all_date_1_m_f . ' ' . $all_date_2_y . '</p>
            <p style="margin: 0;">' . $all_date_2_d . ' ' . $all_date_2_m_f . ' ' . $all_date_2_y . '</p></div></div>';
        }

    }
}

if(!isset($_GET["auc-id"])) {
    $all_auc = '<div class="all-auc-ppt-container">' . $all_auc . '</div>';
}


if($_SERVER['HTTP_HOST'] == TEST_DOMAIN) {
    $serverFile = "test-server.php";
} else {
    $serverFile = "server.php";
}

$ppt_contry_full = contryName($ppt_contry);

/*function contryName($ppt_contry) {
    $ppt_contry = strtolower($ppt_contry);
    include "country/dk/country.php";
    return ucfirst($ppt_contry);
}*/

mysqli_close($link);

?>

<!DOCTYPE html>
<html lang="<?php echo $html_lan; ?>">
<head>
    <!--<meta name="viewport" content="width=1024">-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>Bidaholiday</title>
    <link rel="icon" href="graphics/logo.svg">
    <script type="text/javascript" src="script/jquery.min.js"></script>
    <script type="text/javascript" src="script/swipesense.js"></script>

    <!--<script type="text/javascript" src="script/client.js"></script>-->
    <script src="https://kit.fontawesome.com/4fe49ac51e.js" crossorigin="anonymous"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <?php include_once("gtag/gtag-head.php") ?>

    <script>
        function initMapLoc() {
            const lable = "<?php echo htmlspecialchars($ppt_street . ' ' . $ppt_number . $ppt_floor . $ppt_postcode . ' ' . $ppt_city)?>";
            const pointer = { lat: <?php echo $ppt_lat; ?>, lng: <?php echo $ppt_lon; ?>};
            const mapOptions = {
                zoom: 10,
                center: pointer,
            };

            const map = new google.maps.Map(document.getElementById("gmap_canvas"), mapOptions);

            const contentString =
                '<div id="content">' +
                '<p id="MapInfoWin"><b>' + lable + '</b><br>' +
                '<?php echo htmlspecialchars($ppt_contry) ?></p>'
                "</div>";

            const marker = new google.maps.Marker({
                position: pointer,
                map: map,
                title: lable,
            });
            
            const infowindow = new google.maps.InfoWindow({
                content: contentString,
            });

            google.maps.event.addListener(marker, "click", () => {
                infowindow.open(map, marker);
            });
        }
    </script>

    <script>
        /**
        * @param timestamp
        */
        var time;
        var saveTime;

        function getContent(timestamp) {
    
            var queryString = {'timestamp' : timestamp, 'ppt-id' : <?php echo $ppt_id; ?>, 'auc-id' : <?php echo $auc_id ?>, "id" : <?php echo $id ?>};

            $.ajax( {
            type: 'GET',
            url: 'server.php',
            data: queryString,
            success: function(data){

                if(data != "") {

                var obj = jQuery.parseJSON(data);
                var biduser = obj.userid;
                saveTime = obj.timestamp;

                var cleanfee = 0;

                if($("#clean").is(':checked')) {
                    var cleanprice = Math.round((<?php echo $ppt_clean * $cur_buy ?>) * 100) / 100;
                    //cleanfee = parseInt(cleanprice) * 0.12;
                    cleanfee = 0;
                }

                if(biduser === '0') {
                    var fee = (parseInt(obj.data_from_file, 10)) * 0.12;
                    var nextprice = parseInt(obj.data_from_file, 10);
                    var price = parseInt(obj.data_from_file, 10);
                    var nextbid = price;
                    $("#bidtxt").html("<?php echo $language["ppt-view"]["text"][29]; ?>");
                    $(".nextbidprice > span").html("<?php echo $language["ppt-view"]["text"][29]; ?>");
                } else {
                    var fee = (parseInt(obj.data_from_file, 10) + <?php echo $auc_bidsize ?>) * 0.12;
                    var nextprice = parseInt(obj.data_from_file, 10) + <?php echo $auc_bidsize ?>;
                    var price = parseInt(obj.data_from_file, 10);
                    var nextbid = price + <?php echo $auc_bidsize ?>;
                    $("#bidtxt").html("<?php echo $language["ppt-view"]["text"][7]; ?>");
                    $(".nextbidprice > span").html("<?php echo $language["ppt-view"]["text"][13]; ?>");
                }

                
                var active = parseInt(obj.active, 10);
                var userid = "<?php echo $id; ?>";
                var buynowseller = document.getElementById("buypriceseller");

                var depositprice = Math.round((<?php echo $ppt_deposit ?> * <?php echo $cur_buy ?>) * 100) / 100;
                var pricebidlist = Math.round((nextprice * <?php echo $cur_buy ?>) * 100) / 100;
                var pricebidlist2 = Math.round((nextprice * <?php echo $cur_buy ?>) * 100) / 100;
                var pricesfee = Math.round(((fee + cleanfee) * <?php echo $cur_buy ?>) * 100) / 100;
                var pricebid = Math.round((price * <?php echo $cur_buy ?>) * 100) / 100;
                var sellerclose = ((pricebid * 0.03) * <?php echo $cur_buy ?>);
            <?php
            if($clean_price != "") {
                echo 'var cleanbuy = Math.round(' . $ppt_clean . ' * 100) / 100;
                if($("#clean").prop("checked") == true) {
                    var pricetotatxt = Math.round(((nextprice + fee + depositprice + cleanbuy) * ' . $cur_buy . ') * 100) / 100;
                } else {
                    var pricetotatxt = Math.round(((nextprice + fee + depositprice) * ' . $cur_buy . ') * 100) / 100;
                }';
            } else {
                echo 'var pricetotatxt = Math.round(((nextprice + fee + depositprice) * ' . $cur_buy . ') * 100) / 100;';
            }
            ?>
                //var pricetotatxt = Math.round(((nextprice + fee + depositprice) * <?php echo $cur_buy ?>) * 100) / 100;
                var pricenextbid = Math.round((nextbid * <?php echo $cur_buy ?>) * 100) / 100;

                if($(".bid-window-container > h1").text() !== "<?php echo $buynow_btn ?>") {
                    $('#bidlist').html(currencyFormatDK(parseInt(pricebidlist)));
                    $('#bidlist2').html(currencyFormatDK(parseInt(pricebidlist2)));
                    $('#sfee').html(currencyFormatDK(parseInt(pricesfee)));
                    $('#bid').html(currencyFormatDK(parseInt(pricebid)));
                    $('#dfee').html(currencyFormatDK(parseInt(depositprice)));
                    $('#totatxt').html(currencyFormatDK(parseInt(pricetotatxt)));
                }
                

                if(buynowseller != null) {
                    if(biduser == 0) {
                        $("#buypriceseller").html(currencyFormatDK(0));
                        $('#totatxt').html(currencyFormatDK(0));
                    } else  {
                        $("#buypriceseller").html(currencyFormatDK(Math.ceil(sellerclose)));
                        $('#totatxt').html(currencyFormatDK(Math.ceil(sellerclose)));
                    }
                }

                $('#nextbid').html(pricenextbid);
                $("#addbid").attr("onclick", "addbid(" + (Math.round((nextbid * <?php echo $cur_buy ?>) * 100) / 100) + ")");
                $("#addoverbid").attr("onclick", "addoverbid(" + (Math.round((nextbid * <?php echo $cur_buy ?>) * 100) / 100) + ")");
                $("#bidinputnumber").attr("min", (Math.round((nextbid * <?php echo $cur_buy ?>) * 100) / 100));
                //$("#buy").attr("onclick", "buy(" + "" + ")");

                if(nextbid + <?php echo $auc_bidsize ?> >= <?php echo $auc_buy; ?>) {
                    <?php echo $buynow_ower ?>
                } else {
                    $("#buy").css("display", "block");
                }

                if(active == 0 || active == 2) {
                    if(obj.buynow == 1) {
                        $("#boughttext").text("<?php echo $language["ppt-view"]["text"][1]; ?>:");
                        $("#boughtid").text("<?php echo $language["ppt-view"]["text"][2]; ?> #" + obj.userid);
                    } else if(obj.remove == 1) {
                        $("#boughttext").text("<?php echo $language["ppt-view"]["text"]["close"]; ?>:");
                        $("#boughtid").text("<?php echo $language["ppt-view"]["text"][2]; ?> #" + obj.userid);
                    } else {
                        $("#boughttext").text("<?php echo $language["ppt-view"]["text"][3]; ?>:");
                        $("#boughtid").text("<?php echo $language["ppt-view"]["text"][2]; ?> #" + obj.userid);
                    }
                    $('#buy').css("display", "none");
                    $('#bidview').css("display", "none");
                    $('#autobid').css("display", "none");
                    $('#bidinfo').css("display", "none");
                    $("#bought").css("display", "block");
                }

                if(obj.history[0]["id"] != null) {
                    if(obj.history[0]["id"] === userid) {
                        $("#bidid1").css("font-weight", "bold");
                        $("#biddate1").css("font-weight", "bold");
                        $("#bidtime1").css("font-weight", "bold");
                        $("#bidbid1").css("font-weight", "bold");
                    } else {
                        $("#bidid1").css("font-weight", "normal");
                        $("#biddate1").css("font-weight", "normal");
                        $("#bidtime1").css("font-weight", "normal");
                        $("#bidbid1").css("font-weight", "normal");
                    }
                 $("#bidid1").html(obj.history[0]["id"]);
                 $("#biddate1").html(obj.history[0]["date"]);
                 $("#bidtime1").html(obj.history[0]["time"]);
                 var pricebidbid1 = Math.round((parseInt(obj.history[0]["bid"], 10) * <?php echo $cur_buy ?>) * 100) / 100;
                 $("#bidbid1").html(currencyFormatDK(pricebidbid1) + " <?php echo $cur_user ?>");
                }
                 if(obj.history[1]["id"] != null) {
                    if(obj.history[1]["id"] === userid) {
                        $("#bidid2").css("font-weight", "bold");
                        $("#biddate2").css("font-weight", "bold");
                        $("#bidtime2").css("font-weight", "bold");
                        $("#bidbid2").css("font-weight", "bold");
                    } else {
                        $("#bidid2").css("font-weight", "normal");
                        $("#biddate2").css("font-weight", "normal");
                        $("#bidtime2").css("font-weight", "normal");
                        $("#bidbid2").css("font-weight", "normal");
                    }
                 $("#bidid2").html(obj.history[1]["id"]);
                 $("#biddate2").html(obj.history[1]["date"]);
                 $("#bidtime2").html(obj.history[1]["time"]);
                 var pricebidbid2 = Math.round((parseInt(obj.history[1]["bid"], 10) * <?php echo $cur_buy ?>) * 100) / 100;
                 $("#bidbid2").html(currencyFormatDK(pricebidbid2) + " <?php echo $cur_user ?>");
                 //$("#bidbid2").html(Math.round((parseInt(obj.history[1]["bid"], 10) * <?php echo $cur_buy ?>) * 100) / 100 + " <?php echo $cur_user ?>");
                 }
                 if(obj.history[2]["id"] != null) {
                    if(obj.history[2]["id"] === userid) {
                        $("#bidid3").css("font-weight", "bold");
                        $("#biddate3").css("font-weight", "bold");
                        $("#bidtime3").css("font-weight", "bold");
                        $("#bidbid3").css("font-weight", "bold");
                    } else {
                        $("#bidid3").css("font-weight", "normal");
                        $("#biddate3").css("font-weight", "normal");
                        $("#bidtime3").css("font-weight", "normal");
                        $("#bidbid3").css("font-weight", "normal");
                    }
                 $("#bidid3").html(obj.history[2]["id"]);
                 $("#biddate3").html(obj.history[2]["date"]);
                 $("#bidtime3").html(obj.history[2]["time"]);
                 var pricebidbid3 = Math.round((parseInt(obj.history[2]["bid"], 10) * <?php echo $cur_buy ?>) * 100) / 100;
                 $("#bidbid3").html(currencyFormatDK(pricebidbid3) + " <?php echo $cur_user ?>");
                 //$("#bidbid3").html(Math.round((parseInt(obj.history[2]["bid"], 10) * <?php echo $cur_buy ?>) * 100) / 100 + " <?php echo $cur_user ?>");
                 }
                 if(obj.history[3]["id"] != null) {
                    if(obj.history[3]["id"] === userid) {
                        $("#bidid4").css("font-weight", "bold");
                        $("#biddate4").css("font-weight", "bold");
                        $("#bidtime4").css("font-weight", "bold");
                        $("#bidbid4").css("font-weight", "bold");
                    } else {
                        $("#bidid4").css("font-weight", "normal");
                        $("#biddate4").css("font-weight", "normal");
                        $("#bidtime4").css("font-weight", "normal");
                        $("#bidbid4").css("font-weight", "normal");
                    }
                 $("#bidid4").html(obj.history[3]["id"]);
                 $("#biddate4").html(obj.history[3]["date"]);
                 $("#bidtime4").html(obj.history[3]["time"]);
                 var pricebidbid4 = Math.round((parseInt(obj.history[3]["bid"], 10) * <?php echo $cur_buy ?>) * 100) / 100;
                 $("#bidbid4").html(currencyFormatDK(pricebidbid4) + " <?php echo $cur_user ?>");
                 //$("#bidbid4").html(Math.round((parseInt(obj.history[3]["bid"], 10) * <?php echo $cur_buy ?>) * 100) / 100 + " <?php echo $cur_user ?>");
                 }
                 if(obj.history[4]["id"] != null) {
                    if(obj.history[4]["id"] === userid) {
                        $("#bidid5").css("font-weight", "bold");
                        $("#biddate5").css("font-weight", "bold");
                        $("#bidtime5").css("font-weight", "bold");
                        $("#bidbid5").css("font-weight", "bold");
                    } else {
                        $("#bidid5").css("font-weight", "normal");
                        $("#biddate5").css("font-weight", "normal");
                        $("#bidtime5").css("font-weight", "normal");
                        $("#bidbid5").css("font-weight", "normal");
                    }
                 $("#bidid5").html(obj.history[4]["id"]);
                 $("#biddate5").html(obj.history[4]["date"]);
                 $("#bidtime5").html(obj.history[4]["time"]);
                 var pricebidbid5 = Math.round((parseInt(obj.history[4]["bid"], 10) * <?php echo $cur_buy ?>) * 100) / 100;
                 $("#bidbid5").html(currencyFormatDK(pricebidbid5) + " <?php echo $cur_user ?>");
                 //$("#bidbid5").html(Math.round((parseInt(obj.history[4]["bid"], 10) * <?php echo $cur_buy ?>) * 100) / 100 + " <?php echo $cur_user ?>");
                 }

                 /*
                if(obj.firstname != 0) {
                    $('#first').html(obj.firstname);
                }

                if(obj.lastname != 0) {
                    $('#last').html(obj.lastname);
                }*/
                
                if(obj.day == 0) {

                } else if(obj.day == 1) {
                    $("#dtext").text("<?php echo $language["ppt-view"]["day"][0]; ?>")
                    $("#d").text(obj.day);
                } else {
                    $("#dtext").text("<?php echo $language["ppt-view"]["day"][1]; ?>")
                    $("#d").text(obj.day);
                }
                
                $("#endh").text(obj.endh);
                $("#endi").text(obj.endi);
                $("#hisamount").text("(" + obj.hisamount + ")");
   
                if(obj.hour < 10) {
                    $("#h").text(("0" + obj.hour).slice(-2));
                } else {
                    $('#h').text(obj.hour);
                }
    
                if(obj.minut < 10) {
                    $("#i").text(("0" + obj.minut).slice(-2));
                } else {
                    $('#i').text(obj.minut);
                }
    
                if(obj.second < 10) {
                    $("#s").text(("0" + obj.second).slice(-2));
                } else {
                    $('#s').text(obj.second);
                }
                
                // getContent(saveTime);
            
            }
            getContent(saveTime);
            }
        }
    );
}
<?php
if(isset($_GET["auc-id"])) {
echo '$(function() {
    getContent();
});';
}
?>

function currencyFormatDK(num) {
  return (
    num
    .toFixed(2)
    .replace('.', ',') // replace decimal point character with ,
    .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
  ) // use . as a separator
}

    </script>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/4fe49ac51e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style/rate.css">
    <link rel="stylesheet" href="webdesign.css">
    <link rel="stylesheet" href="slideshow.css">
	<script src="script/splide.min.js"></script>
	<link rel="stylesheet" href="style/splide.min.css">
	<script>
        

    function hideAllSplidesContents() {
        let splidesContent = document.querySelectorAll("#image-slider .splide__slide");
        splidesContent.forEach(element => {
            element.classList.add("hidden");
        });
    }

    function showSelectedContentSplide() {
        let splidesContent = document.querySelectorAll("#image-slider .splide__slide.is-active");
        splidesContent[0].classList.remove("hidden");
        $("#image-slider-list").css("transform", "none");
    }
		document.addEventListener( 'DOMContentLoaded', function () {
            var primarySlider = new Splide( '#primary-slider', {
    start: 0,
    type: 'loop',
	rewind     : true,
    keyboard: false,
	classes: {
		prev: 'splide__arrow--prev prisplide--prev',
		next  : 'splide__arrow--next prisplide--next'
	}
} ).mount();
            var imageSlider = new Splide( '#image-slider', {
    type : 'fade',
    start: 0,
    perPage: 1,
    perMove: 1,
	rewind: true,
	width: '70vw',
    arrows: false,
    keyboard: false,
	pagination : false,
	classes: {
		arrows: 'splide__arrows mobile-ui',
		prev: 'splide__arrow--prev imgsplide--prev',
		next  : 'splide__arrow--next imgsplide--next',
		page: 'splide__pagination__page mobile-ui',
	}
	} ).mount();
			var secondarySlider = new Splide( '#secondary-slider', {
    start: 0,
                // type : 'loop',
	fixedWidth : 100,
	height     : 60,
	gap        : 10,
	rewind     : true,
	cover      : true,
	pagination : false,
	focus      : 'center',
	arrows     : false,
    keyboard: false,
	breakpoints : {
		'600': {
			fixedWidth: 66,
			height    : 40,
		}
	}
} ).mount();
    // primarySlider.sync( secondarySlider ).mount();
    // imageSlider.sync( secondarySlider ).mount();
    // secondarySlider.sync( imageSlider ).mount();

    // primarySlider.mount();
    // secondarySlider.mount();
    // imageSlider.mount();

        secondarySlider.go( primarySlider.index ); 
        imageSlider.go( primarySlider.index );  

       

    primarySlider.on( 'move', function () {
        secondarySlider.go( primarySlider.index ); 
        imageSlider.go( primarySlider.index );
    } );

    imageSlider.on( 'moved', function () { 
        hideAllSplidesContents();
        showSelectedContentSplide();
    } );

    $(document).keydown(function(e) {
            var image_win = $(".full-imgs");

            if(image_win.css("display") === "flex") {
                if(e.keyCode == 37) { //left
                    primarySlider.go( '-1' ); 
                } 
                else if(e.keyCode == 39) { //right
                    primarySlider.go( '+1' ); 
                }
            }

    });


} );
    </script>
</head>
<body>
    <?php include_once("gtag/gtag-body.php") ?>
<div class="header">
</div>
<div class="mobile-header">
</div>
<div class="mobile-header2">
</div>
<div class="main-view">
    <div class="main-view-div">
        <div class="view-header" style="grid-template-columns:<?php echo $view_header_seller; ?>">
        <div class="mobile-view-edit-btn">
    <?php 
    echo $ui_btn;
?>
</div>
    <div class="page-header-view">
        <h1 style="padding-bottom: 0.3em;"><b><?php echo htmlspecialchars($ppt_street . ' ' . $ppt_number . $ppt_floor . $ppt_postcode . ' ' . $ppt_city)?></b></h1>
        <h3 style="margin-bottom: 0;"><?php echo htmlspecialchars($ppt_contry_full); ?></h3>
        <p><?php echo $ppt_type; ?></p>
        <?php
        if(isset($_GET["auc-id"])) {
            $ui_header = $language["ppt-view"]["text"][4] . ' <b>' . $date_diff_1 . '</b><br><br>
            <b>' . $language["ppt-view"]["text"][5] . ': </b>' . $date_1_n . " d. " . $date_1_d . ' ' . $date_1_m_f . ' ' . $date_2_y . ' - kl. ' . $date_1_h . ':' . $date_1_i . '<br>
            <b>' . $language["ppt-view"]["text"][6] . ': </b>' . $date_2_n . " d. " . $date_2_d . ' ' . $date_2_m_f . ' ' . $date_2_y . ' - kl. ' . $date_2_h . ':' . $date_2_i;
        }

        echo $ui_header;
        ?>
        <!--<?php echo $language["ppt-view"]["text"][4]; ?> <b><?php echo $date_diff_1; ?></b><br><br>
        <b><?php echo $language["ppt-view"]["text"][5]; ?>: </b><?php echo $date_1_n . " d. " . $date_1_d . ' ' . $date_1_m_f . ' ' . $date_2_y . ' - kl. ' . $date_1_h . ':' . $date_1_i; ?><br>
        <b><?php echo $language["ppt-view"]["text"][6]; ?>: </b><?php echo $date_2_n . " d. " . $date_2_d . ' ' . $date_2_m_f . ' ' . $date_2_y . ' - kl. ' . $date_2_h . ':' . $date_2_i; ?>-->
        
    </div>
    
    <div class="view-edit-btn">
    <?php 
    echo $ui_btn;
?>
</div>
</div>
<div class="img-bid">
  
    <div class="slideshow-container">
	<div id="primary-slider" class="splide">
	<div class="splide__track">
		<ul class="splide__list">


    <?php    
    foreach($imgs as $img){
        echo $img;
    }
/*
    if($total != 1) {
    echo '<a class="prev" onclick="plusSlides(-1)">&#10094;</a><a class="next" onclick="plusSlides(1)">&#10095;</a>';
    }*/
    
    ?>

	</ul>
	</div>
</div>

        <!--<div class="dots">
        <?php    
        foreach($dots as $dot){
        echo $dot;
        }
    ?>
    </div>-->
    <p></p>
    <div id="secondary-slider" class="splide slideshow-all">
		<div class="splide__track">
		<ul class="splide__list slideshow_list">
        <?php

        foreach($all_imgs as $all_img){
            echo $all_img;
        }

        ?>
		</ul>
		</div>
    </div>
    <?php
    echo $startxt;
    ?>
    
    </div>
    <?php echo $all_auc; ?>
    <div class="your-bid" <?php if($auc_id == 0) {
        echo 'style="display: none"';
    } ?>>
        <div class="bid-info" id="bidinfo">
            <p id="bidtxt"><?php echo $language["ppt-view"]["text"][7]; ?></p>
            <p><?php echo $language["ppt-view"]["text"][8]; ?></p>
            <p><?php echo $language["ppt-view"]["text"][9]; ?></p>
    <div class="bidprice"><div style="display: inline" id="bid"></div> <?php echo $cur_user ?></div>
        <div class="timer"><p id="d"></p> <p id="dtext"></p> <p id="h"></p> : <p id="i"></p> : <p id="s"></p></div>
        <div class="buyprice"><?php echo number_format(round($ppt_value * $cur_buy, 2),2,",","."); ?> <?php echo $cur_user ?></div> 
        <div></div>
        <div style="padding-top:.5em; font-weight:normal;">(<?php echo $date_end_d ?>. <?php echo $date_end_m ?>. <?php echo $date_end_y ?> - <p style="display: inline;" id="endh"></p>:<p style="display: inline;" id="endi"></p>)</div>
</div>
<div class="bid" id="bidview">
        <p><b><?php echo $language["ppt-view"]["text"][10]; ?></b></p>
        <div style="display: grid; grid-template-columns: 50% 50%; width: 75%; margin: auto;">
            <p><?php echo $language["ppt-view"]["text"][11]; ?></p>
            <div style="text-align: right; margin-bottom: 0.7em;"><div style="display: inline" id="bidlist"></div> <?php echo $cur_user ?></div>
            <!--<div>
                <input type="checkbox" id="clean" name="cleanch" style="display: inline; margin-left: -27px;">
                <p style="display: inline; padding-left: 10px;"><b>Rengøringsgebyr</b></p>
            </div>
            <div style="text-align: right; margin-bottom: 1em;"><?php echo $ppt_clean ?> DKK</div>-->
            
            <!--<div class="servicefee"><p><u><b><?php echo $language["ppt-view"]["text"][12]; ?></b><img src="graphics/ppt/info.svg" class="info-ppt"></u><span class="tiptext"><?php echo $language["ppt-view"]["span"][1]; ?></span></p></div>
            <div style="text-align: right; margin-bottom: 0.7em;">
                <div style="display: inline" id="sfee"></div> <?php echo $cur_user ?>
                
            </div>-->
        </div>
    <div class="newbid">
    <div style="margin: auto 0;text-align: center;"><input id="bidinputnumber" type="number"/><span id="bidtextnumber" style="display: none; margin-left: -33px; position: absolute; color: #000;"><?php echo $cur_user; ?></span><div class="nextbidprice"><span><?php echo $language["ppt-view"]["text"][13]; ?></span> <div style="display: inline" id="nextbid"></div> <?php echo $cur_user ?></div></div>
    <?php

    echo $bidbtn;

?>

    </div>
    <div class="bid-item bid-img">
            <img class="bid-pay" id="dankort" src="graphics/payment/dankort.svg">
            <img class="bid-pay" id="mastercard" src="graphics/payment/mastercard.svg">
            <img class="bid-pay" id="visa" src="graphics/payment/visa.svg">
            <img class="bid-pay" id="mobilepay" src="graphics/payment/mobilepay.svg">
            <img class="bid-pay" id="visa-3d" src="graphics/payment/visa-3d-w.svg">
            <img class="bid-pay" id="mastercard-3d" src="graphics/payment/mastercard-3d.svg">
</div>    </div>
<!--<div class="bid" id="autobid" style="margin-top: 1em;">
        <p>Autobyder</p>
    <div class="newbid">
    <div id="autobid-container">
    <input class="autobid1" name="autobid" placeholder="Byd op til">
    <input class="autobid2" id="placeholder" value="DKK">
    </div>
    <input id="autobidinput" type="button" class="btn btn-primary" onclick="autobid()" value="Indstil Autobud">
      
</div>
<div class="nextbidprice" id="autobidset"></div>  
        </div>-->
<div class="bid" id="buy" style="margin-top: 1em; display: <?php echo $buynow ?>;">
        <p><?php echo $buynow_txt; ?></p>
    <div class="newbid">
    <div class="nextbidprice2"><div style="display: inline" id="buyprice<?php echo $buynow_seller; ?>"><?php echo number_format(round($auc_buy * $cur_buy, 2),2,",",".") ?></div> <?php echo $cur_user ?></div>
    <input id="buybtn" type="button" class="btn btn-primary" onclick="//buy(<?php echo round(($auc_buy) * 1.12 * $cur_buy, 2) ?>)" value="<?php echo $buynow_btn; ?>">
    </div>
        </div>
        <div class="bid" id="bought" style="margin-top: 1em;">
        <p><?php echo $language["ppt-view"]["text"][15]; ?></p>
    <div id="boughtgrid">
    <div class="boughtinput" id="boughttext"></div>
    <div class="boughtinput" id="boughtid"></div>
    </div>
        </div>
    <div class="bidder">
    <p class="bidderhead"><?php echo $language["ppt-view"]["text"][16]; ?></p><p class="bidderhead" id="hisamount"></p>
    <div class="history">
    <div class="biduserid">
    <p class="hishead"><?php echo $language["ppt-view"]["text"][17]; ?></p>
    </div>
    <div class="biddate">
    <p class="hishead"><?php echo $language["ppt-view"]["text"][18]; ?></p>
    </div>
    <div class="bidtime">
    <p class="hishead"><?php echo $language["ppt-view"]["text"][19]; ?></p>
    </div>
    <div class="bidamount">
    <p class="hishead"><?php echo $language["ppt-view"]["text"][20]; ?></p>
    </div>
        
    <?php
   foreach(range(1, 5) as $i) {
       echo '<div><p id="bidid' . $i . '"></p></div>
       <div><p id="biddate' . $i . '"></p></div>
       <div><p id="bidtime' . $i . '"></p></div>
       <div><p id="bidbid' . $i . '"></p></div>';
        //var_dump($history);
        /*echo '<div><p>' . $history[$i]["id"] . '</p></div>';
        echo '<div><p>' . $history[$i]["date"] . '</p></div>';
        echo '<div><p>' . $history[$i]["time"] . '</p></div>';
        echo '<div><p>' . $history[$i]["bid"] . '</p></div>';*/

}

   ?>
    </div>
    <a href="history.php?ppt-id=<?php echo $ppt_id; ?>&auc-id=<?php echo $auc_id; ?>" id="hisbtn" class="btn btn-primary-edit"><?php echo $language["ppt-view"]["button"][5]; ?></a>


</div>
    </div>
</div>
    <p></p>



    <div class="ppt-view-info">
        <div class="ppt-view-information" <?php if($auc_id == 0) {echo 'style="grid-template-columns: 1fr 1fr 1fr;"';} ?>>
            <p><b><?php echo $language["ppt-view"]["text"][21]; ?>:</b><br> <?php echo $ppt_room ?></p>
            <p><b><?php echo $language["ppt-view"]["text"][22]; ?>:</b><br> <?php echo $ppt_area ?> m<sup>2</sup></p>
            <p><b><?php echo $language["ppt-view"]["text"][30]; ?>:</b><br> <?php echo round($ppt_deposit * $cur_buy, 2) . ' ' . $cur_user; ?></p>
            <?php
            if($auc_id != 0) {
                echo '<p><b>' . $language["ppt-view"]["text"][23] . ':</b><br> ' . number_format(round($ppt_value * $cur_buy, 2),2,",",".") . ' ' . $cur_user . '</p>';
            }
            ?>
            <!--<p><b><?php echo $language["ppt-view"]["text"][23]; ?>:</b><br> <?php echo number_format(round($ppt_value * $cur_buy, 2),2,",",".") . ' ' . $cur_user; ?></p>-->
    </div>
   <!--<div class="mobile-ppt-view-information">
            <p><b>Værelser:</b><br> <?php echo $ppt_room ?></p>
            <p><b>Boligareal:</b><br> <?php echo $ppt_area ?> m<sup>2</sup></p>
            <p><b>Depositum:</b><br> <?php echo round($ppt_deposit * $cur_buy, 2) . ' ' . $cur_user; ?></p>
            <p><b>Normal pris:</b><br> <?php echo round($ppt_value * $cur_buy, 2) . ' ' . $cur_user; ?></p>
    </div>-->
    <hr class="line">
    <div class="ppt-view-description">
        <p class="ppt-view-description-title"><b><?php echo $language["ppt-view"]["text"][24]; ?></b></p>
        <p id="ppt-view-description-text"><?php echo $ppt_description ?></p>
    </div>
    <hr class="line">
    <div class="ppt-view-description">
        <div style="display: grid; grid-template-columns: 50% 50%;">
        <p class="ppt-view-description-title"><b><?php echo $language["ppt-view"]["text"][25]; ?></b></p>
        <p class="ppt-view-description-title"><b><?php echo $language["ppt-view"]["text"][26]; ?></b></p>
        <div style="width:100%;" class="bed-des">
            <div class="ppt-fact">
            <?php
                if($ppt_bed == 1) {
                    echo '<i class="fas fa-user"></i>';
                } else {
                    echo '<i class="fas fa-users"></i>';
                }

            ?>
                <span> <?php echo $ppt_bed ?></span>
            </div>
    </div>
    <div style="width:100%;" class="bed-des">
            <div class="ppt-fact">
            <img src="graphics/ppt/onebed.svg">
                <span> <?php echo $ppt_onebed ?></span>
            </div>
            <div class="ppt-fact">
            <img src="graphics/ppt/twobed.svg">
                <span> <?php echo $ppt_twobed ?></span>
            </div>
    </div>
            </div>
    <hr class="line">
    <div class="ppt-view-fact">
        <p class="ppt-view-fact-title"><b><?php echo $language["ppt-view"]["text"][27]; ?></b></p>
        <div class="ppt-fact-all">
            <?php

            foreach($facts as $fact){
                echo $fact;
            }

            ?>
        
    </div>
    </div>
    <hr class="line">
    <?php echo $link_des; ?>
    <div class="ppt-view-loc">
        <p class="ppt-view-loc-title"><b><?php echo $language["ppt-view"]["text"][28]; ?></b></p>
        <div id="gmap_canvas"></div>
    </div>
    </div>
</div>
</div>
</div>
<div id="toastbar"></div>
<div id="overbidBox" style="display: none;">
    <div class="overbidConsentContainer" id="overbidConsentContainer" style="">
<div class="overbidTitle">
    <h1><?php echo $language["overbid"]["header"]; ?></h1>
</div>
<div class="overbidDesc">
    <p><?php echo $language["overbid"]["text"][0]; ?></p>
</div>
<div class="overbidButton">
    <a onclick="overbidClose();"><?php echo $language["overbid"]["button"][0]; ?></a>
    <a id="addoverbid" onclick="addoverbid();"><?php echo $language["overbid"]["button"][1]; ?></a>
</div>
</div>
<img class="overlogopop" id="overlogopop" src="graphics/apos.svg">
</div>
<div id="ownoverbidBox" style="display: none;">
    <div class="overbidConsentContainer" id="overbidConsentContainer" style="">
<div class="overbidTitle">
    <h1><?php echo $language["overbid"]["header"]; ?></h1>
</div>
<div class="overbidDesc">
    <p><?php echo $language["overbid"]["text"][0]; ?></p>
</div>
<div style="margin-top: 2.2vw; text-align: right;" class="overbidButton">
    <a style="margin-right: .5vw;" onclick="ownoverbidClose();"><?php echo $language["overbid"]["button"][0]; ?></a>
    <a id="ownaddoverbid" onclick="addownoverbid();"><?php echo $language["overbid"]["button"][1]; ?></a>
</div>
</div>
<img class="overlogopop" id="overlogopop" src="graphics/apos.svg">
</div>
<div id="window-concrete" class="window-container" style="display:none;">
<div id="bid-windowid" class="bid-window">
    <div class="bid-window-container">
    <h1><?php echo $language["ppt-view"]["header"]["window"]; ?></h1>
    <p><b><?php echo htmlspecialchars($ppt_street . ' ' . $ppt_number . $ppt_floor . $ppt_postcode . ' ' . $ppt_city)?></b>
        <br>
        <?php echo $date_1_n . " d. " . $date_1_d . ' ' . $date_1_m . ' - ' . $date_2_n . " d. " . $date_2_d . ' ' . $date_2_m ?></p>
    <div class="bid-grid">
    <div class="bid-grid-title" style="margin-bottom: 1.5vw;">
        <p style="margin: 0;"><b>Udlejning</b></p>
    </div>
    <div class="bid-grid-price" style="text-align: right;"><div style="display: inline" id="bidlist2"></div> <?php echo $cur_user ?></div>
    <div class="bid-grid-title depositfee" style="margin-bottom: 1.5vw;"><p class="depfeep"><b id="depfeetxt"><?php echo $language["ppt-view"]["text"][30]; ?></b><img src="graphics/ppt/info.svg" class="info-ppt"><span id="deptiptext" class="tiptext"><?php echo $language["ppt-view"]["span"][2]; ?></span></p></div>
            <div class="bid-grid-price" style="text-align: right;">
                <div style="display: inline" id="dfee"></div> <?php echo $cur_user ?>
            </div>
            
    <?php

echo $clean_price;

?>

<div class="bid-grid-title servicefee" style="margin-bottom: 1.5vw;"><p class="serfeep"><b id="serfeetxt"><?php echo $language["ppt-view"]["text"][12]; ?></b><img src="graphics/ppt/info.svg" class="info-ppt"><span id="sertiptext" class="tiptext"><?php echo $language["ppt-view"]["span"][1]; ?></span></p></div>
            <div class="bid-grid-price" style="text-align: right;">
                <div style="display: inline" id="sfee"></div> <?php echo $cur_user ?>
            </div>
            <div class="bid-grid-title totalprice">
    <b><?php echo $language["ppt-view"]["text"]["total"]; ?></b>
    </div>
            <div class="bid-grid-price totalpriceval" style="text-align: right;">
                <div style="display: inline" id="totatxt"></div> <?php echo $cur_user ?>
            </div>
    </div>


    <div id="bid-window-button">
    <input id="hidebid" type="button" class="btn btn-remove" onclick="hideBidWindow()" value="<?php echo $language["ppt-view"]["button"][6] ?>">
    <input id="addbid" type="button" class="btn btn-primary" onclick="addbid()" value="<?php echo $language["ppt-view"]["button"][1] ?>">
        </div>
        </div>
        </div>
</div>

    
    <div class="footer">
    
    </div>
    <div class="mobile-footer"></div>
    </div>

	<div style="display: none;" class="full-imgs">
	<div id="full-img-div">
	<div id="image-slider" class="splide">
	<div class="splide__track">
		<ul class="splide__list">
    
    <?php

        foreach($full_imgs as $full_img){
            echo $full_img;
        }

        ?>
		</ul>
	</div>
</div>
		</div></div>
    <script>

        var full_imgs_show = document.getElementsByClassName("full-imgs");
        var full_img = document.getElementsByClassName("full-img");
        var leftpos = ($(window).width() - $("#overbidConsentContainer").width()) / 2;
        var toppos = ($(window).height() - $("#overbidConsentContainer").height()) / 2;
        var slideIndex = 1;
        var slides = document.getElementsByClassName("mySlides");
        var dots = document.getElementsByClassName("dot");
        var imgs_all = document.getElementsByClassName("img-all");

        $(document).ready(function(){
            $(".header").load('header.php');
            $(".footer").load('footer.php');
            if($(".mobile-header").css("display") != "none") {
                $(".mobile-header").load('mobile-header.php');
            }
            <?php if($_SESSION["loggedin"] == true) {
                echo '$(".mobile-header2").load("mobile-header2.php");';
            } else {
                echo '$(".mobile-header2").css("display", "none");';
            }?>
            $(".mobile-footer").load('mobile-footer.php');
            //$(".info-ppt").css("top", ($(".cleanfee > p").height() - 16) / 2);
            if($(".mobile-header").css("display") === "grid") {
                $(".overbidConsentContainer").css("left", '5vw')
            } else {
                $(".overbidConsentContainer").css("left", '37.5vw');
            $(".overlogopop").css("left", '35vw');
            }
            for (i = 0; i < full_imgs_show.length; i++) {
                full_imgs_show[i].style.display = "none";
            }
            if(<?php echo $id ?> === 0) {
                $('#buy').css("display", "none");
                $('#autobid').css("display", "none");
            } else {
                $(".main-view").css("margin-top", "9vw");
            }
            time = setInterval(changeTime, 1000);

            //showSlides(slideIndex);


        });

        $("#clean").change(function() {
            <?php
            if($clean_price != "") {
            echo 'var totaltxt = $("#totatxt").text();
            totaltxt = totaltxt.replace(".","");
            totaltxt = totaltxt.replace(",",".");

            var servicetxt = $("#sfee").text();
            servicetxt = servicetxt.replace(".","");
            servicetxt = servicetxt.replace(",",".");

            if($(this).prop("checked") == true) {
                var cleanch = Math.round((' . $ppt_clean . ' * ' . $cur_buy . ') * 100) / 100;
                var totalch = parseInt(totaltxt) + parseInt(cleanch);
                //var servicech = Math.round((cleanch * 0.12) + parseInt(servicetxt));
                var servicech = Math.round(parseInt(servicetxt));
                $("#totatxt").html(currencyFormatDK(Math.round(totalch, 2)));
                $("#sfee").html(currencyFormatDK(Math.round(servicech, 2)));

                console.log(servicech);
            } else {
                var cleanch = Math.round((' . $ppt_clean . ' * ' . $cur_buy . ') * 100) / 100;
                var totalch = parseInt(totaltxt) - parseInt(cleanch);
                //var servicech = Math.round(parseInt(servicetxt) - (cleanch * 0.12));
                var servicech = Math.round(parseInt(servicetxt));
                $("#totatxt").html(currencyFormatDK(Math.round(totalch, 2)));
                $("#sfee").html(currencyFormatDK(Math.round(servicech, 2)));
            }';
        }
            ?>
            var date=new Date();
            date.setTime(date.getTime()+(2000*24*60*60*31));
            expires="; expires="+date.toUTCString();
            if(this.checked) {
                document.cookie = "cleancookie<?php echo $auc_id ?>=1"+expires;
            } else {
                document.cookie = "cleancookie<?php echo $auc_id ?>=0"+expires;
            }
        });

        $(".full-imgs").click(function() {
            this.style.display = "none";
            $(".paddle-view").css("display", "none");
            $('body').attr('style','overflow:visible');
        });

        $('#image-slider').click(function(event){
            event.stopPropagation();
        });

        $(".logbid").click(function() {
            if($(".mobile-header").css("display") === "grid") {
                window.location.href = "login.php";
            } else {
                showLogin();
            }
        });

        $(".nextbidprice").click(function() {
            $(".nextbidprice").css("display", "none");
            $("#bidinputnumber").css("display", "inline");
            $("#bidtextnumber").css("display", "inline");
            $("#bidinputnumber").focus();
            $("#addbid").attr("onclick", "addownbid()");
        });

        $("#bidinputnumber").focusout(function() {
            var bidinputval = $("#bidinputnumber").val();
            var nextbinval = $("#nextbid").text();
            if(bidinputval === "") {
            $(".nextbidprice").css("display", "block");
            $("#bidinputnumber").css("display", "none");
            $("#bidtextnumber").css("display", "none");
            $("#addbid").attr("onclick", "addbid(" + nextbinval + ")");
            } 
        });

        $(".serfeep").hover(
            function() {
                $("#sertiptext").css("visibility", "visible");
            },
            function() {
                $("#sertiptext").css("visibility", "hidden");
        });

        $(".depfeep").hover(
            function() {
                $("#deptiptext").css("visibility", "visible");
            },
            function() {
                $("#deptiptext").css("visibility", "hidden");
        });

        $(".clefeep").hover(
            function() {
                $("#cletiptext").css("visibility", "visible");
            },
            function() {
                $("#cletiptext").css("visibility", "hidden");
        });

        function showImg() {
            
        hideAllSplidesContents();
        showSelectedContentSplide();
            $(".full-imgs").css("display", "flex");
            $('body').attr('style','overflow:hidden');
        }

        function hideImg(n) {
            full_imgs_show[n].style.display = "none";
            $(".paddle-view").css("display", "none");
            $('body').attr('style','overflow:visible');
        }
        
        $("#buybtn").click(function() {
            var buynowseller = document.getElementById("buypriceseller");
            if(<?php if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){echo 1;} else {echo 0;}?> === 1) {
                if($(".mobile-header").css("display") === "grid") {
                window.location.href = "login.php";
            } else {
                showLogin();
            }
            } else {
            $(".window-container").css("display", "block");
            $(".bid-window-container > h1").text("<?php echo $buynow_btn ?>");
            $(".totalprice > b").text("<?php echo $language["ppt-view"]["text"]["totalbuy"]; ?>");
            $("#addbid").attr("value", "<?php echo $buynow_btn ?>");
            if(buynowseller != null) {
                $(".bid-grid-title").css("display", "none");
                $(".bid-grid-price").css("display", "none");
                $(".totalprice").css("display", "block");
                $(".totalpriceval").css("display", "block");
                $('#totatxt').html($("#buypriceseller").html());
                $("#addbid").attr("onclick", "buy()");

            } else {
                if($("#clean").is(':checked')) {
                    var cleanprice = Math.round((<?php echo $ppt_clean * $cur_buy ?>) * 100) / 100;
                    //var cleanfee = parseInt(cleanprice) * 0.12;
                    var cleanfee = 0;
                } else {
                    var cleanfee = 0;
                }
            var buyprice = Math.round(<?php echo $auc_buy; ?> * 100) / 100;
            var depositbuy = Math.round(<?php echo $ppt_deposit; ?> * 100) / 100;
            var serfee = Math.round((buyprice * 0.12) * 100) / 100;
            <?php
            if($clean_price != "") {
                echo 'var cleanbuy = Math.round(' . $ppt_clean . ' * 100) / 100;
                if($("#clean").prop("checked") == true) {
                    var total = parseInt(buyprice) + parseInt(serfee) + parseInt(depositbuy) + parseInt(cleanbuy);
                } else {
                    var total = parseInt(buyprice) + parseInt(serfee) + parseInt(depositbuy);
                }';
            } else {
                echo 'var total = parseInt(buyprice) + parseInt(serfee) + parseInt(depositbuy);';
            }
            ?>
            $("#addbid").attr("onclick", "buy()");
            $("#bidlist2").html(currencyFormatDK(Math.round(buyprice, 2)));
            $('#sfee').html(currencyFormatDK(Math.round((serfee + cleanfee) * <?php echo $cur_buy ?>, 2)));
            $('#totatxt').html(currencyFormatDK(Math.round((total * <?php echo $cur_buy ?>) * 100) / 100));
            }
            }
        });



        function showBidWindow() {
            var bidinputval = $("#bidinputnumber").val();
            var nextbinval = $("#nextbid").text();
            $(".window-container").css("display", "block");

            if($("#clean").is(':checked')) {
                var cleanprice = Math.round((<?php echo $ppt_clean * $cur_buy ?>) * 100) / 100;
                //var cleanfee = parseInt(cleanprice) * 0.12;
                var cleanfee = 0;
            } else {
                var cleanfee = 0;
            }

            if(bidinputval === "") {
            var depositbid = Math.round(<?php echo $ppt_deposit; ?> * 100) / 100;
            var serfee = Math.round((nextbinval * 0.12) * 100) / 100;
            <?php
            if($clean_price != "") {
                echo 'var cleanbuy = Math.round(' . $ppt_clean . ' * 100) / 100;
                if($("#clean").prop("checked") == true) {
                    var total = parseInt(nextbinval) + parseInt(serfee) + parseInt(depositbid) + parseInt(cleanbuy);
                } else {
                    var total = parseInt(nextbinval) + parseInt(serfee) + parseInt(depositbid);
                }';
            } else {
                echo 'var total = parseInt(nextbinval) + parseInt(serfee) + parseInt(depositbid);';
            }
            ?>
            //var total = parseInt(nextbinval) + parseInt(serfee) + parseInt(depositbid);
            $(".nextbidprice").css("display", "block");
            $("#bidinputnumber").css("display", "none");
            $("#bidtextnumber").css("display", "none");
            $("#addbid").attr("onclick", "addbid(" + nextbinval + ")");
            $("#bidlist2").html(currencyFormatDK(Math.round(nextbinval, 2)));
            $('#sfee').html(currencyFormatDK(Math.round((serfee + cleanfee) * <?php echo $cur_buy ?>, 2)));
            $('#totatxt').html(currencyFormatDK(Math.round((total * <?php echo $cur_buy ?>) * 100) / 100));
            } else {
            var depositbid = Math.round(<?php echo $ppt_deposit; ?> * 100) / 100;
            var serfee = Math.round((bidinputval * 0.12) * 100) / 100;
            <?php
            if($clean_price != "") {
                echo 'var cleanbuy = Math.round(' . $ppt_clean . ' * 100) / 100;
                if($("#clean").prop("checked") == true) {
                    var total = parseInt(bidinputval) + parseInt(serfee) + parseInt(depositbid) + parseInt(cleanbuy);
                } else {
                    var total = parseInt(bidinputval) + parseInt(serfee) + parseInt(depositbid);
                }';
            } else {
                echo 'var total = parseInt(bidinputval) + parseInt(serfee) + parseInt(depositbid);';
            }
            ?>
            //var total = parseInt(bidinputval) + parseInt(serfee) + parseInt(depositbid);
            $("#addbid").attr("onclick", "addownbid()");
            $("#bidlist2").html(currencyFormatDK(Math.round(bidinputval, 2)));
            $('#sfee').html(currencyFormatDK(Math.round((serfee + cleanfee) * <?php echo $cur_buy ?>, 2)));
            $('#totatxt').html(currencyFormatDK(Math.round((total * <?php echo $cur_buy ?>) * 100) / 100));
            }
        }

        <?php
        if($clean_price != "") {
                echo '';
        }
        ?>

        function hideBidWindow() {
            var headerbidwin = $(".bid-window-container > h1").text();
            if(headerbidwin === "<?php echo $buynow_btn; ?>") {
                $(".bid-window-container > h1").text("<?php echo $language["ppt-view"]["header"]["window"] ?>");
            $(".totalprice > b").text("<?php echo $language["ppt-view"]["text"]["total"]; ?>");
            $("#addbid").attr("value", "<?php echo $language["ppt-view"]["header"]["window"]; ?>");
            }
            var nextbinval = $("#nextbid").text();
            $(".window-container").css("display", "none");
            $(".nextbidprice").css("display", "block");
            $("#bidinputnumber").css("display", "none");
            $("#bidtextnumber").css("display", "none");
            $("#addbid").attr("onclick", "addbid(" + nextbinval + ")");
            var depositnext = Math.round((<?php echo $ppt_deposit; ?>) * 100) / 100;
            var serfee = Math.round((nextbinval * 0.12) * 100) / 100;
            <?php
            if($clean_price != "") {
                echo 'var cleanbuy = Math.round(' . $ppt_clean . ' * 100) / 100;
                if($("#clean").prop("checked") == true) {
                    var total = parseInt(nextbinval) + parseInt(serfee) + parseInt(depositnext) + parseInt(cleanbuy);
                } else {
                    var total = parseInt(nextbinval) + parseInt(serfee) + parseInt(depositnext);
                }';
            } else {
                echo 'var total = parseInt(nextbinval) + parseInt(serfee) + parseInt(depositnext);';
            }
            ?>
            //var total = parseInt(nextbinval) + parseInt(serfee) + parseInt(depositnext);
            $("#bidlist2").html(currencyFormatDK(Math.round(nextbinval, 2)));
            $("#bidinputnumber").val("");
            $('#sfee').html(currencyFormatDK(Math.round(serfee * <?php echo $cur_buy ?>, 2)));
            $('#totatxt').html(currencyFormatDK(Math.round((total * <?php echo $cur_buy ?>) * 100) / 100));
        }
        
        $(document).mouseup(function(e) {
            var container_win = $(".window-container");
            var container = $(".bid-window");

            if(container_win.css("display") === "block") {
                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    hideBidWindow();
                }
            }
        });

        function showToast() {
            var x = document.getElementById("toastbar");

            x.className = "show";

            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
        }
        
        function changeTime() {
            //console.log(timestamp);
            if($("#h").text() <= 0 && $("#i").text() <= 0 && $("#s").text() <= 0 ) {
                if($("#d").text() === "") {
                $("#h").text("00").slice(-2);
                $("#i").text("00").slice(-2);
                $("#s").text("00").slice(-2);
                clearInterval(time);

                
        $('#buy').css("display", "none");
        $('#bidview').css("display", "none");
        $('#autobid').css("display", "none");
        $('#bidinfo').css("display", "none");
        $("#bought").css("display", "block");

                var ppt = {'ppt-id' : <?php echo $ppt_id; ?>};

                $.ajax( {
                    type: 'GET',
                    url: 'time-close.php',
                    data: ppt,
                    success: function(){
                        var element = document.getElementById("addbid");
                        
                        element.parentNode.removeChild(element);
                        
                    }
                });
                }  
            } else {
            $("#s").text(eval($("#s").text() - 1));
            }

            if($("#i").text() == -1) {
                $("#i").text(59);
                $("#h").text(eval($("#h").text() - 1));
            }

            if($("#s").text() == -1) {
                $("#s").text(59);
                $("#i").text(eval($("#i").text() - 1));
            }


            if($("#s").text() < 10) {
                $("#s").text(("0" + $("#s").text()).slice(-2));
            }

            if($("#i").text() < 10) {
                $("#i").text(("0" + $("#i").text()).slice(-2));
            }

            if($("#h").text() < 10) {
                $("#h").text(("0" + $("#h").text()).slice(-2));
            }

            
           
        }

        function addbid(bidval) {

            var curbiduser = $("#bidid1").text();
            var userid = "<?php echo $id; ?>";

            if(curbiduser === userid) {
                overbidShow();
            } else {

            if($("#clean").prop("checked") == true) {
                var clean = 1;
            } else {
                var clean = 0;
            }

            var queryString2 = 'id=<?php echo $id; ?>&aucid=<?php echo $auc_id ?>&clean=' + clean;

            $.ajax(
        {
            type: 'GET',
            url: 'addbid.php',
            data: queryString2,
            success: function(data2){

                var obj2 = jQuery.parseJSON(data2);

                /*$('#bid').html(obj2.data_from_file);
                $('#h').text(obj2.hour);
                $('#i').text(obj2.minut);
                $('#s').text(obj2.second);
                
                getContent(obj2.timestamp);*/
                $("#toastbar").text("<?php echo $language["ppt-view"]["toast"][0]; ?>" + " " + currencyFormatDK(bidval) + " " + "<?php echo $cur_user ?>");
                showToast();
                hideBidWindow();
                if(obj2.oldwin != <?php echo $id ?>){
                    var loosmail = 'oldid='+obj2.oldwin+'&pptid=<?php echo $ppt_id ?>&aucid=<?php echo $auc_id ?>&oldbid='+obj2.oldbid;

                    $.ajax(
                        {
                            type: 'GET',
                            url: 'loos.php',
                            data: loosmail,
                            success: function(){

                            }
                        }
                    );
                }
            }
        }
    );
            }
        }

function addownbid() {
/*
    var curbiduser = $("#bidid1").text();
    var userid = "<?php echo $id; ?>";

    if(curbiduser === userid) {
        overbidShow();
    } else {*/

    var curbiduser = $("#bidid1").text();
    var userid = "<?php echo $id; ?>";

    var bidowninputval = parseInt($("#bidinputnumber").val());
    var nextbidval = parseInt($("#nextbid").text());
    if(bidowninputval < nextbidval) {
        alert("<?php echo $language["ppt-view"]["ownbid"][0]; ?> " + nextbidval);
    } else {
    if(curbiduser === userid) {
        //$("#addoverbid").attr("onclick", "addoverbid(" + (Math.round((nextbid * <?php echo $cur_buy ?>) * 100) / 100) + ")");
                $("#ownaddoverbid").attr("onclick", "ownaddoverbid(" + bidowninputval + ")");
                ownoverbidShow();
            } else {
    





    if($("#clean").prop("checked") == true) {
        var clean = 1;
    } else {
        var clean = 0;
    }

    var queryString2 = 'id=<?php echo $id; ?>&aucid=<?php echo $auc_id ?>&clean=' + clean + '&ownbid=' + bidowninputval;

    $.ajax(
{
    type: 'GET',
    url: 'addownbid.php',
    data: queryString2,
    success: function(data2){

        var obj2 = jQuery.parseJSON(data2);

        $("#toastbar").text("<?php echo $language["ppt-view"]["toast"][0]; ?>" + " " + currencyFormatDK(bidowninputval) + " " + "<?php echo $cur_user ?>");
        showToast();
        hideBidWindow();
        $(".nextbidprice").css("display", "block");
        $("#bidinputnumber").css("display", "none");
        $("#bidinputnumber").attr("value", "");
        $("#bidtextnumber").css("display", "none");
        if(obj2.oldwin != <?php echo $id ?>){
            var loosmail = 'oldid='+obj2.oldwin+'&pptid=<?php echo $ppt_id ?>&aucid=<?php echo $auc_id ?>&oldbid='+obj2.oldbid;

            $.ajax(
                {
                    type: 'GET',
                    url: 'loos.php',
                    data: loosmail,
                    success: function(){

                    }
                }
            );
        }
    }
}
);
}
   // }
}
}

        function addoverbid(bidval) {
            $("#overbidBox").css("display", "none");
            $("body").css("overflow", "unset");


            if($("#clean").prop("checked") == true) {
                var clean = 1;
            } else {
                var clean = 0;
            }

            var queryString2 = 'id=<?php echo $id; ?>&aucid=<?php echo $auc_id ?>&clean=' + clean;

            $.ajax(
        {
            type: 'GET',
            url: 'addbid.php',
            data: queryString2,
            success: function(data2){

                var obj2 = jQuery.parseJSON(data2);

                /*$('#bid').html(obj2.data_from_file);
                $('#h').text(obj2.hour);
                $('#i').text(obj2.minut);
                $('#s').text(obj2.second);
                
                getContent(obj2.timestamp);*/
                $("#toastbar").text("<?php echo $language["ppt-view"]["toast"][0]; ?>" + " " + currencyFormatDK(bidval) + " " + "<?php echo $cur_user ?>");
                showToast();
                hideBidWindow();
                if(obj2.oldwin != <?php echo $id ?>){
                    var loosmail = 'oldid='+obj2.oldwin+'&pptid=<?php echo $ppt_id ?>&aucid=<?php echo $auc_id ?>&oldbid='+obj2.oldbid;

                    $.ajax(
                        {
                            type: 'GET',
                            url: 'loos.php',
                            data: loosmail,
                            success: function(){

                            }
                        }
                    );
                }
            }
        }
    );
        }

function ownaddoverbid(bidowninputval) {
    var nextbidval = parseInt($("#nextbid").text());

    if(bidowninputval < nextbidval) {
        alert("<?php echo $language["ppt-view"]["ownbid"][0]; ?> " + nextbidval);
    } else {
    $("#ownoverbidBox").css("display", "none");
    $("body").css("overflow", "unset");


    if($("#clean").prop("checked") == true) {
        var clean = 1;
    } else {
        var clean = 0;
    }

    var queryString2 = 'id=<?php echo $id; ?>&aucid=<?php echo $auc_id ?>&clean=' + clean + '&ownbid=' + bidowninputval;

    $.ajax(
{
    type: 'GET',
    url: 'addownbid.php',
    data: queryString2,
    success: function(data2){

        var obj2 = jQuery.parseJSON(data2);

        /*$('#bid').html(obj2.data_from_file);
        $('#h').text(obj2.hour);
        $('#i').text(obj2.minut);
        $('#s').text(obj2.second);
        
        getContent(obj2.timestamp);*/
        $("#toastbar").text("<?php echo $language["ppt-view"]["toast"][0]; ?>" + " " + currencyFormatDK(bidowninputval) + " " + "<?php echo $cur_user ?>");
        showToast();
        hideBidWindow();
        $(".nextbidprice").css("display", "block");
        $("#bidinputnumber").css("display", "none");
        $("#bidinputnumber").attr("value", "");
        $("#bidtextnumber").css("display", "none");
        if(obj2.oldwin != <?php echo $id ?>){
            var loosmail = 'oldid='+obj2.oldwin+'&pptid=<?php echo $ppt_id ?>&aucid=<?php echo $auc_id ?>&oldbid='+obj2.oldbid;

            $.ajax(
                {
                    type: 'GET',
                    url: 'loos.php',
                    data: loosmail,
                    success: function(){

                    }
                }
            );
        }
    }
}
);
}
}
        
        function overbidClose(){
            $("#overbidBox").css("display", "none");
            $("body").css("overflow", "unset");
        }

        function overbidShow(){
            $("#overbidBox").css("display", "block");
            $("body").css("overflow", "hidden");   
        }
        
        function ownoverbidClose(){
            $("#ownoverbidBox").css("display", "none");
            $("body").css("overflow", "unset");
        }

        function ownoverbidShow(){
            $("#ownoverbidBox").css("display", "block");
            $("body").css("overflow", "hidden");   
        }
/*
        function autobid() {

            var bid = $("[name=autobid").val();

            if(bid != "") {

            var queryString3 = 'id=<?php echo $id; ?>&aucid=<?php echo $auc_id ?>&bid=' + bid;

            $.ajax(
            {
            type: 'GET',
            url: 'autobid.php',
            data: queryString3,
            success: function(data3){

                var obj3 = jQuery.parseJSON(data3);

                if(obj3.autosize != "") {

                $('#autobid-container').css("display", "none");
                $('#autobidinput').css("display", "none");
                $('#autobidset').css("display", "block");
                $('#autobidset').text("Autobud indstillet til: " + obj3.autosize + " <?php echo $cur_user ?>");
                } else {
                    $("[name=autobid]").val("");
                }
            }
            }
            );
            }
            }
            */

        function buy() {


    if($("#clean").prop("checked") == true) {
        var clean = 1;
    } else {
        var clean = 0;
    }

        var queryString4 = 'id=<?php echo $id; ?>&aucid=<?php echo $auc_id ?>&clean=' + clean;

        $.ajax(
        {
        type: 'GET',
        url: 'buy.php',
        data: queryString4,
        success: function(data4){

          var obj4 = jQuery.parseJSON(data4);

          var buyprice = <?php echo $auc_buy; ?>;
          var serfee = Math.round(((buyprice * 0.12) * <?php echo $cur_buy ?>) * 100) / 100;
          var total = parseInt(buyprice) + parseInt(serfee);

          if(<?php echo $seller ?> != 1) {
            $("#toastbar").text("<?php echo $language["ppt-view"]["toast"][1]; ?>" + " " + currencyFormatDK(total) + " " + "<?php echo $cur_user ?>");
            showToast();
          }
        hideBidWindow();

          $('#buy').css("display", "none");
          $('#bidview').css("display", "none");
          $('#bidinfo').css("display", "none");
          $("#bought").css("display", "block");
          $(".circle-cart").text(parseInt($(".circle-cart").text(), 10) + 1);

          var loosmailbuy = 'oldid='+obj4.oldwin+'&pptid=<?php echo $ppt_id ?>&aucid=<?php echo $auc_id ?>';

          $.ajax(
            {
                type: 'GET',
                url: 'loos-buy.php',
                data: loosmailbuy,
                success: function(){
                    $.ajax(
          {
            type: 'GET',
            url: 'order.php',
            success: function(){
                location.href = "basket.php";
            }
        }
        );
                }
            }
          );

          
        }
        }
        );
    }

    function follow() {
        var queryString5 = '<?php 
            if(!isset($_GET["auc-id"])) {
                echo 'id=' . $id , '&pptid=' . $ppt_id;
            } else {
                echo 'id=' . $id , '&aucid=' . $auc_id;
            } ?>';

            $.ajax(
        {
            type: 'GET',
            url: 'follow.php',
            data: queryString5,
            success: function(){
                location.reload();

                
            }
        }
    );

    }

    function unfollow() {
        var queryString6 = '<?php 
            if(!isset($_GET["auc-id"])) {
                echo 'id=' . $id , '&pptid=' . $ppt_id;
            } else {
                echo 'id=' . $id , '&aucid=' . $auc_id;
            } ?>';

            $.ajax(
        {
            type: 'GET',
            url: 'unfollow.php',
            data: queryString6,
            success: function(data6){

                /*
                document.getElementById("unfollow").style.display = "none";
                document.getElementById("follow").style.display = "block"; 
                */
                location.reload();

                
            }
        }
    );

    }
        
    </script>
    <script
      src="https://maps.googleapis.com/maps/api/js?key=####&callback=initMapLoc&libraries=&v=weekly"
      async
    ></script>

</body>
</html>
<?php

include "payalert.php";

?>
