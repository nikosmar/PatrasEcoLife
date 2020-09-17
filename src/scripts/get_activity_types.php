<?php
    $link = mysqli_connect("localhost", "nikosm", "1q2w3e4r", "site");

    $result = mysqli_query($link, "SELECT DISTINCT activity_type FROM activities");

    $result_array = Array();

    foreach($result as $row) {
        $type = $row["activity_type"];

        $result_array[] = array("type" => $type);
    }

    echo json_encode($result_array);

    mysqli_free_result($result);
    mysqli_close($link);
?>
