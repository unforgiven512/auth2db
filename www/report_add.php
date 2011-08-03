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
<html>
<head>
    <link href="style.css" rel="stylesheet" type="text/css">
    <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
    <title>Auth2DB</title>
</head>
<body>

<?
include "conn.php";
include "security.php";
include "header.php";
include "menu_general.php";

$action = sec_cleanTAGS($_GET["action"]);
$action = sec_addESC($action);

$id = sec_cleanTAGS($_GET["id"]);
$id = sec_addESC($id);


?>
<p class="itemsMenu001">

<div class="bloque">
    <p class="title">Report Config</p>
    <p class="itemsMenu001"></p>
</div>

<div class="centerbox">

<?
/*
$sql = "SHOW FIELDS from smtp_config";
$result = mysql_query($sql);

$rs = mysql_fetch_row($result);

#echo $rs[0];

while ($row = mysql_fetch_row($result) ) {
    echo $row[]
}
*/
//$id = sec_cleanTAGS($_GET["id"]);

if ($id != "") {
	$sql = "SELECT * from reports WHERE id = $id";
	$result = mysql_query($sql);

	$rs = mysql_fetch_object($result);
}

show_error();

?>

<form action="report_action.php" method="POST">

<table>
    <tr>
	<td valign="top" align="right">
	    Report Name
	</td>
	<td>
	    <input type="text" name="report_name" value="<?=$rs->report_name ?> ">
	<tr>
    </tr>
    <tr>
	<td valign="top" align="right">
	    Description
	</td>
	<td>
	    <!-- <textarea type="text" name="description" ><?=$rs->description ?></textarea> -->
	    <input type="text" name="description" value="<?=$rs->description ?> ">
	<tr>
    </tr>
    <tr>
	<td valign="top" align="right">
	    Select Fields
	</td>
	<td>
	    <select multiple name="fields[]" class="" style="width: 100px">
	    <?
	    
		$sql = "SELECT * from login LIMIT 1";
		$result = mysql_query($sql);
		
		while ($i < mysql_num_fields($result)) {
		    //echo "Information for column $i:<br />\n";
		    $meta = mysql_fetch_field($result, $i);
		    //if (!$meta) {
			//echo "No information available<br />\n";
		    //}
		    /*    
			echo "<pre>
			blob:         $meta->blob
			max_length:   $meta->max_length
			multiple_key: $meta->multiple_key
			name:         $meta->name
			not_null:     $meta->not_null
			numeric:      $meta->numeric
			primary_key:  $meta->primary_key
			table:        $meta->table
			type:         $meta->type
			default:      $meta->def
			unique_key:   $meta->unique_key
			unsigned:     $meta->unsigned
			zerofill:     $meta->zerofill
			</pre>";
		    */

		    $fields_values = explode(",",$rs->fields_values);

		    if(in_array($meta->name, $fields_values)) {
			$valor = "selected";
		    };   // $key = 1;
		    
		    echo "<option $valor>$meta->name</option>\n";
		    
		    $valor = "";
    
		    $i++;
		}
	    ?>
	    </select>

	</td>
    <tr>
    </tr>
	<td valign="top" align="right">
	    WHERE <br>
	    (use [] like '')
	</td>
	<td>
	    <textarea name="where" type="text" rows=8 cols=50><?=$rs->where_values?></textarea>

	    <br>
	    <br>
	    <input type="hidden" name="show" value="1">
	    <input type="submit" value="Save Report">

	</td>
    </tr>
</table>
<input type="hidden" name="action" value="<? echo $action ?>" >
<input type="hidden" name="id" value="<? echo $id ?>" >
</form>

</div>

</body>
</html>
