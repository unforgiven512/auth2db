<? include "verify.php"; ?>
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

#  Copyright (c) 2007,2008,2009,2010 Ezequiel Vera

?>
<HTML>
<HEAD>
<title>Auth2DB</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
<link href="diff.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="preLoadingMessage.js"></script>
</HEAD>
<body>
<? include "conn.php"; ?>
<? include "security.php"; ?>
<? include "header.php"; ?>
<? include "menu_general.php"; ?>

<?

/******************************************************/
/* Funcion paginar
 * actual:          Pagina actual
 * total:           Total de registros
 * por_pagina:      Registros por pagina
 * enlace:          Texto del enlace
 * Devuelve un texto que representa la paginacion
 */

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
	
	//$texto = "&nbsp;&nbsp;&nbsp;&nbsp;" . $texto . " &nbsp;&nbsp;&nbsp;&nbsp;Showing : " . ($numero_inicio + 1)  . " to " . $numero_final . " of " . $total . " ";
	
	//echo "<div class='stylelinks' ><b>" . $total . " events </b></div>";
	
	//if ($total > $numero_final) {
	  $texto = "<b>Search Result</b> &nbsp;&nbsp;&nbsp;&nbsp;" . $texto . " &nbsp;&nbsp;&nbsp;&nbsp;Showing : " . ($numero_inicio + 1)  . " to " . $numero_final . " of " . $total . " ";
	  return $texto;
	//}
}


// ---------------------------------------------

?>
<?

if ($_POST["search"] != "") {
  $search_field = trim($_POST["search"]);
} else if ($_GET["data"] != "") {
  $search_field = trim($_GET["data"]);
}


//$search_field = trim($_POST["search"]);
//$page_limit = 20;

?>
<?
function f_highlight($line,$search_array) {

    for ($i = 0; $i < count($search_array); $i++) {
	$line = str_replace($search_array[$i],"<FONT style='BACKGROUND-COLOR: #CC6600'>$search_array[$i]</FONT>",$line);
    }

    return $line;

}
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
								array('last 10 min',10),
								array('last 30 min',30),
								array('last 60 min',60),
								array('last 6 hours',360),
								array('last 12 hours',720),
								array('last 24 hours',1440),
								array('last 7 days',10080),
								array('last 30 days',40320),
								array('2 months',40320),
								array('3 months',120960)
								);

?>



<?

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

$sql = "SELECT fecha, detalle, source, host FROM log_".date("Y")."_".date("m")."_".date("d")." WHERE fecha > ( DATE_SUB( NOW(), INTERVAL $select_period MINUTE) ) ORDER BY id DESC";


$search_array = explode(" ",$search_field);

for ($i = 0; $i < count($search_array); $i++) {

    #echo substr($search_array[$i],0,1) . " > " . $search_array[$i] . "<br>";
    
    if (substr($search_array[$i],0,1) == "+") {
	$search_add = $search_add . " " . substr($search_array[$i],1) . " " ;
    } else if (substr($search_array[$i],0,1) == "-") {
	$search_del = $search_del . "|" . substr($search_array[$i],1);
    } else {
	$search_add = $search_add . " " . $search_array[$i] . " " ;
    }

}

#echo $search_add . "<br>";
#echo $search_del . "<br>";
$search_add_array = explode(" ",trim($search_add)) ;
#$search_del_array = explode(" ",trim($search_del)) ;


if ($search_field != "")
{
	// Armo consulta con lo pasado en SEARCH.
	//$sql = "SELECT detalle FROM log_2010_03_25 WHERE ";
	$sql = "SELECT * FROM log_".date("Y")."_".date("m")."_".date("d")." WHERE fecha > ( DATE_SUB( NOW(), INTERVAL $select_period MINUTE) ) AND ";
	/*
	for ($i = 0; $i < count($search_array); $i++) {
		//$sql = $sql . " detalle REGEXP '$search_array[$i]' AND ";
		$sql = $sql . " detalle LIKE '%$search_array[$i]%' AND ";
	}
	*/

	for ($i = 0; $i < count($search_add_array); $i++) {
		//$sql = $sql . " detalle REGEXP '$search_array[$i]' AND ";
		$sql = $sql . " detalle LIKE '%$search_add_array[$i]%' AND ";
	}

	if (isset($search_del)) {
		$sql = $sql . " detalle NOT REGEXP '" . substr($search_del,1) . "' AND ";
	}

	$sql = substr($sql, 0, -4);
	$sql = $sql . " ORDER BY fecha DESC ";
	
} else {
	//$sql = "SELECT detalle FROM log_2010_03_25 ORDER BY id DESC ";
	$sql = "SELECT * FROM log_".date("Y")."_".date("m")."_".date("d")." WHERE fecha > ( DATE_SUB( NOW(), INTERVAL $select_period MINUTE) ) ORDER BY fecha DESC";
}


