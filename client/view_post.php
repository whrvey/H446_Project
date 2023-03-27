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
        }

        $row = $topicPost->fetch();

        $sql1 = "SELECT forum_post.post_id, forum_post.post_body, forum_post.post_author, users.username FROM forum_post, users WHERE forum_post.original_id=? AND forum_post.post_type='r' AND forum_post.post_author = users.id ORDER BY post_id DESC";
        if ($query1 = $db->prepare($sql1)) {
            $query1->bind_param('s', $pid);
            $query1->bind_result($reply_id, $reply_body, $reply_author, $reply_username);
            $query1->execute();
            $query1->store_result();

        }

        $sql2 = "SELECT users.id AS 'uid', forum_post.post_id AS 'pid', users.username AS 'username'
            FROM users, forum_post WHERE users.id = ?";

            $stmt = $db->prepare($sql2);
            $stmt->bind_param('s', $post_author);
            $stmt->execute();
            
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
        
        ?>

        

        <div style="text-align:center;">
            <table class="styled-table">
                <tr>
					<th>Post</th>
				</tr>
                <tr>
                    <td><b><?=$post_title?> • @<?=$row['username']?></b></td>
                </tr>
                <tr>
                    <td><?php echo $post_body ?></td>
                </tr>
                <tr>
                <form action="../server/server.php" method="post">
                    <input type="hidden" name="pid" value="<?php echo $pid; ?>"/>
                    <input type="hidden" name="fid" value="<?php echo $id; ?>"/>
                    <td><textarea placeholder="Enter a reply..." required minlength="2" maxlength="40" rows="2" name="reply-text"></textarea> <br> <button type="submit" name="post-reply">REPLY</button> </td>
                </form>
                </tr>
            </table>
            <br>
            <table class="styled-table">
            <?php if ($query1->num_rows != 0): ?>
                <tr>
					<th>Replies</th>
				</tr>
                <?php while ($query1->fetch()): 
                    if ($_SESSION['username'] === $reply_username) {
						$sameUser = true;
					} ?>
                    <form action="../server/server.php" method="post">
                        <tr>                   
                            <td><?= $reply_body ?> • @<?= $reply_username ?>
                                <?php if ($sameUser) {
                                    ?> <input type="hidden" name="pid" value="<?php echo $reply_id; ?>"/> <?php
                                    echo '<button class="mini" name="post-delete" type="submit">DELETE</button>';
                                } ?>
                            </td>
                        </tr>
                    </form>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td>
                        <h4>There are no replies yet...</h4>
                    </td>
                </tr>
            <?php endif; ?>
            </table>


        </div>


        <?php


}else{
	header('location: ../client/login.php');
}

?>