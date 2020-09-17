<?php
    $link = mysqli_connect("localhost", "nikosm", "1q2w3e4r", "site");

    $username = $_POST["username"];
    $hashed_password = md5($_POST["password"]);
    $email = $_POST["email"];
    $firstname = $_POST["firstName"];
    $lastname = $_POST["lastName"];

    $userid = openssl_encrypt($email, "aes-128-cbc", $hashed_password);
    
    $sql = "INSERT INTO users (userid, username, password, email, firstname, lastname) VALUES (
        '$userid',
        '$username',
        '$hashed_password',
        '$email',
        '$firstname',
        '$lastname'
    )";

    $eco_query = "INSERT INTO eco_score (username, score, total_moving_activities, latest_update) VALUES ('$username', 0, 0, -1)";

    if ($link->query($sql) === TRUE) {
        if ($link->query($eco_query) === TRUE) {
            header('Location: ..');
        } else {
            echo "An error occured on user creation.<br>";
        }
    } else {
        echo "An error occured on user creation.<br>";
    }

    mysqli_close($link);
?>
