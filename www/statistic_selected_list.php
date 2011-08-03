<link href="diff.css" rel="stylesheet" type="text/css">
<style type="text/css">

#box_detail_pie_content {
    margin-top: 10;
    margin-left: 10;
}

#box_detail_pie {
    p-osition: relative;
    float: left;
    width: 8px;
    height: 8px;
    border-color: red;
    border: 1px solid #555;
    font-size: 1px;
    margin-top: 2;
    margin-right: 4;
}

</style>

<?
include "conn.php";
include "security.php";

$tipo =	$_GET["tipo"];
$data = $_GET["data"];

function month__() {

	$sql = "SELECT count(action) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
				FROM login 
				WHERE action = 'Failed' AND tipo = 'sshd' AND fecha like '2009-03-%' AND ip is not NULL AND ip <> ''
				GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
			";
	$result = mysql_query($sql);

	$strXML = "<graph caption='SSHD' bgColor='111111' baseFontColor='FFFFFF' xAxisName='Month' yAxisName='Units' decimalPrecision='0' formatNumberScale='0' pieRadius='100'>";
	while($rs = mysql_fetch_object($result)) {
		$strXML .= "<set name='$rs->fecha' value='$rs->cantidad' color='A186BE'/>";
	}
	$strXML .=  "</graph>";
	
	echo renderChart("graph/Charts/FCF_Pie3D.swf", "", $strXML, "myNext01", 300, 200);
	
}

function month_() {

	$sql = "SELECT count(action) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
				FROM login 
				WHERE action = 'Failed' AND tipo = 'sshd' AND fecha like '2009-03-%' AND ip is not NULL AND ip <> ''
				GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
			";
	$result = mysql_query($sql);

	$strXML = "<graph caption='$tipo : $data' subcaption='For the year 2004' bgColor='111111' baseFontColor='FFFFFF'  xAxisName='Month' yAxisMinValue='15000' yAxisName='Sales' decimalPrecision='0' formatNumberScale='0' numberPrefix='' showNames='1' showValues='0' showAlternateHGridColor='1' AlternateHGridColor='ff5904' divLineColor='ff5904' divLineAlpha='20' alternateHGridAlpha='5'>";
	while($rs = mysql_fetch_object($result)) {
		$strXML .= "<set name='$rs->fecha' value='$rs->cantidad' hoverText='$rs->fecha'/>";	
	}
	$strXML .= "<set name='$rs->fecha' value='0' hoverText='20'/>";
	$strXML .= "<set name='$rs->fecha' value='0' hoverText='21'/>";
	$strXML .=  "</graph>";
	
	echo renderChart("graph/Charts/FCF_Line.swf", "", $strXML, "myNext01", 420, 220);
	
}

function show_hour($tipo,$data) {

	$sql = "SELECT count(action) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
				FROM log_2009_03_16 
				WHERE action = '".$data."' AND tipo = '".$tipo."' AND fecha like '2009-03-%' AND ip is not NULL AND ip <> ''
				AND fecha > '2009-03'
				GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
			";
$ano = date("Y");
$mes = date("m");
$dia = date("d");

if (table_exists($ano."_".$mes."_".$dia) == 1) {
	$sql = "SELECT count(action) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
				FROM log_".$ano."_".$mes."_".$dia." 
				WHERE action = '".$data."' AND tipo = '".$tipo."' AND fecha like '".$ano."-".$mes."-%'
				GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
			";
} else {
	$sql = "SELECT count(action) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
				FROM log_0000_00_00 
				WHERE action = '".$data."' AND tipo = '".$tipo."' AND fecha like '2009-03-%'
				GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
			";
}
	$result = mysql_query($sql);

	$strXML = "<graph caption='".strtoupper($tipo ." : ". $data)."' showValues='0' bgColor='111111' baseFontColor='000000' outCnvBaseFontColor='FFFFFF' xAxisName='Hour' yAxisName='Units' decimalPrecision='0' formatNumberScale='0'>";	
	
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
	
	//if ($check_data > 0)
	    echo renderChart("graph/Charts/FCF_Column2D.swf", "", $strXML, "myNext01", 520, 200);
	    //echo renderChart("graph/Charts/FCF_Column2D.swf", "", $strXML, "myNext01", 620, 220);
	
}


