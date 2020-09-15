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

	$firstRegister = mysqli_query($conn, "SELECT date(ts) FROM activities WHERE username = '$session_username' ORDER BY date(ts) ASC LIMIT 1;");

	$data = array();
	if (mysqli_num_rows($firstRegister) > 0) {
		foreach ($firstRegister as $row) {
			array_push($data, $row);
		}
	}

	$lastRegister = mysqli_query($conn, "SELECT date(ts) FROM activities WHERE username = '$session_username' ORDER BY date(ts) DESC LIMIT 1;");

	if (mysqli_num_rows($lastRegister) > 0) {
		foreach ($lastRegister as $row) {
			array_push($data, $row);
		}
	}

	

	echo json_encode($data);

	mysqli_free_result($sql);
    mysqli_close($conn);
 ?> 