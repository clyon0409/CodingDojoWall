<?php
	
	// --------------------------This script handles all the content on the wall ---------------------//
	if (session_id()  == '')
	{
		session_start();	
		require('new-connection.php');
	}

	//var_dump($_POST);
	// var_dump($_SESSION);

	if(!empty($_POST)){
		if($_POST['action'] == 'post_message')
		{
			if(!empty($_POST['message_text']))
			{
				$text = str_replace("'", "\'", $_POST['message_text']);
				$esc_text = escape_this_string($text);
				$query = "INSERT INTO messages (message, users_id, created_at, updated_at ) VALUES ('$esc_text',{$_SESSION['user_id']}, NOW(),NOW())"; 
				//var_dump($query);
				run_mysql_query($query);
				pack_messages_and_display();
			}
		}

		if(strpos($_POST['action'],'delete_message') !== FALSE)
		{
			$temp=explode(' ', $_POST['action']);
			$user_id=intval($temp[1]);
			$msg_id=intval($temp[2]);
			
			if($user_id == $_SESSION['user_id'])
			{
				if(count($_SESSION['comments'][$msg_id]) > 0)
				{
					$query = "DELETE FROM comments WHERE comments.users_id=$user_id AND comments.messages_id=$msg_id";
					//var_dump($query);
					run_mysql_query($query);
				}

				$query = "DELETE FROM messages WHERE messages.users_id=$user_id AND messages.id=$msg_id";
				//var_dump($query);
				run_mysql_query($query);
				pack_messages_and_display();
			}	

			header('location:wall.php');
			die();
		}

		if(strpos($_POST['action'],'post_comment') !== FALSE)
		{
			//var_dump('got post comment directive');
			$temp = explode(' ',$_POST['action']);
			$msg_id = intval($temp[1]);
			if(!empty($_POST['comment_text']))
			{
				$text = str_replace("'", "\'", $_POST['comment_text']);
				$esc_text = escape_this_string($text);
				$query = "INSERT INTO comments (messages_id, users_id, comment, created_at, updated_at ) VALUES ( $msg_id, {$_SESSION['user_id']},'$esc_text', NOW(),NOW())"; 
				run_mysql_query($query);
				pack_messages_and_display();
			}
		}

		if(strpos($_POST['action'],'delete_comment') !== FALSE)
		{
			$temp=explode(' ', $_POST['action']);
			$user_id=intval($temp[1]);
			$msg_id=intval($temp[2]);
			$cmt_id=intval($temp[3]);
			//var_dump($user_id);
			// var_dump($msg_id);
			// var_dump($cmt_id);

			if($user_id == $_SESSION['user_id'])
			{
				$query = "DELETE FROM comments WHERE comments.messages_id=$msg_id AND comments.id = $cmt_id";
				// var_dump($query);
				run_mysql_query($query);
				pack_messages_and_display();
			}

			header('location:wall.php');
			die();
		}
	}

	function pack_messages_and_display()
	{
		$query = "SELECT messages.users_id, messages.id, message, messages.created_at, first_name, last_name FROM messages JOIN users ON users.id=users_id ORDER BY messages.created_at DESC";
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
			$content = str_replace("'", "\'", $post['message']);
			$_SESSION['messages'][$index]['content']=$content;
			$_SESSION['messages'][$index]['id']=intval($post['id']);
			$_SESSION['messages'][$index]['user_id']=intval($post['users_id']);
			$temp=$post['id'];

			$query = "SELECT comments.id, comments.messages_id,  comments.users_id, comment, users.first_name, users.last_name, comments.created_at FROM comments JOIN messages ON messages.id=comments.messages_id JOIN users ON users.id = comments.users_id WHERE messages_id=$temp ORDER BY comments.created_at ASC";
			//var_dump($query);
			$data = fetch_all($query);
			// var_dump($temp);
			// var_dump('data from comments');
			// var_dump($data);

			if (count($data) > 0){
				foreach ($data as $key => $record)
				{
					$_SESSION['comments'][$temp][$key]['owner']=$record['first_name'].' '.$record['last_name'];
					$_SESSION['comments'][$temp][$key]['post_date']=date('F jS Y',strtotime($record['created_at']));
					$content = str_replace("'", "\'", $record['comment']);
					$_SESSION['comments'][$temp][$key]['content']=$content;
					$_SESSION['comments'][$temp][$key]['comment_id'] = intval($record['id']);
					$_SESSION['comments'][$temp][$key]['message_id'] = intval($record['messages_id']);
					$_SESSION['comments'][$temp][$key]['user_id'] = intval($record['users_id']);
				}
			}
		}
		// var_dump($_SESSION['comments']);
		//var_dump($_SESSION['messages']);
		header('location:wall.php');
		die();
	}


?>