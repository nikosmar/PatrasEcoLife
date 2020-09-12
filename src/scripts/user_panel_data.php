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

	$sql = mysqli_query($conn, "SELECT activity_type, ts FROM activities WHERE username = '$session_username' AND activity_type <> 'TILTING' AND activity_type <> 'STILL' AND ts >  DATE_SUB(now(), INTERVAL 12 MONTH)  ORDER BY ts DESC; ");

	$data = array();
	if (mysqli_num_rows($sql) > 0) {
		foreach ($sql as $row) {
			$data[] = $row;
			$logfile = db_con . ".log";
			// $data = $username . ' ' . $date . ' ' . $type . ' ' . $lat . ' ' . $lng;
			$var_str = var_export($data , true);
			file_put_contents($logfile, $var_str);

		}
	}
	else {
	    printf("First you must upload your data.\n");
	}

	echo json_encode($data);

	mysqli_free_result($sql);
    mysqli_close($conn);
 ?> 
