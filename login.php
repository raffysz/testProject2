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
		// Sanitise username input
		$user = $_POST[ 'username' ];
		$user = stripslashes( $user );
		$user = mysql_real_escape_string( $user );

		// Sanitise password input
		$pass = $_POST[ 'password' ];
		$pass = stripslashes( $pass );
		$pass = mysql_real_escape_string( $pass );
		$pass = md5( $pass );

		// Default values
		$total_failed_login = 3;
		$lockout_time       = 15;
		$account_locked     = false;

		// Check the database (Check user information)
		$data = $db->prepare( 'SELECT failed_login, last_login FROM users WHERE username = (:user) LIMIT 1;' );
		$data->bindParam( ':user', $user, PDO::PARAM_STR );
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
		$data = $db->prepare( 'SELECT * FROM users WHERE username = (:user) AND password = (:password) LIMIT 1;' );
		$data->bindParam( ':user', $user, PDO::PARAM_STR);
		$data->bindParam( ':password', $pass, PDO::PARAM_STR );
		$data->execute();
		$row = $data->fetch();


		//If username and password exist in our database then create a session.
		//Otherwise echo error.

		if( ( $data->rowCount() == 1 ) && ( $account_locked == false ) ) {
			$_SESSION['username'] = $user; // Initializing Session

			// Reset bad login count
			$data = $db->prepare( 'UPDATE users SET failed_login = "0" WHERE username = (:user) LIMIT 1;' );
			$data->bindParam( ':user', $user, PDO::PARAM_STR );
			$data->execute();

			header("location: photos.php"); // Redirecting To Other Page
		}else
		{

			// Login failed
			sleep( rand( 2, 4 ) );

			// Give the user some feedback
			$error = "<pre><br />Username and/or password incorrect.<br /><br/>Alternative, the account has been locked because of too many failed logins.<br />If this is the case, <em>please try again in {$lockout_time} minutes</em>.</pre>";

			// Update bad login count
			$data = $db->prepare( 'UPDATE users SET failed_login = (failed_login + 1) WHERE username = (:user) LIMIT 1;' );
			$data->bindParam( ':user', $user, PDO::PARAM_STR );
			$data->execute();
		}

		// Set the last login time
		$data = $db->prepare( 'UPDATE users SET last_login = now() WHERE username = (:user) LIMIT 1;' );
		$data->bindParam( ':user', $user, PDO::PARAM_STR );
		$data->execute();

	}
}

?>