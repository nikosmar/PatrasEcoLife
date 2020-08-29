<?php
    $link = mysqli_connect("localhost", "nikosm", "1q2w3e4r", "site");

    $username = $_POST["username"];
    $hashed_password = md5($_POST["password"]);
    $email = $_POST["email"];
    $firstname = $_POST["firstName"];
    $lastname = $_POST["lastName"];

    $userid = openssl_encrypt($email, "aes-128-cbc", $hashed_password);
    
    $sql = "INSERT INTO users VALUES (
        '$userid',
        '$username',
        '$hashed_password',
        '$email',
        '$firstname',
        '$lastname'
    )";

    if ($link->query($sql) === TRUE) {
        header('Location: ..');
    } else {
        echo "Error: " . $sql . "<br>" . $link->error;
    }

    mysqli_close($link);
?>
