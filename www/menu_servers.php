<? include "conn.php" ?>
<link href="style.css" rel="stylesheet" type="text/css">
<?
#$sql = "select distinct secciones_items.formato as seccion from secciones_items".
#			" RIGHT JOIN usuarios_permisos ON usuarios_permisos.usuario = ".$_SESSION['user_id']."  AND usuarios_permisos.permiso = secciones_items.id AND (usuarios_permisos.permiso_god = 1 OR usuarios_permisos.permiso_read = 99) ORDER BY seccion";
$sql = "SELECT distinct server from login";
//echo $sql;
$result_seccion = mysql_query($sql); 
$array_sec = array();
$i=0;
while($rs_seccion = mysql_fetch_object($result_seccion))
{
	$array_sec[$i] = $rs_seccion->seccion;
	//echo $i . " " . $rs_seccion->seccion."<br>";
	$i++;
}
//echo array_search(1,$array_sec)."<br><br>";

?>
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
<div align=center class="bloqueTitle" ><b>Host History</b></div>
<?
    $sql = "select * from login";
	$sql = "SELECT distinct server, host_config.type as host_type from login
					LEFT JOIN host_config ON login.server = host_config.hostname";
					
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
	<div class="menu-item" onclick="blocking('sub-items-<? echo $contador ?>')"><img src="icons/<? echo $imagen_host ?>"> <? echo $menu->server ?></div>
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
	$sql = "select secciones_items.* from secciones_items".
			" RIGHT JOIN usuarios_permisos ON usuarios_permisos.usuario = ".$_SESSION['user_id']."  AND usuarios_permisos.permiso = secciones_items.id AND (usuarios_permisos.permiso_god = 1 OR usuarios_permisos.permiso_read = 1) ".
 			" WHERE seccion = ".$menu->id;
			
	$sql = "SELECT distinct tipo from login WHERE server = '".$menu->server."'";
	$result_items = mysql_query($sql);
	while($items = mysql_fetch_object($result_items))
	{
		$mas = $mas +1;
    ?>
	    <div class='menu-sub-item' onclick="blocking('sub-items2-<? echo $mas ?>')" ><a href="selected_list.php?tipo=tipo&data=<? echo $items->tipo ?>&server=<? echo $menu->server?>" target="selection"><b>&raquo;</b> <? echo $items->tipo ?> </a> </div>
    <?
	}
	
?>
	</div>
<?
    }
?>

</div>
<br>
<br>

