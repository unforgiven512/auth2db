<? include "verify.php" ?>
<?
include "conn.php";
include "security.php";

regex_standard($_POST["action"]);
$action = sec_addESC($_POST["action"]);

regex_standard($_POST["id"]);
$id = sec_addESC($_POST["id"]);

regex_standard($_POST["username"]);
$username = sec_addESC($_POST["username"]);

regex_standard($_POST["password"]);
$password = sec_addESC($_POST["password"]);

regex_standard($_POST["password_again"]);
$password_again = sec_addESC($_POST["password_again"]);

regex_standard($_POST["access_level"]);
$access_level = sec_addESC($_POST["access_level"]);

regex_email($_POST["email"]);
$email = sec_addESC($_POST["email"]);


if ($action == "edit" ){
	$sql = "UPDATE users SET username = '$username', access_level = '$access_level', email = '$email' WHERE id = '$id' ";
	$conn = mysql_query($sql);
	
	if ($password != "" AND $password == $password_again) {
		$sql = "UPDATE users SET password = md5('$password') WHERE id = '$id' ";
		$conn = mysql_query($sql);
	}
	
} else if ($action == "new" ){
	if ($password != "" AND $password == $password_again) {
		$sql = "INSERT INTO users (username,password,access_level, email) VALUES ('$username',md5('$password'),'$access_level','$email')";
		$conn = mysql_query($sql);
	} else{
		header("Location: error.php"); 
	}
}

header("Location: user_config_list.php"); 


?>