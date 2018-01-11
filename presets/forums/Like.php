<?php
$comment = new Comment(0, 0, 0, 0, 0, 0);
$comment->get($_GET['id']);
$comment->likes = $comment->likes + 1;
$comment->update();

header("Location: forum.php?id=");