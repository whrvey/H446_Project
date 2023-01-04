<?php  
	include('../server/server.php'); 
?>

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
	
	<div style="text-align:center">
            <h1>Sign In</h1>
            <p>Complete the form below to sign in to your account.</p>
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
        <form action="../server/server.php" method="post">
			<br>
            <input type="text" name="username" placeholder="Enter your username."/>
			<br>
            <input type="password" name="password" placeholder="Enter your password."/>
            <br>
            <button type="submit" name="check">SIGN IN</button>
        </form>
	</div>
	
</header>

</body>
</html>