
<?php

if( isset( $_GET[ 'submit' ] ) ) {
	// Check Anti-CSRF token
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'index.php' );

	// Sanitise username input
	$user = $_GET[ 'username' ];
	$user = stripslashes( $user );
	$user = mysql_real_escape_string( $user );

	// Sanitise password input
	$pass = $_GET[ 'password' ];
	$pass = stripslashes( $pass );
	$pass = mysql_real_escape_string( $pass );
	$pass = md5( $pass );

	// Check database
	$query  = "SELECT * FROM `users` WHERE username = '$user' AND password = '$pass';";
	$result = mysql_query( $query ) or die( '<pre>' . mysql_error() . '</pre>' );

	if( $result && mysql_num_rows( $result ) == 1 ) {
		$_SESSION['username'] = $username; // Initializing Session
		header("location: photos.php"); // Redirecting To Other Page
	}
	else {
		// Login failed
		sleep( rand( 0, 3 ) );
		$error = "Incorrect username or password.";
	}

	mysql_close();
}

// Generate Anti-CSRF token
generateSessionToken();

?>