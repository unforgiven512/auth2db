<? include "verify.php" ?>
<html>
<head>
	<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
	<title>Auth2DB</title>
</head>
<body>

<? include "header.php" ?>

<p class="itemsMenu001"></p>


<?

include "conn.php";
include "security.php";

$action = sec_cleanTAGS($_GET["action"]);
$action = sec_addESC($action);

$id = sec_cleanTAGS($_GET["id"]);
$id = sec_addESC($id);

$sql = "SELECT * FROM source WHERE id = '$id' ";
$result = mysql_query($sql);

$rs = mysql_fetch_object($result);

?>

<div class="bloque">
<p class="title">Source Config</p>
<p class="itemsMenu001"></p>
</div>

<div class="centerbox">
<form action="source_action.php" method="POST">

<? show_error(); ?>

<table cellpadding="4" cellspacing="1" border=0>
	<tr>
		<td align=right >Source: </td>
		<td><input type="text" name="source" value="<? echo $rs->source?>"></td>
	</tr>
	<tr>
		<td valign="top" align="right">
		    Select Filter:
		</td>
		<td>
		    <select multiple name="filter[]" class="field" style="width: 200px" size="5">
		    <?

			$sql = " SELECT filter.id, filter.filter, mm.filter_id   from filter 
				 LEFT JOIN mm_source_filter AS mm ON mm.source_id = $id AND mm.filter_id = filter.id";
			$result = mysql_query($sql);
			
			while ($rs_filter = mysql_fetch_object($result)) {
			    //echo "<option $rs_filter->id>$rs_filter->filter</option>\n";
			?>		    
			    <option value="<?=$rs_filter->id?>" <? if ($rs_filter->id == $rs_filter->filter_id ) echo "selected" ?> ><?=$rs_filter->filter?></option>
			<?  

			}
		    ?>
		    </select>

		</td>
	</tr>
	<tr>
		<td align=right ></td>
		<td><input type="radio" name="enabled" value="0" <? if($rs->enabled == 0) echo "checked" ?>>disabled <input type="radio" name="enabled" value="1" <? if($rs->enabled == 1) echo "checked" ?>>enabled</td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" value="Save" ></td>
	</tr>
</table>
<input type="hidden" name="action" value="<? echo $action ?>" >
<input type="hidden" name="id" value="<? echo $id ?>" >
</form>
</div>