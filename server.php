<?php 
	include_once "../dbase_conn.php";
	include_once "./validation.php";
	include_once "./console_log.php";

	session_start();

	if (! isset($db)) {
		echo "Warning! Not accessible.";
		die("Cannot access database.");
	} else {
		echo "Database is active.";
	}

	// initialize variables
	$username = "";
	$password = "";

	if (isset($_POST['save'])) {

		$username = checkEmail("username");
		$password = checkPassword("password", 8);

		console_log($username);
		console_log($password);

		if ($username) {
			if ($password) {
				$user_check_query = "SELECT * FROM users WHERE username='$username' 
				LIMIT 1";

				$result = mysqli_query($db, $user_check_query);
				$user = mysqli_fetch_assoc($result);

				
				if (!$user) {
					
					$query = "INSERT INTO users (username, password) 
					VALUES('$username', '$password')";
					mysqli_query($db, $query);
					$_SESSION['message'] = "Account created!"; 
					header('location: index.php');

				} else {
				$_SESSION['message'] = "Username already exists!"; 
				header('location: index.php');
			}
				
			} else {
				$_SESSION['message'] = "Invalid password.".$password; 
				header('location: index.php');
			}
		} else {
			$_SESSION['message'] = "Invalid email."; 
			header('location: index.php');
	}
}