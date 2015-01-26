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
			$esc_first_name = escape_this_string($_POST["first_name"]);
			$esc_last_name = escape_this_string($_POST["last_name"]);
			$esc_email = escape_this_string($_POST["email"]);
			$esc_password = escape_this_string($_POST["password"]);
			$query = "INSERT INTO users (first_name, last_name, email, password, created_at, updated_at) VALUES ('$esc_first_name','$esc_last_name','$esc_email','$esc_password',NOW(),NOW())";
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

			//var_dump($_SESSION);
			pack_messages_and_display();  //grab content from database and shove in $_SESSION for access 
	}
	?>