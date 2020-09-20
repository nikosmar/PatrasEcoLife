<?php
    $link = mysqli_connect("localhost", "nikosm", "1q2w3e4r", "site");

    $result = mysqli_query($link, "SELECT loc_ts, latitude, longitude, accuracy, heading, verticalAccuracy, velocity, altitude, activity_type, ts, confidence, userid FROM users, activities WHERE users.username = activities.username");

    switch ($_POST["filetype"]) {
        case 0:
            $filetype = ".csv";
            break;
        case 1:
            $filetype = ".xml";
            break;
        default:
            $filetype = ".json";
    }

    $filename = 'user_activities_' . date('Y-m-d_H-i-s') . $filetype;
    $filepath = "../userdata/" . $filename;

    if ($filetype == ".csv") {
        $fp = fopen($filepath, 'wb');

        foreach ($result as $row) {
            fputcsv($fp, $row);
        }

        fclose($fp);
    }
    elseif ($filetype == ".xml") {
        $xml = new SimpleXmlElement('<activities/>');

        while ($row = mysqli_fetch_assoc($result)) {
            $activity = $xml->addChild('activity');

            $loc_ts = $activity->addChild('timestamp', $row["loc_ts"]);
            $latitude = $activity->addChild('latitude', $row["latitude"]);
            $longitude = $activity->addChild('longitude', $row["longitude"]);
            $accuracy = $activity->addChild('accuracy', $row["accuracy"]);
            $heading = $activity->addChild('heading', $row["heading"]);
            $verticalAccuracy = $activity->addChild('verticalAccuracy', $row["verticalAccuracy"]);
            $velocity = $activity->addChild('velocity', $row["velocity"]);
            $altitude = $activity->addChild('altitude', $row["altitude"]);
            $activity_type = $activity->addChild('activity_type', $row["activity_type"]);
            $ts = $activity->addChild('activity_timestamp', $row["ts"]);
            $confidence = $activity->addChild('confidence', $row["confidence"]);
            $userid = $activity->addChild('userid', $row["userid"]);
        }

        $dom = new DOMDocument("1.0");
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        $fp = fopen($filepath, 'w');
        fwrite($fp, $dom->saveXML());
        fclose($fp);
    }
    else {
        $fp = fopen($filepath, 'w');
        $result_array = Array();
        
        foreach($result as $row) {
            $result_array[] = $row;
        }

        fwrite($fp, json_encode($result_array, JSON_PRETTY_PRINT));
        fclose($fp);
    }
    
    echo $filename;

    mysqli_free_result($result);
    mysqli_close($link);
?>
