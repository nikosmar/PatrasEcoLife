<?php
    $link = mysqli_connect("localhost", "nikosm", "1q2w3e4r", "site");

    $username = $_POST["username"];
    $hashed_password = md5($_POST["password"]);

    $result = mysqli_query($link, "SELECT * FROM users WHERE username='$username' AND password='$hashed_password'");

    if (mysqli_num_rows($result) == 1) {
        printf("successful login.\n");
    }
    else {
        printf("wrong username or password.\n");
    }

    mysqli_free_result($result);

    mysqli_close($link);
?>