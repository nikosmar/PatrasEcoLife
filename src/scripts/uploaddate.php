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

	$sql = mysqli_query($conn, "SELECT date(upload_date) FROM upload WHERE username = '$session_username' ORDER BY date(upload_date) DESC LIMIT 1;");

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