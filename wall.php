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

				echo "<form class='comment' action='add_content.php' method='post'>";
					echo "<p>Post a comment</p>";
					echo "<textarea name='comment_text'></textarea>";
					echo "<input type='submit' name='comment' value='Post a comment'>";
					echo "<input type='hidden' name='action' value='post_comment'>";
				echo "</form>";
			}
?>
			<h5>Michael Choi - Feb 5rd 2015 </h4>
			<div class='cmt_par'>Kramer: [in the sauna]: It's like a sauna in here. George: For me to ask a woman out, I've got to get into a mental state like the karate guys before they break the bricks. Jerry: I don’t get you. Who goes on vacation without a job? What, do you need a break from getting up at 11:00. Kramer: I've cut slices so thin, I couldn't even see them. Elaine: How'd you know you cut it? Kramer: Well, I guess I just assumed? George: The sea was angry that day, my friends. Like an old man trying to send back soup in a deli.</div>
			<form class='comment' action='add_content.php' method='post'>
				<p>Post a comment</p>
				<textarea name='comment_text'></textarea>
				<input type='submit' name='comment' value='Post a comment'>
				<input type='hidden' name='action' value='post_comment'>
			</form>
		</div>
	</div>
</body>>
</html>