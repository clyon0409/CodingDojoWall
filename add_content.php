<?php
	
	if (session_id()  == ''){
		session_start();	
		require('new-connection.php');
	}

	//var_dump($_POST);
	// var_dump($_SESSION);

	if($_POST['action'] == 'post_message')
	{
		if(!empty($_POST['message_text']))
		{
			$query = "INSERT INTO messages (message, users_id, created_at, updated_at ) VALUES ('{$_POST["message_text"]}',{$_SESSION['user_id']}, NOW(),NOW())"; 
			//var_dump($query);
			run_mysql_query($query);
			pack_messages();
			header('location:wall.php');
			die();
		}
	}

	if(strpos($_POST['action'],'post_comment') !== FALSE)
	{
		//var_dump('got post comment directive');
		$temp = explode(' ',$_POST['action']);
		$msg_id = intval($temp[1]);
		if(!empty($_POST['comment_text']))
		{
			$query = "INSERT INTO comments (messages_id, users_id, comment, created_at, updated_at ) VALUES ( $msg_id, {$_SESSION['user_id']},'{$_POST["comment_text"]}', NOW(),NOW())"; 
			run_mysql_query($query);
			pack_messages();
			header('location:wall.php');
			die();
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

		if(!empty($_SESSION['comments']))
		{
			unset($_SESSION['comments']);
		}

		foreach($data as $index=>$post)
		{
			// var_dump('post');
			// var_dump($post);
			$_SESSION['messages'][$index]['owner']= $post['first_name'].' '.$post['last_name'];
			$_SESSION['messages'][$index]['post_date']= date('F jS Y',strtotime($post['created_at']));
			$_SESSION['messages'][$index]['content']=$post['message'];
			$_SESSION['messages'][$index]['id']=intval($post['id']);
			$temp=$post['id'];

			$query = "SELECT comments.id, comment, users.first_name, users.last_name, comments.created_at FROM comments JOIN messages ON messages.id=comments.users_id JOIN users ON users.id = comments.users_id WHERE messages_id=$temp ORDER BY comments.created_at ASC";
			//var_dump($query);
			$data = fetch_all($query);
			// var_dump($temp);
			// var_dump($data);

			if (count($data) > 0){
				foreach ($data as $key => $record)
				{
					$_SESSION['comments'][$temp][$key]['owner']=$record['first_name'].' '.$record['last_name'];
					$_SESSION['comments'][$temp][$key]['post_date']=date('F jS Y',strtotime($record['created_at']));
					$_SESSION['comments'][$temp][$key]['content']=$record['comment'];
					$_SESSION['comments'][$temp][$key]['comment_id'] = $record['id'];
				}
			}
		}
		//var_dump($_SESSION['comments']);
	}
	
?>