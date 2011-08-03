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

$sql = "SELECT * FROM alert_config ORDER BY id DESC";
$result = mysql_query($sql);

?>
<div class="bloque">
<p class="title">Alerts Config</p>
<p class="itemsMenu001"></p>
</div>

<div class="centerbox">

<br>
<a href="alert_add.php?action=new"><img src="icons/edit.png" border=0 ></a> Add New Alert
<br><br>
<table>
	<tr class="filasTituloMain01">
		<td width="60"><b>Name</b></td>
		<td width="60"><b>criticality</b></td>
		<td width="80"><b>Notifications</b></td>
		<td width="20"></td>
		<td width="20"></td>
	</tr>
<?
while($rs = mysql_fetch_object($result))
{
?>
	<tr style="background: #555555">
		<td><? echo $rs->name ?></td>
		<td><? echo $rs->criticality ?></td>
		<td><? 
						$sql = "SELECT count(*) as cantidad FROM alert WHERE id_alert_config = " . $rs->id ; 
						$result_notifications = mysql_query($sql);
						$cantidad = mysql_fetch_object($result_notifications);
						echo $cantidad->cantidad;
						
					?></td>
		<td align=center><a href="alert_add.php?action=edit&id=<? echo $rs->id ?>"><img src="icons/edit.png" border=0 height=12></a></td>
		<td align=center><a href="#?id=<? echo $rs->id ?>"><img src="icons/cancel.png" border=0 height=12></a></td>
	</tr>
<?
}
?>
</div>