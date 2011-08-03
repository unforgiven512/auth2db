<? include "verify.php" ?>
<html>
<head>
	<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
	<title>Auth2DB</title>
</head>
<body>
<link href="style.css" rel="stylesheet" type="text/css">
<link href="diff.css" rel="stylesheet" type="text/css">
<?
/*
<? include "header.php" ?>
<? include "menu_general.php" ?>

<p class="itemsMenu001"></p>
*/
?>
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


function paginar($actual, $total, $por_pagina, $enlace, $tipo, $data) {
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
		$texto .= "<a href='$enlace$i&tipo=$tipo&data=$data' >$i</a> ";
  
	$texto .= "<b>$actual</b> ";
	
	for ($i=$actual+1; $i<=$final; $i++)
		$texto .= "<a href='$enlace$i&tipo=$tipo&data=$data' >$i</a> ";



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

$sql = "SELECT * FROM alert ORDER BY id DESC";
$sql = "SELECT * FROM alert 
	    WHERE notified_time LIKE '2009-03-16%' ORDER BY id DESC";

$sql = "SELECT * FROM alert 
	    WHERE notified_time LIKE '".date("Y")."-".date("m")."-".date("d")."%' ORDER BY id DESC";

$sql = "SELECT * FROM alert_".date("Y")."_".date("m")."_".date("d")." ORDER BY id DESC";

$result = mysql_query($sql);

$result = mysql_query($sql);
$total = mysql_num_rows($result);

$tampag = 5;
$reg1 = ($pag - 1) * $tampag;
$sql_paginar = $sql . " LIMIT $reg1, $tampag";
$result = mysql_query($sql_paginar);


echo "<div class='stylelinks' > <b>Alert List</b> " . paginar($pag, $total, $tampag, "?pag=", $tipo, $data) . "</div>";


// ----------------------------------

?>
<div class="c-enterbox">
<br>
<table>
	<tr class="filasTituloMain01">
		<td width="100"><b>Notified Time</b></td>
		<td width="80"><b>Alert Name</b></td>
		<td width="60"><b>Hostname</b></td>
		<td width="60"><b>criticality</b></td>
		<td width="80"><b>Detail</b></td>
	</tr>
<?
while($rs = mysql_fetch_object($result))
{
?>
	<tr style="background: #555555">
		<td valign=top nowrap><? echo $rs->notified_time ?></td>
		<td valign=top><? echo $rs->alert_name ?></td>
		<td valign=top><? echo $rs->hostname ?></td>
		<td valign=top><? 
											if ($rs->criticality == "High")
											{
													echo "<img src='icons/redled.png' border=0>";
											} else if ($rs->criticality == "Medium")
											{
													echo "<img src='icons/yellowled.png' border=0>";
											} else if ($rs->criticality == "Low")
											{
													echo "<img src='icons/greenled.png' border=0>";
											}
									?> <? echo $rs->criticality ?></td>
		<td width="200"><? echo $rs->detalle?></td>
	</tr>
<?
}
?>
</div>