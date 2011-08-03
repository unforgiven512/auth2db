<? include "verify.php" ?>
<html>
<head>
	<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
	<title>Auth2DB</title>


<script language=javascript>

function showhide_list(id) {
	if(id == "simple") {
		document.getElementById('list_simple').style.display = "block";
		document.getElementById('list_browse').style.display = "none";
	} else if (id == "browse") {
		document.getElementById('list_simple').style.display = "none";
		document.getElementById('list_browse').style.display = "block";
	} 
}


</script>
	

  
</head>
<body>

<? include "header.php" ?>

<p class="itemsMenu001"></p>

<div class="bloque">
<p class="title">Lists Config</p>
<p class="itemsMenu001"></p>
</div>

<div class="centerbox">

<form action="list_action.php" method="POST">
<?

include "conn.php";
include "security.php";

$id = sec_cleanTAGS($_GET["id"]);
$id = sec_addESC($id);

$action = sec_cleanTAGS($_GET["action"]);
$action = sec_addESC($action);

$alert_type = "custom";

show_error();

if($action == "new")
{
?>
	Create List 
	<br><br>

	<table border="0" cellpadding="4" cellspacing="1" >
		<tr>
		    <td align="right" valign="top" >List Name: </td>
		    <td><input name="name" type="text" value="<? echo $rs->name ?>"></td>
		</tr>
		<tr>
		    <td align="right" valign="top" >List Desc:</td> 
		    <td><input name="description" type="text" value="<? echo $rs->description ?>"></td>
		</tr>
		<tr>
			<td align="right" valign="top" >Items&nbsp;:</td>
			<td>
					<label>
					<input type="radio" name="ShowHide_list" value="simple" onClick="showhide_list('simple')" checked>
					Simple</label>
					
					<label>
					<input type="radio" name="ShowHide_list" value="browse" onClick="showhide_list('browse')" >
					Browse File</label>
					
					<br><br>			
					
					<div id="list_simple" style="display:block">
					    <textarea  class="field" style="width: 200px;" name="items" rows="6" cols="120"></textarea>
					</div>
					
					<div id="list_browse" style="display:none">
					    <input class="field" name="upload" type="file">
					    <br>(not implemented yet)
					</div>
					
			</td>
			<td> </td>
		</tr>
		
	</table>

	<br><br>


<?

} else if ($action == "edit") {
	
	$sql = "SELECT * FROM list_config WHERE id = $id ";
	$result = mysql_query($sql);
	$rs = mysql_fetch_object($result);
	
	
?>
	Edit List
	<br><br>

	<table border="0" cellpadding="4" cellspacing="1" >
		<tr>
		    <td align="right" valign="top" >List Name: </td>
		    <td><input name="name" type="text" value="<? echo $rs->name ?>"></td>
		</tr>
		<tr>
		    <td align="right" valign="top" >List Desc:</td> 
		    <td><input name="description" type="text" value="<? echo $rs->description ?>"></td>
		</tr>
		<tr>
			<td align="right" valign="top" >Items&nbsp;:</td>
			<td>
					<label>
					<input type="radio" name="ShowHide_list" value="simple" onClick="showhide_list('simple')" checked>
					Simple</label>
					
					<label>
					<input type="radio" name="ShowHide_list" value="browse" onClick="showhide_list('browse')" >
					Browse File</label>
					
					<br><br>			
					
					<div id="list_simple" style="display:block">
					    <textarea  class="field" style="width: 200px;" name="items" rows="6" cols="120"><?
						$sql = "SELECT * FROM list_item WHERE id_list = $rs->id";
						$result = mysql_query($sql);
						while ($rs_simple = mysql_fetch_object($result) ) {
						    echo $rs_simple->item."\n";
						}
					    ?></textarea>
					</div>
					
					<div id="list_browse" style="display:none">
					    <input class="field" name="upload" type="file">
					    <br>(not implemented yet)
					</div>
					
			</td>
			<td> </td>
		</tr>
		
	</table>

<br><br>

<? } ?>



<input type="hidden" name="id_list" value="<? echo $id?>">
<input type="hidden" name="action" value="<? echo $action?>">
<input type="submit" value="save list">
</form>

</div>
</html>
