<?php

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
    header('Location: ../index.html');
}

if(isset($_POST['submit'])){
    $name       = $_FILES['fileToUpload']['name'];  
    $temp_name  = $_FILES['fileToUpload']['tmp_name'];
    $location = '../uploads/';
    valid_file_type($name);
    $path = check_duplicate_file($location, $name);
    if(isset($name) and !empty($name)){
        if(move_uploaded_file($temp_name, $path)){
            update_table($name);
            echo 'File uploaded successfully!';
        }
    } else {
        echo 'You should select a file to upload!';
    }
}

function valid_file_type($name){
    $extension = end(explode(".", $name));
    if ($extension != "json"){
        echo "File provided is not json. Please provide a.json file.";
        exit(1);
    }
}

function check_duplicate_file($location, $file){
    $index = 1;
    $copies = "";
    $filename = implode(".", array_slice(explode(".", $file), 0, -1));
    $extension = end(explode(".", $file));
    $file = "$location$filename$copies.$extension";
    while (file_exists($file)){
        $copies = "($index)";
        $file = "$location$filename$copies.$extension";
        $index++;
    }
    return $file;
}

function update_table($name){
    $link = mysqli_connect("localhost", "nikosm", "1q2w3e4r", "site");
    $username = $_SESSION["username"];
    $sql = "INSERT INTO upload (username, file_path) VALUES (
        '$username',
        '$name'
    )";

    if ($link->query($sql)){
        echo "Successful";
    }
    else{
        echo "Database error.";
        exit(1);
    }
}

?>
