<?php
require_once './dbconfig.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
	$username = filter_input(INPUT_POST, 'email');
	$password = filter_input(INPUT_POST, 'password');

	// Get DB instance.
	$db = getDbInstance();

	$db->where('user_email', $username);
	$row = $db->getOne('tbl_users');

	if ($db->count >= 1)
    {
		$db_password = $row['password'];
		$user_id = $row['id'];

		if (password_verify($password, $db_password))
        {
			$_SESSION['user_logged_in'] = TRUE;
            $_SESSION['user_id'] = $row['id'];

			// Authentication successfull redirect user
			header('Location: index.php');
		}
        else
        {
			$_SESSION['login_failure'] = 'Invalid user name or password';
			header('Location: login.php');
		}
		exit;
	}
    else
    {
		$_SESSION['login_failure'] = 'Invalid user name or password';
		header('Location: login.php');
		exit;
	}
}
else
{
	die('Method Not allowed');
}
