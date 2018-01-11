<?php
if(isset($_COOKIE['USER'])){
    header("Location: index.php");
}else {
    if (!empty($_POST['minecraft_user']) AND !empty($_POST['minecraft_pass']) AND !empty($_POST['email'])) {
        if (isset($_POST['terms'])) {
            include("MCAuth.class.php");
            $MCAuth = new MCAuth();
            if ($MCAuth->authenticate($_POST['minecraft_user'], $_POST['minecraft_pass']) == TRUE) {
                $msg = "Eye of slender confirmation code\nHere is the code" . rand(99999, 1000000);
                $msg = wordwrap($msg, 70);
                $headers = "From: webmaster@eyeofslender.com";
                //mail($_POST['email'], "Eye of Slender Confirmation", $msg, $headers);

                include("sql/Accounts/Accounts.php");
                $account = new Accounts($MCAuth->account['id']);

                if ($account->account_id == null) {
                    $account->create($MCAuth->account['username'], $MCAuth->account['id'], 'default', 0, 0, 0, '', '');
                }
                include("sql/Accounts/Weber.php");
                $weber = new Weber($account->account_id);

                if ($weber->weber_id == null) {
                    $time = new DateTime($date, new DateTimeZone('America/Los_Angeles'));
                    $time->setTimezone(new DateTimeZone('America/New_York'));
                    $time_result = $time->format('Y-m-d H:i:s');

                    if (getenv('HTTP_CLIENT_IP'))
                        $ip_address = getenv('HTTP_CLIENT_IP');
                    else if (getenv('HTTP_X_FORWARDED_FOR'))
                        $ip_address = getenv('HTTP_X_FORWARDED_FOR');
                    else if (getenv('HTTP_X_FORWARDED'))
                        $ip_address = getenv('HTTP_X_FORWARDED');
                    else if (getenv('HTTP_FORWARDED_FOR'))
                        $ip_address = getenv('HTTP_FORWARDED_FOR');
                    else if (getenv('HTTP_FORWARDED'))
                        $ip_address = getenv('HTTP_FORWARDED');
                    else if (getenv('REMOTE_ADDR'))
                        $ip_address = getenv('REMOTE_ADDR');
                    else
                        $ip_address = 'UNKNOWN';

                    $weber->create($account->account_id, 'NOT-CONFIRMED', $time_result, $time_result, 0, $ip_address, 1);
                }

                setcookie('USER', $account->account_id, time() + 63072000, '/');
                header("Location: index.php");
                //echo '<img src="https://minotar.net/avatar/' . $MCAuth->account['username'] . '">';
            } else {
                echo '<pre>';
                print_r($MCAuth->autherr);
                echo '</pre>';
            }
        } else {
            echo '<pre>';
            print_r('Do you accept the terms?! Maybe you should check it!');
            echo '</pre>';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>AdminLTE 2 | Registration Page</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css"/>
    <link href="plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css"/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="register-page">
<div class="register-box">
    <div class="register-logo">
        <a href="index.php">Eye of <b>Slender</b></a>
    </div>

    <div class="register-box-body">
        <p class="login-box-msg">Register a new account</p>

        <form action="register.php" method="post">
            <div class="form-group has-feedback">
                <input type="email" name="email" class="form-control" placeholder="Email"/>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <p class="login-box-msg"><b>Minecraft Information!</b>
                <small>This information is not shared with anyone, including us! The Minecraft Information you provide is how you will log in!</small>
            </p>
            <div class="form-group has-feedback">
                <input type="text" name="minecraft_user" class="form-control" placeholder="Minecraft Username/Email"/>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="minecraft_pass" class="form-control" placeholder="Minecraft Password"/>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input name="terms" value="checked" type="checkbox"> I agree to the <a href="tos.php">terms</a>
                        </label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                </div>
            </div>
        </form>
        <a href="login.php" class="text-center">I already have an account</a>
    </div>
</div>

<script src="plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
</body>
</html>
