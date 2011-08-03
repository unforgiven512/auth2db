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

/*
if($_POST["sesion"] == "ok")
{
    //$_SESSION["dia"] = $_POST["dia"];
    //$_SESSION["mes"] = $_POST["mes"];
    //$_SESSION["select_server"] = implode(",",$_POST["select_server"]);
    //$_SESSION["select_tipo"] = implode(",",$_POST["select_tipo"]);
    //$_SESSION["select_action"] = implode(",",$_POST["select_action"]);
    //$_SESSION["check_machine"] = $_POST["check_machine"];
    //$_SESSION["check_detail"] = $_POST["check_detail"];
}

*/



include("conn.php");
include("security.php");
require('geoip_functions.php');


if (strlen($_SESSION["mes"]) < 2) {
    $t_mes = "0".$_SESSION["mes"];
} else {
    $t_mes = $_SESSION["mes"];
}
        
if (strlen($_SESSION["dia"]) < 2) {
    $t_dia = "0".$_SESSION["dia"];
} else  {
    $t_dia = $_SESSION["dia"];
}
                

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
	$_SESSION["ano"] = date("Y");
	$_SESSION["dia"] = date("d");
    $_SESSION["mes"] = date("m");
}
else
{
	$consulta_fecha = $_SESSION["ano"]."-".$_SESSION["mes"]."-".$_SESSION["dia"];    
    $consulta_fecha_mas = $_SESSION["ano"]."-".$_SESSION["mes"]."-".($_SESSION["dia"]+1);    
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
	$sql = "SELECT distinct server from log_".$_SESSION["ano"]."_".$t_mes."_".$t_dia;
	//echo $sql;
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
	//echo $sql;
	$sql = "SELECT distinct tipo from log_".$_SESSION["ano"]."_".$t_mes."_".$t_dia;
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
	//echo $sql;
	$sql = "SELECT distinct action from log_".$_SESSION["ano"]."_".$t_mes."_".$t_dia;
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

#echo "check_machine: " . $check_machine;
#echo "<br>check_detail: " . $check_detail;

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

<script type="text/javascript" src="prototype.js"></script>  
	<script type="text/javascript">  
		new Ajax.PeriodicalUpdater("lista", 'realtime_list.php',{frequency:'10'});
	</script>   

</head>

<body>

<?
$check_machine = "ok";
//$check_detail = "ok";
?>

<?

$tipo = sec_cleanTAGS($_GET["tipo"]);
$data = sec_cleanTAGS($_GET["data"]);

$tipo = sec_addESC($tipo);
$data = sec_addESC($data);


echo "<b>".$tipo."</b>: ".$data."<br><br>";

# -----------------------------------
# LIMITE DE ROWS A MOSTRAR
# -----------------------------------

$SHOW_LIMIT = $_SESSION["limite"];

if ($SHOW_LIMIT == "") {
    $SHOW_LIMIT = 100;
}

# -----------------------------------


if($tipo == "")
{
    $sql = "SELECT * FROM login WHERE server in (".$select_server.") AND tipo in (".$select_tipo.") AND action in (".$select_action.") AND fecha > '$consulta_fecha' AND fecha < '$consulta_fecha_mas' order by fecha DESC, id DESC LIMIT $SHOW_LIMIT";
    $sql = "SELECT * FROM log_".$_SESSION["ano"]."_".$t_mes."_".$t_dia." WHERE server in (".$select_server.") AND tipo in (".$select_tipo.") AND action in (".$select_action.") AND fecha > '$consulta_fecha' AND fecha < '$consulta_fecha_mas' order by fecha DESC, id DESC LIMIT $SHOW_LIMIT";
    //echo $sql;
    $result = mysql_query($sql, $link) or die("What are you Doing?");
}
else
{
    $sql = "SELECT distinct substring_index(fecha,':',3) as fecha,server,tipo,pid,action,usuario,ip,machine,detalle FROM login WHERE ".$tipo." = '".$data."' AND fecha > '$consulta_fecha' AND fecha < '$consulta_fecha_mas' ORDER BY fecha DESC, id DESC LIMIT $SHOW_LIMIT";
    $sql = "SELECT distinct substring_index(fecha,':',3) as fecha,server,tipo,pid,action,usuario,ip,machine,detalle FROM log_".$_SESSION["ano"]."_".$t_mes."_".$t_dia." WHERE ".$tipo." = '".$data."' AND fecha > '$consulta_fecha' AND fecha < '$consulta_fecha_mas' ORDER BY fecha DESC, id DESC LIMIT $SHOW_LIMIT";
    //echo $sql;
    $result = mysql_query($sql, $link) or die("What are you Doing?"); 
}

?>
<div id="lista--">
<table align="left">
<tr><td valign="top">

	<table width="200" border="0" cellpadding="1" cellspacing="1">
	  <tr valign="top"> 
		<td nowrap class="filasTituloMain01" >Date</td>
		<td nowrap class="filasTituloMain01" width="60" >Host</td>
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
	  <? 
		$sql = "SELECT action_name, action_alias, color FROM action_config";
		$resultAction = mysql_query($sql);
	
		$array_action = array();

		$i = 1;
		while($rsAction = mysql_fetch_object($resultAction))
		{
			$array_action[$i] = strtolower($rsAction->action_name);
			$array_action_alias[$i] = $rsAction->action_alias;
			$array_color[$i] = $rsAction->color;
			$i++;
		}
		//echo $array_action[1][2];
		//$array_action = array("Failed");
		
		while ($rs = mysql_fetch_object($result)){ ?>
	  <tr class="filas" valign="top" <? if ($rs->action != "") {
		  
				$key = array_search(strtolower($rs->action), $array_action);
				//if (in_array(strtolower($rs->action), $array_action))
				if ($key > 0) {
					echo "bgcolor='#".$array_color[$key]."'";
				} else {
					echo "bgcolor='#EEEEEE'";
				}
			}
				?>>
		<td nowrap ><? echo sec_cleanHTML($rs->fecha) ?></td>
		<td nowrap ><a href="auth2db_list.php?data=<?echo sec_cleanHTML($rs->server) ?>&tipo=server " ><? echo sec_cleanHTML($rs->server) ?></a></td>
		<td nowrap ><a href="auth2db_list.php?data=<?echo sec_cleanHTML($rs->tipo) ?>&tipo=tipo " ><? echo sec_cleanHTML($rs->tipo) ?></a></td>
		<td nowrap ><a href="auth2db_list.php?data=<?echo sec_cleanHTML($rs->pid) ?>&tipo=pid " ><? echo sec_cleanHTML($rs->pid) ?></a></td>
		<td nowrap ><a href="auth2db_list.php?data=<?echo sec_cleanHTML($rs->action) ?>&tipo=action " >
			<?
				$key = array_search(strtolower($rs->action), $array_action);
				//if (in_array(strtolower($rs->action), $array_action))
				if ($key > 0 and $array_action_alias[$key] != "") {
					echo $array_action_alias[$key];
				} else {
					echo sec_cleanHTML($rs->action);
				}
			?>
		</td>
		<td nowrap ><a href="auth2db_list.php?data=<?echo sec_cleanHTML($rs->usuario) ?>&tipo=usuario " ><? echo sec_cleanHTML($rs->usuario) ?></a></td>
		<td ><a href="auth2db_list.php?data=<?echo sec_cleanHTML($rs->ip) ?>&tipo=ip " ><? echo sec_cleanHTML($rs->ip) ?></a></td>
		<? if ($check_machine != "ok" ) { ?>
			<td ><a href="auth2db_list.php?data=<?echo sec_cleanHTML($rs->machine) ?>&tipo=machine " ><? echo sec_cleanHTML($rs->machine) ?></a></td>
		<? } ?>
		<? if ($check_detail != "ok" ) { ?>
			<? 
			    $pos = strpos($rs->detalle, $rs->action); 
			    $resto = strlen($rs->detalle) - $pos;
			?>
			<td nowrap title="<? echo sec_cleanHTML($rs->detalle) ?> " ><? if (strlen($rs->detalle) > 76) echo "... " ?><? echo substr($rs->detalle, $pos, 76) ?><? if ($resto > 76) echo "... " ?></td>
		<? } ?>
<?
/*
		<td nowrap class="filasBandera" ><a href="show_detail.php?id=<? echo $rs->id ?>" target="show_detail" onclick="boxdetailClick('show it'); " >
			<img src="icons/page_white_magnify.png" width="12" height="12" border=0 ></a></td>
*/
?>
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

</td>
<td>

		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		  <tr valign="top">
				  <td valign="top">



				  </td> 
				<tr>

			  </table>
			</td>
		  </tr>
		</table>


</tr>
</tr>
</table>
</div>
</body>
</html>
