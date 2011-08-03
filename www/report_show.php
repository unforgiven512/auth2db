<? include "verify.php" ?>
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

#  Copyright (c) 2007,2008 Ezequiel Vera

?>

<html>
<head>
    <link href="style.css" rel="stylesheet" type="text/css">
    <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
    <title>Auth2DB</title>

<script type="text/javascript" src="preLoadingMessage.js"></script>
    
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

#boxdetail {
	position:absolute;
	right: 0;
	t-op:22px;
	bottom:0;
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

<?
include "conn.php";
include "security.php";
include "header.php";
include "menu_general.php";

session_start();

?>

<?
/*
function table_exists($tabla) {

    $sql = "show tables like 'log_".$tabla."'";
    //echo $sql;
    $result = mysql_query($sql);
    return mysql_num_rows($result);

}
*/

$consulta_fecha = date("Y")."_".date("m")."_".date("d");
//$consulta_fecha_mas = date("Y")."-".date("m")."-".(date("d")+1);
$consulta_fecha_mas = date("Y_m_d", time()+(1*24*60*60));
$consulta_fecha_menos = date("Y_m_d", time()-(28*24*60*60));
/*
echo $consulta_fecha;
echo "<br>";
echo $consulta_fecha_mas;
echo "<br>";
echo $consulta_fecha_menos;
*/
//echo "<br><br>";

$cuantos = $_POST["period"]/1440;
//echo $cuantos;

if ($cuantos > 1) {
    for ($i=$cuantos ; $i >= 1 ; $i--) {
	$tabla_fecha = date("Y_m_d", time()-($i*24*60*60));
	//echo $tabla_fecha."<br>";

	$union_ok = table_exists($tabla_fecha);

	if ($union_ok == 1) {
	
	    $sql = $sql . " SELECT * FROM log_".$tabla_fecha." UNION ";

	}
    }
} else {
    echo "";
}

$tabla_fecha = date("Y_m_d", time()-(0*24*60*60));
//echo $tabla_fecha."<br>";

//echo table_exists($tabla_fecha);
//echo "<br>";
//echo "SELECT * FROM log_".$tabla_fecha;

$union_ok = table_exists($tabla_fecha);

if ($union_ok == 1) {

    $sql = $sql . " SELECT * FROM log_".$tabla_fecha;

}

$sql_union = $sql;

// SQL OK + UNIONS
//echo $sql;

//echo "<br><br>ok.";

//exit;
?>

<?

if($_POST["sesion"] == "ok")
{
    $report = sec_cleanTAGS($_POST["report"]);
    $period = sec_cleanTAGS($_POST["period"]);

    $report = sec_addESC($report);
    $period = sec_addESC($period);

    $_SESSION["report"] = $report;
    $_SESSION["period"] = $period;
	
}

$period_array = array(
								array('Last 10 Min',10),
								array('Last 30 Min',30),
								array('Last Hour',60),
								array('Last Day',1440),
								array('Last Week',10080),
								array('Last Month',40320)								
								);
// array('Last Month',302400)
//$array_server = array(1,2);
//$array_tipo = array(1,2);
//$array_action = array(1,2);

?>

<p class="itemsMenu001"></p>

<? flush(); ?>

<div class="menuconfig">
	<input type="image" src="icons/edit.png" onclick="handleClick('show it'); return false"> <input type="image" src="icons/cancel.png" onclick="handleClick('hide it'); return false"><br>
</div>

<br><br>

<div id="boxthing" style="visibility: hidden;" >
	<b>Select Report</b>
	
	<form action='#' method=post>
		<input type=hidden name=sesion value=ok>
		
			<table>
				<tr>
					<td>Report</td>
					<td>Period</td>
					<td></td>
				</tr>
				<tr>
					<td valign="top" nowrap>
							<?
							
								$sql = "SELECT * FROM reports";
								$result = mysql_query($sql);
								
							?>
							<select name="report" class="field" style="width: 120px;">
								<option>Select</option>
								<? while ($rs_report = mysql_fetch_object($result)) {
										
										if($_SESSION["report"] == $rs_report->id) {
											$SELECTED = "selected";
										}
										echo "<option value='$rs_report->id' $SELECTED>$rs_report->report_name</option>\n";
										$SELECTED = "";
										
										/*											
										if ($rs_year->year == date("Y")) {
											echo "<option selected>$rs_year->year</option>";
										} else {
											echo "<option $SELECTED>$rs_year->year</option>";
										}
										*/
										
								} 
								?>
							</select>
					</td>
					<td valign="top" nowrap>
							<?
								//$period_array = array(["Last Hour",2];["Last Day",2];["Last Week",2];["Last Month",2]);
							?>
							<select name="period" class="field" style="width: 100px;">
								<option value="0">Select</option>
								<? for ($x=0 ; $x < count($period_array) ; $x++) { 
										
										if($_SESSION["period"] == $period_array[$x][1]) {
											$SELECTED = "selected";
										}
										
								?>
									<option value="<? echo $period_array[$x][1] ?>" <? echo $SELECTED ?>><? echo $period_array[$x][0] ?></option>
								<?
										$SELECTED = "";
									} 
								?>
							</select>
					</td>
					<? if (0==1) { ?>
					<td valign="top" nowrap>
						<input name="check_machine" type="checkbox" value="ok" <? if ($_SESSION["check_machine"] == "ok" ) echo "checked" ?>>hide machine<br>
						<input name="check_detail" type="checkbox" value="ok" <? if ($_SESSION["check_detail"] == "ok" ) echo "checked" ?> >hide detail
						<br> 
					</td>
					<? } ?>
					<td valign="bottom">
						<input type="image" src="icons/filesave.png">
						<br><input type="image" src="icons/cancel.png" onclick="handleClick('hide it'); return false">
					</td>
				<tr>
			</table>

	</form>

