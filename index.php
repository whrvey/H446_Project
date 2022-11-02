<?php  include('server.php'); ?>

<!DOCTYPE html>
<html>
<head>
	<title>Signup Page</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<h3>Signup</h3>

<?php if (isset($_SESSION['message'])): ?>
	<div class="msg">
		<?php 
			echo $_SESSION['message']; 
			unset($_SESSION['message']);
		?>
	</div>
<?php endif ?>

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

<form method="post" action="server.php" >
		<div class="input-group">
			<label>Username</label>
			<input type="text" name="username" value="" placeholder = "Create a username.">
		</div>
		<div class="input-group">
			<label>Password</label>
			<input type="password" name="password" value="" placeholder = "Create a password.">
		</div>
		<br>
		<div class="input-group">
			<button class="btn" type="submit" name="save" >Register</button>
		</div>
</form>

</body>
</html>