function show_hour_pie($tipo,$data) {

	$sql = "SELECT count(action) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
				FROM log_2009_03_16 
				WHERE action = '".$data."' AND tipo = '".$tipo."' AND fecha like '2009-03-%' AND ip is not NULL AND ip <> ''
				AND fecha > '2009-03'
				GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
			";
$ano = date("Y");
$mes = date("m");
$dia = date("d");

if (table_exists($ano."_".$mes."_".$dia) == 1) {
	$sql = "SELECT count(action) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
				FROM log_".$ano."_".$mes."_".$dia." 
				WHERE action = '".$data."' AND tipo = '".$tipo."' AND fecha like '".$ano."-".$mes."-%'
				GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
			";
	$sql = " SELECT server, count(server) as cantidad 
		    FROM log_".$ano."_".$mes."_".$dia." 
		    WHERE action = '".$data."' AND tipo = '".$tipo."' AND fecha like '".$ano."-".$mes."-%'
		    GROUP BY server 
		    ORDER BY cantidad DESC
		    LIMIT 10
		";

} else {
	$sql = "SELECT count(action) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
				FROM log_0000_00_00 
				WHERE action = '".$data."' AND tipo = '".$tipo."' AND fecha like '2009-03-%'
				GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
			";
}
	$result = mysql_query($sql);

	$color[0] = "9D080D";
	$color[1] = "F6BD0F";
	$color[2] = "8BBA00";
	$color[3] = "FF8E46";
	$color[4] = "008E8E";
	$color[5] = "D64646";
	$color[6] = "8E468E";
	$color[7] = "588526";
	$color[8] = "B3AA00";
	$color[9] = "008ED6";
	
	$pie_data[0][1];
	$pie_total = 0;

	//$strXML = "<graph caption='".strtoupper($tipo ." : ". $data)."' showValues='0' bgColor='111111' baseFontColor='000000' outCnvBaseFontColor='FFFFFF' xAxisName='Hour' yAxisName='Units' decimalPrecision='0' formatNumberScale='0'>";	
	$strXML = "<graph caption='Pie Chart' shownames='0' bgColor='111111' baseFontColor='FFFFFF' decimalPrecision='0' showPercentageValues='0' showNames='1' numberPrefix='' showValues='0' showPercentageInLabel='1' pieYScale='45' pieBorderAlpha='40' pieFillAlpha='90' pieSliceDepth='15' pieRadius='100'>";
	
	$i = 0;
	
	while($rs = mysql_fetch_object($result)) {
	    //$strXML .= "<set name='$rs->server' value='$rs->cantidad' color='FF9621'/>";
	    $strXML .= "<set name='$rs->server' value='$rs->cantidad' color='$color[$i]' />";
	    //$table_pie = "<dt style='background: #".$color[$i]."; '></dt><dd>".$table_pie.$rs->server."</dd><br>";
	    $table_pie = $table_pie."<div id='box_detail_pie' style='background: #".$color[$i]."; '></div><div>$rs->server 1000</div>";
	    $pie_data[$i][0] = $rs->server;
	    $pie_data[$i][1] = $rs->cantidad;
	    $pie_total = $pie_total + $rs->cantidad;
	    
	    $i++;
	}
	
	/*
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
	*/
	
	$strXML .=  "</graph>";
	
	//if ($check_data > 0)
	
	    //echo renderChart("graph/Charts/FCF_Column2D.swf", "", $strXML, "myNext01", 620, 220);
	
	?>
	<table border=0 cellpadding="0" cellspacing="0">
	    <tr>
		<td><?echo renderChart("graph/Charts/FCF_Pie3D.swf", "", $strXML, "Host_Pie", 220, 160);?></td>
		<td  valign='top'><div id='box_detail_pie_content'><table cellpadding="0" cellspacing="0">
		    <?
			for($i=0;$i<=sizeof($pie_data) - 1;$i++) {
			    echo "<tr><td><div id='box_detail_pie' style='background: #".$color[$i]."; '></div>".$pie_data[$i][0]."</td><td align='right' style='padding-left: 10' > ".number_format( ( $pie_data[$i][1] / $pie_total ) * 100, 2 )." %</td></tr>";
			}
		    ?></table>
		</td>
	    </tr>
	</table>

	<?
	

	
}

