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

<div class="bloque">
<p class="title">Mail Server Config</p>
<p class="itemsMenu001"></p>
</div>

<div class="centerbox">

<?
include "conn.php";

$hostname = sec_cleanTAGS($_GET["hostname"]);
$hostname = sec_addESC($hostname);

show_error();

$sql = "SELECT * from smtp_config";
$result = mysql_query($sql);

$rs = mysql_fetch_object($result);

?>

<form action="smtp_action.php" method="POST">
<table  border="0" cellpadding="4" cellspacing="1" >
	<tr>
		<td align=right >SMTP: </td>
		<td><input type="text" name="smtp_server" value="<? echo $rs->smtp_server ?>"></td>
	</tr>
	<tr>
		<td align=right >PORT: </td>
		<td><input type="text" name="smtp_port" value="<? echo $rs->smtp_port ?>"> (default 25)</td>
	</tr>
	<tr>
		<td align=right >Mail From: </td>
		<td><input type="text" name="mail_from" value="<? echo $rs->mail_from ?>"></td>
	</tr>
	<tr>
		<td align=right >Active Authenticate: </td>
		<td><input type="checkbox" name="auth_active" value="1" <? if ($rs->auth_active == 1) echo "checked" ?> ></td>
	</tr>
	<tr>
		<td align=right >Auth User: </td>
		<td><input type="text" name="auth_user" value="<? echo $rs->auth_user ?>"></td>
	</tr>
	<tr>
		<td align=right >Auth Pass: </td>
		<td><input type="password" name="auth_pass" value="<? echo $rs->auth_pass ?>"></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" value="Save" ></td>
	</tr>
</table>
</form>
<? 

$active_msg = $_GET["active_msg"]; 
if ($active_msg == 1){
	echo "<p align=center>SMTP config has been updated.</p>";
	}

?>
</div>