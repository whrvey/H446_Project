<?php  include('../server/server.php'); ?>

<!DOCTYPE html>
<html>
<head>
	<title>Project - Login</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>

<header class="main">
	<nav>
	<h1>Project</h1>
		<ul class="menu">
			<li><a href="./home.php">Home</a></li>
			<li><a href="./register.php">Register</a></li>
			<li><a href="./login.php" class="active">Sign In</a></li>
			<li><a href="#">About</a></li>
		</ul>
	</nav>

	<?php if (isset($_SESSION['successMsg'])): ?>
		<div class="success">
			<?php 
				echo $_SESSION['successMsg'];
				unset($_SESSION['successMsg']);
			?>
		</div>
	<?php elseif (isset($_SESSION['errorMsg'])): ?>
		<div class="error">
			<?php 
				echo $_SESSION['errorMsg'];
				unset($_SESSION['errorMsg']);
			?>
		</div>
	<?php endif ?>
		
	
	<div style="text-align:center">
            <h1>Sign In</h1>
            <p>Complete the form below to login to your account.</p>
        <form action="../server/server.php" method="post">
            <input type="text" name="username" placeholder="Enter your username."/>
            <input type="password" name="password" placeholder="Enter your password."/>
            
            <button type="submit" name="check">Sign In</button>
        </form>
	</div>
	
</header>

</body>
</html>