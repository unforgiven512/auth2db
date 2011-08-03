<? include "verify.php" ?>
<?
include "conn.php";
include "security.php";

regex_standard($_POST["smtp_server"]);
$smtp_server = sec_addESC($_POST["smtp_server"]);

regex_standard($_POST["smtp_port"]);
$smtp_port = sec_addESC($_POST["smtp_port"]);

regex_email($_POST["mail_from"]);
$mail_from = sec_addESC($_POST["mail_from"]);

regex_standard($_POST["auth_active"]);
$auth_active = sec_addESC($_POST["auth_active"]);

regex_standard($_POST["auth_user"]);
$auth_user = sec_addESC($_POST["auth_user"]);

regex_standard($_POST["auth_pass"]);
$auth_pass = sec_addESC($_POST["auth_pass"]);


$sql = "UPDATE smtp_config SET 
				smtp_server = '$smtp_server', 
				smtp_port = '$smtp_port', 
				mail_from = '$mail_from', 
				auth_active = '$auth_active', 
				auth_user = '$auth_user', 
				auth_pass = '$auth_pass' ";
				
$conn = mysql_query($sql);


header("Location: smtp_config.php?active_msg=1"); 


?>