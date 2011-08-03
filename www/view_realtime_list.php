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
<? session_start(); ?>
<? include "verify.php"; ?>
<? include "conn.php"; ?>
<? include "security.php"; ?>

<?
$_SESSION["select_view"] = str_replace("'","",$_SESSION["select_view"]);
if ($_SESSION["select_view"] AND $_SESSION["all_select_view"] != 1)
{
	$select_view = "'".str_replace(",","','",$_SESSION["select_view"])."'";
	$array_view = explode(",",str_replace("'","",$_SESSION["select_view"]));
}else{
	$sql = "SELECT view from view";
	$result = mysql_query($sql, $link);
	$x = 0;
	while ($rs_view = mysql_fetch_object($result))
	{
			$array_view[$x] = $rs_view->view;
			$x = $x + 1;
	}
	if (is_array($array_view))
	    $select_view = "'".implode("','",$array_view)."'";
}

?>
<link href="style.css" rel="stylesheet" type="text/css">
<link href="diff.css" rel="stylesheet" type="text/css">

<style>
#boxthing {
	position:absolute;
	right: 0;
	top:145px;
	b-ottom:0;
	l-eft:58%;
	w-idth:250px;
	h-eight:150px;
	border:1px #FFFFFF solid;
	background-color:#000000;
	padding:0.5em; }

#b-oxdetail {
	position:absolute;
	right: 0;
	t-op:22px;
	bottom:0;
	l-eft:58%;
	w-idth:250px;
	h-eight:150px;
	border:1px #FFFFFF solid;
	background-color:#000000;
	padding:0.5em; 
    right: auto; 
    bottom: auto;
    }
	
.menuconfig {
	position:absolute;
	right: 0;
	top:120px;
	b-ottom:0;
	l-eft:58%;
	w-idth:250px;
	h-eight:150px;
	b-order:1px #FFFFFF solid;
	b-ackground-color:#000000;
	padding:0.5em; }
</style>

<style type="text/css">
#b--oxdetail { position: absolute; right: 0px; bottom: 0px; }
div > div#boxdetail { position: fixed; }
pre.fixit { overflow:auto;border-left:1px dashed #000;border-right:1px dashed #000;padding-left:2px; }
</style>

<script type="text/javascript" src="preLoadingMessage.js"></script>
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

function boxdetailClick(whichClick) {

	if (whichClick == "hide it") {
		// then the user wants to hide the layer
		hideLayer("boxdetail");
	}
	else if (whichClick == "show it") {
		// then the user wants to show the layer
		showLayer("boxdetail");
	}

}

</script>

<table align="left">
    <tr>
	<td valign="top">

		<table width="200" border="0" cellpadding="1" cellspacing="1">
			<tr valign="top"> 
			      <td nowrap class="filasTituloMain01" >Date</td>
			      <td nowrap class="filasTituloMain01" width="60" >Host</td>
			      <td nowrap class="filasTituloMain01" >Type</td>
			      <td nowrap class="filasTituloMain01" width="60" >User</td>
			      <td nowrap class="filasTituloMain01" width="80" >Action</td>
			</tr>

			<?
			$fecha = date("Y")."_".date("m")."_".date("d");

			$regex[0][0] = "sudo";
			$regex[0][1] = "/(?P<p1>(?<=sudo: )\w+).+(?P<p2>(?<=USER=)\w+)/i";
			$regex[0][2] = "9999CC";
			$regex[1][0] = "root by";
			$regex[1][1] = "/(?P<p1>su).+(?P<p2>user root).+(?P<p3>(?<=by)(?:\W+\w+))/i";
			$regex[1][2] = "FF4444";
			$regex[2][0] = "to root";
			$regex[2][1] = "/(?P<p1>su).+(?P<p3>(?<=from )(?:\w+)).+(?P<p2>to root)/i";
			$regex[2][2] = "FF6666";

			$sql = "SELECT * FROM view WHERE enabled = 1";
			$result_view = mysql_query($sql) or die("What are you Doing?");

			$i_array = 0;
			while ($rs_view = mysql_fetch_object($result_view) ){
			    $regex[$i_array][0] = $rs_view->view;
			    $regex[$i_array][1] = "/".$rs_view->regex."/i";
			    $regex[$i_array][2] = $rs_view->color;
			    $i_array++;
			}


			$sql = "SELECT * FROM log_". $fecha ." WHERE source = '/var/log/local2.log' ORDER BY fecha DESC LIMIT 50";
			$sql = "SELECT * FROM log_". $fecha ." ORDER BY fecha DESC LIMIT 50";
			$sql = "SELECT * FROM log_". $fecha ." ORDER BY fecha DESC, id DESC";

			# TOMA LOS DATOS DE LA TABLA MONITOR_* que ya esta procesada con las vistas creadas.
			$sql = "SELECT * FROM monitor_". $fecha ." ORDER BY fecha DESC, id DESC";
			$sql = "SELECT * FROM monitor_". $fecha ." WHERE v_view IN (".$select_view.") ORDER BY fecha DESC, id DESC";
			
			$result = mysql_query($sql) or die("What are you Doing?");

			while ($rs = mysql_fetch_object($result) ){
			  //echo $rs->detalle ."<br>";

			  // REGEX separa campos
			  //$regex = "/(?P<p1>(?<=sudo: )\w+).+(?P<p2>(?<=USER=)\w+)/i";
			  for($i=0;$i<$i_array;$i++) {

			    if(preg_match($regex[$i][1], $rs->detalle, $matches, PREG_OFFSET_CAPTURE)) {
			    // FIN REGEX separa campos

			      // Arma TABLE
			?>


			      <tr class="filas" valign="top" <? 
				    
			      
				    ////$key = array_search(strtolower($rs->action), $array_action);
				    //if (in_array(strtolower($rs->action), $array_action))
				    if ($regex[$i][2] != "") {
					    echo "bgcolor='#".$regex[$i][2]."'";
				    } else {
					    echo "bgcolor='#EEEEEE'";
				    }
				    
					    ?>>
				    <td nowrap ><?=$rs->fecha?></td>
				    <td nowrap ><?=$rs->host?></td>
				    <td nowrap ><?=$regex[$i][0]?></td>
				    <td nowrap ><?=$matches["p1"][0]?></td>
				    <td nowrap ><?=$matches["p2"][0]?></td>
				    <td nowrap class="filasBandera" ><a href="show_detail.php?id=<? echo $rs->id ?>" target="show_detail" onclick="boxdetailClick('show it'); " >
				    <img src="icons/page_white_magnify.png" width="12" height="12" border=0 ></a></td>
			      </tr>


			<?
			      ////echo $rs->fecha . " | " . $rs->host  . " | " . $regex[$i][0] . " | " . $matches["p1"][0] . " | " . $matches["p2"][0] . " | " . $matches["p3"][0] . "<br>";
			      ////echo $rs->detalle . "<br><br>";
			    }

			  }
			}
			?>

		</table>
	</td>
    </tr>
</table>

