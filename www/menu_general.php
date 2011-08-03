<? session_start; ?>
<div class="boxmenu" >
			<a href="status_indexed.php" >Overview</a> | 
		<? if (rac_verify(111)) { ?>
			<a href="content_simple_realtime.php" >View</a> | 
		<? } ?>
		<? if (rac_verify(1)) { ?>
			<a href="view.php" >View</a> | 
		<? } ?>
		<? if (rac_verify(1)) { ?>
			<a href="search.php" >Search</a> | 
		<? } ?>
		<? if (rac_verify(2)) { ?>
			<a href="contenedor.php" >Advance View</a> |
		<? } ?>
		<? if (rac_verify(3)) { ?>
			<a href="alert_show.php" >Alerts</a> |
		<? } ?>
		<? if (rac_verify(777)) { ?>
			<a href="report_show.php?id=2" >Report</a> | 
		<? } ?>
		<? if (rac_verify(888)) { ?>
			<a href="statistic_show.php" >Statistic</a> | 
		<? } ?>
		<? //if ($_SESSION["access_level"] == "1") { ?>
		<? if (rac_verify(9)) { ?>
		    <a href="settings.php" >Settings</a> | 
		<? } ?>
		<a href="logout.php" >Logout</a> 
</div>