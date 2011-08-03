<? include "verify.php" ?>
<link href="style.css" rel="stylesheet" type="text/css">

<?
include "conn.php";

$sql = "SELECT * FROM event_config";
				
$result = mysql_query($sql);

include "header.php";

?>

<p class="itemsMenu001"></p>


<table border=1>
	<tr>
		<td>name event</td>
		<td>email_active</td>
		<td>email</td>
		<td>ban_check_period</td>
		<td>ban_check_time</td>
		<td>ban_check_interval</td>
		<td>ban_check_maxretry</td>
		<td>ban_period</td>
		<td>ban_time</td>
	</tr>
		<?

		while ($rs = mysql_fetch_object($result) )
		{
		?>
		<tr>
			<td><? echo $rs->name ?></td>
			<td><? echo $rs->email_active ?></td>
			<td><? echo $rs->email ?></td>
			<td><? echo $rs->ban_check_period ?></td>
			<td><? echo $rs->ban_check_time ?></td>
			<td><? echo $rs->ban_check_interval ?></td>
			<td><? echo $rs->ban_check_maxretry ?></td>
			<td><? echo $rs->ban_period ?></td>
			<td><? echo $rs->ban_time ?></td>
		</tr>
		<?
		}
		?>

</table>