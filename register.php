<?php
require_once 'bootstrap/init.php';
$errors = array();
if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $validator = new Validator();
        $validator->validate($_POST, array(
            'username' => 'required|min:6|max:20|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|matches:password'
        ));
        if($validator->fails()) {
            foreach ($validator->errors() as $error) {
                array_push($errors, $error);
            }
        } else {
            $user = new User();
            $salt = Hash::salt(30);
            $created = $user->create([
                'username' => sanitize(Input::get('username')),
                'email' => sanitize(Input::get('email')),
                'name' => sanitize(Input::get('name')),
                'password' => Hash::make(sanitize(Input::get('password')), $salt),
                'salt' => $salt,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            if($created) {
                Session::flash('success', 'You have successfully registered. You can now login');
                Redirect::to('login.php');
            } else {
                array_push($errors, 'An Error occurred while creating account. Please try again');
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
        <link rel="stylesheet" type="text/css" href="assets/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="assets/css/registration.css">
    </head>
<body>
    <nav class="navbar navbar-default navbar-inverse" id=bs-navbar>
  <!--<div class="container-fluid">-->
  <div class="navbar-header">
    <a class="navbar-brand" href="#">Instagram</a>
  </div>
    </nav>
    <div class="container">
        <?php include 'includes/pages/notify.php'; ?>
      <form class="form-signin" method="post">
        <label for="name">Name</label>
        <input type="text" id="name" value="<?php echo sanitize(Input::get('name')); ?>" class="form-control" name="name" placeholder="Name" required autofocus>
        <label for="username">Username</label>
        <input type="text" id="username" class="form-control" name="username" value="<?php echo sanitize(Input::get('username')); ?>" placeholder="Username" required>
        <label for="email">Email Address</label>
        <input type="email" id="email" class="form-control" name="email" value="<?php echo sanitize(Input::get('email')); ?>" placeholder="Email" required>
        <label for="password">Password</label>
        <input type="password" id="password" class="form-control" name="password" placeholder="Password" required>
          <label for="password_confirmation">Confirm Password</label>
          <input type="password" id="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required><br>
          <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
          <button class="btn btn-lg btn-primary btn-block" name="register" type="submit">REGISTER</button><br>
        <p class="text-default">Already registered? <a href="login.php">login</a> here</p>
      </form>
    </div>
</body>
</html>