<? include "verify.php" ?>
<style>
body {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;

	background-color: #000000;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	
	color: #FFFFFF;

}
</style>
<?
include "conn.php";
$id = $_GET["id"];
if ($id != "") {
	$sql = "SELECT detalle from alert where id = ".$id;
	$result = mysql_query($sql);
	$rs = mysql_fetch_object($result);

	echo $rs->detalle;
}
?>
