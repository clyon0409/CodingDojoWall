<?php
	
	if (session_id()  == ''){
		session_start();	
		require('new-connection.php');
	}

	// var_dump($_POST);
	// var_dump($_SESSION);

	if($_POST['action'] === 'post_message')
	{
		if(!empty($_POST['message_text']))
		{
			$query = "INSERT INTO messages (message, users_id, created_at, updated_at ) VALUES ('{$_POST["message_text"]}',{$_SESSION['user_id']}, NOW(),NOW())"; 
			var_dump($query);
			run_mysql_query($query);
			pack_messages();
			header('location:wall.php');
			die();
		}
	}

	if($_POST['action'] === 'post_comment')
	{
		if(!empty($_POST['comment_text']))
		{

			var_dump($_POST);
			//$query = "INSERT INTO messages (message, users_id, created_at, updated_at ) VALUES ('{$_POST["message_text"]}',{$_SESSION['user_id']}, NOW(),NOW())"; 
			// var_dump($query);
			// run_mysql_query($query);
			// pack_messages();
			// header('location:wall.php');
			// die();
		}
	}

	function pack_messages()
	{
		$query = "SELECT messages.id, message, messages.created_at, first_name, last_name FROM messages JOIN users ON users.id=users_id ORDER BY messages.created_at DESC";
		$data = fetch_all($query);
		//var_dump($data);

		if(!empty($_SESSION['messages']))
		{
			unset($_SESSION['messages']);
		}

		foreach($data as $index=>$post)
		{
			// var_dump('post');
			// var_dump($post);
			$_SESSION['messages'][$index]['owner']= $post['first_name'].' '.$post['last_name'];
			$_SESSION['messages'][$index]['post_date']= date('F jS Y',strtotime($post['created_at']));
			$_SESSION['messages'][$index]['id']=$post['id'];
			$_SESSION['messages'][$index]['content']=$post['message'];
		}
	}
	
?>