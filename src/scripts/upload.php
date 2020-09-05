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
    update_table($fileSpecs->filename);
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

function update_table($name) {
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
?>
