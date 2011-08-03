<? include "verify.php"; ?>
<? include "security.php"; ?>
<?

#  This program is free software; you can redistribute it and/or modify
#  it under the terms of the GNU General Public License as published by
#  the Free Software Foundation; either version 2 of the License, or
#  (at your option) any later version.
# 
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU General Public License for more details.
# 
#  You should have received a copy of the GNU General Public License
#  along with this program; if not, write to the Free Software
#  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

#  Copyright (c) 2007,2008,2009 Ezequiel Vera

session_start();

include("conn.php");
require('geoip_functions.php');

// ---------------------------
// GRAPH CLASS
// ---------------------------

//include ("graph_gd/graph.oo.php");


function report($test,$res,$fail,$fa=false){
	//echo "$test: ";
	if($res){
		//echo "$test: ";
		//echo "<font color=green>PASS</font>";
		}
	else{
		echo "$test: ";
		echo "<font color=red>FAIL</font>";
		echo "<br> You need GD extension to see the graphics.";
		echo "<br>";
		exit;
		}
	if(!$res)
		echo "$fail";
	elseif($fail==false)
		echo "$fa";
	else
		echo "";
	echo "";
}


class Statistic_old
{
		function show($tipo,$action,$campo,$color,$mes,$server,$year)
		{
			
				if (strlen($mes) == 1 ){
						$mes = "0".$mes;
				}
			
				//$sql = "SELECT $campo as total from log_2009_11_05 WHERE action = '$action' AND tipo = '$tipo' AND server = '".$server."' AND fecha like '2007-".$mes."%' ";
				$sql = "SELECT $campo as total from log_2009_11_05 WHERE action = '$action' AND tipo = '$tipo' AND server = '".$server."' AND fecha like '".$year."-".$mes."%' ";
				$sql = "SELECT $campo as total from log_2009_11_05 WHERE action = '$action' AND tipo = '$tipo' ";
				$result_total = mysql_query($sql);
				$total = mysql_num_rows($result_total);

				//$sql = "SELECT count($campo) as cantidad, $campo from log_2009_11_05 WHERE action = '$action' AND tipo = '$tipo' AND server = '".$server."' AND fecha like '2007-".$mes."%' GROUP BY $campo ORDER BY cantidad DESC LIMIT 10";
				$sql = "SELECT count($campo) as cantidad, $campo from log_2009_11_05 WHERE action = '$action' AND tipo = '$tipo' AND server = '".$server."' AND fecha like '".$year."-".$mes."%' GROUP BY $campo ORDER BY cantidad DESC LIMIT 10";
				$sql = "SELECT count($campo) as cantidad, $campo from log_2009_11_05 WHERE action = '$action' AND tipo = '$tipo' GROUP BY $campo ORDER BY cantidad DESC LIMIT 10";
				$result = mysql_query($sql);
				
				echo "<b>" . strtoupper($tipo) . "</b> " . $action . " ($campo)-";
				//echo "<br>::".$total;
				
				if ($total > 0) {
			?>
			<table>
			<tr>
			<td valign=top>
				<table border="0" cellpadding="1" cellspacing="1">
					<tr bgcolor="#CCCCCC" class="filasTituloMain01">
						<td width="80" ><? echo $campo; ?></td>
						<td width="40" >count</td>
						<td width="45" align="right" >%</td>
					</tr >
					<?
					
					$graph = new graph(180,120);
					$graph->setProp("showkey",false);
					$graph->setProp("type","pie");
					$graph->setProp("showgrid",false);
					//$graph->setProp("key",array('alpha','beta','gamma','delta','pi'));
					$graph->setProp("keywidspc",-50);
					$graph->setProp("benchmark",true);
					$graph->setProp("keyinfo",2);
					$graph->setProp("keywidspc",1);
					//$graph->setColor("color",-1,255,17,17);
					$graph->setColor("backcolor",-1,17,17,17);
					
					
					?>
					<? 
						while( $rs = mysql_fetch_object($result) ) {
							if (!isset($v_data)) {
							    $v_data = $rs->cantidad;
							    $v_label = $rs->$campo;
							} else {
							    $v_data = $v_data . "*" . $rs->cantidad;
							    $v_label = $v_label . "*" . $rs->$campo;
							}
							
							$graph->addPoint($rs->cantidad);
					?>
					<tr class="filas" bgcolor=<? 	if ($color == "red") {
														echo "#FF9999";
													} else if ($color == "green") {
														echo "#00FF99";
													}
													?> >
						<td><? echo $rs->$campo ?></td>
						<td align="right" ><? echo $rs->cantidad ?></td>
						<td align="right" nowrap><? echo number_format( ( $rs->cantidad / $total ) * 100, 2) . " %" ?></td>
					</tr>
					<? } ?>

				</table>
			</td>
			<td>
			<?
					/*
					$graph = new graph(180,120);
					$graph->setProp("showkey",false);
					$graph->setProp("type","pie");
					$graph->setProp("showgrid",false);
					$graph->setProp("key",array('alpha','beta','gamma','delta','pi'));
					$graph->setProp("keywidspc",-50);
					$graph->setProp("benchmark",true);
					$graph->setProp("keyinfo",2);
					$graph->setProp("keywidspc",1);
					$graph->setColor("color",-1,255,17,17);
					$graph->setColor("backcolor",-1,17,17,17);
					$graph->addPoint(22);
					$graph->addPoint(332);
					$graph->addPoint(222);
					$graph->addPoint(12);
					$graph->addPoint(92);
					#$graph->demoData(5,1,10);
					*/
					$graph->graph();
					$graph->showGraph('images_tmp/'.$tipo.$action.'.png');
					?>
					<img src='images_tmp/<? echo $tipo.$action; ?>.png' >
					<img src="chart_pie.php?data=<?=$v_data?>&label=<?=$v_label?>" />

			</td>
			</tr>
			</table>
			<?
			// Evalua si $total es > 0
			} else {
					echo "<br><br><p align=center>No Record.</p><br>";
				}
		}
	
}


