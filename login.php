<?php
session_start();
include("connection.php"); //Establishing connection with our database

$error = ""; //Variable for storing our errors.
if(isset($_POST["submit"]))
{
	if(empty($_POST["username"]) || empty($_POST["password"]))
	{
		$error = "Both fields are required.";
	}else
	{
		// Define $username and $password
		$username=$_POST['username'];
		$username = mysqli_real_escape_string($db, $username);
		$username = htmlspecialchars($username);

		$password=$_POST['password'];
		$password = mysqli_real_escape_string($db, $password);
		$password = htmlspecialchars($password);

		$total_failed_login =3;
		$lockout_time =15;
		$account_locked =false;

		$sql="SELECT filed_login, last_login FROM users WHERE username='$username'";
		$result=mysqli_query($db,$sql);
		$row=mysqli_fetch_array($result,MYSQLI_ASSOC) ;

		if((mysqli_num_rows($result) == 1) && ($row['failed_login'] >=$total_failed_login){
			$error = "This account has been locked out due to too many incorrect logins";

			$last_login = $row['last_login'];
			$last_login = strtotime("{$last_login}+{$lockout_time} minutes");
			$timenow = strtotime("now");

			if($timenow > $timeout)
				$account_locked = true;
		}

		//Check username and password from database
		$sql="SELECT userID FROM users WHERE username='$username' and password='$password'";
		$result=mysqli_query($db,$sql);
		$row=mysqli_fetch_array($result,MYSQLI_ASSOC) ;

		//If username and password exist in our database then create a session.
		//Otherwise echo error.

		if((mysqli_num_rows($result) == 1)&&($account_locked == false))
		{
			$_SESSION['username'] = $username; // Initializing Session

			$sql="UPDATE users SET failed_login = '0' WHERE username = '$username";

			header("location: photos.php"); // Redirecting To Other Page

		}else
		{
			sleep(rand( 2, 5));
			$error = "Incorrect username or password, or account locked because of too many failed logins.";
			$msg = "If this is the case, please try again in {$lockout_time} minutes";

			$sql="UPDATE users SET failed_login = (failed_login + 1) WHERE username = '$username";
		}

		$sql="UPDATE users SET last_login = NOW() WHERE name = '$username";

	}
}

?>