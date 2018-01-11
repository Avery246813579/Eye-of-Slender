<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <?php
    require_once(dirname(__FILE__) . '/FileHandler.php');
    $file_handler = new FileHandler();

    echo '<title>' . $file_handler->getValue('info.txt', 'FIRST_PART') . $file_handler->getValue('info.txt', 'SECOND_PART') . ' | User</title>';
    ?>

    <link rel="shortcut icon" href="icon.png">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
    <link href="../../dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css"/>
    <link href="../../dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css"/>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
</head>

<body class="skin-blue layout-top-nav">
<div class="wrapper">
    <?php require_once(dirname(__FILE__) . '/presets/GlobalNav.php'); ?>

    <div class="content-wrapper">
        <div class="container">
            <br/>
            <br/>
            <br/>

            <?php
            $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            require_once(dirname(__FILE__) . '/SqlHandler.php');
            require_once(dirname(__FILE__) . '/twitch/TwitchHandler.php');
            $twitch_handler = new TwitchHandler();
            $sql_handler = new SqlHandler();

            if (strpos($actual_link, '?')) {
                $user_split = explode('?', $actual_link);

                $unknown_account = $sql_handler->get_account($user_split[1]);
                if ($unknown_account != null) {
                    $account = $unknown_account;
                }
            } else {
                if (isset($_COOKIE['USER'])) {
                    $unknown_account = $sql_handler->get_account($_COOKIE['USER']);
                    if ($unknown_account != null) {
                        $account = $unknown_account;
                    }
                }
            }

            if ($account == null) {
                echo '
                      <div class="callout callout-info lead">
                        <h4>Reminder!</h4>
                        <p>
                    Avery is currently busy developing other sections of the site! He will make a find user page soon!
                        </p>
                    </div> ';
            } else {
                $name = $account->username;
                $byte_user = $sql_handler->get_byte_user($account->account_id);

                if ($byte_user != null) {
                    $user = $twitch_handler->get_user_using_token($byte_user->access_token);

                    echo '
            <div class="row">
                <div class="col-md-4">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Profile Detail</h3>
                        </div>

                        <div class="box-body">
                            <img src="' . $user->logo . '">
                            <h4><strong>' . $user->display_name . '</strong></h4>
                            <p><i class="fa fa-map-marker"></i> Milky Way</p>
                            <h5>
                                About me
                            </h5>
                            <p>
                                ' . $user->bio . '
                            </p>

                            <hr>

                            <div class="col-md-4">';
                    $posts = $sql_handler->get_post_on_wall($account->account_id);

                    if ($posts != null) {
                        echo '<h5><strong>' . count($posts) . '</strong><br \> Posts</h5>';
                    } else {
                        echo '<h5><strong>0</strong><br \> Posts</h5>';
                    }
                    echo '</div>

                            <div class="col-md-4">';
                    $friends = $sql_handler->get_friends($account->account_id);

                    if ($friends != null) {
                        echo '<h5><strong>' . count($friends) . '</strong><br \> Friends</h5>';
                    } else {
                        echo '<h5><strong>0</strong><br \> Friends</h5>';
                    }
                    echo '</div>

                            <div class="col-md-4">
                                <h5><strong>' . $account->reputation . '</strong><br \> Reputation</h5>
                            </div>

                            <div class="col-md-6">
                                <button type="button" class="btn btn-primary btn-sm btn-block"><i class="fa fa-envelope"></i> Send Message</button>
                            </div>

                            <div class="col-md-6">
                                <button type="button" class="btn btn-default btn-sm btn-block"><i class="fa fa-user-plus"></i> Add Friend</button>
                            </div>
                        </div>
                    </div>
                </div>';

                    if ($_COOKIE['USER'] == $account->username) {
                        echo '
                    <div class="col-md-8">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">New Post</h3>
                            </div>

                            <form action="AccountAction.php" role="form" method="post">
                                <div class="form-group">
                                  <textarea class="form-control" rows="3" name="Post" placeholder="Enter ..."></textarea>
                                </div>

                                <div class="box-footer">
                                    <button type="submit" class="btn btn-info pull-right">Post</button>
                                </div>

                                <input type="hidden" name="User" value="' . $account->account_id . '">
                                <input type="hidden" name="Wall" value="' . $account->account_id . '">
                                <input type="hidden" name="Type" value="NewPost">
                                <input type="hidden" name="ReturnPage" value="user.php?' . explode('?', "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]")[1] . '">
                            </form>
                        </div>
                    </div>
                        ';

                    }

                    echo '
                    <div class="col-md-8">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Posts</h3>
                            </div>

                            <div class="box-body" id="user_posts">
                                                <i class="fa fa-spinner fa-spin"></i>';

                    echo '

                        </div>

                        </div>
                    </div>
                </div>
                </div>';
                }
            }
            ?>
        </div>
    </div>

    <?php require_once(dirname(__FILE__) . '/presets/GlobalFooter.php'); ?>
</div>

<?php require_once(dirname(__FILE__) . '/presets/ExternalLinking.php'); ?>
</body>
</html>

