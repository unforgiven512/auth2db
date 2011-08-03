<? include "verify.php"; ?>
<?
include "conn.php";
include "security.php";

regex_standard($_POST["action"]);
$action = sec_cleanTAGS($_POST["action"]);
$action = sec_addESC($action);

regex_standard($_POST["id"]);
$id = sec_cleanTAGS($_POST["id"]);
$id = sec_addESC($id);

regex_standard($_POST["name"]);
$name = sec_cleanTAGS($_POST["name"]);
$name = sec_addESC($name);

regex_numbers($_POST["server"]);
$server = sec_cleanTAGS($_POST["server"]);
$server = sec_addESC($server);

regex_standard($_POST["server_select"]);
$server_select = sec_cleanTAGS($_POST["server_select"]);
$server_select = sec_addESC($server_select);

regex_standard($_POST["log_type"]);
$log_type = sec_cleanTAGS($_POST["log_type"]);
$log_type = sec_addESC($log_type);

regex_standard($_POST["log_action"]);
$log_action = sec_cleanTAGS($_POST["log_action"]);
$log_action = sec_addESC($log_action);

regex_standard($_POST["log_group"]);
$log_group = sec_cleanTAGS($_POST["log_group"]);
$log_group = sec_addESC($log_group);

regex_numbers($_POST["log_contains"]);
$log_contains = sec_cleanTAGS($_POST["log_contains"]);
$log_contains = sec_addESC($log_contains);

regex_standard($_POST["log_contains_fiel"]);
$log_contains_field = sec_cleanTAGS($_POST["log_contains_field"]);
$log_contains_field = sec_addESC($log_contains_field);

regex_regex($_POST["log_contains_regex"]);
$log_contains_regex = sec_cleanTAGS($_POST["log_contains_regex"]);
$log_contains_regex = sec_addESC($log_contains_regex);

if (is_array ($_POST["log_contains_list"])) {
    $log_contains_list = implode(",",$_POST["log_contains_list"]);
    regex_standard($log_contains_list);
    $log_contains_list = sec_cleanTAGS($log_contains_list);
    $log_contains_list = sec_addESC($log_contains_list);
}

regex_numbers($_POST["log_exclude"]);
$log_exclude = sec_cleanTAGS($_POST["log_exclude"]);
$log_exclude = sec_addESC($log_exclude);

regex_standard($_POST["log_exclude_field"]);
$log_exclude_field = sec_cleanTAGS($_POST["log_exclude_field"]);
$log_exclude_field = sec_addESC($log_exclude_field);

regex_regex($_POST["log_exclude_regex"]);
$log_exclude_regex = sec_cleanTAGS($_POST["log_exclude_regex"]);
$log_exclude_regex = sec_addESC($log_exclude_regex);

if (is_array ($_POST["log_exclude_list"])) {
    $log_exclude_list = implode(",",$_POST["log_exclude_list"]);
    regex_standard($log_exclude_list);
    $log_exclude_list = sec_cleanTAGS($log_exclude_list);
    $log_exclude_list = sec_addESC($log_exclude_list);
}

regex_standard($_POST["occurrences_number"]);
$occurrences_number = sec_cleanTAGS($_POST["occurrences_number"]);
$occurrences_number = sec_addESC($occurrences_number);

regex_standard($_POST["occurrences_within"]);
$occurrences_within = sec_cleanTAGS($_POST["occurrences_within"]);
$occurrences_within = sec_addESC($occurrences_within);

regex_standard($_POST["criticality"]);
$criticality = sec_cleanTAGS($_POST["criticality"]);
$criticality = sec_addESC($criticality);

regex_numbers($_POST["active_email"]);
$active_email = sec_cleanTAGS($_POST["active_email"]);
$active_email = sec_addESC($active_email);

regex_email($_POST["email"]);
$email = sec_cleanTAGS($_POST["email"]);
$email = sec_addESC($email);

if (is_array ($_POST["list_email"])) {

    $list_email = implode(",",$_POST["list_email"]);

    regex_email($list_email);
    $list_email = sec_cleanTAGS($list_email);
    $list_email = sec_addESC($list_email);

    if ($list_email == "" and $email == "") {
	$email = "";
    } else {
	$email = $list_email . " " . $email;
    }

}

if ($action == "new")
{

$sql = "INSERT INTO alert_config (name, server, server_select, log_type, log_action, log_group, log_contains, log_contains_field, log_contains_regex, log_contains_list, log_exclude, log_exclude_field, log_exclude_regex, log_exclude_list, occurrences_number, occurrences_within, criticality, active_email, email) 
		VALUES ('$name', '$server', '$server_select', '$log_type', '$log_action', '$log_group', '$log_contains', '$log_contains_field', '$log_contains_regex', '$log_contains_list', '$log_exclude', '$log_exclude_field', '$log_exclude_regex', '$log_exclude_list', '$occurrences_number', '$occurrences_within', '$criticality', '$active_email', '$email')";

//echo $sql;
//exit;
$conn = mysql_query($sql);

} 
else if ($action == "edit")
{

$sql = "UPDATE alert_config SET 
		name = '$name', 
		server = '$server', 
		server_select = '$server_select',
		log_type = '$log_type', 
		log_action = '$log_action', 
		log_group = '$log_group',
		log_contains = '$log_contains',
		log_contains_field = '$log_contains_field', 
		log_contains_regex = '$log_contains_regex',
		log_contains_list = '$log_contains_list',
		log_exclude = '$log_exclude', 
		log_exclude_field = '$log_exclude_field', 
		log_exclude_regex = '$log_exclude_regex', 
		log_exclude_list = '$log_exclude_list', 
		occurrences_number = '$occurrences_number', 
		occurrences_within = '$occurrences_within', 
		criticality = '$criticality',
		active_email = '$active_email', 
		email = '$email' 
	WHERE id = $id ";

//echo $sql;
//exit;
$conn = mysql_query($sql);	

}

header("Location: alert_config_list.php"); 

?>
