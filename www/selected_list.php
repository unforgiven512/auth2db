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

session_start();

include("conn.php");
include("security.php");
require('geoip_functions.php');

#if(!is_array($_SESSION["select_server"] ))
	#$_SESSION["select_server"] = explode(",",$_SESSION["select_server"]);

/*
if($_POST["sesion"] == "ok")
{
    $_SESSION["dia"] = $_POST["dia"];
    $_SESSION["mes"] = $_POST["mes"];
	$_SESSION["select_server"] = $_POST["select_server"];
	$_SESSION["select_tipo"] = $_POST["select_tipo"];
	$_SESSION["select_action"] = $_POST["select_action"];
}
*/

if($_SESSION["dia"] == "" && $_SESSION["mes"] == "")
{
    $consulta_fecha = date("Y")."-".date("m")."-".date("d");    
    $consulta_fecha_mas = date("Y")."-".date("m")."-".(date("d")+1);        
	$_SESSION["dia"] = date("d");
    $_SESSION["mes"] = date("m");
}
else
{
    $consulta_fecha = date("Y")."-".$_SESSION["mes"]."-".$_SESSION["dia"];    
    $consulta_fecha_mas = date("Y")."-".$_SESSION["mes"]."-".($_SESSION["dia"]+1);    
}

//echo $consulta_fecha;
//echo $consulta_fecha_mas;



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
	t-op:0px;
	bottom: 0;
	l-eft:58%;
	w-idth:250px;
	h-eight:150px;
	border:1px #FFFFFF solid;
	background-color:#000000;
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

<!--
function blocking(nr)
{
	if (document.layers)
	{
		current = (document.layers[nr].display == 'none') ? 'block' : 'none';
		document.layers[nr].display = current;
	}
	else if (document.all)
	{
		current = (document.all[nr].style.display == 'none') ? 'block' : 'none';
		document.all[nr].style.display = current;
	}
	else if (document.getElementById)
	{
		vista = (document.getElementById(nr).style.display == 'none') ? 'block' : 'none';
		document.getElementById(nr).style.display = vista;
	}
}
function ask(q){if(confirm(q)){return true;}else{return false;}}
//-->

</script>

</head>

<body >

<div id="boxthing" style="visibility: hidden;" >
	<iframe src ="show_detail.php" width="100%" height="40" frameborder=0 name="show_detail" scrolling="no" ></iframe>
	<input type="image" src="icons/cancel.png" onclick="handleClick('hide it'); return false" >
</div>

<?


$tipo = sec_cleanHTML($_GET["tipo"]);
$tipo = sec_addESC($tipo);

$data = sec_cleanHTML($_GET["data"]);
$data = sec_addESC($data);

$server = sec_cleanHTML($_GET["server"]);
$server = sec_addESC($server);

// ----------------------------------------------------------

/******************************************************/
/* Funcion paginar
 * actual:          Pagina actual
 * total:           Total de registros
 * por_pagina:      Registros por pagina
 * enlace:          Texto del enlace
 * Devuelve un texto que representa la paginacion
 */
function paginar_original($actual, $total, $por_pagina, $enlace, $tipo, $data) {
	$total_paginas = ceil($total/$por_pagina);
	$anterior = $actual - 1;
	$posterior = $actual + 1;
	
	if ($actual>1)
		$texto = "<a href='$enlace $anterior&tipo=$tipo&data=$data'>&laquo;</a> ";
	else
		$texto = "<b>&laquo;</b> ";
  
	for ($i=1; $i<$actual; $i++)
		$texto .= "<a href='$enlace$i&tipo=$tipo&data=$data'>$i</a> ";
  
	$texto .= "<b>$actual</b> ";
	for ($i=$actual+1; $i<=$total_paginas; $i++)
		$texto .= "<a href='$enlace$i&tipo=$tipo&data=$data'>$i</a> ";
  
	if ($actual<$total_paginas)
		$texto .= "<a href='$enlace$posterior&tipo=$tipo&data=$data'>&raquo;</a>";
	else
		$texto .= "<b>&raquo;</b>";
	
	return $texto;
}