function show_week($tipo,$data) {

	$sql = "SELECT count(action) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
				FROM login 
				WHERE action = 'Failed' AND tipo = 'sshd' AND fecha like '2009-03-%' AND ip is not NULL AND ip <> ''
				GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
			";
	$sql = "SELECT  count(action) as cantidad, substring_index(fecha,' ',1) as fecha
				FROM log_2009_03_16
				WHERE action = '".$data."' AND tipo = '".$tipo."' AND fecha like '2009-%' AND ip is not NULL AND ip <> ''
				AND fecha > '2009-03'
				GROUP BY substring_index(fecha,' ',1) 
			";

$ano = date("Y");
$mes = date("m");
$dia = date("d");

if (table_exists($ano."_".$mes."_".$dia) == 1) {
	$sql = "SELECT  count(action) as cantidad, substring_index(fecha,' ',1) as fecha
				FROM log_".$ano."_".$mes."_".$dia."
				WHERE action = '".$data."' AND tipo = '".$tipo."' AND fecha like '".$ano."-%'
				GROUP BY substring_index(fecha,' ',1) 
			";
    
    if (table_exists($ano."_".$mes."_".$dia) == 1) {
	$sql = "SELECT  count(action) as cantidad, substring_index(fecha,' ',1) as fecha
				FROM log_".date("Y_m_d", time()-(0*24*60*60))."
				WHERE action = '".$data."' AND tipo = '".$tipo."' AND fecha like '".$ano."-%'
				GROUP BY substring_index(fecha,' ',1)	
		";
    }
    if (table_exists( date("Y_m_d", time()-(1*24*60*60)) ) == 1) {
	$sql = $sql . " UNION " . "SELECT  count(action) as cantidad, substring_index(fecha,' ',1) as fecha
				FROM log_".date("Y_m_d", time()-(1*24*60*60))."
				WHERE action = '".$data."' AND tipo = '".$tipo."' AND fecha like '".$ano."-%'
				GROUP BY substring_index(fecha,' ',1) 
		
		";
    }
    if (table_exists( date("Y_m_d", time()-(2*24*60*60)) ) == 1) {	
	$sql = $sql . " UNION " . "SELECT  count(action) as cantidad, substring_index(fecha,' ',1) as fecha
				FROM log_".date("Y_m_d", time()-(2*24*60*60))."
				WHERE action = '".$data."' AND tipo = '".$tipo."' AND fecha like '".$ano."-%'
				GROUP BY substring_index(fecha,' ',1)
		
		";
    }
    if (table_exists( date("Y_m_d", time()-(3*24*60*60)) ) == 1) {
	$sql = $sql . " UNION " . "SELECT  count(action) as cantidad, substring_index(fecha,' ',1) as fecha
				FROM log_".date("Y_m_d", time()-(3*24*60*60))."
				WHERE action = '".$data."' AND tipo = '".$tipo."' AND fecha like '".$ano."-%'
				GROUP BY substring_index(fecha,' ',1)
		
		";
    }
    if (table_exists( date("Y_m_d", time()-(4*24*60*60)) ) == 1) {
	$sql = $sql . " UNION " . "SELECT  count(action) as cantidad, substring_index(fecha,' ',1) as fecha
				FROM log_".date("Y_m_d", time()-(4*24*60*60))."
				WHERE action = '".$data."' AND tipo = '".$tipo."' AND fecha like '".$ano."-%'
				GROUP BY substring_index(fecha,' ',1)
		
		";
    }
    if (table_exists( date("Y_m_d", time()-(5*24*60*60)) ) == 1) {
	$sql = $sql . " UNION " . "SELECT  count(action) as cantidad, substring_index(fecha,' ',1) as fecha
				FROM log_".date("Y_m_d", time()-(5*24*60*60))."
				WHERE action = '".$data."' AND tipo = '".$tipo."' AND fecha like '".$ano."-%'
				GROUP BY substring_index(fecha,' ',1)
		
		";
    }
    if (table_exists( date("Y_m_d", time()-(6*24*60*60)) ) == 1) {
	$sql = $sql . " UNION " . "SELECT  count(action) as cantidad, substring_index(fecha,' ',1) as fecha
				FROM log_".date("Y_m_d", time()-(6*24*60*60))."
				WHERE action = '".$data."' AND tipo = '".$tipo."' AND fecha like '".$ano."-%'
				GROUP BY substring_index(fecha,' ',1)
		";
    }	
	//echo $sql;
	
} else {
	$sql = "SELECT  count(action) as cantidad, substring_index(fecha,' ',1) as fecha
				FROM log_0000_00_00
				WHERE action = '".$data."' AND tipo = '".$tipo."' AND fecha like '2009-%'
				GROUP BY substring_index(fecha,' ',1) 
			";
}
	$result = mysql_query($sql);

	$strXML = "<graph caption='".strtoupper($tipo ." : ". $data)."' showValues='0' bgColor='111111' baseFontColor='000000' outCnvBaseFontColor='FFFFFF' xAxisName='Week' yAxisName='Units' decimalPrecision='0' formatNumberScale='0'>";	
	
/*
	$week[1][0] = "Sun";
	$week[2][0] = "Mon";
	$week[3][0] = "Tue";
	$week[4][0] = "Wed";
	$week[5][0] = "Thu";
	$week[6][0] = "Fri";
	$week[7][0] = "Sat";
*/
	$week = array("Sun" => 0, "Mon" => 0, "Tue" => 0, "Wed" => 0, "Thu" => 0, "Fri" => 0, "Sat" => 0);
	
	$check_data = 0;
	while($rs = mysql_fetch_object($result)) {
		//$strXML .= "<set name='$rs->fecha' value='$rs->cantidad' color='AFD8F8'/>";	
		//$week[number_format($rs->fecha)] = $rs->cantidad;
		$week_tmp = explode("-",$rs->fecha);
		$week_name = date("D", mktime(0, 0, 0, number_format($week_tmp[1]), number_format($week_tmp[2]), $week_tmp[0]));
		//$week[array_search($week_name,$week)][1] = $rs->cantidad;
		$week[$week_name] = $week[$week_name] + $rs->cantidad;
		/*
		echo $week_name . ": "; 
		echo $rs->cantidad; 
		echo " -> " . $rs->fecha . "<br>";
		*/
		//echo date("D", mktime(0, 0, 0, number_format($week_tmp[1]), number_format($week_tmp[2]), number_format($week_tmp[0])))." ".$rs->cantidad."<br>";
		$check_data = $check_data + $rs->cantidad;
	}

	foreach ($week as $k => $v) {
		$strXML .= "<set name='$k' value='$v' color='FF9621'/>";
	}

	$strXML .=  "</graph>";
	
	//if ($check_data > 0)
	    echo renderChart("graph/Charts/FCF_Column2D.swf", "", $strXML, "myNext03", 520, 200);
	    //echo renderChart("graph/Charts/FCF_Column2D.swf", "", $strXML, "myNext03", 620, 220);
	
}