class Statistic 
{
		function show($tipo,$action,$campo,$color,$mes,$server,$year)
		{
		    $data = $action;
			
				if (strlen($mes) == 1 ){
						$mes = "0".$mes;
				}
			
				//$sql = "SELECT $campo as total from log_2009_11_05 WHERE action = '$action' AND tipo = '$tipo' AND server = '".$server."' AND fecha like '2007-".$mes."%' ";
				$sql = "SELECT $campo as total from log_2009_11_05 WHERE action = '$action' AND tipo = '$tipo' AND server = '".$server."' AND fecha like '".$year."-".$mes."%' ";
				$sql = "SELECT $campo as total from log_2009_11_05 WHERE action = '$action' AND tipo = '$tipo' ";
				$sql = "SELECT action as total from log_2009_11_05 WHERE action = '$action' AND tipo = '$tipo' ";
				
				$result_total = mysql_query($sql);
				$total = mysql_num_rows($result_total);

				//$sql = "SELECT count($campo) as cantidad, $campo from log_2009_11_05 WHERE action = '$action' AND tipo = '$tipo' AND server = '".$server."' AND fecha like '2007-".$mes."%' GROUP BY $campo ORDER BY cantidad DESC LIMIT 10";
				$sql = "SELECT count($campo) as cantidad, $campo from log_2009_11_05 WHERE action = '$action' AND tipo = '$tipo' AND server = '".$server."' AND fecha like '".$year."-".$mes."%' GROUP BY $campo ORDER BY cantidad DESC LIMIT 10";
				$sql = "SELECT count($campo) as cantidad, $campo from log_2009_11_05 WHERE action = '$action' AND tipo = '$tipo' GROUP BY $campo ORDER BY cantidad DESC LIMIT 10";
				$sql = "SELECT count(action) as cantidad, action from log_2009_11_05 WHERE action = '$action' AND tipo = '$tipo' GROUP BY action ORDER BY cantidad DESC LIMIT 10";
				//$result = mysql_query($sql);
				
				echo "<b>" . strtoupper($tipo) . "</b> " . $action . " ($campo)<br><br>";
				//echo "<br>::".$total;


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


				
				if ($total > 0) {
			?>
				
					<? 
						while( $rs = mysql_fetch_object($result) ) {
							if (!isset($v_data)) {
							    $v_data = $rs->cantidad;
							    $v_label = $rs->server;
							} else {
							    $v_data = $v_data . "*" . $rs->cantidad;
							    $v_label = $v_label . "*" . $rs->server;
							}
							
						}
					 ?>

				<img src="chart_pie.php?data=<?=$v_data?>&label=<?=$v_label?>" />

			<?
			// Evalua si $total es > 0
			} else {
					echo "<br><br><p align=center>No Record.</p><br>";
				}
		}
	
}



