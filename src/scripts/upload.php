<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
    header('Location: ..');
}

if (isset($_POST['prunedLocationHistory']) && isset($_POST['fileName'])) {
    $file_text = $_POST['prunedLocationHistory'];
    
    $name = $_SESSION['username'] . "_" . time() . "." . end(explode(".", $_POST['fileName']));
    rename($_POST['fileName'], $name);
    $location = '../uploads/';
    writeFile($name, $location, $file_text);
}

function writeFile($name, $location, $txt) {
    mkdir($location);
    $fileSpecs = json_decode(check_duplicate_file($location, $name));
    valid_file_type($name);
    $myfile = fopen("$fileSpecs->path$fileSpecs->filename", "w") or die("Unable to open file!");
    fwrite($myfile, $txt);
    update_upload_table($fileSpecs->filename);
    update_activities_table($location, $fileSpecs->filename);
    echo 'File uploaded successfully!';
}

function valid_file_type($name) {
    $extension = end(explode(".", $name));
    
    if ($extension != "json") {
        echo "File provided is not json. Please provide a .json file.";
        exit(1);
    }
}

function check_duplicate_file($location, $file) {
    $index = 1;
    $copies = "";
    $filename = implode(".", array_slice(explode(".", $file), 0, -1));
    $extension = end(explode(".", $file));
    $file = "$location$filename$copies.$extension";
    
    while (file_exists($file)) {
        $copies = "($index)";
        $file = "$location$filename$copies.$extension";
        $index++;
    }
    
    $file_specs->path = $location;
    $file_specs->filename = "$filename$copies.$extension";
    
    return json_encode($file_specs);
}

function update_upload_table($name) {
    $link = mysqli_connect("localhost", "nikosm", "1q2w3e4r", "site");
    $username = $_SESSION["username"];
    $sql = "INSERT INTO upload (username, file_path) VALUES (
        '$username',
        '$name'
    )";

    if ($link->query($sql)) {
        echo "Successful";
    }
    else {
        echo "Database error.";
        exit(1);
    }
}

function update_activities_table($location, $name) {
    $link = mysqli_connect("localhost", "nikosm", "1q2w3e4r", "site");
    $username = $_SESSION["username"];
    
    $file_path = $location . $name;
    $json_string = file_get_contents($file_path);
    $json_data = json_decode($json_string, true);

    $locations = $json_data['locations'];
    
    for ($i = 0; $i < count($locations); $i++) {
        $loc_ts = $locations[$i]['timestampMs'] / 1000;
        $loc_date = date("Y-m-d H:i:s", $loc_ts);

        $lat = $locations[$i]['latitudeE7'];
        $lng = $locations[$i]['longitudeE7'];
        $accuracy = $locations[$i]['accuracy'];

        $heading = "NULL";
        $verticalAccuracy = "NULL";
        $velocity = "NULL";
        $altitude = "NULL";

        if (array_key_exists('heading', $locations[$i])) {
            $heading = $locations[$i]["heading"];
        }
        if (array_key_exists('verticalAccuracy', $locations[$i])) {
            $verticalAccuracy = $locations[$i]["verticalAccuracy"];
        }
        if (array_key_exists('velocity', $locations[$i])) {
            $velocity = $locations[$i]["velocity"];
        }
        if (array_key_exists('altitude', $locations[$i])) {
            $altitude = $locations[$i]["altitude"];
        }
        
        $activities = $locations[$i]['activity'];

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

            if ($link->query($sql)) {
                echo "Successful";
            }
            else {
                echo "Database error.";
                exit(1);
            }
        }
    }
}
?>
