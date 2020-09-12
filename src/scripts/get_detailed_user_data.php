<?php
    session_start();

    $link = mysqli_connect("localhost", "nikosm", "1q2w3e4r", "site");

    $username = $_SESSION["username"];

    $result = mysqli_query($link, "SELECT activity_type, COUNT(*) AS cnt from activities where username='$username' GROUP BY activity_type ORDER BY COUNT(*)");

    $sum = 0;

    foreach($result as $row) {
        $sum += $row["cnt"];
    }

    foreach($result as $row) {
        $type = $row["activity_type"];
        $perc = $row["cnt"] * 100 / $sum;
        $perc = round($perc, 1);
        echo "<td>" . $type . " : " . $perc . "\n</td>";
    }

    mysqli_free_result($result);
    mysqli_close($link);
?>
