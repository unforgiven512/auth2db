<?php
function getALLfromIP($addr,$db) {
	// this sprintf() wrapper is needed, because the PHP long is signed by default
	$ipnum = sprintf("%u", ip2long($addr));
	$query = "SELECT cc, cn FROM geoip_ip NATURAL JOIN geoip_cc WHERE ${ipnum} BETWEEN start AND end";
	$result = mysql_query($query, $db);
	
	if((! $result) or mysql_numrows($result) < 1) {
		//exit("mysql_query returned nothing: ".(mysql_error()?mysql_error():$query));
		return false;
	}
	
	return mysql_fetch_array($result);
}

function getCCfromIP($addr,$db) {
	$data = getALLfromIP($addr,$db);
	if($data) return $data['cc'];
	return false;
}

function getCOUNTRYfromIP($addr,$db) {
	$data = getALLfromIP($addr,$db);
	if($data) return $data['cn'];
	return false;
}

function getCCfromNAME($name,$db) {
	$addr = gethostbyname($name);
	return getCCfromIP($addr,$db);
}

function getCOUNTRYfromNAME($name,$db) {
	$addr = gethostbyname($name);
	return getCOUNTRYfromIP($addr,$db);
}
?>