function show_month($tipo,$data) {

	$sql = "SELECT count(action) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
				FROM login 
				WHERE action = 'Failed' AND tipo = 'sshd' AND fecha like '2009-03-%' AND ip is not NULL AND ip <> ''
				GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
			";
	$sql = "SELECT count(action) as cantidad, substring_index(fecha,' ',1) as fecha 
				FROM log_2009_03_16 
				WHERE action = '".$data."' AND tipo = '".$tipo."' AND fecha like '2009-03-%' AND ip is not NULL AND ip <> ''
				AND fecha > '2009-03'
				GROUP BY substring_index(fecha,' ',1)
			";

$ano = date("Y");
$mes = date("m");
$dia = date("d");

if (table_exists($ano."_".$mes."_".$dia) == 1) {
	$sql = "SELECT count(action) as cantidad, substring_index(fecha,' ',1) as fecha 
				FROM log_".$ano."_".$mes."_".$dia." 
				WHERE action = '".$data."' AND tipo = '".$tipo."' AND fecha like '".$ano."-".$mes."-%' 
				GROUP BY substring_index(fecha,' ',1)
			";
} else {
	$sql = "SELECT count(action) as cantidad, substring_index(fecha,' ',1) as fecha 
				FROM log_0000_00_00 
				WHERE action = '".$data."' AND tipo = '".$tipo."' AND fecha like '2009-03-%' 
				GROUP BY substring_index(fecha,' ',1)
			";
}
	$result = mysql_query($sql);

	$strXML = "<graph caption='".strtoupper($tipo ." : ". $data)."' showValues='0' bgColor='111111' baseFontColor='000000' outCnvBaseFontColor='FFFFFF' xAxisName='Month' yAxisName='Units' decimalPrecision='0' formatNumberScale='0'>";	
	
	for($i=1; $i<=31; $i++){
		$day[$i] = 0;
	}
	
	$check_data = 0;
	while($rs = mysql_fetch_object($result)) {
		//$strXML .= "<set name='$rs->fecha' value='$rs->cantidad' color='AFD8F8'/>";	
		$day_tmp = explode("-",$rs->fecha);
		//echo $day[2];
		$day[number_format($day_tmp[2])] = $rs->cantidad;
		$check_data = $check_data + $rs->contidad;
	}
	
	for($i=1; $i<=31; $i++){
		//$graph->addPoint($day[$i],$i,0);
		$strXML .= "<set name='$i' value='$day[$i]' color='FF9621'/>";
		//echo $mes[$i] . "<br>";
	}
	
	$strXML .=  "</graph>";
	
	//if ($check_data > 0)
	    echo renderChart("graph/Charts/FCF_Column2D.swf", "", $strXML, "myNext02", 620, 200);
	    //echo renderChart("graph/Charts/FCF_Column2D.swf", "", $strXML, "myNext02", 620, 220);
	
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
show_hour($tipo,$data); 
//echo "<br>";
show_hour_pie($tipo,$data); 
//echo "<br>";
show_week($tipo,$data);
//echo "<br>";
//show_month($tipo,$data);
?>
</CENTER>
</BODY>
</HTML>