class Statistic_Year
{
		function show($tipo,$action,$campo,$color)
		{
			
				$sql = "SELECT $campo as total from log_2009_11_05 WHERE action = '$action' AND tipo = '$tipo' ";
				$result_total = mysql_query($sql);
				$total = mysql_num_rows($result_total);

				$sql = "SELECT count($campo) as cantidad, $campo from log_2009_11_05 WHERE action = '$action' AND tipo = '$tipo' GROUP BY $campo ORDER BY cantidad DESC LIMIT 10";
				
				
				
				echo "<b>" . strtoupper($tipo) . "</b> " . $action . " Year ($campo) <br><br>";
			?>
			
			<?php
			$graph = new graph(400,150);
			//$graph->demoData();
			//$graph->setProp('scale','date');
			//$graph->setProp('dateformat',1);
			//$graph->setProp('startdate','January 2002');
			$graph->setProp('xincpts',11);
			$graph->setColor("backcolor",-1,17,17,17);
			
			// -------
				$sql = "SELECT count($campo) as cantidad, substring_index(fecha,'-',2) as fecha
								from log_2009_11_05 
								WHERE action = '$action' AND tipo = '$tipo' AND ip is not NULL AND ip <> ''
								GROUP BY substring_index(fecha,'-',2)
								ORDER BY fecha DESC, cantidad DESC
								";
				
				$result = mysql_query($sql);
				
				for($i=1; $i<=12; $i++){
					//$graph->addPoint(0,$i,0);
				}
				//$graph->addPoint(0,1,0);
				//$graph->addPoint(0,12,0);
				while ($rs = mysql_fetch_object($result) ){
					//echo $rs->fecha .": ". $rs->cantidad ."<br>";
					$mes = explode("-",$rs->fecha);
					//echo $mes[1]."<br>";
					$graph->addPoint($rs->cantidad,$mes[1],0);
				}
				
				
				// -------
				
				// -------
				$sql = "SELECT count($campo) as cantidad, substring_index(fecha,'-',2) as fecha
								from log_2009_11_05 
								WHERE action = 'Accepted' AND tipo = '$tipo' AND ip is not NULL AND ip <> ''
								GROUP BY substring_index(fecha,'-',2)
								ORDER BY fecha DESC, cantidad DESC
								";
				
				$result = mysql_query($sql);
				
				for($i=1; $i<=12; $i++){
					//$graph->addPoint(0,$i,1);
				}
				
				//$graph->addPoint(0,1,1);
				//$graph->addPoint(0,12,1);
				while ($rs = mysql_fetch_object($result) ){
					//echo $rs->fecha .": ". $rs->cantidad ."<br>";
					$mes = explode("-",$rs->fecha);
					//echo $mes[1]."<br>";
					$graph->addPoint($rs->cantidad,$mes[1],1);
				}
				
				// -------
				
				$graph->addPoint(0,1,2);
				$graph->addPoint(0,31,2);
			$graph->setColor('color',0,'red'); 
			$graph->setColor('color',1,20,200,20);
			$graph->setColor('color',2,17,17,17);
			$graph->graph();
			$graph->showGraph("images_tmp/year.png");
			?> 
			<img src='images_tmp/year.png' >

			<?
		}
	
}

