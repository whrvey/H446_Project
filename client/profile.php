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

            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                $id = $_GET['id'];
            } else {
                die("ERROR - IDs have not been set and/or are not numeric.");
            }

            $userCheck = $db->prepare("SELECT * FROM users WHERE id=?");
            $userCheck->bind_param("i", $id);
            $userCheck->execute();
            
            $result = $userCheck->get_result();
            
            if ($result->num_rows === 0) {
                die("ERROR - No user found.");
            }
            
            $row = $result->fetch_assoc();


            $postQuery = $db->prepare("SELECT * FROM forum_post WHERE post_author=?");
            $postQuery->bind_param("i", $id);
            $postQuery->execute();
            
            $postResult = $postQuery->get_result();
            ?>

            <h2 style="text-align: center;">Account: <?php echo ($row['username']); ?></h2>


            <div style="text-align:center;">
                <table class="styled-table">
                    <tr>
                        <th>User Posts</th>
                    </tr>
                    <?php if ($postResult->num_rows != 0): ?>
                        <?php while ($postRow = $postResult->fetch_assoc()): 
                            
                            $usernameQuery = "SELECT username FROM users WHERE id=" . $postRow["post_author"];
							$usernameResult = mysqli_query($db, $usernameQuery);
							$usernameRow = mysqli_fetch_assoc($usernameResult);

                            $postTitle = $postRow["post_title"];

                            if($postTitle === null){
                                $postTitle = "Reply";
                                $postRow["post_id"] = $postRow['original_id'];
                            }
                            
                            ?>
                            <tr>
                                <td>
                                    <a href="view_post.php?pid=<?= $postRow["post_id"] ?>&id=<?= $postRow["forum_id"] ?>"><?= $postTitle ?></a> • <a href=profile.php?id=<?=$postRow["post_author"]?>>@<?= $usernameRow["username"]?></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td>
                                <h4>There are no posts yet...</h4>
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
