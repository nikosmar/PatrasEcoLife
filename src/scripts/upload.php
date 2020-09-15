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
        $lat = $locations[$i]['latitudeE7'];
        $lng = $locations[$i]['longitudeE7'];
        
        $activities = $locations[$i]['activity'];

        for ($j = 0; $j < count($activities); $j++) {
            $type = $activities[$j]['activity'][0]['type'];
        
            $ts = $activities[$j]['timestampMs'];
            $ts = $ts / 1000;
            $date = date("Y-m-d H:i:s", $ts);

            $sql = "INSERT INTO activities (username, ts, activity_type, latitude, longitude) VALUES (
                '$username',
                '$date',
                '$type',
                $lat,
                $lng
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
