<?
//start the session
session_start();

//check to make sure the session variable is registered
if(session_is_registered('username')){
	
	// UPDATE close_session IN users_audit
	include "conn.php";
	$username = $_SESSION["username"];
	$session_id = session_id();
	
	$fecha = date("Y-m-d H:i:s");
	
	$sql = "UPDATE users_audit SET close_session = '$fecha' WHERE session_id = '$session_id' AND username = '$username' ";
	$conn = mysql_query($sql);
	
	//session variable is registered, the user is ready to logout
	session_unset();
	session_destroy();
	
	
	header( "Location: index.php" );
}
else{

	//the session variable isn't registered, the user shouldn't even be on this page
	header( "Location: index.php" );
}

?> 