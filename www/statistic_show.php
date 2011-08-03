<? 
include "verify.php"; 
include "security.php";
?>
<HTML>
<HEAD>
<title>Auth2DB</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
<link href="diff.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="preLoadingMessage.js"></script>

<script type="text/javascript" src="prototype.js"></script>  
   <script type="text/javascript">  
		new Ajax.PeriodicalUpdater("lista", 'realtime_list.php',{frequency:'30'});
   </script>   

</HEAD>
<body>

<? include "header.php"; ?>
<? include "menu_general.php"; ?>
<?

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

?>
<p class="itemsMenu001"></p>

<? flush();?> 

<table border=0 width="100%" height="82%">
	<tr>
		<td width=130 valign="top" valign="top">
			<div align=center class="bloqueTitle" ><b><a href="statistic_list<?=$graphtype;?>.php" target="selection">Statistic Today</a></b></div>
			<br>
			<iframe src ="menu_statistic.php" width="145" height="100%" frameborder=0></iframe>
		</td>
		<td valign="top" >
			<div class="right-frame" >
				<iframe src ="statistic_list<?=$graphtype;?>.php" name="selection" width="100%" height="100%" frameborder=0 >
			</div>
			</iframe>
		</td>
	</tr>
</table>

</HTML>
