<?php  include('../server/server.php'); 

if (isset($_SESSION['username'])) {
	header('location: ../client/home.php');
}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Project - Register</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>

<header class="main">
	<nav>
	<h1>Project</h1>
		<ul class="menu">
			<li><a href="./home.php">Home</a></li>
			<li><a href="./register.php" class="active">Register</a></li>
			<li><a href="./login.php">Sign In</a></li>
			<li><a href="#">About</a></li>
		</ul>
	</nav>
	
	<div style="text-align:center">
            <h1>Register</h1>
            <p>Complete the form below to register an account.</p>
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
						unset($_SESSION['errorMsg'])
					?>
				</div>
			<?php endif ?>
        <form action="../server/server.php" method="post">
			<br>
            <input pattern=".{3,}" required title="3 characters minimum." type="text" name="username" placeholder="Create a unique username."/>
			<br>
            <input required type="text" name="email" placeholder="Enter your email."/>
			<br>
            <input pattern=".{8,}" required title="8 characters minimum." type="password" name="password" placeholder="Create a password."/>
            <br>
            <button type="submit" name="save">REGISTER</button>
        </form>
	</div>
</header>

<?php $results = mysqli_query($db, "SELECT * FROM users"); ?>

<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>Username</th>
			<th>Password</th>
		</tr>
	</thead>
	
	<?php while ($row = mysqli_fetch_array($results)) { ?>
		<tr>
			<td><?php echo $row['id']; ?></td>
			<td><?php echo $row['username']; ?></td>
			<td><?php echo $row['password']; ?></td>
		</tr>
	<?php } ?>
</table>

</body>
</html>