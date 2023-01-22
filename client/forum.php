<?php

include('../server/server.php'); 

session_start();

if (isset($_SESSION['username'])) {

	?>

	<!DOCTYPE html>
	<html>
	<head>
		<title>Project - Forums</title>
		<link rel="stylesheet" type="text/css" href="../css/style.css">
	</head>
	<body>

	<header class="main">
		<nav>
		<h1>Project</h1>
			<ul class="menu">
				<li><a href="./home.php" class="active">Home</a></li>
				<li><a href="./register.php">Register</a></li>
				<li><a href="./logout.php">Sign Out</a></li>
				<li><a href="#">About</a></li>
			</ul>

		</nav>

		<?php

		if(isset($_GET['id'])&&is_numeric($_GET['id'])){
			$id = $_GET['id'];
		} else {
			die("ERROR - ID has not been set and/or is not numeric.");
		}

		$idCheck = $db->query("SELECT * FROM forum_table WHERE id='$id' ");
		if($idCheck->num_rows !==1) {
			die("ERROR - ID does not exist.");
		}

		$row = $idCheck->fetch_object();
		$sql = "SELECT post_id, post_title FROM forum_post WHERE forum_id=? AND post_type='o' ";
		if ($query = $db->prepare($sql)) {
			$query->bind_param('s', $id);
			$query->bind_result($post_id, $post_title);
			$query->execute();
			$query->store_result();

		}else{
			echo $db->error;
		}


		?>

		<div id="container">
			<table width="80%" align="center">
			<h2>Forum: <?= $row->name?></h2>
				<?php if ($query->num_rows != 0):?>
				<?php while ($query->fetch()):?>
					<tr>
						<td><a href="view_post.php?pid=<?=$post_id?>&id=<?=$id?>"><?= $post_title?></a></td>
					</tr>
				<?php endwhile;?>
				<?php else:?>
					<tr>
						<td><h2>No Posts Found</h2></td>
					</tr>
				<?php endif;?>
			</table>
		</div>
		

	<?php

}else{
	header('location: ../client/login.php');
}

?>