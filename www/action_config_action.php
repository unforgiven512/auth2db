<?
include "conn.php";
include "security.php";

regex_standard($_POST["action"]);
$action = sec_addESC($_POST["action"]);

regex_standard($_POST["action_alias"]);
$action_alias = sec_addESC($_POST["action_alias"]);

regex_standard($_POST["color"]);
$color = sec_addESC($_POST["color"]);

regex_standard($_POST["other_color"]);
$other_color = sec_addESC($_POST["other_color"]);


if ($color != $other_color and $color == "other")
	$color = $other_color;

$sql = "SELECT action_name FROM action_config WHERE action_name = '$action' ";
$result = mysql_query($sql);

$rowCheck = mysql_num_rows($result);
if($rowCheck > 0){
	$sql = "UPDATE action_config SET action_alias = '$action_alias', color = '$color' WHERE action_name = '$action' ";
	$conn = mysql_query($sql);
} else {
	$sql = "INSERT INTO action_config (action_name,action_alias , color) VALUES ('$action','$action_alias','$color')";
	$conn = mysql_query($sql);
}

header("Location: action_config_list.php"); 


?>