<? include "verify.php" ?>
<style>
body {
	font-family: Helvetica, Arial, Verdana, Arial, Helvetica, sans-serif;
	font-size: 9px;

	background-color: #000000;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	
	color: #EEEEEE;

}
</style>
<?
include "conn.php";

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
                

$id = $_GET["id"];

if ($id != "") {
	$sql = "SELECT detalle from login where id = ".$id;
	$sql = "SELECT detalle from log_".$_SESSION["ano"]."_".$t_mes."_".$t_dia." where id = ".$id;
	$sql = "SELECT detalle from log_".date("Y")."_".date("m")."_".date("d")." where id = ".$id;
	
	$result = mysql_query($sql);
	$rs = mysql_fetch_object($result);

	echo $rs->detalle;
} else {
	echo "error?";
}
?>
