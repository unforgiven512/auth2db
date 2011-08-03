<? 
session_start() ;
$_SESSION["ano"] = date("Y");
$_SESSION["mes"] = date("m");
$_SESSION["dia"] = date("d");

$SMTP_ENABLED = 0;


// ----------------------------------->
$conf="/etc/auth2db/auth2db.conf";

if ($file = @file( $conf )) {
    foreach ($file as $line) {
        $temp = explode("=", $line);

         switch ( trim($temp[0]) ){
            case "ACTIVE_GD": $active_gd = str_replace("\n", "", str_replace("'", "", str_replace('"', "", 
trim($temp[1]) ))); break;
        }
    }
}
else
    print "Configuration file " .$conf. " couldn't be read ";

if ($active_gd == "Y" OR $active_gd == "y" )
    $graphtype = "_gd";
// ----------------------------------->


?>
<? 
include "verify.php";
include "security.php";
?>
<HTML>
<HEAD>
<title>Auth2DB</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
<link href="diff.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="preLoadingMessage.js"></script>

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


</HEAD>
<body>

<? include "header.php"; ?>
<? include "menu_general.php"; ?>
<? include "conn.php"; ?>
<p class="itemsMenu001"></p>


<?

flush();

// ---------------------------
// GRAPH CLASS
// ---------------------------
include("graph/Includes/FusionCharts.php");

