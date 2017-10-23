<?php
require_once 'bootstrap/init.php';

$user = new User();
$user->logout();
Redirect::to('login.php');