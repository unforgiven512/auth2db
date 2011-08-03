<? 
include "verify.php";
include "conn.php";
include "security.php";
?>

<link href="style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="ajax_alert.js"></script>
<script type="text/javascript">
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

<div class="left-menu">
<div align=center class="bloqueTitle" ><b>Alerts History</b></div>
<?
    //$sql = "select * from login";
    $sql = "SELECT distinct server, host_config.type as host_type from login
		LEFT JOIN host_config ON login.server = host_config.hostname ORDER BY server";

$ano = date("Y");
$mes = date("m");
$dia = date("d");

if (table_exists($ano."_".$mes."_".$dia) == 1) {
    $sql = "SELECT distinct host, T2.type as host_type from log_".$ano."_".$mes."_".$dia." AS T1
		LEFT JOIN host_config AS T2 ON T1.host = T2.hostname ORDER BY host";
} else {
    $sql = "SELECT distinct host, T2.type as host_type from log_0000_00_00 AS T1
		LEFT JOIN host_config AS T2 ON T1.host = T2.hostname ORDER BY host";
}

    //$sql = "SELECT DISTINCT server FROM login ORDER BY server";
    $result = mysql_query($sql);
    while($menu = mysql_fetch_object($result))
    {

	$contador++;
	
	if (strtolower($menu->host_type) == "linux") {
		$imagen_host = "tux.png";
	} else if (strtolower($menu->host_type) == "windows"){
		$imagen_host = "win.png";
	} else if (strtolower($menu->host_type) == "cisco"){
		$imagen_host = "cisco.gif";
	} else {
		$imagen_host = "server.png";
	}

?>
<div class="divisor"></div>
	<div class="menu-item" onclick="blocking('sub-items-<? echo $contador ?>');<?echo "Load('" . $menu->host . "','sub-items-". $contador ."')";?>"><img src="icons/<? echo $imagen_host ?>"> <? echo $menu->host ?></div>
	<?
    
	if(intval($display) == $contador)
	{
?>
	    <div class="menu-sub-item" id="sub-items-<? echo $contador; ?>" style="display: block;">
<?
	}
	else
	{
?>
	    <div class="menu-sub-item" id="sub-items-<? echo $contador; ?>" style="display: none;">
<?
	}
	
	//echo "Load('" . $menu->server . "','sub-items-". $contador ."')";
	
	/*
	$sql = "SELECT count(id_alert_config) as cantidad, id_alert_config, alert_name from alert WHERE hostname = '".$menu->server."' GROUP BY id_alert_config, alert_name ";

	$result_items = mysql_query($sql);
	while($items = mysql_fetch_object($result_items))
	{
		$mas = $mas +1;
    ?>
	    <div class='menu-sub-item' onclick="blocking('sub-items2-<? echo $mas ?>')" ><a href="alert_selected_list.php?tipo=tipo&data=<? echo $items->id_alert_config ?>&server=<? echo $menu->server?>" target="selection" alt="<? echo "Alerts: (".$items->cantidad.")" ?>" title="<? echo "Alerts: (".$items->cantidad.")" ?>"><b>&raquo;</b> <? echo $items->alert_name ?> </a> </div>
    <?
	}
	*/
?>
	</div>
<?
    }
?>

</div>
<br>
<br>