class Statistic_Day
{
		function show($tipo,$action,$campo,$color,$mes,$server,$year)
		{
				if ($color == "R"){
					//$color = "red";
				}else{
					//$color = "green";
				}
				
				if (strlen($mes) == 1 ){
						$mes = "0".$mes;
				}
				
				echo "<b>" . strtoupper($tipo) . "</b> " . $action . " Day ($campo) <br><br>";
			?>
			
			<?php
			$graph = new graph(400,150);
			//$graph->setProp("type","bar");
			//$graph->setProp("barstyle",1); 
			//$graph->setProp("barwidth",.8); 
			//$graph->setColor("gstart",-1,$color);
			//$graph->setColor("gend",-1,$color);
			$graph->setProp('xincpts',10);
			$graph->setColor("backcolor",-1,17,17,17);
			$graph->setColor('color',0,$color); 
			
			// -------
				$sql = "SELECT count($campo) as cantidad, substring_index(fecha,' ',1) as fecha
								from log_2009_11_05 
								WHERE action = '$action' AND tipo = '$tipo' AND fecha like '".$year."-".$mes."%' AND server = '".$server."' AND ip is not NULL AND ip <> ''
								GROUP BY substring_index(fecha,' ',1)
								ORDER BY fecha
								";
				
				$sql = "SELECT count($campo) as cantidad, substring_index(fecha,' ',1) as fecha
								from log_2009_11_05 
								WHERE action = '$action' AND tipo = '$tipo'  AND ip is not NULL AND ip <> ''
								GROUP BY substring_index(fecha,' ',1)
								ORDER BY fecha
								";
				
				$result = mysql_query($sql);
				
				for($i=1; $i<=31; $i++){
					$day[$i] = 0;
				}
				
				//$graph->addPoint(0,1,0);
				//$graph->addPoint(0,31,0);
				while ($rs = mysql_fetch_object($result) ){
					//echo $rs->fecha .": ". $rs->cantidad ."<br>";
					$day_tmp = explode("-",$rs->fecha);
					//echo $day[2];
					$day[number_format($day_tmp[2])] = $rs->cantidad;
					//echo number_format($day[2])."<br>";
					//$graph->addPoint($rs->cantidad,$day[2],0);
				}
				
				for($i=1; $i<=31; $i++){
					$graph->addPoint($day[$i],$i,0);
					//echo $mes[$i] . "<br>";
				}
				
				// -------
			
			//$graph->setColor('color',1,20,200,20);
			//$graph->setColor('color',1,17,17,17);
			$graph->graph();
			$graph->showGraph("images_tmp/Month".$tipo.$action.".png");
			?> 
			<img src='images_tmp/Month<? echo $tipo.$action ?>.png' >

			<?
		}
	
}


class Statistic_Hour
{
		function show($tipo,$action,$campo,$color,$mes,$server,$year)
		{
		    $data = $action;
				if ($color == "R"){
					//$color = "red";
				}else{
					//$color = "green";
				}
				
				if (strlen($mes) == 1 ){
						$mes = "0".$mes;
				}
				
				$color = "orange";
				
				echo "<b>" . strtoupper($tipo) . "</b> " . $action . " Hour ($campo) <br><br>";
			?>
			
			<?php
			//$graph = new graph(400,150);
			//$graph->setProp('xincpts',12);
			//$graph->setColor("backcolor",-1,17,17,17);
			//$graph->setColor('color',0,$color); 
			
				// -------
				$sql = "SELECT count($campo) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
								from log_2009_11_05 
								WHERE action = '$action' AND tipo = '$tipo' AND fecha like '".$year."-".$mes."%' AND server = '".$server."' AND ip is not NULL AND ip <> ''
								GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
								ORDER BY fecha
								";
				
				$sql = "SELECT count($campo) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
								from log_2009_11_05 
								WHERE action = '$action' AND tipo = '$tipo' AND ip is not NULL AND ip <> ''
								GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
								ORDER BY fecha
								";
				
				//$result = mysql_query($sql);



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


				$v_flag = 1;
				for($i=1; $i<=24; $i++){
					$hour[$i] = 0;
					
				}
				
				$v_flag = 1;
				while ($rs = mysql_fetch_object($result) ){
					//echo $rs->fecha .": ". $rs->cantidad ."<br>";
					//$day = explode("-",$rs->fecha);
					//$graph->addPoint($rs->cantidad,$rs->fecha,0);
					$hour[number_format($rs->fecha)] = $rs->cantidad;
				}

				for($i=0; $i<=23; $i++){
					//----> $graph->addPoint($hour[$i],$i,0);
					//echo $mes[$i] . "<br>";

					if (!isset($v_data)) {
					    $v_data = $hour[$i];
					    $v_label = $i;
					} else {
					    $v_data = $v_data . "*" . $hour[$i];
					    $v_label = $v_label . "*" . $i;
					}
				}


				// -------
								
				//$graph->addPoint(0,1,2);
				//$graph->addPoint(0,24,2);
				
			//$graph->setColor('color',1,20,200,20);
			//$graph->setColor('color',2,17,17,17);
			/*
			$graph->graph();
			$graph->showGraph("images_tmp/Hour".$tipo.$action.".png");
			?> 
			<img src='images_tmp/Hour<? echo $tipo.$action ?>.png' >
			<? //echo $v_data . "<br>" . $v_label
			*/
			?>
			
			<img src='chart_bar.php?data=<?=$v_data?>&label=<?=$v_label?>' />

			<?
		}
	
}



