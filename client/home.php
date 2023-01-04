<?php

session_start();

if (isset($_SESSION['id'])) {

	?>

	<!DOCTYPE html>
	<html>
	<head>
		<title>Project - Home</title>
		<link rel="stylesheet" type="text/css" href="../css/style.css">
	</head>
	<body>

	<header class="main">
		<nav>
		<h1>Project</h1>
			<ul class="menu">
				<li><a href="./home.php" class="active">Home</a></li>
				<li><a href="./register.php">Register</a></li>
				<li><a href="./login.php">Sign In</a></li>
				<li><a href="#">About</a></li>
			</ul>

		</nav>

		<div style="text-align:center">
			<h2>Welcome</h2>
			<p>Your user ID is: <?php echo $_SESSION['id']; ?> </p>
		</div>	
	</header>
	</html>

	<?php

}else{
	header('location: ../client/login.php');
}

?>