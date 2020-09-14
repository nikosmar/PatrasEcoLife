<?php
    session_start();

    $link = mysqli_connect("localhost", "nikosm", "1q2w3e4r", "site");
    $username = $_SESSION["username"];

    $result = mysqli_query($link, "SELECT latitude AS lat, longitude AS lng, COUNT(*) AS cnt FROM activities WHERE username='$username' GROUP BY latitude, longitude ORDER BY COUNT(*) DESC");

    $result_array = Array();
    foreach($result as $row) {
        $result_array[] = $row;
    }

    echo json_encode($result_array);

    mysqli_free_result($result);
    mysqli_close($link);
?>
