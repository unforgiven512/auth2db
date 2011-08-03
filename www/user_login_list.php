<? include "verify.php" ?>
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

$id = sec_cleanTAGS($_GET["id"]);
$id = sec_addESC($id);

$sql = "SELECT * FROM users_audit WHERE user_id = $id ORDER BY id DESC";
$result = mysql_query($sql);

?>

<div class="bloque">
<p class="title">Users Login List</p>
<p class="itemsMenu001"></p>
</div>

<div class="centerbox">

<table>
	<tr class="filasTituloMain01">
		<td nowrap><b>User Name</b></td>
		<td nowrap><b>Remote Host</b></td>
		<td >Start Session</td>
		<td >Close Session</td>
	</tr>
<?
while($rs = mysql_fetch_object($result))
{
?>
	<tr style="background: #555555">
		<td><? echo sec_cleanHTML($rs->username) ?></td>
		<td><? echo sec_cleanHTML($rs->remote_host) ?></td>
		<td ><? echo sec_cleanHTML($rs->start_session) ?></td>
		<td ><? echo sec_cleanHTML($rs->close_session) ?></td>
	</tr>
<?
}
?>
</div>