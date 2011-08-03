<? include "verify.php" ?>
<html>
<head>
	<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
	<title>Auth2DB</title>
</head>
<body>

<? include "header.php" ?>

<p class="itemsMenu001"></p>

<div class="bloque">
<p class="title">Hosts Config</p>
<p class="itemsMenu001"></p>
</div>

<div class="centerbox">

<?
include "conn.php";
include "security.php";

$hostname = sec_cleanTAGS($_GET["hostname"]);
$hostname = sec_addESC($hostname);

show_error();

$sql = "SELECT * from host_config WHERE hostname = '$hostname'";
$result = mysql_query($sql);

$rs = mysql_fetch_object($result);

?>

<form action="host_action.php" method="POST">
<table  border="0" cellpadding="4" cellspacing="1" >
	<tr>
		<td align=right >Hostname: </td>
		<td><b><? echo $hostname ?></b></td>
	</tr>
	<tr>
		<td align=right >Host Type: </td>
		<td>
			<select class="field" name="type" size="1" >
				<? 
					$arrayType = array('Linux', 'Windows', 'Cisco', 'Other' );
					for($i=0;$i<=sizeof($arrayType) - 1;$i++)
					{
				?>
					<option value="<? echo $arrayType[$i]  ?>" <? if ($rs->type == $arrayType[$i] ) echo "selected" ?> ><? echo $arrayType[$i] ?></option>
				<? } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td align=right >IP: </td>
		<td><input type="text" name="ip" value="<? echo sec_cleanHTML($rs->ip)?>"> (optional) </td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" value="Save" ></td>
	</tr>
</table>
<input type="hidden" name="hostname" value="<? echo $hostname ?>" >
</form>

</div>