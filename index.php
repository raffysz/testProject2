<?php
	include('login.php'); // Include Login Script

	if ((isset($_SESSION['username']) != '')) 
	{
		header('Location: photos.php');
	}	
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>PHP Login Form with Session</title>
<link rel="stylesheet" href="style.css" type="text/css" />
</head>

<body>
<div class="main">
<h1 style="font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif; font-size:32px;">Welcome to Photo Commenter</h1>
    <div class="formbox">
        <h3>Login Form</h3>
        <br><br>
        <form action="#" method="POST">
            Username:<br>
            <input type="text" name="username"><br>
            Password:<br>
            <input type="password" autocomplete="off" name="password"><br>
            <br>
            <input type="submit" value="Login" name="Login">
            <input type="hidden" name="user_token" value="815d67cbc3912915704adc69c408c005">
        </form>
        <div class="error"><?php echo $error;?></div>
        <div class="register">You can register <a href="register.php"> here </a> </div>
    </div>

</div>
</body>
</html>