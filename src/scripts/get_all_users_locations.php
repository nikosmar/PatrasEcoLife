<?php
    session_start();

    $link = mysqli_connect("localhost", "nikosm", "1q2w3e4r", "site");

    $year_from = $_POST["year_from"];
    $year_to = $_POST["year_to"];
    $month_from = $_POST["month_from"];
    $month_to = $_POST["month_to"];
    $day_from = $_POST["day_from"];
    $day_to = $_POST["day_to"];
    $ts_from = $_POST["ts_from"];
    $ts_to = $_POST["ts_to"];

    $selectedActivities = Array();
    $selectedActivities = $_POST["selectedActivities"];

    $activities = '';
    foreach($selectedActivities as $act) {
        $activities = $activities . 'OR ' . 'activity_type=' . '\'' . $act . '\' ';
    }

    $queryStr = "SELECT latitude AS lat, longitude AS lng, COUNT(*) AS cnt FROM activities WHERE 
    YEAR(ts) >= '$year_from' AND
    YEAR(ts) <= '$year_to' AND
    MONTH(ts) >= '$month_from' AND
    MONTH(ts) <= '$month_to' AND
    WEEKDAY(ts) >= '$day_from' AND
    WEEKDAY(ts) <= '$day_to' AND
    TIME(ts) >= '$ts_from' AND
    TIME(ts) <= '$ts_to' AND
    (activity_type = '' " .
    $activities
    . ") GROUP BY latitude, longitude ORDER BY COUNT(*) DESC";

    $result = mysqli_query($link, $queryStr);

    $result_array = Array();
    foreach($result as $row) {
        $result_array[] = $row;
    }

    echo json_encode($result_array);

    mysqli_free_result($result);
    mysqli_close($link);
?>
