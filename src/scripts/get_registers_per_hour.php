<?php
    session_start();
    
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
        header('Location: ..');
    }


    $session_username = $_SESSION ['username'];


    // Create connection
    $conn = mysqli_connect("localhost", "nikosm", "1q2w3e4r", "site");
    // Check connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }

    $sql = mysqli_query($conn, "SELECT hour(ts), COUNT(activity_type) AS cnt FROM activities GROUP BY hour(ts);");

    $data = array();
    if (mysqli_num_rows($sql) > 0) {
        foreach ($sql as $row) {
            $data[] = $row;
        }
    }

    echo json_encode($data);

    mysqli_free_result($sql);
    mysqli_close($conn);
 ?> 