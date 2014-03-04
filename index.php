<?php
// composer autoloader
require __DIR__ . '/vendor/autoload.php';
// helper functions
require __DIR__ . '/helper.php';

// error flag
$error = false;

if (isset($_POST['login'])) {
    $username = isset($_POST['username'])
              ? $_POST['username']
              : null;

    $password = isset($_POST['password'])
              ? $_POST['password']
              : null;


    // create user repository
    $repo = new Sody\Repository\UserRepository(new Sody\Database\SimplePdo);
    // attempt to log user in return true if success
    $auth = (new Sody\Authenticate\Auth($repo))->attempt($username, $password);

    if ($auth) {
        return redirectTo('dashboard.php');
    } else {
        $error = true;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- site title -->
    <title>Login system - Demo</title>
    <!-- css files -->
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
    <style>
        .custom-margin {
            margin: 40px 0
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="custom-margin"></div>
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading"><h1 class="panel-title">Login to your account</h1></div>
                <div class="panel-body">
                <?php if($error): ?>
                    <div class="alert alert-danger">Wrong username/password</div>
                <?php endif ?>
                    <form method="POST" action="<?= htmlentities($_SERVER['PHP_SELF']) ?>" accept-charset="UTF-8">
                        <div class="form-group">
                            <label for="username" class="block">Username</label>
                            <input placeholder="Username"
                                class="form-control"
                                type="text"
                                name="username"
                                id="username"
                                value="<?= isset($username) ? _safe($username) : null ?>">
                        </div>
                        <div class="form-group">
                            <label for="password" class="block">Password</label>
                            <input placeholder="Password" class="form-control" type="password" name="password" id="password">
                        </div>

                        <input class="btn btn-lg btn-success btn-block" type="submit" name="login" value="Login">
                    </form>
                </div>
            </div>
        </div> <!-- /.col-md-4 -->
    </div> <!-- /.row -->

    <footer id="site-footer" class="row">
        <section class="col-md-12">
            <p class="text-center">
                <span class="label label-success">Created by Kenny Damgren</span>
            </p>
        </section>
    </footer> <!-- /#site-footer -->
</div>
</body>
</html>