<?
include "conn.php";
include "security.php";

$username = sec_cleanTAGS($_POST["username"]);
$username = sec_addESC($username);

$password = sec_cleanTAGS($_POST["password"]);
$password = sec_addESC($password);

//check that the user is calling the page from the login form and not accessing it directly
//and redirect back to the login form if necessary
if (!isset($username) || !isset($password)) {
	header( "Location: index.php" );
}
//check that the form fields are not empty, and redirect back to the login page if they are
elseif (empty($username) || empty($password)) {
	header( "Location: index.php" );

} else {

	//add slashes to the username and md5() the password
	//$user = addslashes($_POST['username']);
	//$pass = md5($_POST['password']);

	//$user = sec_addESC($_POST['username']);	
	//$pass = md5(sec_addESC($_POST['password']));
	$user = $username;
	$pass = md5($password);


	$sql = "select * from users where username = '$user' AND password = '$pass' ";
	$result=mysql_query($sql);
	
	//check that at least one row was returned

	$rowCheck = mysql_num_rows($result);
	
	if($rowCheck > 0){
		
		while($row = mysql_fetch_object($result)){

			//start the session and register a variable

			session_start();
			session_regenerate_id();
			
			session_register('username');
			$_SESSION["username"] = $user;
			$_SESSION["access_level"] = $row->access_level;
			// ------- RAC ------------------>
			// Asigna los permisos a la variable SESSION->RAC
			$sql = "SELECT item_id FROM ac_mm_role_item WHERE role_id = ". $row->access_level;
			$result = mysql_query($sql);

			while($rs_rac = mysql_fetch_object($result)) {
				$_SESSION["rac"][] = $rs_rac->item_id;
			}
			// ------- RAC END -------------->
			
			// INSERT users_audit
			$fecha = date("Y-m-d H:i:s");
			
			if ( isset($_SERVER['HTTP_X_FORWARDED_FOR'] )) { 
			$ip_real = $_SERVER['HTTP_X_FORWARDED_FOR']; 
			} 
			elseif ( isset($_SERVER['HTTP_CLIENT_IP'] )) { 
			$ip_real = $_SERVER['HTTP_CLIENT_IP']; 
			} 
			else { 
			$ip_real = $_SERVER['REMOTE_ADDR']; 
			} 
			
			$session_id = session_id();
			
			$user_id = $row->id;
			
			$sql = "INSERT INTO users_audit (user_id, username,remote_host,start_session,session_id) VALUES ('$user_id', '$user','$ip_real','$fecha','$session_id')";
			$conn = mysql_query($sql);
			

			//we will redirect the user to another page where we will make sure they're logged in
			////header( "Location: contenedor.php" );
                header( "Location: status_indexed.php" );
			//header( "Location: content_simple_realtime.php" );
			
		}

	}	else {

		//if nothing is returned by the query, unsuccessful login code goes here...
		//echo 'Incorrect login name or password. Please try again.';
		header( "Location: index.php?error=1" );
	}
}
  ?> 
