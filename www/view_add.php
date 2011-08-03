<? include "verify.php" ?>
<html>
<head>
	<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
	<title>Auth2DB</title>

<script type="text/javascript" src="encode.js"></script>

<script>

function getRegex() {
  
  document.getElementById("regex").innerHTML = document.getElementById('p0').value + document.getElementById('p1').value + document.getElementById('p2').value + document.getElementById('p3').value;

}

function copyRegexToHex() {
  //encodeHex
  //document.getElementById("txtRegexHex").innerHTML = document.getElementById('strRegexHex').innerHTML;
  //document.getElementById("txtRegex").innerHTML = document.getElementById('strRegex').innerHTML;
  document.getElementById("txtRegexHex").innerHTML = window.frames['show_detail'].document.getElementById('strRegexHex').innerHTML;
  //document.getElementById("txtRegex").innerHTML = window.frames['show_detail'].document.getElementById('strRegex').innerHTML;
  document.getElementById("regex").innerHTML = window.frames['show_detail'].document.getElementById('strRegex').innerHTML;
  //document.getElementById("regex").innerHTML = window.frames['show_detail'].document.getElementById('strRegex').innerHTML.replace("<","\<");

}

function loadRegex() {

  //window.frames['show_detail'].alerta();
  window.frames['show_detail'].showP(window.frames['show_detail'].document.getElementById('string').value, window.frames['show_detail'].document.getElementById('p0').value, window.frames['show_detail'].document.getElementById('p1').value, window.frames['show_detail'].document.getElementById('p2').value, window.frames['show_detail'].document.getElementById('p3').value);
  setTimeout ('copyRegexToHex()', 20);

}

function submitForm() {
  document.getElementById("txtRegexHex").value = encodeHex(document.getElementById("regex").innerHTML);
  //document.forms['view'].submit();
  setTimeout ("document.forms['view'].submit()", 20);
}

</script>

<style>

#boxthing {
    position:absolute;
    width:420px;height:400px;
    padding:0.5em; 
    border:1px #FFFFFF solid;
    background-color:#000000;
    top:24px;
    left:0px; }

</style>

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
		hideLayer("boxregex");
	}
	else if (whichClick == "show it") {
		// then the user wants to show the layer
		showLayer("boxregex");
	}

}

</script>

</head>
<body>

<? include "header.php" ?>

<p class="itemsMenu001"></p>


<?

include "conn.php";
include "security.php";

$action = sec_cleanTAGS($_GET["action"]);
$action = sec_addESC($action);

$id = sec_cleanTAGS($_GET["id"]);
$id = sec_addESC($id);

$sql = "SELECT * FROM view WHERE id = '$id' ";
$result = mysql_query($sql);

$rs = mysql_fetch_object($result);

?>

<div class="bloque">
<p class="title">View Config</p>
<p class="itemsMenu001"></p>
</div>

	

<div class="centerbox">

<div class="menuconfig">
    <input type="image" src="icons/edit.png" onclick="handleClick('show it'); return false"> 
    <input type="image" src="icons/cancel.png" onclick="handleClick('hide it'); return false"><br>
</div>

<div id="boxthing" style="visibility: hidden;" >
	<iframe src ="regex.php" width="100%" height="96%" frameborder=0 id="show_detail" name="show_detail" scrolling="no" ></iframe>
	<? //include("regex.php"); ?>
	<input type="image" src="icons/cancel.png" onclick="handleClick('hide it'); return false" >
	<input type="button" value="Get Regex" onclick="loadRegex();">
</div>


<form action="view_action.php" name="view" method="POST">

<? show_error(); ?>

<script type="text/javascript" src="jscolor/jscolor.js"></script>

<table cellpadding="4" cellspacing="1" border=0 style="width: 440px;">
	<tr>
		<td align=right nowrap>View Name: </td>
		<td ><input type="text" name="view" value="<? echo $rs->view?>"></td>
	</tr>
	<tr>
		<td valign=top align=right >REGEX </td>
		<td><textarea class="field" style="width: 200px;" id="regex" name="regex" value="" rows="4" ><? echo $rs->regex ?></textarea>
		    <br><textarea class="field" style="width: 200px;display: none;" id="txtRegexHex" name="txtRegexHex" value="" rows="4" ></textarea>
		</td>
	</tr>
<!--	<tr>
		<td valign=top align=right nowrap>Get REGEX: </td>
		<td><span id="txtRegex"></span></td>
	</tr>-->
	<tr>
		<td align=right >Colour: </td>
		<td><input class="color  {pickerPosition:'right'}" type="text" name="color" value="<? echo $rs->color?>"></td>
	</tr>
	<tr>
		<td align=right ></td>
		<td><input type="radio" name="enabled" value="0" <? if($rs->enabled == 0) echo "checked" ?>>disabled <input type="radio" name="enabled" value="1" <? if($rs->enabled == 1) echo "checked" ?>>enabled</td>
	</tr>
	<tr>
		<td></td>
		<td><input type="button" onclick="submitForm();" value="Save" ></td>
	</tr>
</table>
<? //include("regex.php") ?> 
<input type="hidden" name="action" value="<? echo $action ?>" >
<input type="hidden" name="id" value="<? echo $id ?>" >
</form>
</div>