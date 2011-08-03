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

#  Copyright (c) 2007 Ezequiel Vera

session_start();

include("conn.php");

if($_POST["sesion"] == "ok")
{
	$_SESSION["ano"] = $_POST["ano"];
    $_SESSION["dia"] = $_POST["dia"];
    $_SESSION["mes"] = $_POST["mes"];
	$_SESSION["select_server"] = implode(",",$_POST["select_server"]);
	$_SESSION["select_tipo"] = implode(",",$_POST["select_tipo"]);
	$_SESSION["select_action"] = implode(",",$_POST["select_action"]);
	//$_SESSION["check_machine"] = $_POST["check_machine"];
	//$_SESSION["check_detail"] = $_POST["check_detail"];
}

?>

<?

##########################################
## FILTROS SELECT MULTIPLE
##########################################
$_SESSION["select_server"] = str_replace("'","",$_SESSION["select_server"]);
if ($_SESSION["select_server"])
{
	$select_server = "'".str_replace(",","','",$_SESSION["select_server"])."'";
	$array_server = explode(",",str_replace("'","",$_SESSION["select_server"]));
}else{
	$sql = "SELECT distinct server from login";
	$result = mysql_query($sql, $link);
	$x = 0;
	while ($rs_server = mysql_fetch_object($result))
	{
			$array_server[$x] = $rs_server->server;
			$x = $x + 1;
	}
	
	$select_server = "'".implode("','",$array_server)."'";
}

$_SESSION["select_tipo"] = str_replace("'","",$_SESSION["select_tipo"]);
if ($_SESSION["select_tipo"])
{
	$select_tipo = "'".str_replace(",","','",$_SESSION["select_tipo"])."'";
	$array_tipo = explode(",",str_replace("'","",$_SESSION["select_tipo"]));
}else{
	$sql = "SELECT distinct tipo from login";
	$result = mysql_query($sql, $link);
	$x = 0;
	while ($rs_tipo = mysql_fetch_object($result))
	{
			$array_tipo[$x] = $rs_tipo->tipo;
			$x = $x + 1;
	}
	$select_tipo = "'".implode("','",$array_tipo)."'";
}

$_SESSION["select_action"] = str_replace("'","",$_SESSION["select_action"]);
if ($_SESSION["select_action"])
{
	$select_action = "'".str_replace(",","','",$_SESSION["select_action"])."'";
	$array_action = explode(",",str_replace("'","",$_SESSION["select_action"]));
}else{
	$sql = "SELECT distinct action from login";
	$result = mysql_query($sql, $link);
	$x = 0;
	while ($rs_action = mysql_fetch_object($result))
	{
			$array_action[$x] = $rs_action->action;
			$x = $x + 1;
	}
	$select_action = "'".implode("','",$array_action)."'";
}

#echo $select_server;
#echo "<br>";
#echo $select_tipo;
#echo "<br>";
#echo $select_action;

#echo "check_machine: " . $_SESSION["check_machine"];
#echo "<br>check_detail: " . $_SESSION["check_detail"];

##########################################
## FIN FILTROS SELECT MULTIPLE
##########################################

# Usa los select como filtro, sino, usa checkbox 
$mostrar = "select";
$select_max = 6;

?>

<html>  
<head>  
<link href="style.css" rel="stylesheet" type="text/css">
<link href="diff.css" rel="stylesheet" type="text/css">
   <script type="text/javascript" src="prototype.js"></script>  
   <script type="text/javascript">  
		new Ajax.PeriodicalUpdater("lista", 'realtime_list.php',{frequency:'30'});
   </script>   

<style>
#boxthing {
	position:absolute;
	right: 0;
	top:30px;
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
	top:0px;
	b-ottom:0;
	l-eft:58%;
	w-idth:250px;
	h-eight:150px;
	b-order:1px #FFFFFF solid;
	b-ackground-color:#000000;
	padding:0.5em; }
</style>

<style type="text/css">
#boxdetail { position: absolute; right: 0px; bottom: 0px; }
div > div#boxdetail { position: fixed; }
pre.fixit { overflow:auto;border-left:1px dashed #000;border-right:1px dashed #000;padding-left:2px; }
</style>

<!--[if gte IE 5.5]><![if lt IE 7]>

<style type="text/css">

div#boxdetail {
	right: auto; bottom: auto;
	left: expression( ( 0 - boxdetail.offsetWidth + ( document.documentElement.clientWidth ? document.documentElement.clientWidth : document.body.clientWidth ) + ( ignoreMe2 = document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft ) ) + 'px' );
	top: expression( ( 0 - boxdetail.offsetHeight + ( document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight ) + ( ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop ) ) + 'px' );
}
</style>
<![endif]><![endif]-->

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
<body >
 <b>Real Time Views</b>
<div id="lista"></div>

<div id="conteniendo">

<div class="menuconfig">
<input type="image" src="icons/edit.png" onclick="handleClick('show it'); return false"> <input type="image" src="icons/cancel.png" onclick="handleClick('hide it'); return false"><br>
</div>

