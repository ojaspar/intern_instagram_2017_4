<?php
require_once 'bootstrap/init.php';
$errors = array();
if (Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $validator = new Validator();
        $validator->validate($_POST, [
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors() as $error) {
                array_push($errors, $error);
            }
        } else {
            $user = new User();
            $login = $user->login(Input::get('username'), Input::get('password'));
            if($login) {
                Session::flash('success', 'Welcome, ' . $user->data()->name);
                Redirect::to('index.php');
            } else {
                array_push($errors, 'Incorrect Credentials');
            }
        }
    } else {
        Redirect::to(404);
    }
}

?>
<html>
    <head>
        <title>Instagram</title>
            <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="assets/css/registration.css">
    </head>
    <body>
        <nav class="navbar navbar-default navbar-inverse" id="bs-navbar">
          <div class="navbar-header">
            <a class="navbar-brand" href="#">Instagram</a>
          </div>
        </nav>
        <div class="container">
            <?php include 'includes/pages/notify.php'; ?>

          <form class="form-signin" method="post">
            <label for="name">Username</label>
            <input
                    type="text"
                    id="username"
                    class="form-control"
                    name="username"
                    placeholder="Username"
                    autofocus
                    value="<?php echo sanitize(Input::get('username')); ?>">
            <label for="password">Password</label>
            <input type="password" id="password" class="form-control" name="password" placeholder="Password"><br>
              <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
              <button class="btn btn-primary" name="login" type="submit">Login</button><br>
              <p class="text-default">Don't have an account? <a href="register.php">Sign up</a> here</p>
          </form>
        </div>
    </body>
</html>