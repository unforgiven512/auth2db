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

$sql = "SELECT * FROM list_config ORDER BY id DESC";
$result = mysql_query($sql);

?>
<div class="bloque">
<p class="title">Lists Config</p>
<p class="itemsMenu001"></p>
</div>

<div class="centerbox">

<br>
<a href="list_add.php?action=new"><img src="icons/edit.png" border=0 ></a> Add New List
<br><br>
<table>
	<tr class="filasTituloMain01">
		<td width="60"><b>Name</b></td>
		<td width="60"><b>Description</b></td>
		<td width="20">Items</td>
		<td width="20"></td>
		<td width="20"></td>
	</tr>
<?
while($rs = mysql_fetch_object($result))
{
?>
	<tr style="background: #555555">
		<td><? echo $rs->name ?></td>
		<td><? echo $rs->description ?></td>
		<td><?
                        $sql = "SELECT count(*) as cantidad FROM list_item WHERE id_list = " . $rs->id ;
                        $result_notifications = mysql_query($sql);
                        $cantidad = mysql_fetch_object($result_notifications);
                        echo $cantidad->cantidad;
		?></td>
		<td align=center><a href="list_add.php?action=edit&id=<? echo $rs->id ?>"><img src="icons/edit.png" border=0 height=12></a></td>
		<td align=center><a href="#?id=<? echo $rs->id ?>"><img src="icons/cancel.png" border=0 height=12></a></td>
	</tr>
<?
}
?>
</div>
