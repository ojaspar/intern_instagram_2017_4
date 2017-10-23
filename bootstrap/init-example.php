<?php
	session_start();

	$GLOBALS['config'] = array(
		'mysql' => array(
			'host' => '127.0.0.1',
			'username' => 'your-db-username',
			'password' => '*****',
			'db' => 'your-db-name'
		),

        'session' => array(
            'session_name' => 'user',
            'token_name' => 'token'
        )
	);

	spl_autoload_register(function($classname)
	{
		include 'classes/' . $classname . '.php';
	});

	require_once 'helpers/sanitize.php';
?>