<div id="boxdetail" style="visibility: hidden;" >
	<iframe src ="show_detail.php" width="100%" height="40" frameborder=0 name="show_detail" scrolling="no" ></iframe>
	<input type="image" src="icons/cancel.png" onclick="boxdetailClick('hide it'); return false" >
</div>

<div id="boxthing" style="visibility: hidden;" >
	<b>Filters Config</b>
	
	<form action='content_realtime.php' method=post>
		<input type=hidden name=sesion value=ok>
		
			<table>
				<tr>
					<td>Date</td>
					<td>Host</td>
					<td>Type</td>
					<td>Action</td>
					<td></td>
				</tr>
				<tr>
					<td valign="top" nowrap>
							<?
								$sql = "SELECT distinct LEFT(fecha, 4) AS year FROM login ORDER BY year";
								$result = mysql_query($sql);
								
							?>
							<select name="ano" class="field" style="width: 60px;">
								<option>Year</option>
								<? while ($rs_year = mysql_fetch_object($result)) {
										
										if($_SESSION["ano"] == $rs_year->year) {
											$SELECTED = "selected";
										}
										echo "<option $SELECTED>$rs_year->year</option>\n";
										$SELECTED = "";
										
								} 
								?>
							</select>
							<br>
							<select name="mes" class="field" style="width: 60px;">
								<option>Month</option>
							<?
								for($i=1;$i<=12;$i++)
								{
							?>
									<option <?if($_SESSION["mes"] == $i) echo "selected" ?>><?echo $i ?></option>
							<?
								}
							?>
							
							</select>
							<br>
							<select name="dia" class="field" style="width: 60px;">
								<option>Date</option>
							<?
								for($i=1;$i<=31;$i++)
								{
							?>
									<option <?if($_SESSION["dia"] == $i) echo "selected" ?>><?echo $i ?></option>
							<?
								}
							?>
							</select>
					</td>
					<td valign="top" nowrap>
							<?
								if($mostrar == "select")
								{
								
							?>
									   <select name="select_server[]" class="field" MULTIPLE size="<? echo $select_max ?>" >
										<?
											$sql = "select distinct server from login";
											$result = mysql_query($sql, $link);
											
											while ($rs = mysql_fetch_object($result))
											{
												$valor = array_search($rs->server, $array_server);
										?>
												<option <?if(($i = array_search($rs->server, $array_server)) !== FALSE) echo "selected" ?> value="<? echo $rs->server?>"><? echo $rs->server  ?></option>
										<? 
											}
										?>

										</select>
								<?
								}else{
											$sql = "select distinct server from login";
											$result = mysql_query($sql, $link);
											
											while ($rs = mysql_fetch_object($result))
											{
												$valor = array_search($rs->server, $array_server);
										?>
												<input name="select_server[]" type="checkbox" <?if(($i = array_search($rs->server, $array_server)) !== FALSE) echo "checked" ?> value="<? echo $rs->server?>" /><? echo $rs->server ?><br>
										<? 
											}
								}
								?>
					</td>
					<td valign="top" nowrap>            
							<?
								if($mostrar == "select")
								{
								
							?>
									<select name="select_tipo[]" class="field" MULTIPLE size="<? echo $select_max ?>">
									<?
										$sql = "select distinct tipo from login";
										$result = mysql_query($sql, $link);
										
										while ($rs = mysql_fetch_object($result))
										{
											$valor = array_search($rs->tipo, $array_tipo);
									?>
											<option <?if(($i = array_search($rs->tipo, $array_tipo)) !== FALSE) echo "selected" ?> value="<? echo $rs->tipo?>"><? echo $rs->tipo ?></option>
									<?
										}
									?>
									</select>
					<?
								}else{
										$sql = "select distinct tipo from login";
										$result = mysql_query($sql, $link);
										
										while ($rs = mysql_fetch_object($result))
										{
											$valor = array_search($rs->tipo, $array_tipo);
									?>
											<input name="select_tipo[]" type="checkbox"  <?if(($i = array_search($rs->tipo, $array_tipo)) !== FALSE) echo "checked" ?> value="<? echo $rs->tipo?>" /><? echo $rs->tipo ?><br>
									<?
										}
						}
					?>
					</td>
					<td valign="top" nowrap>            
							<?
								if($mostrar == "select")
								{
							?>
									<select name="select_action[]" class="field" MULTIPLE  size="<? echo $select_max ?>">
									<?
										$sql = "select distinct action from login";
										$result = mysql_query($sql, $link);
										
										while ($rs = mysql_fetch_object($result))
										{
									?>
											<option <?if(($i = array_search($rs->action, $array_action)) !== FALSE) echo "selected" ?>><?echo $rs->action ?></option>
									<?
										}
									?>

									</select>
								<?
								}else{

										$sql = "select distinct action from login";
										$result = mysql_query($sql, $link);
										
										while ($rs = mysql_fetch_object($result))
										{
									?>
											<input name="select_action[]" type="checkbox" <?if(($i = array_search($rs->action, $array_action)) !== FALSE) echo "checked" ?> value="<? echo $rs->action?>" /><?echo $rs->action ?><br>
									<?
										}
								}
								?>
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

</div>

</body>
</html>  
