<? include "verify.php" ?>
<html>
<head>
	<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
	<title>Auth2DB</title>
</head>
<body>

<? include "header.php" ?>

<p class="itemsMenu001"></p>


<?

include "conn.php";
include "security.php";

$action = sec_cleanTAGS($_GET["action"]);
$action = sec_addESC($action);

$id = sec_cleanTAGS($_GET["id"]);
$id = sec_addESC($id);

$sql = "SELECT * FROM users WHERE id = '$id' ";
$result = mysql_query($sql);

$rs = mysql_fetch_object($result);

?>

<div class="bloque">
<p class="title">Users Config</p>
<p class="itemsMenu001"></p>
</div>

<div class="centerbox">
<form action="user_action.php" method="POST">

<? show_error(); ?>

<table cellpadding="4" cellspacing="1" border=0>
	<tr>
		<td align=right >User Name: </td>
		<td><input type="text" name="username" value="<? echo $rs->username?>"></td>
	</tr>
	<tr>
		<td align=right >User Password: </td>
		<td><input type="password" name="password" value=""> </td>
	</tr>
	<tr>
		<td align=right >Retype Password: </td>
		<td><input type="password" name="password_again" value=""> </td>
	</tr>
	<tr>
		<td align=right >Access Level: </td>
		<td>
			<select class="field" name="access_level" size="1" >
				<?
					/*
					$arrayAccess = array('Administrator', 'Opeartor' );
					for($i=0;$i<=sizeof($arrayAccess) - 1;$i++)
					{
				?>
					<option value="<? echo $arrayAccess[$i]  ?>" <? if ($rs->access_level == $arrayAccess[$i] ) echo "selected" ?> ><? echo $arrayAccess[$i] ?></option>
				<? 
					} 
					*/
				?>
				<?
					$sql = "SELECT * FROM ac_role";
					$result_role = mysql_query($sql);
					while ($rs_role = mysql_fetch_object($result_role)) {
						echo "<option value='" . $rs_role->id . "'";
						if ($rs->access_level == $rs_role->id ) echo "selected";
						echo ">" . $rs_role->name_role . "</option>";
					}
					
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td align=right >Email: </td>
		<td><input type="text" name="email" value="<? echo $rs->email?>"></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" value="Save" ></td>
	</tr>
</table>
<input type="hidden" name="action" value="<? echo $action ?>" >
<input type="hidden" name="id" value="<? echo $id ?>" >
</form>
</div>