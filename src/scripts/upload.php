<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
    header('Location: ..');
}

if (isset($_POST['prunedLocationHistory']) && isset($_POST['fileName'])) {
    require '../vendor/autoload.php';

    valid_file_type($_POST['fileName']);

    $link = mysqli_connect("localhost", "nikosm", "1q2w3e4r", "site");
    $username = $_SESSION["username"];

    $total_moving_activities = 0;
    $eco_moving_activities = 0;

    // insert activities to DB
    $jsonString = $_POST['prunedLocationHistory'];
    $jsonStream = \JsonMachine\JsonMachine::fromString($jsonString, "/locations");

    foreach ($jsonStream as $location => $locationDataArray) {
        if (count($locationDataArray["activity"]) > 0) {
            list($total_moving_activities, $eco_moving_activities) = update_activities_table($locationDataArray, $total_moving_activities, $eco_moving_activities);
        }
    }

    // update user's eco score
    $sqlSel = mysqli_query($link, "SELECT score, total_moving_activities AS tma, latest_update FROM eco_score WHERE username = '$username'");
    $data = array();
    $data = mysqli_fetch_array($sqlSel, MYSQLI_ASSOC);

    if ($total_moving_activities > 0) {
        $sqlUpd = "";
        $cur_month = date('m');

        if ($data["latest_update"] == $cur_month) {
            $new_total = $data["tma"] + $total_moving_activities;
            $new_score = ($data["score"] * $data["tma"] + $eco_moving_activities) / $new_total;
    
            $sqlUpd = "UPDATE eco_score SET score = '$new_score', total_moving_activities = '$new_total' WHERE username = '$username'"; 
        }  
        else {
            $new_score = $eco_moving_activities / $total_moving_activities;
            
            $sqlUpd = "UPDATE eco_score SET score = '$new_score', total_moving_activities = '$total_moving_activities', latest_update='$cur_month' WHERE username = '$username'";
        }

        if (!$link->query($sqlUpd)) {
            echo "Database error.";
            exit(1);
        }
    }
    
    mysqli_free_result($sqlSel);

    // update last upload date
    update_users_table();

    echo 'File uploaded successfully!';
}

function valid_file_type($fileName) {
    $extension = explode(".", $fileName);
    
    if (end($extension) != "json") {
        echo "File provided is not json. Please provide a .json file.";
        exit(1);
    }
}


function update_users_table() {
    $link = mysqli_connect("localhost", "nikosm", "1q2w3e4r", "site");
    
    $username = $_SESSION["username"];

    $current_date = date("Y-m-d H:i:s");
    
    $sql = "UPDATE users SET last_upload = '$current_date' WHERE username='$username'";

    if (!$link->query($sql)) {
        echo "Database error.";
        exit(1);
    }
}

function update_activities_table($location, $total_moving_activities, $eco_moving_activities) {
    $link = mysqli_connect("localhost", "nikosm", "1q2w3e4r", "site");
    $username = $_SESSION["username"];
    
    $loc_ts = $location['timestampMs'] / 1000;
    $loc_date = date("Y-m-d H:i:s", $loc_ts);

    $lat = $location['latitudeE7'];
    $lng = $location['longitudeE7'];
    $accuracy = $location['accuracy'];

    $heading = "NULL";
    $verticalAccuracy = "NULL";
    $velocity = "NULL";
    $altitude = "NULL";

    if (array_key_exists('heading', $location)) {
        $heading = $location["heading"];
    }
    if (array_key_exists('verticalAccuracy', $location)) {
        $verticalAccuracy = $location["verticalAccuracy"];
    }
    if (array_key_exists('velocity', $location)) {
        $velocity = $location["velocity"];
    }
    if (array_key_exists('altitude', $location)) {
        $altitude = $location["altitude"];
    }
    
    $activities = $location['activity'];

    for ($j = 0; $j < count($activities); $j++) {
        $type = $activities[$j]['activity'][0]['type'];
        $confidence = $activities[$j]['activity'][0]['confidence'];
        $ts = $activities[$j]['timestampMs'] / 1000;
        $act_date = date("Y-m-d H:i:s", $ts);

        $sql = "INSERT INTO activities (username, loc_ts, latitude, longitude, accuracy, heading, verticalAccuracy, velocity, altitude, activity_type, ts, confidence) VALUES (
            '$username',
            '$loc_date',
            $lat,
            $lng,
            $accuracy,
            $heading,
            $verticalAccuracy,
            $velocity,
            $altitude,
            '$type',
            '$act_date',
            $confidence
        )";

        // update eco_score table
        $month_of_activity = date("m", $ts);
        $year_of_activity = date("Y", $ts);
        
        if ($type != "STILL" && $month_of_activity == date('m') && $year_of_activity == date('Y')) {
            $total_moving_activities++;
            
            if ($type != "IN_VEHICLE") {
                $eco_moving_activities++;
            }
        }

        if (!$link->query($sql)) {
            echo "Database error.";
            exit(1);
        }
    }

    return array($total_moving_activities, $eco_moving_activities);   
}
?>
