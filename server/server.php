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

		$username = checkUsername("username"); // check username length
		if (!$username) {
			$_SESSION['errorMsg'] = "Your username is invalid. (Must be at least 3 characters.)"; 
			header('location: ../client/register.php');
			return;
		}
		$email = checkEmail("email"); // check if valid email
		if (!$email) {
			$_SESSION['errorMsg'] = "Your email is invalid. Please try again."; 
			header('location: ../client/register.php');
			return;
		}
		$password = checkPassword("password"); // check password length
		if (!$password){
			$_SESSION['errorMsg'] = "Your password is invalid. (Must be at least 8 characters.)"; 
			header('location: ../client/register.php');
			return;
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
		$username = $_POST['username'];
		$password = $_POST['password'];

		$sql = "SELECT * FROM users WHERE username='$username' LIMIT 1"; // get password hash from database
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
						$_SESSION['userid'] = $row['id'];
						$_SESSION['username'] = $row['username'];
						header('location: ../client/home.php');
					} else {
						$_SESSION['errorMsg'] = "Your password is incorrect!";
						header('location: ../client/login.php');
					}
				}
			} else {
				$_SESSION['errorMsg'] = "Your username is not valid!";
				header('location: ../client/login.php');
			}
		}
	}
		
	if (isset($_POST['createpost'])) {
		$title = $_POST['title'];
		$message = $_POST['message'];
		$topic = $_POST['topic'];
		$userid = $_SESSION['userid'];

		$imageId = null;

		# file handling

		if (!empty($_FILES["file"]["name"])) {
			$file = $_FILES['file'];
			$fileName = $_FILES['file']['name'];
			$fileTempName = $_FILES['file']['tmp_name'];

			$fileExt = explode('.',$fileName);
			$fileActualExt = strtolower(end($fileExt));
			$allowExt = array('jpg','png','jpeg','gif','pdf');

			$fileNameNew = $userid."-".uniqid().".".$fileActualExt;
			$fileDestination = '../uploads/'.$fileNameNew;

			if(!in_array($fileActualExt, $allowExt)){
				$_SESSION['errorMsg'] = "Only JPG, JPEG, PNG, GIF, & PDF files are allowed.";
				header('location: ../client/create.php');
				return;
			}

			if ($_FILES["file"]["size"] > 10000000) { #10,000,000
				$_SESSION['errorMsg'] = "File is too large (Over 50 MB).";
				header('location: ../client/create.php');
				return;
			}
			
			if(!move_uploaded_file($fileTempName, $fileDestination)) {
				echo "Not uploaded because of error #".$_FILES["file"]["error"];
				return;
			}
		}

		if (!$topic) {
			$_SESSION['errorMsg'] = "You did not include a topic for your post.";
			header('location: ../client/create.php');
			return;
		}

		$postQuery = "INSERT INTO forum_post (post_title, post_body, forum_id, post_author, file_name) 
		VALUES('$title', '$message', '$topic', '$userid', '$fileNameNew')";
		mysqli_query($db, $postQuery);


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
	} elseif (isset($_POST['post-delete'])) {
		$pid = $_POST['pid'];
		$userid = $_SESSION['userid'];

		$query = "SELECT post_author, forum_id, original_id, file_name FROM forum_post WHERE post_author='$userid' AND post_id='$pid'";
		$result = mysqli_query($db, $query);
		$row = mysqli_fetch_array($result);

		if ($row['post_author']===$userid) {
			$query = "DELETE FROM forum_post WHERE post_author='$userid' AND post_id='$pid'";
			mysqli_query($db, $query);
		}

		if ($row['file_name']) { # delete file from uploads folder to prevent memory leak
			$file_name = $row['file_name'];
			unlink("../uploads/".$file_name);
		}

		if ($row['original_id']) { # check if original id exits (if so, this means the post is a reply)
			header('location: ../client/view_post.php?pid='.$row['original_id'].'&id='.$row['forum_id'].'');
		} else {
			header('location: ../client/forum.php?id='.$row['forum_id'].'');
		}
		
	}
