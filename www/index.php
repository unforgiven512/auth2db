<?
include "header.php";
?>

<p class="itemsMenu001"></p>

<br><br><br>
<div class="indexlink">
<form method="POST" action="login.php">
	
	<table>
		<tr>
			<td>Username: </td>
			<td><input type="text" name="username" size="20"></td>
		</tr>
		<tr>
			<td>Password: </td>
			<td><input type="password" name="password" size="20"></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="Submit" name="login"></td>
		</tr>
	</table>
	
</form>
<?
$error = $_GET["error"];
if ( $error == 1){
	echo "Incorrect login name or password. Please try again.";
	}
?>
</div>