/*
$_condicion = " WHERE fecha > ( DATE_SUB( NOW(), INTERVAL $select_period MINUTE) ) ";

$sql = str_replace("UNION",$_condicion." UNION",$sql);
//$sql = $sql . " WHERE fecha > ( DATE_SUB( NOW(), INTERVAL $select_period MINUTE) ) ORDER BY fecha DESC LIMIT 100";
$sql = $sql . " WHERE fecha > ( DATE_SUB( NOW(), INTERVAL $select_period MINUTE) ) ORDER BY fecha DESC";
*/

//echo $sql."<br><br>";
    
    $result = mysql_query($sql) or die("What are you Doing?");
    
    //echo "<br>";
    //echo count($_POST["fields"]);
?>

<p class="itemsMenu001"></p>

<form ACTION="search.php" METHOD="POST">
<input type=hidden name=sesion value=ok>
search: <input name=search value="<?=$search_field?> " size=100>

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

<input type=submit value=go>

</form>

<table border=0 width="100%" height="82%">
    <tr>
        <td width=24 valign="top" nowrap>
<?
/*
        <div class="left-menu">
        <div align=center class="bloqueTitle" ><b>Details</b></div>
        <div class="divisor"></div>
	    host ()
	    <br>
	    source ()
        </div>
        </div>
*/
?>
        </td>
        <td valign="top" align="left">
        <div class="right-frame" >
	    
	    <div class="search-log" >
        <? flush();?>
        <?
		/*
	    $search_array = explode(" ",$search_field);

	    if ($search_field != "")
	    {
		// Armo consulta con lo pasado en SEARCH.
		$sql = "SELECT detalle FROM log_2010_03_25 WHERE ";
		for ($i = 0; $i < count($search_array); $i++) {
		    $sql = $sql . "detalle REGEXP '$search_array[$i]' AND ";
		}
		$sql = substr($sql, 0, -4);
		$sql = $sql . " ORDER BY id DESC ";
		//$sql = $sql . "LIMIT $page_limit";

	    //echo $sql;
	    //echo "<br>";
	    //exit;

	    } else {
		//$sql = "SELECT detalle FROM login LIMIT $page_limit";
		$sql = "SELECT detalle FROM log_2010_03_25 ORDER BY id DESC ";
	    }
		*/

	    // -----------------------------------
	    // PAGINADO
	    // -----------------------------------
	    $pag = $_GET["pag"];

	    if (!isset($pag)) $pag = 1; // Por defecto, pagina 1

	    //$sql = "SELECT * FROM alert 
		//	WHERE notified_time LIKE '".date("Y")."-".date("m")."-".date("d")."%' ORDER BY id DESC";
		//echo "paginar: " . $sql;
		
	    $result = mysql_query($sql);

	    $result = mysql_query($sql);
	    $total = mysql_num_rows($result);

	    $tampag = 10;
	    $reg1 = ($pag - 1) * $tampag;
	    $sql_paginar = $sql . " LIMIT $reg1, $tampag";
	    $result = mysql_query($sql_paginar);

	    $data = $search_field;

	    echo "<div class='stylelinks' > " . paginar($pag, $total, $tampag, "?pag=", $tipo, $data) . "</div>";


	    // ----------------------------------



            //$result = mysql_query($sql, $link);
            while ($rs = mysql_fetch_object($result) ){
                //echo $rs_tipo->tipo;
                echo "<br>";
                ////$template = new Statistic_Hour;
                ////$template->show($rs_tipo->tipo,'action','campo','green','mes','server','year');
                //echo $rs->detalle;
                echo $rs->fecha;
                echo " | ";
                echo f_highlight($rs->detalle,$search_array);
                echo "<div class='stylelinks' >";
                echo "host=<b>".$rs->host . "</b>";
                echo "&nbsp; &nbsp; &nbsp; &nbsp;";
                echo "source=<b>".$rs->source . "</b>";
                echo "&nbsp; &nbsp; &nbsp; &nbsp;";
		echo "sourcetype=<b>".$rs->sourcetype . "</b>";
                echo "&nbsp; &nbsp; &nbsp; &nbsp;";
                echo "punct=<b>".$rs->punct . "</b>";
                echo "</div>";
                flush();
                //echo "<br>";
            }

            //$template = new Statistic_Hour;
            //$template->show('sshd','action','campo','green','mes','server','year');
        ?>
	    </div>

        <br><br>

        </div>
        </td>
    </tr>
</table>

</HTML>
