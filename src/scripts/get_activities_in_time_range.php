<?php
    session_start();

    $link = mysqli_connect("localhost", "nikosm", "1q2w3e4r", "site");

    $username = $_SESSION["username"];

    $result = mysqli_query($link, "SELECT activity_type, COUNT(*) AS cnt FROM activities WHERE username='$username' GROUP BY activity_type ORDER BY COUNT(*)");

    $sum = 0;
    $result_array = Array();
    
    foreach($result as $row) {
        $sum += $row["cnt"];
    }

    foreach($result as $row) {
        $type = $row["activity_type"];
        $perc = $row["cnt"] * 100 / $sum;
        $perc = round($perc, 1);
        $result_array[] = array("type" => $type, "percentage" => $perc);
    }

    echo json_encode($result_array);

    mysqli_free_result($result);
    mysqli_close($link);
?>