function paginar($actual, $total, $por_pagina, $enlace, $tipo, $data,$server) {
	$total_paginas = ceil($total/$por_pagina);
	$anterior = $actual - 1;
	$posterior = $actual + 1;
	
	if ($actual>1) {
		$texto = "<a href='$enlace 1&tipo=$tipo&data=$data&server=$server'><img src='icons/go-first.png' border=0></a>";
		$texto .= "<a href='$enlace $anterior&tipo=$tipo&data=$data&server=$server'><img src='icons/go-previous.png' border=0></a> ";
	} else {
		$texto = "<img src='icons/go-first.png' border=0>";
		$texto .= "<img src='icons/go-previous.png' border=0> ";
	}

	//for ($i=1; $i<$actual; $i++)
		//$texto .= "<a href='$enlace$i&tipo=$tipo&data=$data'>$i</a> ";
  
	//$texto .= "<b>$actual</b> ";
	//for ($i=$actual+1; $i<=$total_paginas; $i++)
		//$texto .= "<a href='$enlace$i&tipo=$tipo&data=$data'>$i</a> ";
		
		
		$valor_limite = 9;
	if (($actual - $valor_limite) > 1){
		$inicio = $actual - $valor_limite;
	} else {
		$inicio = 1;
	}
	
	if (($inicio + $valor_limite) < $total_paginas){
		$final = $inicio + $valor_limite;
	} else {
		$final = $total_paginas;
	}

	for ($i=$inicio; $i<$actual; $i++)
		$texto .= "<a href='$enlace$i&tipo=$tipo&data=$data&server=$server' >$i</a> ";
  
	$texto .= "<b>$actual</b> ";
	
	for ($i=$actual+1; $i<=$final; $i++)
		$texto .= "<a href='$enlace$i&tipo=$tipo&data=$data&server=$server' >$i</a> ";
		
		
		
  
	if ($actual<$total_paginas){
		$texto .= "<a href='$enlace$posterior&tipo=$tipo&data=$data&server=$server'><img src='icons/go-next.png' border=0></a>";
		$texto .= "<a href='$enlace$total_paginas&tipo=$tipo&data=$data&server=$server'><img src='icons/go-last.png' border=0></a>";
	} else {
		$texto .= "<img src='icons/go-next.png' border=0>";
		$texto .= "<img src='icons/go-last.png' border=0>";
	}
	
	
	
	if ( (($actual * $por_pagina) - $por_pagina) < 1){
		$numero_inicio = 0;
	} else {
		$numero_inicio = (($actual * $por_pagina) - $por_pagina) ; 
	}
	
	if ( ($actual * $por_pagina) > $total ){
		$numero_final = $total ;
	} else {
		$numero_final = ($actual * $por_pagina) ;
	}
	
	$texto = "&nbsp;&nbsp;&nbsp;&nbsp;" . $texto . " &nbsp;&nbsp;&nbsp;&nbsp;Showing : " . ($numero_inicio + 1)  . " to " . $numero_final . " of " . $total . " ";
	
	
	
	return $texto;
}


// -----------------------------------------------------------


if($tipo == "")
{
    if ($server == "" AND $data == "")	{
	$sql = "SELECT server from login LIMIT 1";
	$result_server = mysql_query($sql, $link);
	$row_server = mysql_fetch_row($result_server);
    
	$sql = "SELECT id,fecha,server,tipo,pid,action,usuario,ip FROM login WHERE server = '".$row_server[0]."' order by fecha DESC, id DESC "; 
    } else {
	$sql = "SELECT * FROM login WHERE server in (".$select_server.") AND tipo in (".$select_tipo.") AND action in (".$select_action.")  order by fecha DESC, id DESC";
    }

    $result = mysql_query($sql, $link);

}
else
{
    $sql = "SELECT distinct substring_index(fecha,':',3) as fecha, id, server,tipo,pid,action,usuario,ip,machine,detalle FROM login WHERE ".$tipo." = '".$data."' AND fecha > '$consulta_fecha' AND fecha < '$consulta_fecha_mas' ORDER BY fecha DESC, id DESC";
	
    $sql = "SELECT distinct substring_index(fecha,':',3) as fecha, id, server,tipo,pid,action,usuario,ip,machine,detalle FROM login WHERE ".$tipo." = '".$data."' AND server = '".$server."' ORDER BY fecha DESC, id DESC";
    //$result = mysql_query($sql, $link); 

}

#echo $sql;

// -----------------------------------
// PAGINADO
// -----------------------------------
$pag = sec_cleanHTML($_GET["pag"]);
$pag = sec_addESC($pag);

if ($pag == ""){
    $pag = 1;
}

#echo "|- ".$pag." -|";

if (!isset($pag)) $pag = 1; // Por defecto, pagina 1
//$result = mysql_query("SELECT COUNT(*) FROM clientes", $link); 
//list($total) = mysql_fetch_row($result);

//$result = mysql_query("SELECT count(*) FROM login WHERE ".$tipo." = '".$data."' AND server = '".$server."'");
//list($total) = mysql_fetch_row($result);

$result = mysql_query($sql) or die("What are you Doing?");
$total = mysql_num_rows($result);

$tampag = 10;
$reg1 = ($pag - 1) * $tampag;
$sql_paginar = $sql . " LIMIT $reg1, $tampag";
$result = mysql_query($sql_paginar) or die("What are you Doing?");

echo "<div class='stylelinks' >  <b>Views History</b> " . paginar($pag, $total, $tampag, "selected_list.php?pag=", $tipo, $data, $server) . "</div>";

// ----------------------------------

?>

<?
$check_machine = "ok";
$check_detail = "ok";
?>

