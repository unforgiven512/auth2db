<? include "verify.php" ?>
<?
include "conn.php";
include "security.php";

regex_standard($_POST["action"]);
$action = sec_addESC($_POST["action"]);

regex_numbers($_POST["id"]);
$id = sec_addESC($_POST["id"]);

regex_standard($_POST["filter"]);
$filter = sec_addESC($_POST["filter"]);

regex_regex($_POST["regex"]);
$regex = sec_addESC($_POST["regex"]);

regex_numbers($_POST["enabled"]);
$enabled = sec_addESC($_POST["enabled"]);


if ($action == "edit" ){
	$sql = "UPDATE filter SET filter = '$filter', regex = '$regex', enabled = '$enabled' WHERE id = '$id' ";
	$conn = mysql_query($sql);
	
} else if ($action == "new" ){
	$sql = "INSERT INTO filter (filter, regex, enabled) VALUES ('$filter','$regex','$enabled')";
	$conn = mysql_query($sql);
}

header("Location: filter_config_list.php"); 


?>