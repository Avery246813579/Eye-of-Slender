<?php
setcookie('LAST_PAGE', 'index.php', time() + 3000);
?>

<!DOCTYPE html>
<html>
<?php require_once(dirname(__FILE__) . '/presets/general/RegularHeader.php'); ?>

<body class="skin-blue layout-top-nav">
<div class="wrapper">
    <div class="content-wrapper">
        <div class="container">
            <br/>
            <br/>
            <br/>

            <div class="row">
                <div class="col-xs-9">
                    <div class="well">
                        <?php
                        include('sql/Accounts/Accounts.php');
                        include('sql/Forums/Post.php');
                        include('sql/Accounts/Weber.php');
                        include('sql/Forums/Forum.php');
                        include('util/ParseBBCode.php');

                        if (isset($_GET['id'])) {
                            /****************************************************
                             *
                             *  View Post
                             *
                             *****************************************************/

                            $post = new Post(0, 0, 0, 0, 0, 0, 0, 0, 0);
                            $post->get_post($_GET['id']);
                            $forum = new Forum(0, 0, 0, 0, 0);
                            $forum->get($post->forum_id);
                            $parse = new ParseBBCode();
                            $post->load_comments();
                            $comments = $post->comments;
                            $op = true;
                            $more = false;
                            $post->views = $post->views + 1;
                            $post->update();

                            if (isset($_GET['page'])) {
                                $page = $_GET['page'];
                                $p_start = (($page - 1) * 9);

                                if (count($comments) > $p_start) {
                                    $temp_array = array();
                                    $plus = 10;
                                    if ($page == 1) {
                                        $plus = 9;
                                    } else {
                                        $op = false;
                                    }

                                    for ($i = $p_start; $i < ($p_start + $plus); $i++) {
                                        if ($comments[$i] != null) {
                                            array_push($temp_array, $comments[$i]);
                                        }
                                    }

                                    if($comments[$p_start + $plus] != null){
                                        $more = true;
                                    }

                                    $comments = $temp_array;
                                } else {
                                    $temp_array = array();
                                    $plus = 10;
                                    if ($page == 1) {
                                        $plus = 9;
                                    } else {
                                        $op = false;
                                    }

                                    for ($i = 0; $i < $plus; $i++) {
                                        if ($comments[$i] != null) {
                                            array_push($temp_array, $comments[$i]);
                                        }
                                    }

                                    if($comments[$p_start + $plus] != null){
                                        $more = true;
                                    }

                                    $comments = $temp_array;
                                }
                            } else {
                                $temp_array = array();

                                for ($i = 0; $i < 9; $i++) {
                                    if ($comments[$i] != null) {
                                        array_push($temp_array, $comments[$i]);
                                    }
                                }

                                if($comments[9] != null){
                                    $more = true;
                                }

                                $comments = $temp_array;

                                $page = 1;
                            }

                            echo '
                            <div>
                                <div style="float: left; font-size: 20px; margin-top: 1px; margin-bottom: 3px" >
                                    <a href="forums.php">Forums</a> &raquo; <a href="forums.php?forum_id=' . $forum->forum_id . '">' . $forum->name . ' </a>&raquo; ' . $post->subject . '
                                </div>

                                <div class="pull-right">';
                            if ($page > 1) {
                                echo '<a href = "forums.php?id=' . $post->post_id . '&page=' . ($page-1) . '"><button class="btn btn-default btn-sm" ><i class="fa fa-chevron-left" ></i ></button ></a >';
                            }

                            if ($more) {
                                echo '<a href="forums.php?id=' . $post->post_id . '&page=' . ($page+1) . '"><button class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button></a>';
                            }

                            echo '
                                </div>

                            </div>

                            <table id = "example2" class="table table-bordered table-hover" >
                                <col width="18%">
                                <col width="2%">
                                <col width="80%">

                                <tbody>';

                            if ($op) {
                                echo '<tr>
                                <td>
                                <img src="https://minotar.net/bust/avery246813579/100.png">
                                <a href="">Avery246813579</a>
                                <small>Posts: 24</small><br>
                                <small>Reputation: 52</small>
                                </td>
                                <td></td>
                                <td>
                                <span style="word-break:break-all;">
                                ' . $parse->parse($post->body) . '
                                </span>
                                <br>
                                <br>
                                <div style="bottom: 0; float: left;"><p>Posted a moment ago</p></div>
                                <div style="bottom: 0; float: right;"><p>Likes: 5&nbsp;<i class="fa fa-thumbs-o-up"></i>&nbsp;<a href=""><i class="fa fa-edit"></i></a>&nbsp;&nbsp;<a href=""><i class="fa fa-remove"></i></a></p></div>
                                </td>
                                </tr>';
                            }

                            if (is_array($comments)) {
                                if (count($comments) > 0) {
                                    foreach ($comments as $values) {
                                        if ($values instanceof Comment) {
                                            $account = new Accounts($values->account_id);
                                            $weber = new Weber($account->account_id);
                                            $weber->load_posts();
                                            $weber->load_comments();
                                            echo '<tr >
                                                <td >
                                                <img src = "https://minotar.net/bust/' . $account->username . '/100.png" >
                                                <a href = "" > ' . $account->username . '</a >
                                                <small > Posts: ' . ($weber->posts + $weber->comments) . ' </small ><br >
                                                <small > Reputation: ' . $weber->reputation . '</small >
                                                </td >
                                                <td ></td >
                                                <td >
                                                <span style="word-break:break-all;">
                                                ' . $parse->parse($values->subject) . '
                                                </span>
                                                <br >
                                                <br >
                                                <div style = "bottom: 0; float: left;" ><p > Posted a moment ago </p ></div >
                                                <div id="like_div_' . $values->comment_id . '" style = "bottom: 0; float: right;" ><p > Likes: ' . $values->likes . '&nbsp;<i id="like_' . $values->comment_id . '" class="fa fa-thumbs-o-up" ></i >&nbsp;<a href = "" ><i class="fa fa-edit" ></i ></a >&nbsp;&nbsp;<a href = "" ><i class="fa fa-remove" ></i ></a ></p ></div >
                                                </td >
                                            </tr >

                                            <script>

                                            $(function() {
                                                $("#like_' . $values->comment_id . '").click(function() {
                                                    $("#like_div_' . $values->comment_id . '").load("/presets/forums/Like.php?id=' . $values->comment_id . '");
                                                });
                                            });

                                            </script>
                                            ';
                                        }
                                    }
                                }
                            }
                            echo '</tbody >
                            </table >';
                            if (isset($_COOKIE['USER'])) {
                                echo '
                                    <form action="actions/ForumAction.php" method="post">
	                                    <textarea id="text-area" style="height: 200px;" name="SUBJECT" placeholder="Type your comment here"></textarea>
                                        <button type="submit" class="btn btn-primary">Create New Comment</button>
                                        <input type="hidden" name="ID" value="' . $post->post_id . '">
                                        <input type="hidden" name="BACK" value="forums.php?id=' . $post->post_id . '">
                                        <input type="hidden" name="TYPE" value="CREATE_COMMENT">
                                    </form>
                                    ';
                            }

                        } else if (isset($_GET['forum_id'])) {
                            if(isset($_GET['create'])){
                                if (isset($_COOKIE['USER'])) {
                                    echo '
                                    <form action="actions/ForumAction.php" method="post">
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label for="TITLE">Title</label>
                                                <input type="text" class="form-control" name="TITLE" placeholder="Insert creative title here">
                                            </div>

                                            <div class="form-group">
                                                <label for="CONTENT">Content</label>
                                                <textarea class="textarea" id="text-area" name="CONTENT" placeholder="Place awesome content here"
                                                          style="width: 100%; height: 250px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                            </div>
                                        </div>

                                        <div class="box-footer">
                                            <button type="submit" class="btn btn-primary">Create Post</button>
                                        </div>

                                        <input type="hidden" name="FORUM_ID" value="' . $_GET['forum_id'] . '">
                                        <input type="hidden" name="TYPE" value="CREATE_POST">
                                    </form>
                                    ';
                                }
                            }else {

                                /****************************************************
                                 *
                                 *  View Posts
                                 *
                                 *****************************************************/
                                $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

                                if ($mysqli->connect_errno > 0) {
                                    die('Unable to connect to database [' . $mysqli->connect_error . ']');
                                }

                                $c_id = 0;
                                $stmt = $mysqli->prepare('SELECT * FROM Forums WHERE forum_id = ?');
                                $stmt->bind_param('i', $_GET['forum_id']);
                                $stmt->execute();
                                $stmt->store_result();

                                $forum = null;
                                if ($stmt->num_rows > 0) {
                                    $stmt->bind_result($forum_id, $category_id, $name, $description, $rank_id);

                                    include('sql/Forums/Forum.php');
                                    while ($stmt->fetch()) {
                                        $forum = new Forum($forum_id, $category_id, $name, $description, $rank_id);
                                    }
                                }

                                $mysqli->close();

                                echo '
                                <div style="text-align: center;">
                                <div style="float: left; font-size: 20px; margin-top: 12px;" >
                                    <a href="forums.php">Forums</a> &raquo; ' . $forum->name . '
                                </div>';

                                if(isset($_COOKIE['USER'])) {
                                    echo '<span style="display: inline-block; position: relative; margin-top: 10px;" >
                                                <a href="forums.php?forum_id=' . $forum->forum_id . '&create=1"><button class="btn btn-success btn-flat" type = "button" > Create Post!</button ></a>
                                        </span >';
                                }

                                echo '<div style="width: 300px; float: right;">
                                    <form action="" method="post">
                                        <div class="input-group margin">
                                            <input type="text" name="Test" class="form-control">
                                            <span class="input-group-btn">
                                                <button class="btn btn-info btn-flat" type="button">Search!</button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <hr style = "width: 100%; color: black; height: 1px; background-color:slategray; margin-bottom: 0px; margin-top: 5px;" />
                                <table id = "example2" class="table table-bordered table-hover" >
                                  <col width="75%">
                                  <col width="5%">
                                  <col width="5%">
                                  <col width="10%">
                                  <col width="5%">

                                    <thead >
                                    <tr >
                                        <th ><b > Forum Threads</b ></th >
                                        <th ><b > Replies</b ></th >
                                        <th ><b > Views</b ></th >
                                        <th ><b > Latest Post</b ></th >
                                        <td></td>
                                    </tr >
                                    </thead >
                                    <tbody >';

                                if ($forum instanceof Forum) {
                                    $forum->load_posts();
                                    $posts = $forum->posts;

                                    if (is_array($posts)) {
                                        if (count($posts) > 0) {
                                            foreach ($posts as $values) {
                                                if ($values instanceof Post) {
                                                    $account = new Accounts($values->account_id);
                                                    $values->load_comments();
                                                    $comments = 1;
                                                    if(is_array($values->comments)){
                                                        $comments = $comments + count($values->comments);
                                                    }

                                                    echo '<tr>
                                                    <td>
                                                        <a href="forums.php?id=' . $values->post_id . '">' . $values->subject . '</a>
                                                        by ' . $account->username . '
                                                    </td>
                                                    <td>
                                                        ' . $comments . '
                                                    </td>
                                                    <td>
                                                        ' . $values->views . '
                                                    </td>
                                                    <td>
                                                        <a href="' . $account->account_id . '">' . $account->username . '</a><br>
                                                        3 hours ago
                                                    </td>
                                                    <td>
                                                        <img width = "30" height = "30" src = "https://minotar.net/avatar/' . $account->username . '" >
                                                    </td>
                                                    </tr>
                                                    ';
                                                }
                                            }
                                        }
                                    }
                                }
                                echo '</tbody>
                            </table>
                        ';
                            }
                        } else {
                            /****************************************************
                             *
                             *  View Forums
                             *
                             *****************************************************/

                            echo
                            '<div>
                                <div style="float: left; font-size: 20px; margin-top: 12px;" >
                                    Welcome to the Felcraft Forums!!
                                </div>

                                <div style="width: 300px; float: right;">
                                    <form action="" method="post">
                                        <div class="input-group margin">
                                            <input type="text" name="Test" class="form-control">
                                            <span class="input-group-btn">
                                                <button class="btn btn-info btn-flat" type="button">Search!</button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>

                                <hr style = "width: 100%; color: black; height: 1px; background-color:slategray; margin-bottom: 0px; margin-top: 5px;" />
                                <table id = "example2" class="table table-bordered table-hover" >
                                <col width="60%">
                                  <col width="5%">
                                  <col width="5%">
                                  <col width="25%">
                                  <col width="5%">

                                    <thead >
                                    <tr>
                                        <th ><b > Forum</b ></th >
                                        <th ><b > Threads</b ></th >
                                        <th ><b > Posts</b ></th >
                                        <th ><b > Latest</b ></th >
                                        <th ></th >
                                    </tr >
                                    </thead >
                                    <tbody >';

                            $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

                            if ($mysqli->connect_errno > 0) {
                                die('Unable to connect to database [' . $mysqli->connect_error . ']');
                            }

                            $c_id = 0;
                            $stmt = $mysqli->prepare('SELECT * FROM Forums WHERE category_id = ?');
                            $stmt->bind_param('i', $c_id);
                            $stmt->execute();
                            $stmt->store_result();

                            $accounts = null;
                            if ($stmt->num_rows > 0) {
                                $stmt->bind_result($forum_id, $category_id, $name, $description, $rank_id);
                                $forums = array();

                                while ($stmt->fetch()) {
                                    array_push($forums, new Forum($forum_id, $category_id, $name, $description, $rank_id));
                                }
                            }

                            $mysqli->close();

                            if (is_array($forums)) {
                                if (count($forums) > 0) {
                                    foreach ($forums as $values) {
                                        if ($values instanceof Forum) {
                                            $values->get_last_post();
                                            $last_post = $values->last_post;

                                            echo '
                                <tr>
                                        <td >
                                            <a href = "forums.php?forum_id=' . $values->forum_id . '" > ' . $values->name . '</a ><br >
                                            <small > ' . $values->description . '</small >
                                        </td >
                                        <td >
                                35
                                        </td >
                                        <td >
                                125
                                        </td >
                                ';
                                            if ($last_post instanceof Post) {
                                                $account = new Accounts($last_post->account_id);
                                                echo '
                                <td>
                                                <div >
                                                    <a href = "forums.php?id=' . $last_post->post_id . '"> ' . $last_post->subject . ' </a ><br >
                                By <a href = "" > ' . $account->username . '</a >
                                                </div >
                                            </td >

                                            <td >
                                                <img width = "30" height = "30" src = "https://minotar.net/avatar/' . $account->username . '" >
                                            </td >
                                        </tr >
                                ';
                                            } else {

                                            }
                                        }
                                    }
                                }
                            }


                            echo '
                            </tbody>
                            </table>
                        ';
                        }
                        ?>
                    </div>
                </div>

                <div class="col-xs-3">
                    <div class="well">
                        <table id="example2" class="table table-bordered table-hover">
                            <col width="10%">
                            <col width="90%">
                            <tbody>
                            <?php
                            $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

                            if ($mysqli->connect_errno > 0) {
                                die('Unable to connect to database [' . $mysqli->connect_error . ']');
                            }

                            $c_id = 0;
                            $stmt = $mysqli->prepare('SELECT * FROM Posts ORDER BY post_id DESC LIMIT 5');
                            $stmt->execute();
                            $stmt->store_result();

                            $posts = array();
                            if ($stmt->num_rows > 0) {
                                $stmt->bind_result($post_id, $forum_id, $account_id, $subject, $body, $views, $likes, $dislikes, $posted);

                                while ($stmt->fetch()) {
                                    array_push($posts, new Post($post_id, $forum_id, $account_id, $subject, $body, $views, $likes, $dislikes, $posted));
                                }
                            }

                            $mysqli->close();

                            echo '<div style="float: left; font-size: 20px; margin-top: 12px;" >
                                Latest Posts
                                </div>

                                <hr style = "width: 100%; color: black; height: 1px; background-color:slategray; margin-bottom: 0px; margin-top: 5px;" />';


                            if (count($posts) > 0) {
                                foreach ($posts as $values) {
                                    if ($values instanceof Post) {
                                        $account = new Accounts($values->account_id);
                                        echo '<tr style="margin-top: -3px">
                                                    <td><img width = "30" height = "30" src = "https://minotar.net/avatar/' . $account->username . '" ></td>
                                                    <td>
                                                    <a href="forums.php?id=' . $values->post_id . '">' . $values->subject . '</a><br>
                                                    <span style="margin-top: -5px;"><small>Just now by <a href="">' . $account->username . '</a></small></span>
                                                    </td>
                                                </tr>';
                                    }
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>


                            <div class="col-xs-3">
                    <div class="well">
                        <table id="example2" class="table table-bordered table-hover">
                            <col width="10%">
                            <col width="90%">
                            <tbody>
                            <?php
                            $mysqli = new mysqli("127.0.0.1", "root", "", 'EOS') or die('Kittens');

                            if ($mysqli->connect_errno > 0) {
                                die('Unable to connect to database [' . $mysqli->connect_error . ']');
                            }

                            $c_id = 0;
                            $stmt = $mysqli->prepare('SELECT * FROM Comments ORDER BY comment_id DESC LIMIT 5');
                            $stmt->execute();
                            $stmt->store_result();

                            $comments = array();
                            if ($stmt->num_rows > 0) {
                                $stmt->bind_result($comment_id, $post_id, $account_id, $subject, $likes, $dislikes);

                                include('sql/Forums/Comment.php');
                                while ($stmt->fetch()) {
                                    array_push($comments, new Comment($comment_id, $post_id, $account_id, $subject, $likes, $dislikes));
                                }
                            }

                            $mysqli->close();

                            echo '<div style="float: left; font-size: 20px; margin-top: 12px;" >
                                Latest Comments
                                </div>

                                <hr style = "width: 100%; color: black; height: 1px; background-color:slategray; margin-bottom: 0px; margin-top: 5px;" />';


                            if (count($comments) > 0) {
                                foreach ($comments as $values) {
                                    if ($values instanceof Comment) {
                                        $account = new Accounts($values->account_id);
                                        $post = new Post(0, 0, 0, 0, 0, 0, 0, 0, 0);
                                        $post->get_post($values->post_id);
                                        echo '<tr style="margin-top: -3px">
                                                    <td><img width = "30" height = "30" src = "https://minotar.net/avatar/' . $account->username . '" ></td>
                                                    <td>
                                                    <a href="forums.php?id=' . $values->post_id . '">' . $post->subject . '</a><br>
                                                    <span style="margin-top: -5px;"><small>Just now by <a href="">' . $account->username . '</a></small></span>
                                                    </td>
                                                </tr>';
                                    }
                                }
                            }

                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once(dirname(__FILE__) . '/presets/general/RegularFooter.php'); ?>
</div>

<?php require_once(dirname(__FILE__) . '/presets/general/RegularExternals.php'); ?>
<script src=plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src=bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src=plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src=plugins/fastclick/fastclick.min.js'></script>
<script src=dist/js/app.min.js" type="text/javascript"></script>
<script src=dist/js/demo.js" type="text/javascript"></script>
<!-- Load jQuery  -->
<script src="http://cdn.wysibb.com/js/jquery.wysibb.min.js"></script>
<link rel="stylesheet" href="http://cdn.wysibb.com/css/default/wbbtheme.css"/>

<!-- Init WysiBB BBCode editor -->
<script>
    $(function () {
        var wbbOpt = {
            allButtons: {
                bold: {
                    hotkey: "ctrl+shift+b"
                },
                strike: {
                    hotkey: "ctrl+shift+s"
                },
                underline: {
                    hotkey: "ctrl+shift+u"
                },
                italic: {
                    hotkey: "ctrl+shift+i"
                }
            }
        }

        $("#text-area").wysibb(wbbOpt);
        $("#post_content").htmlcode();

        $("#showmore").click(function() {
            $("#more_posts").load("/presets/profiles/ShowMore.php?times=");
        });
    })

</script>
</body>
</html>