<?php

//	----------------  This script handles log-on, registration and log-off -------------------------------//
	session_start();

	require_once('new-connection.php');
	include_once('add_content.php');

	if(isset($_POST['action']) && $_POST['action'] === 'register')
	{
		validate_registrant($_POST);

		if(count($_SESSION['errors']) > 0){
			header('location:index.php');
			die();
		}
		else
		{
			$query = "INSERT INTO users (first_name, last_name, email, password, created_at, updated_at) VALUES ('{$_POST["first_name"]}','{$_POST["last_name"]}','{$_POST["email"]}','{$_POST["password"]}',NOW(),NOW())";
			run_mysql_query($query);
			login_user($_POST);
		}
	}
	elseif(isset($_POST['action']) && $_POST['action'] === 'login')
	{
		validate_login($_POST);

		if(isset($_SESSION['erros'])){
			if(count($_SESSION['errors']) > 0)
			{
				header('location:index.php');
				die();
			}
		}
		else
		{
			login_user($_POST);
		}
	}
	else //user must want to log off
	{
		$_SESSION=array();
		session_destroy();
		header('location:index.php');
		die();
	}

	function validate_registrant($post){

		var_dump('start registration process');
		if(empty($post['first_name']))
		{
			$_SESSION['errors']['first_name']="Please re-enter your first name";
		}
		elseif( preg_match('/[0-9]/', $post['first_name']))
		{
			$_SESSION['errors']['first_name_numbers']="Your user name cannot contain numbers";
		}

		if(empty($post['last_name']))
		{
			$_SESSION['errors']['last_name']="Please re-enter your last name";
		}
		elseif( preg_match('/[0-9]/', $post['last_name']))
		{
			$_SESSION['errors']['last_name_numbers']="Your user name cannot contain numbers";
		}

		if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL))
		{
			$_SESSION['errors']['email']="You have entered an invalid e-mail address";
		}

		if(empty($post['password'])  || strlen($post['password']) < 6 )
		{
			$_SESSION['errors']['password']="Please enter a password that is greater than 6 characters";
		}

		if($post['password'] !== $post['confirm_password'])
		{
			$_SESSION['errors']['confirm']="This does not match the password you entered";
		}
	}

	function validate_login($post)
	{
		//var_dump('validate login');

		if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL))
		{
			$_SESSION['errors']['login_email'] = "Please enter a valid email";
		}

		if(empty($post['password']))
		{
			$_SESSION['errors']['login_password'] = "Please enter your password";
		}

		//var_dump($_SESSION['errors']);
	}

	function login_user($post)
	{
			$query = "SELECT first_name, last_name, id FROM users WHERE email='{$post["email"]}' AND password='{$post["password"]}' ";
			$record = fetch_record($query);
			$_SESSION['user_id'] = intval($record['id']);
			$_SESSION['first_name'] = $record['first_name'];
			$_SESSION['last_name'] = $record['last_name'];

			pack_messages();  //grab content from database and shove in $_SESSION for access 
			// var_dump('messages');
			// var_dump($_SESSION['messages']);

			header('location:wall.php');
			die();
	}
	?>