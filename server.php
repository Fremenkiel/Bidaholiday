<?php
include "config.php";

set_time_limit(0);

while (true) {

    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    $last_ajax_call = isset($_GET['timestamp']) ? (int)$_GET['timestamp'] : null;

    $ppt_id = $_GET["ppt-id"];
    $auc_id = $_GET["auc-id"];
    $id = $_GET["id"];

    clearstatcache();

    $ppt_data = "SELECT end, bid, bidtime, user, active, removed FROM auction WHERE id = $auc_id";

    $ppt_info = mysqli_query($link, $ppt_data);

    $row = mysqli_fetch_array($ppt_info);

    $userid = $row["user"];
    if($userid == 0) {
        $firstname = 0;
        $lastname = 0;
    } else {

        $user_data = "SELECT firstname, lastname FROM users WHERE id = $userid";

    $user_info = mysqli_query($link, $user_data);

    $user = mysqli_fetch_array($user_info);

    $firstname = $user["firstname"];
    $lastname = $user["lastname"];
    }

    $time = $row["bidtime"];
    $active = $row["active"];
    $data = $row["bid"];
    $removed = $row["removed"];
    $autosize = "";
    $maxauto = $data;


    

    $last_change_in_data_file = strtotime($time);

    if ($last_ajax_call == null || $last_change_in_data_file > $last_ajax_call) {

    date_default_timezone_set('Europe/Copenhagen');

    $end = date_create(date('d-m-Y H:i:s', strtotime($row["end"])));
    $date = date_create(date('d-m-Y H:i:s'));

    $end_h = date_format($end, 'H');
    $end_i = date_format($end, 'i');

    $arrayname = json_decode(file_get_contents("auc/" . $auc_id . ".json"), true);

    $hisamount = count($arrayname);

    $history = array_reverse($arrayname);

    if($active == 0 && $end > $time) {
        $buynow = 1;
    } else {
        $buynow = 0;
    }

    if ($date >= $end) {
  

        $result = array(
            'data_from_file' => $data,
            'hour' => 0,
            'minut' => 0,
            'second' => 0,
            'userid' => $userid,
            'history' => array(
                0 => array( 
                    "id" => $history[0]["id"], 
                    "date" => $history[0]["date"], 
                    "time" => $history[0]["time"], 
                    "bid" => $history[0]["bid"]
                ),
                1 => array( 
                    "id" => $history[1]["id"], 
                    "date" => $history[1]["date"], 
                    "time" => $history[1]["time"], 
                    "bid" => $history[1]["bid"]
                ),
                2 => array( 
                    "id" => $history[2]["id"], 
                    "date" => $history[2]["date"], 
                    "time" => $history[2]["time"], 
                    "bid" => $history[2]["bid"]
                ),
                3 => array( 
                    "id" => $history[3]["id"], 
                    "date" => $history[3]["date"], 
                    "time" => $history[3]["time"], 
                    "bid" => $history[3]["bid"]
                ),
                4 => array( 
                    "id" => $history[4]["id"], 
                    "date" => $history[4]["date"], 
                    "time" => $history[4]["time"], 
                    "bid" => $history[4]["bid"]
                ),
            ),
            'hisamount' => $hisamount,
            'active' => $active,
            'buynow' => $buynow,
            'remove' => $removed,
            'timestamp' => $last_change_in_data_file
        );

        $json = json_encode($result);
        echo $json;
        
        mysqli_close($link);

        break;
    } else {

    $date_diff = date_diff($date, $end);

    $date_diff_h = strval($date_diff->format("%h"));
    $date_diff_h2 = strval($date_diff->format("%h") + ($date_diff->format("%a") * 24));
    $date_diff_d = (int) ($date_diff_h2 / 24);
    $date_diff_i = strval($date_diff->format("%i"));
    $date_diff_s = strval($date_diff->format("%s"));


    

       

        $result = array(
            'data_from_file' => $data,
            'active' => $active,
            'endh' => $end_h,
            'endi' => $end_i,
            'day' => $date_diff_d,
            'hour' => $date_diff_h,
            'minut' => $date_diff_i,
            'second' => $date_diff_s,
            'userid' => $userid,
            'history' => array(
                0 => array( 
                    "id" => $history[0]["id"], 
                    "date" => $history[0]["date"], 
                    "time" => $history[0]["time"], 
                    "bid" => $history[0]["bid"]
                ),
                1 => array( 
                    "id" => $history[1]["id"], 
                    "date" => $history[1]["date"], 
                    "time" => $history[1]["time"], 
                    "bid" => $history[1]["bid"]
                ),
                2 => array( 
                    "id" => $history[2]["id"], 
                    "date" => $history[2]["date"], 
                    "time" => $history[2]["time"], 
                    "bid" => $history[2]["bid"]
                ),
                3 => array( 
                    "id" => $history[3]["id"], 
                    "date" => $history[3]["date"], 
                    "time" => $history[3]["time"], 
                    "bid" => $history[3]["bid"]
                ),
                4 => array( 
                    "id" => $history[4]["id"], 
                    "date" => $history[4]["date"], 
                    "time" => $history[4]["time"], 
                    "bid" => $history[4]["bid"]
                ),
            ),
            'hisamount' => $hisamount,
            'buynow' => $buynow,
            'remove' => $removed,
            'autobid' => $autobid,
            'autosize' => $autosize,
            'timestamp' => $last_change_in_data_file
        );

        $json = json_encode($result);
        echo $json;
        
        mysqli_close($link);

        break;

    }
} else {
        mysqli_close($link);
        sleep(1);
        break;
    }
    

}
?>