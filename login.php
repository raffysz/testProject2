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
		$user = $_POST[ 'username' ];
		$user = stripslashes( $user );
		$user = mysql_real_escape_string( $user );

		$pass = $_POST[ 'password' ];
		$pass = stripslashes( $pass );
		$pass = mysql_real_escape_string( $pass );



		$query  = "SELECT * FROM `users` WHERE username = '$user' AND password = '$pass';";
		$result = mysql_query( $query ) or die( '<pre>' . mysql_error() . '</pre>' );

		if( $result && mysql_num_rows( $result ) == 1 ) {
			$_SESSION['username'] = $username; // Initializing Session
			header("location: photos.php"); // Redirecting To Other Page
		}
		else {
			// Login failed
			sleep( rand( 0, 3 ) );
			$error =  "<pre><br />Username and/or password incorrect.</pre>";
		}

		mysql_close();
	}

}

?>