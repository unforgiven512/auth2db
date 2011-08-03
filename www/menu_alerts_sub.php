<?
header("Cache-Control: no-cache");
include "conn.php";

$host = $_GET["host"];

$sql = "SELECT count(id_alert_config) as cantidad, id_alert_config, alert_name from alert_".date("Y")."_".date("m")."_".date("d")."
	    WHERE host = '".$host."' GROUP BY id_alert_config, alert_name ";

$result_items = mysql_query($sql);
while($items = mysql_fetch_object($result_items))
{
    $mas = $mas +1;
?>
    <div class='menu-sub-item' onclick="blocking('sub-items2-<? echo $mas ?>')" ><a href="alert_selected_list.php?tipo=tipo&data=<? echo $items->id_alert_config ?>&host=<? echo $host ?>" target="selection" alt="<? echo "Alerts: (".$items->cantidad.")" ?>" title="<? echo "Alerts: (".$items->cantidad.")" ?>"><b>&raquo;</b> <? echo $items->alert_name ?> </a> </div>
<?
        }
?>
