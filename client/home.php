<?php

include('../server/server.php'); 

if (isset($_SESSION['userid'])) {

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
				<li><a href="./create.php">Create</a></li>
				<li><a href="./logout.php">Sign Out</a></li>
				<li><a href="#">About</a></li>
			</ul>

		</nav>

		<div style="text-align:center">
			<h2>Welcome, <?php echo $_SESSION['username']?>.</h2>
		</div>	

		<?php $results = mysqli_query($db, "SELECT * FROM forum_table"); ?>



			<table class="styled-table">
					<tr>
						<th>Forums</th>
					</tr>
				<?php while ($row = mysqli_fetch_array($results)) { ?>
					<tr>
						<td><a href="forum.php?id=<?php echo $row["id"]?>"><?php echo $row["name"]?></a></td>
					</tr>
				<?php } ?>

			</table>

	</header>
	</html>

	<?php

}else{
	header('location: ../client/login.php');
}

?>