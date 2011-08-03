<? include "verify.php" ?>
<?

#  This program is free software; you can redistribute it and/or modify
#  it under the terms of the GNU General Public License as published by
#  the Free Software Foundation; either version 2 of the License, or
#  (at your option) any later version.
# 
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU General Public License for more details.
# 
#  You should have received a copy of the GNU General Public License
#  along with this program; if not, write to the Free Software
#  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

#  Copyright (c) 2007,2008 Ezequiel Vera

?>
<?
include "conn.php";
include "security.php";

regex_standard($_POST["action"]);
$action = sec_addESC($_POST["action"]);

regex_standard($_POST["id"]);
$id = sec_addESC($_POST["id"]);

regex_standard($_POST["report_name"]);
$report_name = sec_addESC($_POST["report_name"]);

regex_standard($_POST["description"]);
$description = sec_addESC($_POST["description"]);

//$fields = $_POST["fields"];

$fields_values = implode(",",$_POST["fields"]);
$fields_values = sec_addESC($fields_values);
//regex_standard($fields_values);

regex_where($_POST["where"]);
$where_values = sec_addESC($_POST["where"]);

$fecha = date("Y-m-d H:i:s");

if ($action == "edit" ){
    
    $sql = "UPDATE reports SET report_name = '$report_name', description = '$description', fields_values = '$fields_values', where_values = '$where_values' WHERE id = '$id' ";
    $conn = mysql_query($sql);
	
	
} else if ($action == "new" ){
	
    $sql = "INSERT INTO reports (report_name,description,fields_values,where_values,fecha) VALUES ('$report_name','$description','$fields_values','$where_values','$fecha')";
    $conn = mysql_query($sql);
	
}
//echo $sql;

header("Location: report_config_list.php"); 

?>