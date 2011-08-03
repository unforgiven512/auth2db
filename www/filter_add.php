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

$sql = "SELECT * FROM filter WHERE id = '$id' ";
$result = mysql_query($sql);

$rs = mysql_fetch_object($result);

?>

<div class="bloque">
<p class="title">Filters Config</p>
<p class="itemsMenu001"></p>
</div>

<div class="centerbox">
<form action="filter_action.php" method="POST">

<? show_error(); ?>

<table cellpadding="4" cellspacing="1" border=0>
	<tr>
		<td align=right >User Name: </td>
		<td><input type="text" name="filter" value="<? echo $rs->filter?>"></td>
	</tr>
	<tr>
		<td valign=top align=right >Log message contains :<br>(REGEX) </td>
		<td><textarea class="field" style="width: 200px;" name="regex" value="" rows="4" ><? echo $rs->regex ?></textarea></td>
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