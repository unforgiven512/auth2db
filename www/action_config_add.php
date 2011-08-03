<html>
<head>
	<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
	<title>Auth2DB</title>
</head>
<body>

<? include "header.php" ?>

<p class="itemsMenu001"></p>

<div class="bloque">
<p class="title">Action Config</p>
<p class="itemsMenu001"></p>
</div>

<div class="centerbox">

<?
include "conn.php";
include "security.php";

$action = sec_cleanTAGS($_GET["action"]);
$action = sec_addESC($action);

show_error();

$sql = "SELECT * from action_config WHERE action_name = '$action'";
$result = mysql_query($sql);

$rs = mysql_fetch_object($result);

?>

<form action="action_config_action.php" method="POST">
<table  border="0" cellpadding="4" cellspacing="1" >
	<tr>
		<td align=right >Action: </td>
		<td><b><? echo $action ?></b></td>
	</tr>
	<tr>
		<td align=right >Action Alias: </td>
		<td><input type="text" name="action_alias" value="<? echo $rs->action_alias?>"> (optional) </td>
	</tr>
	<tr>
		<td align=right valign=top>Color: </td>
		<td>
			<table>
				<? 
						$arrayColor = array('00FF99', '00FF22', '00FE22','FF9999','FFBBBB','EEEEEE' );
						for($i=0;$i<=sizeof($arrayColor) - 1;$i++)
						{
				?>
				<tr>
					<td><input type=radio name="color" value="<? echo $arrayColor[$i]  ?>" <? if ($rs->color == $arrayColor[$i] ) echo "checked" ?> ></td>
					<td><div class="box-color" style="background-color: #<? echo $arrayColor[$i]  ?>" ></div></td>
					<td>#<? echo $arrayColor[$i]  ?></td>
				</tr>
				<? } ?>
				<tr>
					<td><input type=radio name="color" value="other"></td>
					<td></td>
					<td><input type="text" name="other_color" value="<? echo $rs->color?>"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" value="Save" ></td>
	</tr>
</table>
<input type="hidden" name="action" value="<? echo $action ?>" >
</form>

</div>