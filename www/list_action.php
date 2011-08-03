<? 
include "verify.php";
include "conn.php";
include "security.php";

$name = $_POST["name"];
$description = $_POST["description"];
$items = $_POST["items"];
$id_list = $_POST["id_list"];

$action = $_POST["action"];



function new_list($name,$description) {
    $sql = "INSERT INTO list_config (name,description) VALUES ('$name','$description')";
    $result = mysql_query($sql) or die("Couldn't execute query");
    
    $sql = "SELECT LAST_INSERT_ID() AS last_id";
    $result = mysql_query($sql) or die("Couldn't execute query");
    $rs = mysql_fetch_object($result);
    return $rs->last_id;
}

function update_list($name,$description,$id_list) {
    $sql = "UPDATE list_config SET name = '$name', description = '$description' WHERE id = $id_list";
    $result = mysql_query($sql) or die("Couldn't execute query");
}

function delete_list($id_list) {
    $sql = "DELETE FROM list_item WHERE id_list = $id_list";
    $result = mysql_query($sql) or die("Couldn't execute query");
}

function list_simple_add($id_list,$items) {

    if (isset($items))
    {
	$items_line = explode("\r\n",$items);
	
	for($i = 0; $i < count($items_line); $i++)
	{
		
		//echo "Piece $i = $users_line[$i] <br />";
		
		
		$items_array = explode(";",$items_line[$i]);
		//echo $items_line[$i]."<br>";
		//echo count($users_array)."<br>";
		//if (count($items_array) == 1)
		//{
			/*
			for($j = 0; $j < count($users_array); $j++)
			{
				//echo "Piece $j = $users_array[$j] <br />";
	
			}
			*/
			
			//$sql = "SELECT item from list_item WHERE list_id = UCASE('$items_array[0]')";
			$sql = "SELECT item from list_item WHERE id_list = $id_list";
			//echo $sql;
			$result_id = mysql_query($sql) or die("Couldn't execute query");
			$rs_id = mysql_fetch_object($result_id);
			//echo $rs_srv_id->ID;
			//exit;
			
			//$sql = "SELECT count(*) as cantidad from list_item WHERE id = '$items_array[0]' AND user = '$items_array[1]'";
			$sql = "SELECT count(*) as cantidad from list_item WHERE id_list = $id_list AND item = '$items_line[$i]'";
			$result = mysql_query($sql) or die("Couldn't execute query");
			$rs = mysql_fetch_object($result);
			
			//echo $rs->cantidad;
			
			if($rs->cantidad == 0 AND $items_line[$i] != "")
			{	
				$sql_insert = "INSERT INTO list_item (id_list,item) VALUES ($id_list,'$items_line[$i]')";	
				
				$result_insert = mysql_query($sql_insert) or die("Couldn't execute query");
				//echo $sql_insert."<br>";
			} else {
			    //echo "ya existe...<br><br>";
			}
		//} 
		//else
		//{
		//	echo "error line... <br>";
		//}
	}
	
    }

}

if ($action == "new") {

    $id_list = new_list($name,$description);
    list_simple_add($id_list,$items);

} else if ($action == "edit") {

    update_list($name,$description,$id_list);
    delete_list($id_list);
    list_simple_add($id_list,$items);

}

header("Location: list_config_list.php");

?>