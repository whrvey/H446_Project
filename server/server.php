<?php 
	include_once "dbase_conn.php";
	include_once "validation.php";
	include_once "console_log.php";

	session_start();

	if (! isset($db)) {
		echo "Warning! Not accessible.";
		die("Cannot access database.");
	} else {
		//echo "Database is active.";
	}

	// initialize variables
	$email = "";
	$password = "";

	if (isset($_POST['save'])) {

		$email = checkEmail("email");
		$password = checkPassword("password", 8);

		if ($email) {
			if ($password) {

				$user_check_query = "SELECT * FROM users WHERE username='$email' 
				LIMIT 1";

				$result = mysqli_query($db, $user_check_query);
				$user = mysqli_fetch_assoc($result);

				$hash = password_hash($password, PASSWORD_DEFAULT);

				if (!$user) {
					
					$query = "INSERT INTO users (username, email, password) 
					VALUES('$username', '$email', '$hash')";
					mysqli_query($db, $query);

					$_SESSION['successMsg'] = "Your account has been created successfully!" ;
					/*$_SESSION['message'] = password_verify("12345678", $hash); */
					/* 1 = true null = false */

					header('location: ../client/register.php');

				} else {
				$_SESSION['errorMsg'] = "Your chosen username already exists! Please choose another one."; 
				header('location: ../client/register.php');
			}
				
			} else {
				$_SESSION['errorMsg'] = "Your password is invalid. (Must be at least 8 characters.)"; 
				header('location: ../client/register.php');
			}
		} else {
			$_SESSION['errorMsg'] = "Your email is invalid. Please try again."; 
			header('location: ../client/register.php');
		}
	} elseif (isset($_POST['check'])) {
		$email = $_POST['email'];
		$password = $_POST['password'];

		$sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_array($result);
		$value = $row['password'];

		if (!$value) {
			$_SESSION['errorMsg'] = "User not found.";
		} else {
			$verify = password_verify($password, $value);
			if ($verify == 1) {
				$_SESSION['successMsg'] = "Your password is correct!";
				$_SESSION['username'] = $row['username'];
			} else {
				$_SESSION['errorMsg'] = "Your password is incorrect!";
			}
		}

		/*$_SESSION['message'] = password_verify($password, $getHash);*/
		header('location: ../client/login.php');
	}
	