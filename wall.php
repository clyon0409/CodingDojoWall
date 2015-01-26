<?php 
	session_start();
?>
<!DOCTYPE HTML>
<html lang='en'>
<head>
	<title>Coding Dojo Wall</title>
	<meta charset='UTF-8'
	<meta name='description' content='an electronic wall on which you can post messages'>
	<link rel='stylesheet' type='text/css' href='wall.css'>
</head>
<body>
	<div id='wrapper'>
		<div id='header'>
			<h3>CodingDojo Wall</h3>
			<p>Welcome <?php echo ucfirst($_SESSION['first_name']); ?> </p>
			<a href='process.php'>log off</a>
		</div>
		<form class='message' action='add_content.php' method='post'>
			<p>Post a message</p>
			<textarea name='message_text'></textarea>
			<input type='submit' name='message' value='Post a message'>
			<input type='hidden' name='action' value='post_message'>
		</form>
		<div class='message_area'>
<?php 
			foreach ($_SESSION['messages'] as $index=>$post)
			{
				echo '<h4>'.$post['owner'].' - '.$post['post_date'].'</h4>';
				echo '<div class="msg_par">'.$post['content'].'</div>';

				if(isset($_SESSION['comments'][$post['id']]))
				{
					if (count($_SESSION['comments'][$post['id']]) > 0)
					{
						foreach ($_SESSION['comments'][$post['id']] as $key=>$record)
						{
							echo '<h5>'.$record['owner'].' - '.$record['post_date'].'</h5>';
							echo '<div class="cmt_par">'.$record['content'].'</div>';
							echo "<form class='comment' action='add_content.php' method='post'>";
							echo "<input type='submit' name='delete' value='Delete'>";

							$val = 'delete_comment'. ' '.$record['message_id'].' '.$record['comment_id'];
							echo "<input type='hidden' name='action' value='$val'>";
							echo "</form>";
						}
					}
				}
				echo "<form class='comment' action='add_content.php' method='post'>";
					echo "<p>Post a comment</p>";
					echo "<textarea name='comment_text'></textarea>";
					echo "<input type='submit' name='comment' value='Post a comment'>";
					$val='post_comment '.$post['id'];
					echo "<input type='hidden' name='action' value='$val'>";
				echo "</form>";
			}
?>
		</div>
	</div>
</body>>
</html>