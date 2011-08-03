<? include "verify.php" ?>
<?
include "conn.php";
include "security.php";

regex_standard($_POST["action"]);
$action = sec_addESC($_POST["action"]);

regex_numbers($_POST["id"]);
$id = sec_addESC($_POST["id"]);

regex_standard($_POST["source"]);
$source = sec_addESC($_POST["source"]);

regex_numbers($_POST["enabled"]);
$enabled = sec_addESC($_POST["enabled"]);

$filter = $_POST["filter"];


if ($action == "edit" ){
	$sql = "UPDATE source SET source = '$source', enabled = '$enabled' WHERE id = '$id' ";
	$conn = mysql_query($sql);
	
} else if ($action == "new" ){
	$sql = "INSERT INTO source (source, enabled) VALUES ('$source','$enabled')";
	$conn = mysql_query($sql);
}

// MM_SOURCE_FILTER DELETE AND INSERTS
$sql = "DELETE FROM mm_source_filter WHERE source_id = $id";
$conn = mysql_query($sql);

for ($i=0;$i<=(sizeof($filter)-1);$i++) {

	$sql = "INSERT INTO mm_source_filter (source_id, filter_id) VALUES ('$id','$filter[$i]')";
	$conn = mysql_query($sql);
}

header("Location: source_config_list.php"); 


?>