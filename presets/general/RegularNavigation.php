<!DOCTYPE html>
<html>
<body>
<header class="main-header">
    <nav class="navbar navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a href="index.php" class="navbar-brand"><b>Felcraft</b></a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <i class="fa fa-bars"></i>
                </button>
            </div>

            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="palace.php">POINT PALACE</a></li>
                    <li><a href="store.php">ITEM SHOP</a></li>
                    <li><a href="sponsors.php">SPONSORS</a></li>
                    <li><a href="watch.php">WATCH</a></li>
                    <li><a href="aboutme.php">ABOUT ME</a></li>
                </ul>
            </div>

            <?php
            $id = $_COOKIE['USER'];

            include('sql/Accounts/Weber.php');
            include('sql/Accounts/Accounts.php');
            $account = new Accounts($id);
            $weber = new Weber($account->account_id);
            $weber->load_notifications();
            $weber->load_requests();

            if ($account != null) {
                $requests = $weber->requests;
                $notes = $weber->notifications;

                echo '
                         <div class="navbar-custom-menu">
                            <ul class="nav navbar-nav">
                        <li class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-users"></i>';

                if (count($requests) > 0) {
                    echo '<span class="label label-warning" > ' . count($requests) . '</span>';
                }

                echo '</a>
                            <ul class="dropdown-menu">
                                <li class="header">You have ' . count($requests) . ' new friend requests</li>
                                    <li>
                                        <ul class="menu" id="nav_friend">

                                        </ul>
                                    </li>
                                <li class="footer"><a href="#">View all</a></li>
                                    </ul>
                                </li>';

                echo '<li class="dropdown messages-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-envelope"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have 0 messages</li>
                                <li>
                                    <ul class="menu">
                                        <!--<li>
                                            <a href="#">
                                                <div class="pull-left">
\                                                    <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
                                                </div>
\                                                <h4>
                                                    Support Team
                                                    <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                                </h4>
\                                                <p>Why not buy a new awesome theme?</p>
                                            </a>
                                        </li>-->
\                                    </ul>
\                                </li>
                                <li class="footer"><a href="#">See All Messages</a></li>
                            </ul>
                        </li>';

                echo '
                        <li class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-bell"></i>';

                if (count($notes) > 0) {
                    echo '<span class="label label-warning" > ' . count($notes) . '</span>';
                }

                echo '</a>
                            <ul class="dropdown-menu">
                                <li class="header">You have ' . count($notes) . ' new notifications</li>
                                    <li>
                                        <ul class="menu" id="nav_notification">';

                echo '</ul>
                            </li>
                                <li class="footer"><a href="#">View all</a></li>
                            </ul>
                        </li>';

                echo '<li class="dropdown user user-menu">';

                echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
                echo '<img src="https://minotar.net/avatar/' . $account->username . '" class="user-image" alt="User Image"/>';
                echo '<span class="hidden-xs">' . $account->username . '</span>';
                echo '</a>';

                echo '<ul class="dropdown-menu">';
                echo '<li class="user-header">';

                echo '<img src="https://minotar.net/avatar/' . $account->username . '" class="img-circle" alt="User Image" />';

                $created_date = explode('-', explode(' ', $weber->joined)[0]);

                echo '<p>';
                echo $account->username . ' - ADD RANK';
                echo '<small>Member since ' . date('M', mktime(0, 0, 0, $created_date[1], 10)) . '.' . $created_date[2] . '.' . $created_date[0] . '</small>';
                echo '</p>';

                echo '</li>';

                echo '<li class="user-body">';

                echo '<div class="col-xs-4 text-center">';
                echo '<a href="#">Followers</a>';
                echo '</div>';

                echo '<div class="col-xs-4 text-center">';
                echo '<a href="#">Points</a>';
                echo '</div>';

                echo '<div class="col-xs-4 text-center">';
                echo '<a href="#">Friends</a>';
                echo '</div>';

                echo '</li>';
                echo '<li class="user-footer">';

                echo '<div class="pull-left">';
                echo '<a href="user.php?' . $account->username . '" class="btn btn-default btn-flat">Profile</a>';
                echo '</div>';

                /**$rank = $sql_handler->get_rank($byte->rank_id);
                if ($rank != null) {
                    if ($rank->rank_index >= 10) {
                        echo '<div class="col-xs-4 text-center">
                            <a href="../admin/dashboard/dashboard.php" class="btn btn-default btn-flat">Admin</a>
                            </div>';
                    }
                }**/

                echo '<div class="pull-right">';
                echo '<a href="../../logout.php" class="btn btn-default btn-flat">Sign out</a>';
                echo '</div>';

                echo '</li>';
            } else {
                echo '<div class="collapse navbar-collapse pull-right" id="navbar-collapse">
                                    <ul class="nav navbar-nav">
                                        <li><a href="../../login.php">LOGIN / REGISTER</a></li>
                                    </ul>
                                </div>';
            }
            ?>
            </ul>
            </li>
            </ul>
        </div>
        <!-- /.navbar-custom-menu -->
        </div>
        <!-- /.container-fluid -->
    </nav>
</header>
</body>
</html>