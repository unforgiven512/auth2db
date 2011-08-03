<?
header("Cache-Control: no-cache");
include "conn.php";
include "security.php";

// ----------------------------------->
$conf="/etc/auth2db/auth2db.conf";

if ($file = @file( $conf )) {
    foreach ($file as $line) {
        $temp = explode("=", $line);

         switch ( trim($temp[0]) ){
            case "ACTIVE_GD": $active_gd = str_replace("\n", "", str_replace("'", "", str_replace('"', "", trim($temp[1]) ))); break;
        }
    }
}
else
    print "Configuration file " .$conf. " couldn't be read ";

if ($active_gd == "Y" OR $active_gd == "y" )
    $graphtype = "_gd";
// ----------------------------------->

$var = $_GET["var"];
/*
$sql = "SELECT count(id_alert_config) as cantidad, id_alert_config, alert_name from alert 
	    WHERE hostname = '".$var."' GROUP BY id_alert_config, alert_name ";
*/

$ano = date("Y");
$mes = date("m");
$dia = date("d");

if (table_exists($ano."_".$mes."_".$dia) == 1) {

    $sql = "SELECT count(action) as cantidad, action, tipo from log_".$ano."_".$mes."_".$dia."
		WHERE tipo = '".$var."' GROUP BY action, tipo ";

} else {
    $sql = "SELECT count(action) as cantidad, action, tipo from log_0000_00_00
		WHERE tipo = '".$var."' GROUP BY action, tipo ";
}

$result_items = mysql_query($sql);
while($items = mysql_fetch_object($result_items))
{
    $mas = $mas +1;
?>
    <div class='menu-sub-item' onclick="blocking('sub-items2-<? echo $mas ?>')" nowrap><a href="statistic_selected_list<?=$graphtype;?>.php?tipo=<? echo $items->tipo ?>&data=<? echo $items->action ?>&server=<? echo $var ?>" target="selection" alt="<? echo "Alerts: (".$items->cantidad.")" ?>" title="<? echo "Alerts: (".$items->cantidad.")" ?>"><b>&raquo;</b> <? echo $items->action ?> </a> </div>
<?
        }
?>
