<? include "verify.php" ?>
<?

include "conn.php";
include "security.php";

regex_standard($_POST["hostname"]);
$hostname = sec_addESC($_POST["hostname"]);

regex_standard($_POST["type"]);
$type = sec_addESC($_POST["type"]);

regex_standard($_POST["ip"]);
$ip = sec_addESC($_POST["ip"]);

$sql = "SELECT id FROM host_config WHERE hostname = '$hostname' ";
$result = mysql_query($sql);

$rowCheck = mysql_num_rows($result);
if($rowCheck > 0){
	$sql = "UPDATE host_config SET type = '$type', ip = '$ip' WHERE hostname = '$hostname' ";
	$conn = mysql_query($sql);
} else {
	$sql = "INSERT INTO host_config (hostname,type, ip) VALUES ('$hostname','$type','$ip')";
	$conn = mysql_query($sql);
}

header("Location: host_config_list.php"); 


?>