<? include "verify.php" ?>
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

<div id="boxthing" style="visibility: hidden;" >
	<iframe src ="show_detail.php" width="100%" height="40" frameborder=0 name="show_detail" scrolling="no" ></iframe>
	<input type="image" src="icons/cancel.png" onclick="handleClick('hide it'); return false" >
</div>

<?
include "conn.php";

/******************************************************/
/* Funcion paginar
 * actual:          Pagina actual
 * total:           Total de registros
 * por_pagina:      Registros por pagina
 * enlace:          Texto del enlace
 * Devuelve un texto que representa la paginacion
 */
function paginar_BAK($actual, $total, $por_pagina, $enlace, $tipo, $data) {
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


function paginar($actual, $total, $por_pagina, $enlace, $tipo, $data, $server) {
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


// ---------------------------------------------

// -----------------------------------
// PAGINADO
// -----------------------------------
$pag = $_GET["pag"];

if (!isset($pag)) $pag = 1; // Por defecto, pagina 1
//$result = mysql_query("SELECT COUNT(*) FROM clientes", $link); 
//list($total) = mysql_fetch_row($result);

//$result = mysql_query("SELECT count(*) FROM login WHERE ".$tipo." = '".$data."' AND server = '".$server."'");
//list($total) = mysql_fetch_row($result);

$host = $_GET["host"];
$data = $_GET["data"];
$tipo = $_GET["tipo"];
$id_alert_config = $_GET["data"];

if($server == "")
{
	$sql = "SELECT * FROM alert ORDER BY id DESC";
} else {
	$sql = "SELECT * FROM alert WHERE id_alert_config = '$id_alert_config' AND hostname = '$server' ORDER BY id DESC";
	}

if($host == "")
{
	$sql = "SELECT * FROM alert_".date("Y")."_".date("m")."_".date("d")." ORDER BY id DESC";
} else {
	$sql = "SELECT * FROM alert_".date("Y")."_".date("m")."_".date("d")." WHERE id_alert_config = '$id_alert_config' AND host = '$host' ORDER BY id DESC";
	}

$result = mysql_query($sql);
$total = mysql_num_rows($result);

$tampag = 20;
$reg1 = ($pag - 1) * $tampag;
$sql_paginar = $sql . " LIMIT $reg1, $tampag";
$result = mysql_query($sql_paginar);


echo "<div class='stylelinks' > <b>Alert History</b> " . paginar($pag, $total, $tampag, "?pag=", $tipo, $data, $server) . "</div>";


// ----------------------------------

?>
<div class="c-enterbox">
<table>
	<tr class="filasTituloMain01">
		<td width="100" nowrap><b>Notified Time</b></td>
		<td width="80" nowrap><b>Alert Name</b></td>
		<td width="60"><b>Hostname</b></td>
		<td width="60"><b>criticality</b></td>
	</tr>
<?
while($rs = mysql_fetch_object($result))
{
?>
	<tr style="background: #555555">
		<td valign=top nowrap><? echo $rs->notified_time ?></td>
		<td valign=top nowrap><? echo $rs->alert_name ?></td>
		<td valign=top><? echo $rs->host ?></td>
		<td valign=top><? 
											if ($rs->criticality == "High")
											{
													echo "<img src='icons/redled.png' border=0 width=14>";
											} else if ($rs->criticality == "Medium")
											{
													echo "<img src='icons/yellowled.png' border=0>";
											} else if ($rs->criticality == "Low")
											{
													echo "<img src='icons/greenled.png' border=0>";
											}
									?> <? echo $rs->criticality ?></td>
		<td class="filasBandera"><a href="show_alert_detail.php?id=<? echo $rs->id ?>" target="show_detail" onclick="handleClick('show it'); " >
			<img src="icons/page_white_magnify.png" width="12" height="12" border=0 ></a></td>
	</tr>
<?
}
?>
</div>
