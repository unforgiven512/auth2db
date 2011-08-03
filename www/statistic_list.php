<?
include "conn.php";
include "security.php";

function show_hour($tipo) {

$ano = date("Y");
$mes = date("m");
$dia = date("d");

if (table_exists($ano."_".$mes."_".$dia) == 1) {
	$sql = "SELECT count(action) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
				FROM log_".$ano."_".$mes."_".$dia." 
				WHERE action = 'Failed' AND tipo = 'sshd' AND fecha like '".$ano."-".$mes."-%'
				AND fecha > '".$ano."-".$mes."'
				GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
			";

	$sql = "SELECT count(tipo) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
				FROM log_".$ano."_".$mes."_".$dia." 
				WHERE tipo = '".$tipo."' AND fecha like '".$ano."-".$mes."-%'
				AND fecha > '".$ano."-".$mes."'
				GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
			";

} else {
	$sql = "SELECT count(action) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
				FROM log_0000_00_00
				WHERE action = 'Failed' AND tipo = 'sshd' AND fecha like '2009-03-%'
				AND fecha > '2009-03'
				GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
			";
}
	$result = mysql_query($sql);
	
	$strXML = "<graph caption='".strtoupper($tipo)."' showValues='0' bgColor='111111' baseFontColor='000000' outCnvBaseFontColor='FFFFFF' xAxisName='Hour' yAxisName='Units' decimalPrecision='0' formatNumberScale='0'>";	
	
	for($i=0; $i<=23; $i++){
		$hour[$i] = 0;
	}
	
	$check_data = 0;
	while($rs = mysql_fetch_object($result)) {
		//$strXML .= "<set name='$rs->fecha' value='$rs->cantidad' color='AFD8F8'/>";	
		$hour[number_format($rs->fecha)] = $rs->cantidad;
		$check_data = $check_data + $rs->cantidad;
	}
	
	for($i=0; $i<=23; $i++){
		//$graph->addPoint($hour[$i],$i,0);
		$strXML .= "<set name='$i' value='$hour[$i]' color='FF9621'/>";
		//echo $mes[$i] . "<br>";
	}

	$strXML .=  "</graph>";
	
	if ($check_data > 0)
	    echo renderChart("graph/Charts/FCF_Column2D.swf", "", $strXML, $tipo, 620, 220);
	
}


function show_week() {

	$sql = "SELECT count(action) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
				FROM login 
				WHERE action = 'Failed' AND tipo = 'sshd' AND fecha like '2009-03-%' AND ip is not NULL AND ip <> ''
				GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
			";

$ano = date("Y");
$mes = date("m");
$dia = date("d");

if (table_exists($ano."_".$mes."_".$dia) == 1) {

	$sql = "SELECT  count(action) as cantidad, substring_index(fecha,' ',1) as fecha
				FROM log_".$ano."_".$mes."_".$dia."
				WHERE action = 'Failed' AND tipo = 'sshd' AND fecha like '".$ano."-%' 
				AND fecha > '".$ano."-".$mes."'
				GROUP BY substring_index(fecha,' ',1) 
			";
} else {
	$sql = "SELECT  count(action) as cantidad, substring_index(fecha,' ',1) as fecha
				FROM log_0000_00_00
				WHERE action = 'Failed' AND tipo = 'sshd' AND fecha like '0000-%' AND ip is not NULL AND ip <> ''
				AND fecha > '0000-00'
				GROUP BY substring_index(fecha,' ',1) 
			";
}
	$result = mysql_query($sql);

	$strXML = "<graph caption='SSHD' bgColor='111111' baseFontColor='000000' outCnvBaseFontColor='FFFFFF' xAxisName='Week' yAxisName='Units' decimalPrecision='0' formatNumberScale='0'>";	
	

	//$week[1][0] = "Sun";
	//$week[2][0] = "Mon";
	//$week[3][0] = "Tue";
	//$week[4][0] = "Wed";
	//$week[5][0] = "Thu";
	//$week[6][0] = "Fri";
	//$week[7][0] = "Sat";

	$week = array("Sun" => 0, "Mon" => 0, "Tue" => 0, "Wed" => 0, "Thu" => 0, "Fri" => 0, "Sat" => 0);
	
	while($rs = mysql_fetch_object($result)) {
		//$strXML .= "<set name='$rs->fecha' value='$rs->cantidad' color='AFD8F8'/>";	
		//$week[number_format($rs->fecha)] = $rs->cantidad;
		$week_tmp = explode("-",$rs->fecha);
		$week_name = date("D", mktime(0, 0, 0, number_format($week_tmp[1]), number_format($week_tmp[2]), $week_tmp[0]));
		//$week[array_search($week_name,$week)][1] = $rs->cantidad;
		$week[$week_name] = $week[$week_name] + $rs->cantidad;
		
		//echo $week_name . ": "; 
		//echo $rs->cantidad; 
		//echo " -> " . $rs->fecha . "<br>";
		
		//echo date("D", mktime(0, 0, 0, number_format($week_tmp[1]), number_format($week_tmp[2]), number_format($week_tmp[0])))." ".$rs->cantidad."<br>";
	}
	foreach ($week as $k => $v) {
		$strXML .= "<set name='$k' value='$v' color='FF9621'/>";
	}

	$strXML .=  "</graph>";
	
	echo renderChart("graph/Charts/FCF_Column2D.swf", "", $strXML, "myNext03", 620, 220);
	
}

