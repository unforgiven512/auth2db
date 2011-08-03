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

if($_POST["sesion"] == "ok")
{
    $_SESSION["ano"] = $_POST["ano"];
    $_SESSION["dia"] = $_POST["dia"];
    $_SESSION["mes"] = $_POST["mes"];
 
   if (is_array($_POST["select_view"]))
	$_SESSION["select_view"] = implode(",",$_POST["select_view"]);

    if (is_array($_POST["select_tipo"]))
	$_SESSION["select_tipo"] = implode(",",$_POST["select_tipo"]);

    if (is_array($_POST["select_action"]))
	$_SESSION["select_action"] = implode(",",$_POST["select_action"]);


    $_SESSION["limite"] = $_POST["limite"];
    
    $_SESSION["all_select_server"] = $_POST["all_select_server"];
    $_SESSION["all_select_tipo"] = $_POST["all_select_tipo"];
    $_SESSION["all_select_action"] = $_POST["all_select_action"];

}

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
<html>
<head>
<title>Auth2DB</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
	bottom: auto; }

#boxdetail {
    position:absolute;
    width:300px;height:54px;
    padding:0.5em; 
    border:1px #FFFFFF solid;
    background-color:#000000;
    top:10px;
    left:600px; }
	
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
#b-oxdetail { position: absolute; right: 0px; bottom: 0px; }
div > div#b-oxdetail { position: fixed; }
pre.fixit { overflow:auto;border-left:1px dashed #000;border-right:1px dashed #000;padding-left:2px; }
</style>

<script type="text/javascript" src="preLoadingMessage.js"></script>

<script type="text/javascript" src="prototype.js"></script>  
<script type="text/javascript">  
		new Ajax.PeriodicalUpdater("lista", 'view_realtime_list.php',{frequency:'30'});
</script>

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

</head>
<body>

<? include "header.php"; ?>
<? include "menu_general.php"; ?>

<p class="itemsMenu001"></p>


<div id="lista"></div>

    <div id="conteniendo">

	<div class="menuconfig">
	    <input type="image" src="icons/edit.png" onclick="handleClick('show it'); return false"> 
	    <input type="image" src="icons/cancel.png" onclick="handleClick('hide it'); return false"><br>
	</div>


    </div>

</div>


<div id="boxdetail" style="visibility: hidden;" >
	<iframe src ="show_detail.php" width="100%" height="40" frameborder=0 name="show_detail" scrolling="no" ></iframe>
	<input type="image" src="icons/cancel.png" onclick="boxdetailClick('hide it'); return false" >
</div>

<div id="boxthing" style="visibility: hidden;" >
	<form action='#' method=post>
		<input type=hidden name=sesion value=ok>

		<b>Views Config</b>
		<br><br>
		<select name="select_view[]" class="field" MULTIPLE size=" <? echo $select_max ?> " style="height: 90px; width: 100px;">
		<?
		$sql = "SELECT * FROM view WHERE enabled = 1";
		$result = mysql_query($sql) or die("What are you Doing?");

		while ($rs = mysql_fetch_object($result) ){

		  //$array_server[0] = "sudo";
		  //$array_server[1] = "to root";
		  $valor = array_search($rs->view, $array_view);

		?>
		  <option <?if(($i = array_search($rs->view, $array_view)) !== FALSE) echo "selected" ?> value="<? echo $rs->view?>"><? echo $rs->view  ?></option>
		<?
		  echo $rs->view ."<br>";
		}
		?>
		</select>

		<br><br>

		Limit<br>
		<input class="field" style="width: 60px;" name="limite" value=<? echo $SHOW_LIMIT; ?>>
		
		<input type="image" src="icons/filesave.png">
		<br><input type="image" src="icons/cancel.png" onclick="handleClick('hide it'); return false">
		
	</form>
</div>

<script type="text/javascript">
<!--
/* Script by: www.jtricks.com
 * Version: 20071017
 * Latest version:
 * www.jtricks.com/javascript/navigation/floating.html
 */
var floatingMenuId = 'boxdetail';
var floatingMenu =
{
    targetX: -320,
    targetY: -80,

    hasInner: typeof(window.innerWidth) == 'number',
    hasElement: typeof(document.documentElement) == 'object'
        && typeof(document.documentElement.clientWidth) == 'number',

    menu:
        document.getElementById
        ? document.getElementById(floatingMenuId)
        : document.all
          ? document.all[floatingMenuId]
          : document.layers[floatingMenuId]
};

floatingMenu.move = function ()
{
    floatingMenu.menu.style.left = floatingMenu.nextX + 'px';
    floatingMenu.menu.style.top = floatingMenu.nextY + 'px';
}

floatingMenu.computeShifts = function ()
{
    var de = document.documentElement;

    floatingMenu.shiftX =  
        floatingMenu.hasInner  
        ? pageXOffset  
        : floatingMenu.hasElement  
          ? de.scrollLeft  
          : document.body.scrollLeft;  
    if (floatingMenu.targetX < 0)
    {
        floatingMenu.shiftX +=
            floatingMenu.hasElement
            ? de.clientWidth
            : document.body.clientWidth;
    }

    floatingMenu.shiftY = 
        floatingMenu.hasInner
        ? pageYOffset
        : floatingMenu.hasElement
          ? de.scrollTop
          : document.body.scrollTop;
    if (floatingMenu.targetY < 0)
    {
        if (floatingMenu.hasElement && floatingMenu.hasInner)
        {
            // Handle Opera 8 problems
            floatingMenu.shiftY +=
                de.clientHeight > window.innerHeight
                ? window.innerHeight
                : de.clientHeight
        }
        else
        {
            floatingMenu.shiftY +=
                floatingMenu.hasElement
                ? de.clientHeight
                : document.body.clientHeight;
        }
    }
}

floatingMenu.calculateCornerX = function()
{
    if (floatingMenu.targetX != 'center')
        return floatingMenu.shiftX + floatingMenu.targetX;

    var width = parseInt(floatingMenu.menu.offsetWidth);

    var cornerX =
        floatingMenu.hasElement
        ? (floatingMenu.hasInner
           ? pageXOffset
           : document.documentElement.scrollLeft) + 
          (document.documentElement.clientWidth - width)/2
        : document.body.scrollLeft + 
          (document.body.clientWidth - width)/2;
    return cornerX;
};

floatingMenu.calculateCornerY = function()
{
    if (floatingMenu.targetY != 'center')
        return floatingMenu.shiftY + floatingMenu.targetY;

    var height = parseInt(floatingMenu.menu.offsetHeight);

    // Handle Opera 8 problems
    var clientHeight = 
        floatingMenu.hasElement && floatingMenu.hasInner
        && document.documentElement.clientHeight 
            > window.innerHeight
        ? window.innerHeight
        : document.documentElement.clientHeight

    var cornerY =
        floatingMenu.hasElement
        ? (floatingMenu.hasInner  
           ? pageYOffset
           : document.documentElement.scrollTop) + 
          (clientHeight - height)/2
        : document.body.scrollTop + 
          (document.body.clientHeight - height)/2;
    return cornerY;
};

floatingMenu.doFloat = function()
{
    // Check if reference to menu was lost due
    // to ajax manipuations
    if (!floatingMenu.menu)
    {
        menu = document.getElementById
            ? document.getElementById(floatingMenuId)
            : document.all
              ? document.all[floatingMenuId]
              : document.layers[floatingMenuId];

        initSecondary();
    }

    var stepX, stepY;

    floatingMenu.computeShifts();

    var cornerX = floatingMenu.calculateCornerX();

    var stepX = (cornerX - floatingMenu.nextX) * .07;
    if (Math.abs(stepX) < .5)
    {
        stepX = cornerX - floatingMenu.nextX;
    }

    var cornerY = floatingMenu.calculateCornerY();

    var stepY = (cornerY - floatingMenu.nextY) * .07;
    if (Math.abs(stepY) < .5)
    {
        stepY = cornerY - floatingMenu.nextY;
    }

    if (Math.abs(stepX) > 0 ||
        Math.abs(stepY) > 0)
    {
        floatingMenu.nextX += stepX;
        floatingMenu.nextY += stepY;
        floatingMenu.move();
    }

    setTimeout('floatingMenu.doFloat()', 20);
};

// addEvent designed by Aaron Moore
floatingMenu.addEvent = function(element, listener, handler)
{
    if(typeof element[listener] != 'function' || 
       typeof element[listener + '_num'] == 'undefined')
    {
        element[listener + '_num'] = 0;
        if (typeof element[listener] == 'function')
        {
            element[listener + 0] = element[listener];
            element[listener + '_num']++;
        }
        element[listener] = function(e)
        {
            var r = true;
            e = (e) ? e : window.event;
            for(var i = element[listener + '_num'] -1; i >= 0; i--)
            {
                if(element[listener + i](e) == false)
                    r = false;
            }
            return r;
        }
    }

    //if handler is not already stored, assign it
    for(var i = 0; i < element[listener + '_num']; i++)
        if(element[listener + i] == handler)
            return;
    element[listener + element[listener + '_num']] = handler;
    element[listener + '_num']++;
};

floatingMenu.init = function()
{
    floatingMenu.initSecondary();
    floatingMenu.doFloat();
};

// Some browsers init scrollbars only after
// full document load.
floatingMenu.initSecondary = function()
{
    floatingMenu.computeShifts();
    floatingMenu.nextX = floatingMenu.calculateCornerX();
    floatingMenu.nextY = floatingMenu.calculateCornerY();
    floatingMenu.move();
}

if (document.layers)
    floatingMenu.addEvent(window, 'onload', floatingMenu.init);
else
{
    floatingMenu.init();
    floatingMenu.addEvent(window, 'onload',
        floatingMenu.initSecondary);
}

//-->
</script>

</body>
</html>