</div>


<?

/*
$sql = "SHOW FIELDS from smtp_config";
$result = mysql_query($sql);

$rs = mysql_fetch_row($result);

#echo $rs[0];

while ($row = mysql_fetch_row($result) ) {
    echo $row[]
}
*/

$sql = "SELECT * from login";
$sql = "SELECT * from log_".date("Y")."_".date("m")."_".date("d");

$sql = $sql_union;
//echo $sql."<br><br>";
//$result = mysql_query($sql);


// ######################################################
// Si SHOW = 1 muestra el resultado
// ######################################################

$id = sec_cleanTAGS($_GET["id"]);
$id = sec_addESC($id);

if ($_SESSION["report"] != "") {
	$id = $_SESSION["report"];
}

if ($_SESSION["period"] == "") {
	$select_period = 60;
} else {
	$select_period = $_SESSION["period"];
}

$sql = "SELECT * FROM reports WHERE id = $id";
$result = mysql_query($sql) or die("What are you Doing?");

$rs = mysql_fetch_object($result);

$fields_array = explode(",",$rs->fields_values);
    
$sql = "SELECT $rs->fields_values FROM login WHERE fecha > ( DATE_SUB( NOW(), INTERVAL $select_period MINUTE) ) ORDER BY id DESC LIMIT 100";
$sql = "SELECT $rs->fields_values FROM log_".date("Y")."_".date("m")."_".date("d")." WHERE fecha > ( DATE_SUB( NOW(), INTERVAL $select_period MINUTE) ) ORDER BY id DESC LIMIT 100";

$_condicion = " WHERE fecha > ( DATE_SUB( NOW(), INTERVAL $select_period MINUTE) ) ";

$sql = str_replace("*",$rs->fields_values,$sql_union);
$sql = str_replace("UNION",$_condicion." UNION",$sql);
$sql = $sql . " WHERE fecha > ( DATE_SUB( NOW(), INTERVAL $select_period MINUTE) ) ORDER BY fecha DESC LIMIT 100";

if ($rs->where_values != "" ) {
    $WHERE = str_replace("[","'",$rs->where_values);
    $WHERE = str_replace("]","'",$WHERE);
    $sql = "SELECT $rs->fields_values FROM log_".date("Y")."_".date("m")."_".date("d")." WHERE $WHERE AND fecha > ( DATE_SUB( NOW(), INTERVAL $select_period MINUTE) ) ORDER BY id DESC LIMIT 100";
    $sql = str_replace("*",$rs->fields_values,$sql_union);
    $sql = str_replace("UNION"," WHERE ".$WHERE." UNION",$sql);
    $sql = $sql . " WHERE $WHERE AND fecha > ( DATE_SUB( NOW(), INTERVAL $select_period MINUTE) ) ORDER BY fecha DESC LIMIT 100";
}
//echo $sql."<br><br>";
    
    $result = mysql_query($sql) or die("What are you Doing?");
    
    //echo "<br>";
    //echo count($_POST["fields"]);
?>

<strong style="padding-left:10;" >Auth2DB <?=$rs->report_name?></strong>

<table>
    <tr class="filasTituloMain01">
	<? for ($i = 0; $i < count($fields_array); $i++) { ?>
	<td><?=sec_cleanHTML($fields_array[$i]) ?></td>
	<? } ?>
    </tr>
<?
    while ($row = mysql_fetch_row($result)) {
    //while ($rs = mysql_fetch_object($result)) {
	//echo $row[1];
	//echo "<br>";
	?>
	<tr style="background: #555555">
	    <? for ($j = 0; $j < count($fields_array); $j++) { ?>
		<? if ($fields_array[$j] == "detalle") {?>
		    <td w-idth=400> <?=sec_cleanHTML($row[$j]) ?> </td>
		<?} else {?>
		    <td nowrap valign='top'> <?=sec_cleanHTML($row[$j]) ?> </td>
		<? } ?>
	    <? } ?>
	</tr>
	<?
    }
?>
</table>

</body>
</html>
