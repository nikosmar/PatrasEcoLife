<?php
    session_start();
    
    $link = mysqli_connect("localhost", "nikosm", "1q2w3e4r", "site");

    $username = mysqli_real_escape_string($link, $_POST["username"]);
    $hashed_password = md5($_POST["password"]);

    $result = mysqli_query($link, "SELECT * FROM users WHERE username='$username' AND password='$hashed_password'");

    if (mysqli_num_rows($result) == 1) {
        while($row = mysqli_fetch_assoc($result)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['userid'] = $row["userid"];
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = $row["admin"];

            if ($_SESSION['user_type'] == 1) {
                header('Location: ../adminpanel.php');
            }
            else {
                header('Location: ../userpanel.php');
            }
        }
    }
    else {
        printf("wrong username or password.\n");
    }

    mysqli_free_result($result);
    mysqli_close($link);
?>
