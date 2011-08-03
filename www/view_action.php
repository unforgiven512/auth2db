<? include "verify.php" ?>
<?
include "conn.php";
include "security.php";

// Decode HEXA
$encode = $_POST["txtRegexHex"];
$regex = preg_replace("'([\S,\d]{2})'e","chr(hexdec('\\1'))",$encode);

regex_standard($_POST["action"]);
$action = sec_addESC($_POST["action"]);

regex_numbers($_POST["id"]);
$id = sec_addESC($_POST["id"]);

regex_standard($_POST["view"]);
$filter = sec_addESC($_POST["view"]);

#regex_regex($_POST["regex"]);
#$regex = sec_addESC($_POST["regex"]);

regex_standard($_POST["color"]);
$color = sec_addESC($_POST["color"]);

regex_numbers($_POST["enabled"]);
$enabled = sec_addESC($_POST["enabled"]);


if ($action == "edit" ){
	$sql = "UPDATE view SET view = '$filter', regex = '$regex', color = '$color', enabled = '$enabled' WHERE id = '$id' ";
	$conn = mysql_query($sql);
	
} else if ($action == "new" ){
	$sql = "INSERT INTO view (view, regex, color, enabled) VALUES ('$filter','$regex','$color','$enabled')";
	$conn = mysql_query($sql);
}

header("Location: view_config_list.php"); 


?>