class Statistic_Week
{
		function show($tipo,$action,$campo,$color,$mes,$server,$year)
		{
		    $data = $action;
				if ($color == "R"){
					//$color = "red";
				}else{
					//$color = "green";
				}
				
				if (strlen($mes) == 1 ){
						$mes = "0".$mes;
				}
				
				$color = "orange";
				
				echo "<b>" . strtoupper($tipo) . "</b> " . $action . " Hour ($campo) <br><br>";
			?>
			
			<?php
			//$graph = new graph(400,150);
			//$graph->setProp('xincpts',12);
			//$graph->setColor("backcolor",-1,17,17,17);
			//$graph->setColor('color',0,$color); 
			
				// -------
				$sql = "SELECT count($campo) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
								from log_2009_11_05 
								WHERE action = '$action' AND tipo = '$tipo' AND fecha like '".$year."-".$mes."%' AND server = '".$server."' AND ip is not NULL AND ip <> ''
								GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
								ORDER BY fecha
								";
				
				$sql = "SELECT count($campo) as cantidad, substring_index(substring_index(fecha,' ',-1), ':', 1) as fecha
								from log_2009_11_05 
								WHERE action = '$action' AND tipo = '$tipo' AND ip is not NULL AND ip <> ''
								GROUP BY substring_index(substring_index(fecha,' ',-1), ':', 1)
								ORDER BY fecha
								";
				
				//$result = mysql_query($sql);


// ----------------------->

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


    $week = array("Sun" => 0, "Mon" => 0, "Tue" => 0, "Wed" => 0, "Thu" => 0, "Fri" => 0, "Sat" => 0);

        $check_data = 0;
        while($rs = mysql_fetch_object($result)) {
                //$strXML .= "<set name='$rs->fecha' value='$rs->cantidad' color='AFD8F8'/>";
                //$week[number_format($rs->fecha)] = $rs->cantidad;
                $week_tmp = explode("-",$rs->fecha);
                $week_name = date("D", mktime(0, 0, 0, number_format($week_tmp[1]), number_format($week_tmp[2]),$week_tmp[0]));
                //$week[array_search($week_name,$week)][1] = $rs->cantidad;
                $week[$week_name] = $week[$week_name] + $rs->cantidad;
                /*
                echo $week_name . ": ";
                echo $rs->cantidad;
                echo " -> " . $rs->fecha . "<br>";
                */
                //echo date("D", mktime(0, 0, 0, number_format($week_tmp[1]), number_format($week_tmp[2]),number_format($week_tmp[0])))." ".$rs->cantidad."<br>";
                $check_data = $check_data + $rs->cantidad;


		if (!isset($v_data)) {
		    $v_data = $hour[$i];
		    $v_label = $i;
		} else {
		    $v_data = $v_data . "*" . $hour[$i];
		    $v_label = $v_label . "*" . $i;
		}


        }


	foreach ($week as $k => $v) {
                $strXML .= "<set name='$k' value='$v' color='FF9621'/>";

		if (!isset($v_data)) {
		    $v_data = $v;
		    $v_label = $k;
		} else {
		    $v_data = $v_data . "*" . $v;
		    $v_label = $v_label . "*" . $k;
		}

        }

        $strXML .=  "</graph>";

        //if ($check_data > 0)
            //---> echo renderChart("graph/Charts/FCF_Column2D.swf", "", $strXML, "myNext03", 520, 200);
            //echo renderChart("graph/Charts/FCF_Column2D.swf", "", $strXML, "myNext03", 620, 220);

// ---------------------->

?>
	<img src='chart_bar.php?data=<?=$v_data?>&label=<?=$v_label?>&bar=40' />
<?

	}

}



// ---------------------------
// END GRAPH CLASS
// ---------------------------

?>

<?

//include("conn.php");

