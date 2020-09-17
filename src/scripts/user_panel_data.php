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

	$sql = mysqli_query($conn, "SELECT YEAR(ts), MONTH(ts),activity_type, COUNT(*) AS cnt FROM activities WHERE username = '$session_username' AND activity_type <> 'TILTING' AND activity_type <> 'STILL'AND ts > DATE_SUB(now(), INTERVAL 12 MONTH) GROUP BY YEAR(ts), MONTH(ts), activity_type ; ");

	$data = array();
	if (mysqli_num_rows($sql) > 0) {
		foreach ($sql as $row) {
			$data[] = $row;
		}
	}

	if (empty($data)){
  		echo json_encode($data);
	}else{
		echo json_encode($data);
	}
	
	mysqli_free_result($sql);
    mysqli_close($conn);
 ?> 
