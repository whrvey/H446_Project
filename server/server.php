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

	function createUser($db, $username, $email, $password){ // add user to users table
		$hash = password_hash($password, PASSWORD_DEFAULT);

		$query = "INSERT INTO users (username, email, password) 
		VALUES('$username', '$email', '$hash')";
		mysqli_query($db, $query);
		
	}

	// initialize variables
	$username="";
	$email = "";
	$password = "";

	if (isset($_POST['save'])) { // registration

		$username = checkUsername("username", 3); // check username length
		if (!$username) {
			$_SESSION['errorMsg'] = "Your username is invalid. (Must be at least 3 characters.)"; 
			header('location: ../client/register.php');
		}
		$email = checkEmail("email"); // check if valid email
		if (!$email) {
			$_SESSION['errorMsg'] = "Your email is invalid. Please try again."; 
			header('location: ../client/register.php');
		}
		$password = checkPassword("password", 8); // check password length
		if (!$password){
			$_SESSION['errorMsg'] = "Your password is invalid. (Must be at least 8 characters.)"; 
			header('location: ../client/register.php');
		}

		// validation checks complete

		$mail_check_query = "SELECT * FROM users WHERE email='$email' OR username='$username' 
		LIMIT 1";

		$result = mysqli_query($db, $mail_check_query);
		$mail = mysqli_fetch_assoc($result);

		if (!$mail) { // account does not exist

			createUser($db, $username, $email, $password);
			$_SESSION['successMsg'] = "Your account has been created successfully!" ;

			header('location: ../client/login.php');

		} else { // account exists
			$_SESSION['errorMsg'] = "Your chosen email or username already exist! Please choose another one."; 
			header('location: ../client/register.php');
		}
	}
				
			
	if (isset($_POST['check'])) { // login
		$email = $_POST['email'];
		$password = $_POST['password'];

		$sql = "SELECT * FROM users WHERE email='$email' LIMIT 1"; // get password hash from database
		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_array($result);
		$value = $row['password'];

		if ($result) {
			if (mysqli_num_rows($result) > 0) { // if email is not found
				if (!$value) { // if user does not exist
					$_SESSION['errorMsg'] = "User not found.";
				} else {
					$verify = password_verify($password, $value); // check hash with password input
					if ($verify == 1) {
						$_SESSION['successMsg'] = "Your password is correct!";
						$_SESSION['userid'] = $row['id'];
						$_SESSION['username'] = $row['username'];
						header('location: ../client/home.php');
					} else {
						$_SESSION['errorMsg'] = "Your password is incorrect!";
						header('location: ../client/login.php');
					}
				}
			} else {
				$_SESSION['errorMsg'] = "Your email is not valid!";
				header('location: ../client/login.php');
			}
		}
	}
		
	if (isset($_POST['createpost'])) {
		$title = $_POST['title'];
		$message = $_POST['message'];
		$topic = $_POST['topic'];
		$userid = $_SESSION['userid'];

		$query = "INSERT INTO forum_post (post_title, post_body, forum_id, post_author) 
		VALUES('$title', '$message', '$topic', '$userid')";
		mysqli_query($db, $query);

		header('location: ../client/forum.php?id='.$topic.'');
		
	} elseif (isset($_POST['post-reply'])) {

		$body = $_POST['reply-text'];
		$author = $_SESSION['userid'];
		$pid = $_POST['pid'];
		$fid = $_POST['fid'];
		
		$query = "INSERT INTO forum_post (post_body, post_author, post_type, original_id, forum_id) 
		VALUES('$body', '$author', 'r', '$pid', '$fid')";
		mysqli_query($db, $query);

		header('location: ../client/view_post.php?pid='.$pid.'&id='.$fid.'');
		
		/*

		$query = "INSERT INTO forum_post (post_body, forum_id, post_author, post_type) 
		VALUES('$title', '$message', '$topic', '$userid')";
		mysqli_query($db, $query);

		header('location: ../client/home.php');
		*/
	}
