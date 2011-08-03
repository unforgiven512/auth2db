<?php
#	require('geoip.inc.php');
	require('geoip_functions.php');
	require('conn.php');
#	$db = mysql_connect("localhost","root","") or die ("mysql_connect() failed: " . mysql_error());
#	mysql_select_db("auth-log",$db) or die ("mysql_select_db() failed: " . mysql_error());

	$remote = $_SERVER['REMOTE_ADDR'];
	$remote = "200.68.83.66";

	echo "<p>".getCCfromIP($remote,$link)."</p>\n";
	echo "<p>".getCOUNTRYfromIP($remote,$link)."</p>\n";

?>