<?php 
	session_start();
?>

<!DOCTYPE HTML>
<html lang='en'>
<head>
	<title>Login and Registration</title>
	<meta charset='UTF-8'>
	<meta name='description' content='basic login and registration page'>
	<link rel='stylesheet' type='text/css' href='login.css'>
</head>
<body>
	<div id='container'>
		<fieldset>
			<legend>Login</legend>
			<form action='process.php' method='post'>
				<label>Email:<input  class='email' type='text' name='email'>
<?php 				if(!empty($_SESSION['errors']['login_email']))
					{
						echo '<p class="error">'.$_SESSION['errors']['login_email'].'</p>';
						unset($_SESSION['errors']['login_email']);
					}
?>
				</label> 
				<label>Password: <input class='pass' type='text' name='password'>
<?php 				if(!empty($_SESSION['errors']['login_password']))
					{
						echo '<p class="error">'.$_SESSION['errors']['login_password'].'</p>';
						unset($_SESSION['errors']['login_password']);
					}
?>
				</label>
				<input class='button' type='submit' name='login' value='Login'>
				<input type='hidden' name='action' value='login'>
			</form>
		</fieldset>
		<fieldset>
			<legend>Registration</legend>
			<form action='process.php' method='post'>
				<label>First Name: <input  class='name' type='text' name='first_name'>
<?php 				if(!empty($_SESSION['errors']['first_name']))
					{
						echo '<p class="error">'.$_SESSION['errors']['first_name'].'</p>';
						unset($_SESSION['errors']['first_name']);
					}elseif (!empty($_SESSION['errors']['first_name_numbers'])) {
						echo '<p class="error">'.$_SESSION['errors']['first_name_numbers'].'</p>';
						unset($_SESSION['errors']['first_name_numbers']);
					}
?>
				</label>
				<label>Last Name: <input  class='name' type='text' name='last_name'>
<?php 				if(!empty($_SESSION['errors']['last_name']))
					{
						echo '<p class="error">'.$_SESSION['errors']['last_name'].'</p>';
						unset($_SESSION['errors']['last_name']);
					}elseif (!empty($_SESSION['errors']['last_name_numbers'])) {
						echo '<p class="error">'.$_SESSION['errors']['last_name_numbers'].'</p>';
						unset($_SESSION['errors']['last_name_numbers']);
					}
?>
				</label>
				<label>Email: <input class='email' type='text' name='email'>
<?php 				if(!empty($_SESSION['errors']['email']))
					{
						echo '<p class="error">'.$_SESSION['errors']['email'].'</p>';
						unset($_SESSION['errors']['email']);
					}
?>
				</label>
				<label>Password: <input class='pass' type='text' name='password'>
<?php 				if(!empty($_SESSION['errors']['password']))
					{
						echo '<p class="error">'.$_SESSION['errors']['password'].'</p>';
						unset($_SESSION['errors']['password']);
					}
?>
				</label>
				<label>Confirm Password: <input id='confirm' type='text' name='confirm_password'>
<?php 				if(!empty($_SESSION['errors']['confirm']))
					{
						echo '<p class="error">'.$_SESSION['errors']['confirm'].'</p>';
						unset($_SESSION['errors']['confirm']);
					}
?>
				</label>
				<input class='button' type='submit' name='register' value='Register'>
				<input type='hidden' name='action' value='register'>
			</form>
		</fieldset>
	</div>
</body>
</html>s