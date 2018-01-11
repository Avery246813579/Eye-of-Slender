<?php
$back = "forum.php";
if(isset($_POST['BACK'])){
    $back = $_POST['BACK'];
}

if(isset($_COOKIE['USER'])){
    if(isset($_POST['TYPE'])){
        $type = $_POST['TYPE'];

        if($type == "CREATE_COMMENT"){
            if(isset($_POST['ID']) && isset($_POST['SUBJECT'])){
                include('../sql/Forums/Comment.php');
                $comment = new Comment(0,0,0,0,0,0);
                $comment->create($_POST['ID'], $_COOKIE['USER'], $_POST['SUBJECT'], 0, 0);
            }
        }

        if($type == "CREATE_POST"){
            if(isset($_POST['CONTENT']) && isset($_POST['TITLE'])){
                include('../sql/Forums/Post.php');

                $time = new DateTime($date, new DateTimeZone('America/Los_Angeles'));
                $time->setTimezone(new DateTimeZone('America/New_York'));
                $time_result = $time->format('Y-m-d H:i:s');

                $post = new Post(0,0,0,0,0,0,0,0,0);
                $post->create($_POST['FORUM_ID'], $_COOKIE['USER'], $_POST['TITLE'], $_POST['CONTENT'], 0, 0, 0, $time_result);
                include('../sql/Accounts/Weber.php');
                include('../sql/Accounts/Accounts.php');
                $account = new Accounts($_COOKIE['USER']);
                $weber = new Weber($account->account_id);
                $weber->load_last_post();
                $last_post = $weber->last_post;

                if($last_post instanceof Post) {
                    $back = 'forums.php?id=' . $last_post->post_id;
                }
            }
        }
    }
}

header("Location: ../" . $back);

