<? 
include "verify.php";
include "security.php";
?>
<html>
<head>
	<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
	<title>Auth2DB</title>
</head>
<body>

<? include "header.php" ?>
<? include "menu_general.php" ?>

<p class="itemsMenu001"></p>

<?
include "conn.php";

$sql = "SELECT * FROM view ORDER BY id DESC";

$result = mysql_query($sql);

?>

<div class="bloque">
<p class="title">Views Config</p>
<p class="itemsMenu001"></p>
</div>

<div class="centerbox">
<br>
<a href="view_add.php?action=new"><img src="icons/edit.png" border=0 ></a> Add New Filter
<br><br>
<table>
	<tr class="filasTituloMain01">
		<td width="80" nowrap><b>View Name</b></td>
		<td width="60" nowrap><b>Enabled</b></td>
		<td width="20"></td>
		<td width="20"></td>
	</tr>
<?
while($rs = mysql_fetch_object($result))
{
?>
	<tr style="background: #555555">
		<td><? echo $rs->view ?></td>
		<td><? if ($rs->enabled == 1) echo 'enabled'; else echo 'disabled'; ?></td>
		<td align=center><a href="view_add.php?action=edit&id=<? echo $rs->id ?>"><img src="icons/edit.png" border=0 height=12></a></td>
		<td align=center><a href="?id=<? echo $rs->id ?>"><img src="icons/cancel.png" border=0 height=12></a></td>
	</tr>
<?
}
?>
</div>