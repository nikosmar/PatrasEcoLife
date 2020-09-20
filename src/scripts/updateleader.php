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

	$rank = mysqli_query($conn, "SET @rank:=0;");
	$usersRank = mysqli_query($conn, "SELECT @rank:=@rank+1 AS rank, firstname, lastname, score FROM users, eco_score WHERE users.username=eco_score.username ORDER BY score DESC;");

	$user = mysqli_query($conn, "SELECT firstname, lastname, score FROM users, eco_score WHERE users.username=eco_score.username AND eco_score.username='$session_username';");

	$user_check = array();
	if (mysqli_num_rows($user) > 0) {
		foreach ($user as $row) {
			$user_check[] = $row;
		}
	}

	$data = array();
	if (mysqli_num_rows($usersRank) > 0) {
		foreach ($usersRank as $row){
			$data[] = $row;
		}
	}

	$leaderBoard = array();
	$data["user"] = false;
	$count = 0;
	for ($i = 0; $i < sizeof($data); $i++) {
		if ($count < 3) {
			if ($data[$i]["firstname"] == $user_check[0]["firstname"] AND $data[$i]["lastname"] == $user_check[0]["lastname"] AND $data[$i]["score"]==$user_check[0]["score"]) {
				$data[$i]["user"] = true;
			}
			else {
				$data[$i]["user"] = false;
			}

			$firstname = $data[$i]["firstname"];
			$lastname = substr($data[$i]["lastname"], 0, 1);
			$name = $firstname . ' ' .$lastname .'.';
			unset($data[$i]["lastname"]);
			$data[$i]["firstname"] = $name;
			$data[$i]["name"] = $data[$i]["firstname"];
			unset($data[$i]["firstname"]);
			array_push($leaderBoard, $data[$i]);
			
			$count++;
		}
		else {
			// $count++;
			if ($data[$i]["firstname"]==$user_check[0]["firstname"] AND $data[$i]["lastname"]==$user_check[0]["lastname"] AND $data[$i]["score"]==$user_check[0]["score"]){
				$firstname = $data[$i]["firstname"];
				$lastname = substr($data[$i]["lastname"], 0, 1);
				$name = $firstname . ' ' .$lastname .'.';
				unset($data[$i]["lastname"]);
				$data[$i]["firstname"] = $name;
				$data[$i]["name"] = $data[$i]["firstname"];
				unset($data[$i]["firstname"]);
				array_push($leaderBoard, $data[$i]);
				$data[$i]["user"] = true;
				break;
			 }
		}
	}


	echo json_encode($leaderBoard);

	mysqli_free_result($rank);
	mysqli_free_result($usersRank);
	mysqli_free_result($user);

    mysqli_close($conn);
 ?>
 