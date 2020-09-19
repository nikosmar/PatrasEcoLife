<?php
    session_start();

    $link = mysqli_connect("localhost", "nikosm", "1q2w3e4r", "site");

    $username = $_SESSION["username"];

    $activity_types = mysqli_query($link, "SELECT DISTINCT activity_type AS 'type' from activities WHERE username='$username' ORDER BY activity_type ASC");

    $result_array = Array();

    foreach($activity_types as $row) {
        $activity_type = $row["type"];
        $timeQuery = mysqli_query($link, "SELECT COUNT(activity_type), DATE_FORMAT(ts,'%H:00') AS hour FROM activities WHERE activity_type='$activity_type' AND username='$username' GROUP BY hour ORDER BY COUNT(activity_type) DESC LIMIT 1");
        
        $timeOfDay = mysqli_fetch_array($timeQuery, MYSQLI_ASSOC);

        $dayQuery = mysqli_query($link, "SELECT COUNT(activity_type), DAYNAME(ts) AS day FROM activities WHERE activity_type='$activity_type' AND username='$username' GROUP BY day ORDER BY COUNT(activity_type) DESC LIMIT 1");

        $dayOfWeek = mysqli_fetch_array($dayQuery, MYSQLI_ASSOC);
    
        $result_array[] = array("type" => $activity_type, "time" => $timeOfDay["hour"], "day" => $dayOfWeek["day"]);
    }

    echo json_encode($result_array);

    mysqli_free_result($activity_types);
    mysqli_free_result($timeQuery);
    mysqli_free_result($dayQuery);

    mysqli_close($link);
?>