function show_month() {

	$sql = "SELECT count(action) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
				FROM login 
				WHERE action = 'Failed' AND tipo = 'sshd' AND fecha like '2009-03-%' AND ip is not NULL AND ip <> ''
				GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
			";

$ano = date("Y");
$mes = date("m");
$dia = date("d");

if (table_exists($ano."_".$mes."_".$dia) == 1) {
	$sql = "SELECT count(action) as cantidad, substring_index(fecha,' ',1) as fecha 
				FROM log_".$ano."_".$mes."_".$dia."
				WHERE action = 'Failed' AND tipo = 'sshd' AND fecha like '".$ano."-".$mes."-%' 				
				AND fecha > '".$ano."-".$mes."'
				GROUP BY substring_index(fecha,' ',1)
			";

} else {
	$sql = "SELECT count(action) as cantidad, substring_index(fecha,' ',1) as fecha 
				FROM log_0000_00_00 
				WHERE action = 'Failed' AND tipo = 'sshd' AND fecha like '0000-00-%' AND ip is not NULL AND ip <> ''
				AND fecha > '0000-00'
				GROUP BY substring_index(fecha,' ',1)
			";
}
	$result = mysql_query($sql);

	$strXML = "<graph caption='SSHD' bgColor='111111' baseFontColor='000000' outCnvBaseFontColor='FFFFFF' xAxisName='Month' yAxisName='Units' decimalPrecision='0' formatNumberScale='0'>";	
	
	for($i=1; $i<=31; $i++){
		$day[$i] = 0;
	}
	
	while($rs = mysql_fetch_object($result)) {
		//$strXML .= "<set name='$rs->fecha' value='$rs->cantidad' color='AFD8F8'/>";	
		$day_tmp = explode("-",$rs->fecha);
		//echo $day[2];
		$day[number_format($day_tmp[2])] = $rs->cantidad;
		
	}
	
	for($i=1; $i<=31; $i++){
		//$graph->addPoint($day[$i],$i,0);
		$strXML .= "<set name='$i' value='$day[$i]' color='FF9621'/>";
		//echo $mes[$i] . "<br>";
	}
	
	$strXML .=  "</graph>";
	
	echo renderChart("graph/Charts/FCF_Column2D.swf", "", $strXML, "myNext02", 620, 220);
	
}

?>
<HTML>
<HEAD>
	<TITLE>
	FusionCharts Free - Simple Column 3D Chart using dataXML method
	</TITLE>
	<?php
	//You need to include the following JS file, if you intend to embed the chart using JavaScript.
	//Embedding using JavaScripts avoids the "Click to Activate..." issue in Internet Explorer
	//When you make your own charts, make sure that the path to this JS file is correct. Else, you would get JavaScript errors.
	?>
	<link href="style.css" rel="stylesheet" type="text/css">
	<SCRIPT LANGUAGE="Javascript" SRC="graph/JSClass/FusionCharts.js"></SCRIPT>
	<style type="text/css">
	<!--
	body {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
	}
	-->
	</style>
</HEAD>
	<?php
	//We've included ../Includes/FusionCharts.php, which contains functions
	//to help us easily embed the charts.
    include("graph/Includes/FusionCharts.php");
	?>
<BODY>

<CENTER>

<? 
/*
show_hour(); 
echo "<br>";
show_week();
echo "<br>";
show_month()
*/
$ano = date("Y");
$mes = date("m");
$dia = date("d");

if (table_exists($ano."_".$mes."_".$dia) == 1) {
    $sql = "SELECT DISTINCT tipo AS tipo FROM log_".$ano."_".$mes."_".$dia;
} else {
    $sql = "SELECT DISTINCT tipo AS tipo FROM log_0000_00_00";
}

$result_tipo = mysql_query($sql, $link);
while ($rs_tipo = mysql_fetch_object($result_tipo) ){
    //echo $rs_tipo->tipo;
    //echo "<br>";
    show_hour($rs_tipo->tipo);
    flush();
}

?>
</CENTER>
</BODY>
</HTML>
