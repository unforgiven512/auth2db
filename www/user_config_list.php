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

$sql = "SELECT * FROM users ORDER BY id DESC";
$sql = "SELECT * FROM users AS T1
		LEFT JOIN ac_role AS T2 ON T1.access_level = T2.id";
$result = mysql_query($sql);

?>

<div class="bloque">
<p class="title">Users Config</p>
<p class="itemsMenu001"></p>
</div>

<div class="centerbox">
<br>
<a href="user_add.php?action=new"><img src="icons/edit.png" border=0 ></a> Add New User
<br><br>
<table>
	<tr class="filasTituloMain01">
		<td width="80" nowrap><b>User Name</b></td>
		<td width="80" nowrap><b>Access Level</b></td>
		<td width="20"></td>
		<td width="20"></td>
		<td width="20"></td>
	</tr>
<?
while($rs = mysql_fetch_object($result))
{
?>
	<tr style="background: #555555">
		<td><? echo $rs->username ?></td>
		<td><? echo $rs->name_role ?></td>
		<td align=center><a href="user_add.php?action=edit&id=<? echo $rs->id ?>"><img src="icons/edit.png" border=0 height=12></a></td>
		<td align=center><a href="?id=<? echo $rs->id ?>"><img src="icons/cancel.png" border=0 height=12></a></td>
		<td nowrap><a href="user_login_list.php?id=<? echo $rs->id ?>">View Logins</a></td>
	</tr>
<?
}
?>
</div>