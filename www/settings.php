<?
session_start();
include "header.php";
include "security.php"; 
include "menu_general.php";
?>

<p class="itemsMenu001"></p>

<div class="bloque">
<p class="title">Settings Config</p>
<p class="itemsMenu001"></p>
</div>

<div class="indexlink">
<table>
    <tr>
	<td  valign=top>
		<table>
			<? if (rac_verify(109)) { ?>
			<tr>
				<td><img src="icons/computer.png" border=0></td>
				<td><a href="host_config_list.php">Hosts Config</a></td>
			</tr>
			<? } ?>
			<? if (rac_verify(11)) { ?>
			<tr>
				<td><img src="icons/system-users.png" border=0></td>
				<td><a href="user_config_list.php">Users Config</a></td>
			</tr>
			<? } ?>
			<? if (rac_verify(129)) { ?>
			<tr>
				<td><img src="icons/x-office.png" border=0></td>
				<td><a href="report_config_list.php">Reports Config</a></td>
			</tr>
			<? } ?>
			<? if (rac_verify(13)) { ?>
			<tr>
				<td><img src="icons/bell.png" border=0></td>
				<td><a href="alert_config_list.php">Alerts Config</a></td>
			</tr>
			<? } ?>
			<? if (rac_verify(14)) { ?>
			<tr>
				<td><img src="icons/mail-forward.png" border=0 valign=top></td>
				<td><a href="smtp_config.php">Mail Server Config</a></td>
			</tr>
			<? } ?>
			<? if (rac_verify(159)) { ?>
			<tr>
				<td><img src="icons/appearance.png" border=0 valign=top></td>
				<td><a href="action_config_list.php">Actions Config</a></td>
			</tr>
			<? } ?>
			<? if (rac_verify(13)) { ?>
			<tr>
				<td><img src="icons/x-office.png" border=0 valign=top></td>
				<td><a href="list_config_list.php">Lists Config</a></td>
			</tr>
			<? } ?>
			
		</table>
	</td>
	<td  valign=top>
		<table>
			<? if (rac_verify(13)) { ?>
			<tr>
				<td><img src="icons/x-office.png" border=0 valign=top></td>
				<td><a href="source_config_list.php">Source Config</a></td>
			</tr>
			<? } ?>
			<? if (rac_verify(13)) { ?>
			<tr>
				<td><img src="icons/x-office.png" border=0 valign=top></td>
				<td><a href="filter_config_list.php">Filter Config</a></td>
			</tr>
			<? } ?>
			<? if (rac_verify(13)) { ?>
			<tr>
				<td><img src="icons/x-office.png" border=0 valign=top></td>
				<td><a href="view_config_list.php">View Config</a></td>
			</tr>
			<? } ?>
		</table>
	</td>
    </tr>
</table>
</div>