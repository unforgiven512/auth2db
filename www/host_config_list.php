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

$sql = "SELECT distinct server, host_config.* FROM login
	    LEFT JOIN host_config ON login.server = host_config.hostname
	";


$ano = date("Y");
$mes = date("m");
$dia = date("d");

if (table_exists($ano."_".$mes."_".$dia) == 1) {
    $sql = "SELECT distinct T1.server AS server, T3.type AS type, T3.ip FROM log_".$ano."_".$mes."_".$dia." AS T1
	    LEFT JOIN host_config AS T3 ON T1.server = T3.hostname
	UNION 
	SELECT distinct T2.hostname AS server, T3.type AS type, T3.ip  FROM host_config AS T2
	    LEFT JOIN host_config AS T3 ON T2.hostname = T3.hostname
	ORDER BY type DESC, server
	";
} else {
    $sql = "SELECT distinct T1.server AS server, T3.type AS type, T3.ip FROM log_0000_00_00 AS T1
	    LEFT JOIN host_config AS T3 ON T1.server = T3.hostname
	UNION 
	SELECT distinct T2.hostname AS server, T3.type AS type, T3.ip  FROM host_config AS T2
	    LEFT JOIN host_config AS T3 ON T2.hostname = T3.hostname
	ORDER BY type DESC, server
	";
}

		
$result = mysql_query($sql);

?>

<div class="bloque">
<p class="title">Hosts Config</p>
<p class="itemsMenu001"></p>
</div>

<div class="centerbox">
<br><br>
<table>
	<tr class="filasTituloMain01">
		<td nowrap></td>
		<td width="80" nowrap><b>Hostname</b></td>
		<td width="80" nowrap><b>Host IP</b></td>
		<td width="20"></td>
		<td width="20"></td>
	</tr>
<?
while($rs = mysql_fetch_object($result))
{
?>
	<tr style="background: #555555">
		<td>
			<?
				if (strtolower(sec_cleanHTML($rs->type)) == "linux"){
					echo "<img src='icons/tux.png' >" ;
				} else if (strtolower(sec_cleanHTML($rs->type)) == "windows"){
					echo "<img src='icons/win.png' >" ;
				} else if (strtolower(sec_cleanHTML($rs->type)) == "cisco"){
					echo "<img src='icons/cisco.gif' >" ;
				}else{
					echo "<img src='icons/server.png' >" ;
				}
			?>
		</td>
		<td><? echo sec_cleanHTML($rs->server) ?></td>
		<td><? echo sec_cleanHTML($rs->ip) ?></td>
		<td align=center><a href="host_add.php?hostname=<? echo sec_cleanHTML($rs->server) ?>"><img src="icons/edit.png" border=0 height=12></a></td>
		<td align=center><a href="#?id=<? echo sec_cleanHTML($rs->id) ?>"><img src="icons/cancel.png" border=0 height=12></a></td>
	</tr>
<?
}
?>
</div>