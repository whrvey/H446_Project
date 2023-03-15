<?php

include('../server/server.php');
#include('../server/console_log.php');

if (isset($_SESSION['userid'])) {

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
					<li><a href="./create.php">Create</a></li>
					<li><a href="./logout.php">Sign Out</a></li>
					<li><a href="#">About</a></li>
				</ul>

			</nav>

			<?php

			if (isset($_GET['id']) && is_numeric($_GET['id'])) {
				$id = $_GET['id'];
			} else {
				die("ERROR - ID has not been set and/or is not numeric.");
			}

			$idCheck = $db->query("SELECT * FROM forum_table WHERE id='$id' ");
			if ($idCheck->num_rows !== 1) {
				die("ERROR - ID does not exist.");
			}

			$row = $idCheck->fetch_object();
			$sql = "SELECT post_id, post_title, post_author FROM forum_post WHERE forum_id=? AND post_type='o' ORDER BY post_id DESC";
			if ($query = $db->prepare($sql)) {
				$query->bind_param('s', $id);
				$query->bind_result($post_id, $post_title, $post_author);
				$query->execute();
				$query->store_result();

			}

			?>

			<div style="text-align:center">

				<h2>Forum:
					<?= $row->name ?>
				</h2>
				<?= $row->description ?>
				<br></br>
				<table class="styled-table">

					<?php if ($query->num_rows != 0): ?>
						<tr>
							<th>Posts</th>
						</tr>
						<?php while ($query->fetch()):

							$sql2 = "SELECT username FROM users WHERE id=".$post_author;
							$result = mysqli_query($db, $sql2); 
							$row = mysqli_fetch_assoc($result) ?>

							<tr>
								<td><a href="view_post.php?pid=<?= $post_id ?>&id=<?= $id ?>"><?= $post_title ?></a> • @<?= $row["username"] ?></td>
							</tr>
						<?php endwhile; ?>
					<?php else: ?>
						<tr>
							<td>
								<h2>No Posts Found</h2>
							</td>
						</tr>
					<?php endif; ?>

				</table>
			</div>

			<?php

} else {
	header('location: ../client/login.php');
}

?>