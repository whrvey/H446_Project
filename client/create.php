<?php  
	include('../server/server.php'); 
	
	if (isset($_SESSION['userid'])) {
	
	?>

	<!DOCTYPE html>
	<html>
	<head>
		<title>Project - Create</title>
		<link rel="stylesheet" type="text/css" href="../css/style.css">
	</head>
	<body>

	<header class="main">
		<nav>
		<h1>Project</h1>
			<ul class="menu">
				<li><a href="./home.php">Home</a></li>
				<li><a href="./create.php"class="active">Create</a></li>
				<li><a href="./logout.php">Sign Out</a></li>
				<li><a href="#">About</a></li>
			</ul>
		</nav>	
		
		<div style="text-align:center">
				<h1>Create a post</h1>
				<p>Complete the form below to create a post in a designated forum.</p>
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
				<input type="text" pattern=".{2,50}" required title="2 characters minimum." name="title" placeholder="Enter a title."/>
				<br>
				<textarea style="  width: 30%;height: 100px;" required minlength="2" maxlength="255" name="message" placeholder="Enter your message."></textarea>
				
				<!-- <input type="file" name="file"/> -->
				<br>
				<select required name="topic" id="topic"style="   border-top-style: solid;border-right-style: solid;border-left-style: solid;border-bottom-style: solid;box-sizing: border-box;width: 30%;height: 40px;padding: 10px;border-radius: 5px;border-color: rgb(211, 211, 211);border-width: 1px;display: block;margin: auto;">
					<option value="" disabled selected>Choose a topic.</option>
					<?php $results = mysqli_query($db, "SELECT * FROM forum_table"); ?>
					<?php while ($row = mysqli_fetch_array($results)) { ?>
						<option value=<?php echo $row["id"]?>><?php echo $row["name"]?></option>
					<?php } ?>
				</select>
				<br>
				<button type="submit" name="createpost">CREATE POST</button>
			</form>
		</div>
		
	</header>

	</body>
	</html>

	<?php

}else{
	header('location: ../client/login.php');
}

?>