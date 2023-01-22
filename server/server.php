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
	$username="";
	$email = "";
	$password = "";

	if (isset($_POST['save'])) {

		$username = checkUsername("username", 3);
		$email = checkEmail("email");
		$password = checkPassword("password", 8);

		if ($username) {
			if ($email) {
				if ($password) {

					$mail_check_query = "SELECT * FROM users WHERE email='$email' OR username='$username' 
					LIMIT 1";

					$result = mysqli_query($db, $mail_check_query);
					$mail = mysqli_fetch_assoc($result);

					$hash = password_hash($password, PASSWORD_DEFAULT);


					if (!$mail) {
						
						$query = "INSERT INTO users (username, email, password) 
						VALUES('$username', '$email', '$hash')";
						mysqli_query($db, $query);

						$_SESSION['successMsg'] = "Your account has been created successfully!" ;
						/*$_SESSION['message'] = password_verify("12345678", $hash); */
						/* 1 = true null = false */

						header('location: ../client/register.php');

					} else {
					$_SESSION['errorMsg'] = "Your chosen email or username already exist! Please choose another one."; 
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
		} else {
			$_SESSION['errorMsg'] = "Your username is invalid. (Must be at least 3 characters.)"; 
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