if($_POST["sesion"] == "ok")
{
    $_SESSION["ano_graph"] = $_POST["ano"];
    $_SESSION["dia_graph"] = $_POST["dia"];
    $_SESSION["mes_graph"] = $_POST["mes"];
	$_SESSION["select_server_graph"] = implode(",",$_POST["select_server"]);
	$_SESSION["select_tipo_graph"] = implode(",",$_POST["select_tipo"]);
	//$_SESSION["select_action_graph"] = implode(",",$_POST["select_action"]);
	//$_SESSION["check_machine"] = $_POST["check_machine"];
	//$_SESSION["check_detail"] = $_POST["check_detail"];
}

?>

<?

##########################################
## FILTROS SELECT MULTIPLE
##########################################
$_SESSION["select_server_graph"] = str_replace("'","",$_SESSION["select_server_graph"]);
if ($_SESSION["select_server_graph"])
{
	$select_server = "'".str_replace(",","','",$_SESSION["select_server_graph"])."'";
	$array_server = explode(",",str_replace("'","",$_SESSION["select_server_graph"]));
}else{
	$sql = "SELECT distinct server from log_2009_11_05";
	$result = mysql_query($sql, $link);
	$x = 0;
	while ($rs_server = mysql_fetch_object($result))
	{
			$array_server[$x] = $rs_server->server;
			$x = $x + 1;
	}
	
	$select_server = "'".implode("','",$array_server)."'";
}

$_SESSION["select_tipo_graph"] = str_replace("'","",$_SESSION["select_tipo_graph"]);
if ($_SESSION["select_tipo_graph"])
{
	$select_tipo = "'".str_replace(",","','",$_SESSION["select_tipo_graph"])."'";
	$array_tipo = explode(",",str_replace("'","",$_SESSION["select_tipo_graph"]));
}else{
	$sql = "SELECT distinct tipo from log_2009_11_05";
	$result = mysql_query($sql, $link);
	$x = 0;
	while ($rs_tipo = mysql_fetch_object($result))
	{
			$array_tipo[$x] = $rs_tipo->tipo;
			$x = $x + 1;
	}
	$select_tipo = "'".implode("','",$array_tipo)."'";
}

$_SESSION["select_action_graph"] = str_replace("'","",$_SESSION["select_action_graph"]);
if ($_SESSION["select_action_graph"])
{
	$select_action = "'".str_replace(",","','",$_SESSION["select_action_graph"])."'";
	$array_action = explode(",",str_replace("'","",$_SESSION["select_action_graph"]));
}else{
	$sql = "SELECT distinct action from log_2009_11_05";
	$result = mysql_query($sql, $link);
	$x = 0;
	while ($rs_action = mysql_fetch_object($result))
	{
			$array_action[$x] = $rs_action->action;
			$x = $x + 1;
	}
	$select_action = "'".implode("','",$array_action)."'";
}
$select_ano = $_SESSION["ano_graph"];
$select_mes = $_SESSION["mes_graph"];

//echo $select_mes;
//echo "<br>";
//echo $select_server;
//echo "<br>";
//echo $select_tipo;
//echo "<br>";
//echo $select_action;

#echo "check_machine: " . $_SESSION["check_machine"];
#echo "<br>check_detail: " . $_SESSION["check_detail"];

##########################################
## FIN FILTROS SELECT MULTIPLE
##########################################

# Usa los select como filtro, sino, usa checkbox 
$mostrar = "select";
$select_max = 8;

?>


<html>
<head>
<title>Auth2DB</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
<link href="diff.css" rel="stylesheet" type="text/css">

<style>
#boxthing {
	position:absolute;
	right: 0;
	top:145px;
	b-ottom:0;
	l-eft:58%;
	w-idth:250px;
	h-eight:150px;
	border:1px #FFFFFF solid;
	background-color:#000000;
	padding:0.5em; }

.menuconfig {
	position:absolute;
	right: 0;
	top:120px;
	b-ottom:0;
	l-eft:58%;
	w-idth:250px;
	h-eight:150px;
	b-order:1px #FFFFFF solid;
	b-ackground-color:#000000;
	padding:0.5em; }
</style>

<script>

function hideLayer(whichLayer) {

	if (document.getElementById) {
		// this is the way the standards work
		document.getElementById(whichLayer).style.visibility = "hidden";
	}
	else if (document.all) {
		// this is the way old msie versions work
		document.all[whichlayer].style.visibility = "hidden";
	}
	else if (document.layers) {
		// this is the way nn4 works
		document.layers[whichLayer].visibility = "hidden";
	}

}

