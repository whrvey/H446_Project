<?php

include('../server/server.php'); 

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

        if(isset($_GET['pid'])&&is_numeric($_GET['pid'])&&isset($_GET['id'])&&is_numeric($_GET['id'])){
			$pid = $_GET['pid'];
            $id = $_GET['id'];
		} else {
			die("ERROR - IDs have not been set and/or are not numeric.");
		}

        $postCheck = $db->query("SELECT * FROM forum_post WHERE post_id='$pid' AND forum_id='$id' AND post_type='o'")->num_rows;
        if ($postCheck === 0) {
            die("ERROR - No forum post found.");
        }

        $sql = "SELECT post_title, post_body, post_author FROM forum_post WHERE post_id=? AND forum_id=? AND post_type='o'";
        if ($topicPost=$db->prepare($sql)) {
            $topicPost->bind_param('ss',$pid,$id);
            $topicPost->bind_result($post_title, $post_body, $post_author);
            $topicPost->execute();
            $topicPost->store_result();
        } else {
            echo "ERROR - ".$db->error;
            exit();
        }

        $row = $topicPost->fetch();
        
        ?>

        <div style="text-align:center;">
            <table class="styled-table">
                <tr>
					<th>Posts</th>
				</tr>
                <tr>
                    <td><b><?=$post_title?> • @<?=$post_author?></b></td>
                </tr>
                <tr>
                    <td><?php echo $post_body ?></td>
                </tr>
            </table>
            <br>
            <!--<button type="submit" name="reply">REPLY</button>-->
        </div>


        <?php


}else{
	header('location: ../client/login.php');
}

?>