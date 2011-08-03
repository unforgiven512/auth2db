<? session_start(); ?>
<html>
<head>
	<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
	<title>Auth2DB</title>
</head>
<body>

<? 
include "header.php";
include "security.php";
include "menu_general.php"; 
?>

<p class="itemsMenu001"></p>

<?
include "conn.php";

$sql = "SELECT distinct login.action, action_config.* FROM login
	LEFT JOIN action_config ON login.action = action_config.action_name ORDER BY color
	";

$sql = "SELECT distinct T1.action_name AS action, action_config.* FROM tipo_action_config AS T1
	LEFT JOIN action_config ON T1.action_name = action_config.action_name ORDER BY color
	";

$result = mysql_query($sql);

?>

<div class="bloque">
<p class="title">Action Config</p>
<p class="itemsMenu001"></p>
</div>

<div class="centerbox">
<br><br>
<table>
	<tr class="filasTituloMain01">
		<td nowrap>Action</td>
		<td width="80" nowrap><b>Action Alias</b></td>
		<td nowrap><b>Color</b></td>
		<td width="20"></td>
	</tr>
<?
while($rs = mysql_fetch_object($result))
{
?>
	<tr style="background: #555555">
		<td><? echo $rs->action ?></td>
		<td><? echo $rs->action_alias ?></td>
		<td align=center><div class="box-color" style="background-color: #<? echo $rs->color ?>"></div></td>
		<td align=center><a href="action_config_add.php?action=<? echo $rs->action ?>"><img src="icons/edit.png" border=0 height=12></a></td>
	</tr>
<?
}
?>
</div>