function show_hour($tipo) {

    $sql = "SELECT count(action) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
                FROM log_2009_03_16
                WHERE action = '$action' AND tipo = '$tipo' AND fecha like '2009-03-%' AND ip is not NULL AND ip <> ''
                AND fecha > '2009-03'
                GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
    	    ";
$ano = date("Y");
$mes = date("m");
$dia = date("d");

if (table_exists($ano."_".$mes."_".$dia) == 1) {

    $sql = "SELECT count(tipo) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
                FROM log_".$ano."_".$mes."_".$dia."
                WHERE tipo = '$tipo' AND fecha like '".$ano."-".$mes."-%'
                GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
    	    ";
} else { 
    $sql = "SELECT count(tipo) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
                FROM log_000_00_00
                WHERE tipo = '$tipo' AND fecha like '0000-00-%'
                GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
    	    ";
}
    $result = mysql_query($sql);

    $strXML = "<graph caption='".strtoupper($tipo)."' showvalues='0' bgColor='111111' baseFontColor='000000' outCnvBaseFontColor='FFFFFF' xAxisName='Hour' yAxisName='Units' decimalPrecision='0' formatNumberScale='0'>";

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


function show_cpu($server) {

    // SELECT CPU USER
    $sql = "SELECT left(substring_index(fecha,' ',-1),4) AS fecha, avg(value) AS cpu FROM snmp 
	    WHERE fecha > ( DATE_SUB( NOW(), INTERVAL 180 MINUTE) ) AND fecha <= NOW() AND oid = 1 
	    GROUP BY left(substring_index(fecha,' ',-1),4);  ";

    //echo $sql;
    $result_cpu_1 = mysql_query($sql);

    $i=0;
    while($rs_cpu_1 = mysql_fetch_object($result_cpu_1)) {
	$cpu_1[$i][0] = $rs_cpu_1->fecha;
	$cpu_1[$i][1] = $rs_cpu_1->cpu;
	//echo $cpu_1[$i][1]."<br>";
	$i = $i+1;
    }
    //echo "<br>";

    // SELECT CPU SYSTEM
    $sql = "SELECT left(substring_index(fecha,' ',-1),4) AS fecha, avg(value) AS cpu FROM snmp 
	    WHERE fecha > ( DATE_SUB( NOW(), INTERVAL 180 MINUTE) ) AND fecha <= NOW() AND oid = 2
	    GROUP BY left(substring_index(fecha,' ',-1),4);  ";

    //echo $sql;
    $result_cpu_2 = mysql_query($sql);

    $i=0;
    while($rs_cpu_2 = mysql_fetch_object($result_cpu_2)) {
	$cpu_2[$i][0] = $rs_cpu_2->fecha;
	$cpu_2[$i][1] = $rs_cpu_2->cpu;
	//echo $cpu_2[$i][1]."<br>";
	$i = $i+1;
    }
    //echo "<br>";

    //$strXML = "<graph caption='".strtoupper($tipo)."' showvalues='0' bgColor='111111' baseFontColor='000000' outCnvBaseFontColor='FFFFFF' xAxisName='Hour' yAxisName='Units' decimalPrecision='0' formatNumberScale='0'>";
    $strXML = "<graph caption='CPU' subcaption='' bgColor='111111' baseFontColor='000000' outCnvBaseFontColor='FFFFFF' divlinecolor='F47E00' numdivlines='4' showAreaBorder='1' areaBorderColor='000000' numberPrefix='%' showNames='1' numVDivLines='20' vDivLineAlpha='20' formatNumberScale='1' rotateNames='1' decimalPrecision='0' animation='0' yAxisMaxValue='100'>";

    $strXML .= "<categories>";

    for($i=0; $i<sizeof($cpu_1); $i++){
	$strXML .= "	<category name='".$cpu_1[$i][0]."0' /><set value='".$cpu_1[$i][0]."0' />";
    }
    $strXML .= "</categories>";

    $strXML .= "<dataset seriesname='User' color='0F5999' showValues='0' areaAlpha='50' showAreaBorder='1' areaBorderThickness='1' areaBorderColor='FF0000' yAxisMaxValue='100' >";

    for($i=0; $i<sizeof($cpu_1); $i++){
	$strXML .= "	<set value='".$cpu_1[$i][1]."' />";
    }
    $strXML .= "</dataset>";

    $strXML .= "<dataset seriesname='System' color='FF5904' showValues='0' areaAlpha='50' showAreaBorder='1' areaBorderThickness='1' areaBorderColor='FF2200'>";
    //0F5999
    //FF5904
    for($i=0; $i<sizeof($cpu_2); $i++){
	$strXML .= "	<set value='".$cpu_2[$i][1]."' />";
    }
    $strXML .= "</dataset>";

    $strXML .=  "</graph>";

    //if ($check_data > 0)
	echo renderChart("graph/Charts/FCF_MSArea2D.swf", "", $strXML, $tipo, 420, 220);

}


function show_mem($server) {

    // SELECT MEM USER
    $sql = "SELECT left(substring_index(fecha,' ',-1),4) AS fecha, avg(value) AS cpu FROM snmp 
	    WHERE fecha > ( DATE_SUB( NOW(), INTERVAL 180 MINUTE) ) AND fecha <= NOW() AND oid = 3
	    GROUP BY left(substring_index(fecha,' ',-1),4);  ";

    //echo $sql;
    $result_mem_1 = mysql_query($sql);

    $i=0;
    while($rs_mem_1 = mysql_fetch_object($result_mem_1)) {
	$mem_1[$i][0] = $rs_mem_1->fecha;
	$mem_1[$i][1] = $rs_mem_1->cpu;
	//echo $mem_1[$i][1]."<br>";
	$i = $i+1;
    }
    //echo "<br>";

    // SELECT MEM USER
    $sql = "SELECT left(substring_index(fecha,' ',-1),4) AS fecha, avg(value) AS cpu FROM snmp 
	    WHERE fecha > ( DATE_SUB( NOW(), INTERVAL 180 MINUTE) ) AND fecha <= NOW() AND oid = 4
	    GROUP BY left(substring_index(fecha,' ',-1),4);  ";

    //echo $sql;
    $result_mem_2 = mysql_query($sql);

    $i=0;
    while($rs_mem_2 = mysql_fetch_object($result_mem_2)) {
	$mem_2[$i][0] = $rs_mem_2->fecha;
	$mem_2[$i][1] = $rs_mem_2->cpu;
	//echo $mem_2[$i][1]."<br>";
	$i = $i+1;
    }
    //echo "<br>";

    //$strXML = "<graph caption='".strtoupper($tipo)."' showvalues='0' bgColor='111111' baseFontColor='000000' outCnvBaseFontColor='FFFFFF' xAxisName='Hour' yAxisName='Units' decimalPrecision='0' formatNumberScale='0'>";
    $strXML = "<graph caption='MEMORY' subcaption='' bgColor='111111' baseFontColor='000000' outCnvBaseFontColor='FFFFFF' divlinecolor='F47E00' numdivlines='4' showAreaBorder='1' areaBorderColor='000000' numberPrefix='' showNames='1' numVDivLines='20' vDivLineAlpha='20' formatNumberScale='1' rotateNames='1' decimalPrecision='0' animation='0' yAxisMaxValue='".$mem_1[0][1]."' >";

    $strXML .= "<categories>";

    for($i=0; $i<sizeof($mem_1); $i++){
	$strXML .= "	<category name='".$mem_1[$i][0]."0' /><set value='".$mem_1[$i][0]."0' />";
    }
    $strXML .= "</categories>";

    $strXML .= "<dataset seriesname='Used Memory' color='0F5999' showValues='0' areaAlpha='50' showAreaBorder='1' areaBorderThickness='1' areaBorderColor='FF0000' yAxisMaxValue='100' >";

    for($i=0; $i<sizeof($mem_1); $i++){
	$strXML .= "	<set value='".($mem_1[$i][1] - $mem_2[$i][1])."' />";
    }
    $strXML .= "</dataset>";

    $strXML .=  "</graph>";

    //if ($check_data > 0)
	echo renderChart("graph/Charts/FCF_MSArea2D.swf", "", $strXML, "mem", 420, 220);

}

function show_events_pie($server) {

$ano = date("Y");
$mes = date("m");
$dia = date("d");

if (table_exists($ano."_".$mes."_".$dia) == 1) {

        $sql = " SELECT server, count(server) as cantidad
                    FROM log_".$ano."_".$mes."_".$dia."
                    WHERE action = '".$data."' AND tipo = '".$tipo."' AND fecha like '".$ano."-".$mes."-%'
                    GROUP BY server
                    ORDER BY cantidad DESC
                    LIMIT 10
                ";
                
        $sql = " SELECT tipo AS server, count(tipo) as cantidad
                    FROM log_".$ano."_".$mes."_".$dia."
                    WHERE fecha like '".$ano."-".$mes."-%'
                    GROUP BY tipo
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
        $strXML = "<graph caption='EVENTS' shownames='0' bgColor='111111' baseFontColor='FFFFFF' decimalPrecision='0' showPercentageValues='0' showNames='1' numberPrefix='' showValues='0' showPercentageInLabel='1' pieYScale='45' pieBorderAlpha='40' pieFillAlpha='90' pieSliceDepth='15' pieRadius='100'>";

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

        $strXML .=  "</graph>";

        //if ($check_data > 0)

            //echo renderChart("graph/Charts/FCF_Column2D.swf", "", $strXML, "myNext01", 620, 220);

        ?>
        <table border=0 cellpadding="0" cellspacing="0">
            <tr>
                <td><?echo renderChart("graph/Charts/FCF_Pie3D.swf", "", $strXML, "Events_Pie", 220, 160);?></td>
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


function show_alerts_pie($server) {

$ano = date("Y");
$mes = date("m");
$dia = date("d");

if (table_exists($ano."_".$mes."_".$dia) == 1) {

        $sql = " SELECT server, count(server) as cantidad
                    FROM log_".$ano."_".$mes."_".$dia."
                    WHERE action = '".$data."' AND tipo = '".$tipo."' AND fecha like '".$ano."-".$mes."-%'
                    GROUP BY server
                    ORDER BY cantidad DESC
                    LIMIT 10
                ";
                
        $sql = " SELECT criticality AS server, count(criticality) as cantidad
                    FROM alert
                    WHERE notified_time like '".$ano."-".$mes."-".$dia."%'
                    GROUP BY criticality
                    ORDER BY criticality
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
        $strXML = "<graph caption='ALERTS' shownames='0' bgColor='111111' baseFontColor='FFFFFF' decimalPrecision='0' showPercentageValues='0' showNames='1' numberPrefix='' showValues='0' showPercentageInLabel='1' pieYScale='45' pieBorderAlpha='40' pieFillAlpha='90' pieSliceDepth='15' pieRadius='100'>";

        $i = 0;

        while($rs = mysql_fetch_object($result)) {
            //$strXML .= "<set name='$rs->server' value='$rs->cantidad' color='FF9621'/>";
            
            if ($rs->server == "High")
        	$alert_color = "9D080D";

	    if ($rs->server == "Medium")
        	$alert_color = "F6BD0F";

	    if ($rs->server == "Low")
		$alert_color = "8BBA00";

            $strXML .= "<set name='$rs->server' value='$rs->cantidad' color='$alert_color' />";
            //$table_pie = "<dt style='background: #".$color[$i]."; '></dt><dd>".$table_pie.$rs->server."</dd><br>";
            $table_pie = $table_pie."<div id='box_detail_pie' style='background: #".$alert_color."; '></div><div>$rs->server 1000</div>";
            $pie_data[$i][0] = $rs->server;
            $pie_data[$i][1] = $rs->cantidad;
            $pie_total = $pie_total + $rs->cantidad;

            $i++;
        }

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
			    if ($pie_data[$i][0] == "High")
        			$alert_color = "9D080D";

			    if ($pie_data[$i][0] == "Medium")
        			$alert_color = "F6BD0F";
        			
        		    if ($pie_data[$i][0] == "Low")
        			$alert_color = "8BBA00";

                            echo "<tr><td><div id='box_detail_pie' style='background: #".$alert_color."; '></div>".$pie_data[$i][0]."</td><td align='right' style='padding-left: 10' > ".number_format( ( $pie_data[$i][1] / $pie_total ) * 100, 2 )." %</td></tr>";
                        }
                    ?></table>
                </td>
            </tr>
        </table>

        <?

}


// ------------->


function show_events_pie_gd($server) {

$ano = date("Y");
$mes = date("m");
$dia = date("d");

if (table_exists($ano."_".$mes."_".$dia) == 1) {

        $sql = " SELECT tipo AS server, count(tipo) as cantidad
                    FROM log_".$ano."_".$mes."_".$dia."
                    WHERE fecha like '".$ano."-".$mes."-%'
                    GROUP BY tipo
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

        $i = 0;

        while($rs = mysql_fetch_object($result)) {

	    if (!isset($v_data)) {
		$v_data = $rs->cantidad;
		$v_label = $rs->server;
	    } else {
		$v_data = $v_data . "*" . $rs->cantidad;
		$v_label = $v_label . "*" . $rs->server;
	    }

            $i++;
        }

        ?>

        <img src="chart_pie.php?data=<?=$v_data?>&label=<?=$v_label?>" />

        <?

}


function show_alerts_pie_gd($server) {

$ano = date("Y");
$mes = date("m");
$dia = date("d");

if (table_exists($ano."_".$mes."_".$dia) == 1) {

        $sql = " SELECT criticality AS server, count(criticality) as cantidad
                    FROM alert
                    WHERE notified_time like '".$ano."-".$mes."-".$dia."%'
                    GROUP BY criticality
                    ORDER BY criticality
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

        $i = 0;

	$v_datos[0][0] = "High";
	$v_datos[1][0] = "Low";
	$v_datos[2][0] = "Medium";
	$v_datos[0][1] = 0;
	$v_datos[1][1] = 0;
	$v_datos[2][1] = 0;

        while($rs = mysql_fetch_object($result)) {

            if ($rs->server == "High")
        	$alert_color = "9D080D";

	    if ($rs->server == "Medium")
        	$alert_color = "F6BD0F";

	    if ($rs->server == "Low")
		$alert_color = "8BBA00";

	    $v_datos[$i][0] = $rs->server;
	    $v_datos[$i][1] = $rs->cantidad;

            $i++;
        }

	$v_data = $v_datos[0][1]."*".$v_datos[2][1]."*".$v_datos[1][1];
	$v_label = $v_datos[0][0]."*".$v_datos[2][0]."*".$v_datos[1][0];

        ?>

        <img src="chart_pie.php?data=<?=$v_data?>&label=<?=$v_label?>" />

        <?

}



?>


<?

$sql = "SELECT count($campo) as cantidad, substring_index(fecha,' ',1) as fecha
            FROM login 
            WHERE action = '$action' AND tipo = '$tipo' AND fecha like '".$year."-".$mes."%' 
            AND server = '".$server."' AND ip is not NULL AND ip <> ''
			GROUP BY substring_index(fecha,' ',1)
			ORDER BY fecha";
            
$sql = "SELECT count(*) as cantidad, substring_index(fecha,' ',1) as fecha
            FROM login 
            WHERE action = 'Failed' AND tipo = 'sshd' AND fecha like '".date("Y")."-".date("m")."%' 
			GROUP BY substring_index(fecha,' ',1)
			ORDER BY fecha";
            
$sql = "SELECT count(*) as cantidad, substring_index(fecha,' ',1) as fecha
            FROM login 
            WHERE tipo = 'sshd' AND fecha like '".date("Y")."-".date("m")."%' 
			GROUP BY substring_index(fecha,' ',1)
			ORDER BY fecha";

$sql = "SELECT count(*) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
			FROM login 
			WHERE tipo = 'sshd' AND fecha like '".date("Y")."-".date("m")."%' 
			GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
			ORDER BY fecha";

/*            
$result = mysql_query($sql, $link);
while ($rs = mysql_fetch_object($result) ){
    echo $rs->cantidad ." " . $rs->fecha;
    echo "<br>";
}
*/

?>
<SCRIPT LANGUAGE="Javascript" SRC="graph/JSClass/FusionCharts.js"></SCRIPT>
<table border=0 width="100%" height="82%">
    <tr>
        <td width=100 valign="top" nowrap>
        <div class="left-menu">
        <div align=center class="bloqueTitle" ><b>Details</b></div>
        <div class="divisor"></div>
        <?
            $sql = "SELECT DISTINCT server FROM login";
            if (table_exists($_SESSION["ano"]."_".$_SESSION["mes"]."_".$_SESSION["dia"]) == 1) {
        	$sql = "SELECT DISTINCT server FROM log_".$_SESSION["ano"]."_".$_SESSION["mes"]."_".$_SESSION["dia"];
            } else {
        	$sql = "SELECT DISTINCT server FROM log_0000_00_00";
            }
            
            $result_cantidad = mysql_query($sql);
            $rs_cantidad = mysql_num_rows($result_cantidad);
            echo "<b>Servers: </b>".$rs_cantidad;
            
            echo "<br><br>";
            
            //$sql = "SELECT * FROM login WHERE server in (".$select_server.") AND tipo in (".$select_tipo.") AND action in (".$select_action.") AND fecha > '$consulta_fecha' AND fecha < '$consulta_fecha_mas' order by fecha DESC, id DESC LIMIT $SHOW_LIMIT";
            
            $consulta_fecha = date("Y")."-".date("m")."-".date("d");
            //$consulta_fecha_mas = date("Y")."-".date("m")."-".(date("d")+1);  
            $consulta_fecha_mas = date("Y-m-d", time()+(1*24*60*60)); 
            
            $sql = "SELECT COUNT( *) AS cantidad FROM login WHERE fecha > '$consulta_fecha' AND fecha < '$consulta_fecha_mas'";        
            if (table_exists($_SESSION["ano"]."_".$_SESSION["mes"]."_".$_SESSION["dia"]) == 1) {
        	$sql = "SELECT COUNT( *) AS cantidad FROM log_".$_SESSION["ano"]."_".$_SESSION["mes"]."_".$_SESSION["dia"];
            } else {
        	$sql = "SELECT COUNT( *) AS cantidad FROM log_0000_00_00";
            }
            $result_count_eventos = mysql_query($sql, $link);
            //$rs_count_eventos = mysql_num_rows($result_count_eventos);
            $rs_count_eventos = mysql_fetch_object($result_count_eventos);
            echo "<b>Eventos: </b>".$rs_count_eventos->cantidad;
            
            echo "<br><br>";
            
            //$consulta_fecha = date("Y")."-".date("m")."-".(date("d") - 6);
            $consulta_fecha = date("Y-m-d", time()-(7*24*60*60));
            //echo $consulta_fecha."<br>";
            //$consulta_fecha_mas = date("Y")."-".date("m")."-".(date("d")+1);   
            $consulta_fecha_mas = date("Y-m-d", time()+(1*24*60*60));
            //echo $consulta_fecha_mas."<br>";
            
            $sql = "SELECT COUNT( *) AS cantidad FROM login WHERE fecha > '$consulta_fecha' AND fecha < '$consulta_fecha_mas'";
            //$result_count_eventos = mysql_query($sql, $link);
            //$rs_count_eventos = mysql_num_rows($result_count_eventos);
            //$rs_count_eventos = mysql_fetch_object($result_count_eventos);
            //echo "<b>Eventos desde $consulta_fecha: </b>".$rs_count_eventos->cantidad;
            
            
            $consulta_fecha = date("Y")."-".date("m")."-".date("d");
            //$consulta_fecha_mas = date("Y")."-".date("m")."-".(date("d")+1);  
            $consulta_fecha_mas = date("Y-m-d", time()+(1*24*60*60)); 
            
            $sql = "SELECT COUNT( *) AS cantidad FROM alert WHERE notified_time > '$consulta_fecha' AND notified_time < '$consulta_fecha_mas'";
            $result_count_alertas = mysql_query($sql, $link);
            //$rs_count_eventos = mysql_num_rows($result_count_eventos);
            $rs_count_alertas = mysql_fetch_object($result_count_alertas);
            echo "<b>Alertas: </b>".$rs_count_alertas->cantidad;
            
            flush();
        ?>
        </div>
        </div>
        </td>
        <td valign="top" align="left">
        <div class="right-frame" >
        <table>
    	    <?
	    if ($SMTP_ENABLED == 1) {
    	    ?>
    	    <tr>
    		<td><? show_cpu("localhost"); ?></td>
    		<td><? show_mem("localhost"); ?></td>
    	    </tr>
    	    <? } ?>
    	    <tr>
    		<td align='center' style='padding-left:40;'><? 
    		    if ($graphtype == "_gd") {
    			echo "<b>EVENTS</b> <br><br>";
    			show_events_pie_gd("localhost"); 
    		    } else
    			show_events_pie("localhost");

    		?></td>
    		<td align='center' style='padding-left:40;'><? 
    		    if ($graphtype == "_gd") {
    			echo "<b>ALERTS</b> <br><br>";
    			show_alerts_pie_gd("localhost");
    		    } else
    			show_alerts_pie("localhost");

    		?></td>
    	    </tr>
        </table>
        

        <? flush();?>
        <?
	    /*

            //$template->show("sshd","Failed","ip","R");
            //show($tipo,$action,$campo,$color,$mes,$server,$year)
            //$template->show($multi_tipo[$clave][0],$multi_tipo[$clave][1],"ip",$multi_tipo[$clave][3],$select_mes,str_replace("'","",$select_server),$select_ano);
            //'sshd', 'Failed', 'Accepted','red','green'

            //$template = new Statistic_Day; 
            //$template->($tipo,$action,$campo,$color,$mes,$server,$year);

            $sql = "SELECT DISTINCT tipo AS tipo FROM login WHERE fecha like '".date("Y")."-".date("m")."-".date("d")."%' ";
            $sql = "SELECT DISTINCT tipo AS tipo FROM log_".$_SESSION["ano"]."_".$_SESSION["mes"]."_".$_SESSION["dia"];

            $result_tipo = mysql_query($sql, $link);
            while ($rs_tipo = mysql_fetch_object($result_tipo) ){
                //echo $rs_tipo->tipo;
                echo "<br>";
                ////$template = new Statistic_Hour;
                ////$template->show($rs_tipo->tipo,'action','campo','green','mes','server','year');
                show_hour($rs_tipo->tipo);
                flush();
            }
            
            //$template = new Statistic_Hour;
            //$template->show('sshd','action','campo','green','mes','server','year');

	    */
        ?>
        </div>
        </td>
    </tr>
</table>

</HTML>