function showLayer(whichLayer) {

	if (document.getElementById) {
		// this is the way the standards work
		document.getElementById(whichLayer).style.visibility = "visible";
	}
	else if (document.all) {
		// this is the way old msie versions work
		document.all[whichlayer].style.visibility = "visible";
	}
	else if (document.layers) {
		// this is the way nn4 works
		document.layers[whichLayer].visibility = "visible";
	}

}

function handleClick(whichClick) {

	if (whichClick == "hide it") {
		// then the user wants to hide the layer
		hideLayer("boxthing");
	}
	else if (whichClick == "show it") {
		// then the user wants to show the layer
		showLayer("boxthing");
	}

}

function boxdetailClick(whichClick) {

	if (whichClick == "hide it") {
		// then the user wants to hide the layer
		hideLayer("boxdetail");
	}
	else if (whichClick == "show it") {
		// then the user wants to show the layer
		showLayer("boxdetail");
	}

}

</script>

</head>
<body>

<? //include "header.php" ?>
<? //include "menu_general.php" ?>

<p class="itemsMenu001"></p>


<?

// Verifica GD
//report('PHP Version',((int)phpversion()>=4),'This class only works with PHP 4 and above. You can download PHP 4 & 5 from php.net.');
//report('Safe Mode',!ini_get('safe_mode'),'Safe mode must be disabled to use this class.  Your web hosting company may be at fault.');
report('gd Extension',extension_loaded('gd'),'You must enable the gd extension.  Modify your php.ini file or recompile PHP with this extension.');
$GDArray = gd_info();
$version = ereg_replace('[[:alpha:][:space:]()]+', '', $GDArray['GD Version']);


?>

<?

$multi_tipo_clave = array('sshd','proftpd','apache');

$multi_tipo = array( 
    array('sshd', 'Failed', 'Accepted','red','green'), 
    array('proftpd', 'no such user', 'Login successful','red','green'),
	array('apache', 'not found', 'failure','red','red')
);

// array('sshd', 'closed', 'opened','darkgray','green')

$array_tipos = explode(",",$select_tipo);

/*
for ($i=0;$i<=(sizeof($array_tipos)-1);$i++)
{
	// busca la clave a ver si existe el tipo en el array de graficos
	$temp_tipo = str_replace("'","",$array_tipos[$i]);
	if (is_int(array_search($temp_tipo,$multi_tipo_clave)))
	{
		// pasa la posision del tipo en el array a la variable $clave
		$clave = array_search($temp_tipo,$multi_tipo_clave);
*/		
		?>

<div align="center">

<?



$c_tipo = sec_cleanHTML($_GET["tipo"]);
$c_tipo = sec_cleanTAGS($c_tipo);
$c_tipo = sec_addESC($c_tipo);

$c_data = sec_cleanHTML($_GET["data"]);
$c_data = sec_cleanTAGS($c_data);
$c_data = sec_addESC($c_data);



	$template = new Statistic_Hour; 
	//$template->show("sshd","Failed","ip","R");
	//$template->show($multi_tipo[$clave][0],$multi_tipo[$clave][1],"ip",$multi_tipo[$clave][3],$select_mes,str_replace("'","",$select_server),$select_ano);
	$template->show($c_tipo,$c_data,"ip",$multi_tipo[$clave][3],$select_mes,str_replace("'","",$select_server),$select_ano);

	echo "<br><br>";

	$template = new Statistic; 
	//$template->show("sshd","Failed","ip","R");
	//$template->show($multi_tipo[$clave][0],$multi_tipo[$clave][1],"ip",$multi_tipo[$clave][3],$select_mes,str_replace("'","",$select_server),$select_ano);
	$template->show($c_tipo,$c_data,"ip",$multi_tipo[$clave][3],$select_mes,str_replace("'","",$select_server),$select_ano);

	echo "<br><br>";

	$template = new Statistic_Week; 
	$template->show($c_tipo,$c_data,"ip",$multi_tipo[$clave][3],$select_mes,str_replace("'","",$select_server),$select_ano);

?>
</div>

		<?
//	}
//}
?>


</body>
</html>
