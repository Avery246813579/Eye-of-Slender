<?php
if(isset($_COOKIE['USER'])){
    header("Location: index.php");
}else {
    if (!empty($_POST['minecraft_user']) AND !empty($_POST['minecraft_pass'])) {
        include("MCAuth.class.php");
        $MCAuth = new MCAuth();
        if ($MCAuth->authenticate($_POST['minecraft_user'], $_POST['minecraft_pass']) == TRUE) {
            setcookie('USER', $account->account_id, time() + 63072000, '/');
            header("Location: index.php");
        } else {
            echo '<pre>';
            print_r($MCAuth->autherr);
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
        <p class="login-box-msg">Login</p>

        <form action="login.php" method="post">
            <div class="form-group has-feedback">
                <input type="text" name="minecraft_user" class="form-control" placeholder="Minecraft Username/Email"/>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="minecraft_pass" class="form-control" placeholder="Minecraft Password"/>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Login</button>
                </div>
            </div>
        </form>
        <a href="register.php" class="text-center">I don't have an account yet!</a>
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
