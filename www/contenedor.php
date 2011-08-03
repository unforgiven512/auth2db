<? include "verify.php" ?>
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
<?include "menu_general.php" ?>

<p class="itemsMenu001"></p>

<? flush();?> 

<table border=0 width="100%" height="82%">
	<tr>
		<td width=100 valign="top" valign="top">
			<iframe src ="menu.php" width="145" height="100%" frameborder=0></iframe>
		</td>
		<td valign="top" >
			<div class="right-frame" >
				<iframe src ="frameset.htm" width="100%" height="100%" frameborder=0 >
			</div>
			</iframe>
		</td>
	</tr>
</table>

</HTML>
