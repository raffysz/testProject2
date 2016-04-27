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
		$username = $_POST[ 'username' ];
		$username = stripslashes( $username );
		$username = mysql_real_escape_string( $username );

		$password = $_POST[ 'password' ];
		$password = stripslashes( $password );
		$password = mysql_real_escape_string( $password );

		// Default values
		$total_failed_login = 3;
		$lockout_time       = 15;
		$account_locked     = false;

		// Check the database (Check user information)
		$data = $db->prepare( 'SELECT failed_login, last_login FROM users WHERE username = (:username) LIMIT 1;' );
		$data->bindParam( ':username', $username, PDO::PARAM_STR );
		$data->execute();
		$row = $data->fetch();

		// Check to see if the user has been locked out.
		if( ( $data->rowCount() == 1 ) && ( $row[ 'failed_login' ] >= $total_failed_login ) )  {
			// User locked out.  Note, using this method would allow for user enumeration!
			//echo "<pre><br />This account has been locked due to too many incorrect logins.</pre>";

			// Calculate when the user would be allowed to login again
			$last_login = $row[ 'last_login' ];
			$last_login = strtotime( $last_login );
			$timeout    = strtotime( "{$last_login} +{$lockout_time} minutes" );
			$timenow    = strtotime( "now" );

			// Check to see if enough time has passed, if it hasn't locked the account
			if( $timenow > $timeout )
				$account_locked = true;
		}

// Check the database (if username matches the password)
		$data = $db->prepare( 'SELECT * FROM users WHERE username = (:username) AND password = (:password) LIMIT 1;' );
		$data->bindParam( ':username', $username, PDO::PARAM_STR);
		$data->bindParam( ':password', $password, PDO::PARAM_STR );
		$data->execute();
		$row = $data->fetch();

		// If its a valid login...
		if( ( $data->rowCount() == 1 ) && ( $account_locked == false ) ) {
			$_SESSION['username'] = $username; // Initializing Session
			header("location: photos.php"); // Redirecting To Other Page
		}else
		{
			$error = "Incorrect username or password.";
		}

	}
}

?>