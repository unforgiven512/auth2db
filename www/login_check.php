<?

session_start();

if(session_is_registered('username')){
	// echo OK;
}
else{
	header( "Location: index.php" );
}

?>