<table align="left">
<tr><td>

	<table width="200" border="0" cellpadding="1" cellspacing="1">
	  <tr valign="top"> 
		<td nowrap class="filasTituloMain01" >Date</td>
		<td nowrap class="filasTituloMain01" width="60"  >Host</td>
		<td nowrap class="filasTituloMain01" >Type</td>
		<td nowrap class="filasTituloMain01" width="35" >PID</td>
		<td nowrap class="filasTituloMain01" width="80" >Action</td>
		<td nowrap class="filasTituloMain01" width="60" >User</td>
		<td nowrap class="filasTituloMain01" width="100" >IP</td>
		<? if ($check_machine != "ok" ) { ?>
			<td nowrap  class="filasTituloMain01" >Machine</td>
		<? } ?>
		<? if ($check_detail != "ok" )  { ?>
			<td nowrap  class="filasTituloMain01" >Detail</td>
		<? } ?>
	  </tr>
	  <? while ($rs = mysql_fetch_object($result)){ ?>
	  <tr class="filas" valign="top" <? if ($rs->action != "") {
					if ($rs->action == 'no') {
					echo "bgcolor='#FFFFFF'";
					} else if ($rs->action == 'closed'){
					echo "bgcolor='#EEEEEE'";
					} else if ($rs->action == 'opened'){
						echo "bgcolor='#00FF99'";
					} else if (strtolower($rs->action) == 'accepted' or strtolower($rs->action) == 'successful'){
						echo "bgcolor='#00FF22'";
					} else if (strtolower($rs->action) == 'failed'){
						echo "bgcolor='#FF9999'";
					} else if (strtolower($rs->action) == 'failure' or strtolower($rs->action) == 'not found'){
						echo "bgcolor='#FFBBBB'";
					} else if (strtolower($rs->action) == 'incorrect password' or strtolower($rs->action) == 'no such user'){
						echo "bgcolor='#FFBBBB'";
					} else if (strtolower($rs->action) == 'login successful'){
						echo "bgcolor='#00FE22'";
					} else {
						echo "bgcolor='#BBBBBB'";
					}
				} else {
					echo "bgcolor='#EEEEEE'";
				}
				?>>
		<td nowrap height=10><? echo $rs->fecha ?></td>
		<td nowrap ><a href="auth2db_list.php?data=<?echo $rs->server?>&tipo=server " target="_blank" ><? echo $rs->server ?></a></td>
		<td nowrap ><a href="auth2db_list.php?data=<?echo $rs->tipo?>&tipo=tipo " target="_blank" ><? echo $rs->tipo ?></a></td>
		<td nowrap ><a href="auth2db_list.php?data=<?echo $rs->pid?>&tipo=pid " target="_blank" ><? echo $rs->pid ?></a></td>
		<td nowrap ><a href="auth2db_list.php?data=<?echo $rs->action?>&tipo=action " target="_blank" ><? echo $rs->action ?></td>
		<td nowrap ><a href="auth2db_list.php?data=<?echo $rs->usuario?>&tipo=usuario " target="_blank" ><? echo $rs->usuario ?></a></td>
		<td ><a href="auth2db_list.php?data=<?echo $rs->ip?>&tipo=ip " target="_blank" ><? echo $rs->ip ?></a></td>
		<? if ($check_machine != "ok" ) { ?>
			<td ><a href="auth2db_list.php?data=<?echo $rs->machine?>&tipo=machine " target="_blank" ><? echo $rs->machine ?></a></td>
		<? } ?>
		<? if ($check_detail != "ok" ) { ?>
			<? 
			    $pos = strpos($rs->detalle, $rs->action); 
			    $resto = strlen($rs->detalle) - $pos;
			?>
			<td nowrap title="<? echo $rs->detalle ?> " ><? if (strlen($rs->detalle) > 76) echo "... " ?><? echo substr($rs->detalle, $pos, 76) ?><? if ($resto > 76) echo "... " ?></td>
		<? } ?>
		
		<td class="filasBandera"><a href="show_detail.php?id=<? echo $rs->id ?>" target="show_detail" onclick="handleClick('show it'); " >
			<img src="icons/page_white_magnify.png" width="12" height="12" border=0 ></a></td>
		
		<?
//			if (($rs->action == 'Accepted' OR $rs->action == 'Failed' OR $rs->tipo == 'proftpd' OR $rs->tipo == 'apache' OR $rs->tipo == 'opened') AND file_exists("banderas/".getCCfromIP($rs->ip,$link).".gif") )
			if (file_exists("banderas/".getCCfromIP($rs->ip,$link).".gif") )
			{
		?>
				<td nowrap class="filasBandera" ><? echo "<img width=18 height=12 border='0' src='banderas/".getCCfromIP($rs->ip,$link).".gif' >" ?></td>
				<td nowrap class="filasBandera" ><? echo getCOUNTRYfromIP($rs->ip,$link) . " (".getCCfromIP($rs->ip,$link).")" ?></td>
		<?
			}
		?>

	  </tr>
	  <? } ?>
	</table>

</td></tr>
</table>

</body>
</html>
