<?php
    session_start();

    $conn = mysqli_connect("localhost", "nikosm", "1q2w3e4r", "site");
    
    if (!$conn){
            die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "DELETE FROM activities";
    $update = "UPDATE eco_score SET score = 0, total_moving_activities=0, latest_update=-1;";

   if (mysqli_query($conn, $sql) AND mysqli_query($conn, $update) ) {
            echo "deleted";
    }else{
        echo "Error deleting record: " . mysqli_error($conn);
    }

    mysqli_close($conn);
?>
