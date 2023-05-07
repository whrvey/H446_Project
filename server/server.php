<?php 

	# Importing Libraries

	include_once "dbase_conn.php";
	include_once "validation.php";
	include_once "console_log.php";

	# Start the session.

	session_start();

	# Checks if database is accessible.

	if (! isset($db)) {
		echo "Warning! Not accessible.";
		die("Cannot access database.");
	} else {
		//echo "Database is active.";
	}

	# createUser function - called to handle SQL insertion.

	function createUser($db, $username, $email, $password){ // add user to users table
		$hash = password_hash($password, PASSWORD_DEFAULT);

		$query = "INSERT INTO users (username, email, password) 
		VALUES('$username', '$email', '$hash')";
		mysqli_query($db, $query);
		
	}

	# Initialize Variables

	$username="";
	$email = "";
	$password = "";

	# Save Registration Details  - Called when save button is clicked.

	if (isset($_POST['save'])) {

		$username = checkUsername("username"); # Check the username length.
		if (!$username) {
			# If checkUsername returns false, return.
			$_SESSION['errorMsg'] = "Your username is invalid. (Must be at least 3 characters.)"; 
			header('location: ../client/register.php');
			return;
		}
		$email = checkEmail("email"); # Check if the email is valid.
		if (!$email) {
			# If checkEmail returns false, return.
			$_SESSION['errorMsg'] = "Your email is invalid. Please try again."; 
			header('location: ../client/register.php');
			return;
		}
		$password = checkPassword("password"); # Check the password length.
		if (!$password){
			# If checkPassword returns false, return.
			$_SESSION['errorMsg'] = "Your password is invalid. (Must be at least 8 characters.)"; 
			header('location: ../client/register.php');
			return;
		}

		# Validation checks are complete - Check the user.
		# SQL Handling

		$mail_check_query = "SELECT * FROM users WHERE email='$email' OR username='$username' 
		LIMIT 1";

		$result = mysqli_query($db, $mail_check_query);
		$mail = mysqli_fetch_assoc($result);

		if (!$mail) {
			# The account does not exist, create the user.
			createUser($db, $username, $email, $password);
			$_SESSION['successMsg'] = "Your account has been created successfully!" ;

			header('location: ../client/login.php');

		} else {
			# The account exists, return.
			$_SESSION['errorMsg'] = "Your chosen email or username already exist! Please choose another one."; 
			header('location: ../client/register.php');
		}
	}
	
	# Check Login Details - Called when check button is clicked.
			
	if (isset($_POST['check'])) {

		# Variables

		$username = $_POST['username'];
		$password = $_POST['password'];

		# SQL Handling

		$sql = "SELECT * FROM users WHERE username='$username' LIMIT 1"; // get password hash from database
		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_array($result);
		$value = $row['password'];

		if ($result) {
			# If the email is not found, return.
			if (mysqli_num_rows($result) > 0) {
				# If the user does not exist, return.
				if (!$value) {
					$_SESSION['errorMsg'] = "User not found.";
					header('location: ../client/login.php');
				} else {
					$verify = password_verify($password, $value); // Check hash with password input.
					if ($verify == 1) {
						# If the password does match the hash, redirect to the homepage and begin the session.
						$_SESSION['userid'] = $row['id'];
						$_SESSION['username'] = $row['username'];
						header('location: ../client/home.php');
					} else {
						# If the password does not match the hash, return.
						$_SESSION['errorMsg'] = "Your password is incorrect!";
						header('location: ../client/login.php');
					}
				}

			} else {
				# If the username does not exist in the users table, return.
				$_SESSION['errorMsg'] = "Your username is not valid!";
				header('location: ../client/login.php');
			}
		}
	}

	# Create Posts - Called when createpost button is clicked.
		
	if (isset($_POST['createpost'])) {

		# Variables

		$title = $_POST['title'];
		$message = $_POST['message'];
		$topic = $_POST['topic'];
		$userid = $_SESSION['userid'];

		$imageId = null;

		# File handling
		# If files have been submitted, continue.

		if (!empty($_FILES["file"]["name"])) {

			# Variables

			$file = $_FILES['file'];
			$fileName = $_FILES['file']['name'];
			$fileTempName = $_FILES['file']['tmp_name'];

			# Gather file extentions for validation.

			$fileExt = explode('.',$fileName);
			$fileActualExt = strtolower(end($fileExt));
			echo($fileActualExt);
			$allowExt = array('jpg','png','jpeg','gif','pdf','doc','docx','ppt', 'pptx', 'pptm',);

			# Create a unique ID for the file so it can be located later on, prevents overwriting.

			$fileNameNew = $userid."-".uniqid().".".$fileActualExt;
			$fileDestination = '../uploads/'.$fileNameNew;

			# If the file is not in the array, return.

			if(!in_array($fileActualExt, $allowExt)){
				$_SESSION['errorMsg'] = "Only JPG, JPEG, PNG, GIF, DOC, PPT & PDF files are allowed.";
				header('location: ../client/create.php');
				return;
			}

			# If the file is over 5 MB, return.

			if ($_FILES["file"]["size"] > 5000000) { #5,000,000
				$_SESSION['errorMsg'] = "File is too large (Over 5 MB).";
				header('location: ../client/create.php');
				return;
			}
			
			if(!move_uploaded_file($fileTempName, $fileDestination)) {
				echo "Not uploaded because of error #".$_FILES["file"]["error"];
				return;
			}
		}

		# If there isn't a topic within the submission data, return.

		if (!$topic) {
			$_SESSION['errorMsg'] = "You did not include a topic for your post.";
			header('location: ../client/create.php');
			return;
		}

		# Insert post data into forum_post. No errors past this point (dealt with above).

		$postQuery = "INSERT INTO forum_post (post_title, post_body, forum_id, post_author, file_name) 
		VALUES('$title', '$message', '$topic', '$userid', '$fileNameNew')";
		mysqli_query($db, $postQuery);


		# Redirects to the forum page.
		header('location: ../client/forum.php?id='.$topic.'');

	# Replying to Posts - Called when post-reply button is clicked.
		
	} elseif (isset($_POST['post-reply'])) {

		# Variables

		$body = $_POST['reply-text'];
		$author = $_SESSION['userid'];
		$pid = $_POST['pid'];
		$fid = $_POST['fid'];

		# SQL Query Handling
		# Inserts post reply data into forum_post table
		
		$query = "INSERT INTO forum_post (post_body, post_author, post_type, original_id, forum_id) 
		VALUES('$body', '$author', 'r', '$pid', '$fid')";
		mysqli_query($db, $query);

		# Redirects page to the original post.

		header('location: ../client/view_post.php?pid='.$pid.'&id='.$fid.'');

	# Deleting Posts - Called when delete button is clicked.

	} elseif (isset($_POST['post-delete'])) {
		# Variables
		$pid = $_POST['pid'];
		$userid = $_SESSION['userid'];

		# SQL Query Handling
		# Select the post the user is attempting to delete.
		$query = "SELECT post_author, forum_id, original_id, file_name FROM forum_post WHERE post_author='$userid' AND post_id='$pid'";
		$result = mysqli_query($db, $query);
		$row = mysqli_fetch_array($result);

		# If the post belongs to the user, delete it.
		if ($row['post_author']===$userid) {
			$query = "DELETE FROM forum_post WHERE post_author='$userid' AND post_id='$pid'";
			mysqli_query($db, $query);
		}

		if ($row['file_name']) { # Delete file from uploads folder to prevent memory leak.
			$file_name = $row['file_name'];
			unlink("../uploads/".$file_name);
		}

		if ($row['original_id']) { # Check if original id exits (if so, this means the post is a reply).
			# Redirect to the full post (if post contains an original id)
			header('location: ../client/view_post.php?pid='.$row['original_id'].'&id='.$row['forum_id'].'');
		} else {
			# Redirect to the forum page (if the post does not contain an original id)
			header('location: ../client/forum.php?id='.$row['forum_id'].'');
		}
		
	}
