<? include "verify.php" ?>

<html>
<head>
	<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
	<title>Auth2DB</title>


<script language=javascript>

function showhide_server(id) {
	if(id == "off") {
		document.getElementById('server_off').style.display = "block";
		document.getElementById('server_select').style.display = "none";
	} else if (id == "select") {
		document.getElementById('server_off').style.display = "none";
		document.getElementById('server_select').style.display = "block";
	} 
}

function showhide_contains(id) {
	if(id == "off") {
		document.getElementById('contains_off').style.display = "block";
		document.getElementById('contains_field').style.display = "none";
		document.getElementById('contains_single').style.display = "none";
		document.getElementById('contains_list').style.display = "none";
	} else if (id == "single") {
		document.getElementById('contains_off').style.display = "none";
		document.getElementById('contains_field').style.display = "block";
		document.getElementById('contains_single').style.display = "block";
		document.getElementById('contains_list').style.display = "none";
	} else if (id == "list") {
		document.getElementById('contains_off').style.display = "none";
		document.getElementById('contains_field').style.display = "block";
		document.getElementById('contains_single').style.display = "none";
		document.getElementById('contains_list').style.display = "block";
	}
}

function showhide_exclude(id) {
	if(id == "off") {
		document.getElementById('exclude_off').style.display = "block";
		document.getElementById('exclude_field').style.display = "none";
		document.getElementById('exclude_single').style.display = "none";
		document.getElementById('exclude_list').style.display = "none";
	} else if (id == "single") {
		document.getElementById('exclude_off').style.display = "none";
		document.getElementById('exclude_field').style.display = "block";
		document.getElementById('exclude_single').style.display = "block";
		document.getElementById('exclude_list').style.display = "none";
	} else if (id == "list") {
		document.getElementById('exclude_off').style.display = "none";
		document.getElementById('exclude_field').style.display = "block";
		document.getElementById('exclude_single').style.display = "none";
		document.getElementById('exclude_list').style.display = "block";
	}
}

function showhide_exclude__(id) {
	if(id == "off") {
		document.getElementById('exclude_off').style.display = "block";
		document.getElementById('exclude_single').style.display = "none";
		document.getElementById('exclude_list').style.display = "none";
	} else if (id == "single") {
		document.getElementById('exclude_off').style.display = "none";
		document.getElementById('exclude_single').style.display = "block";
		document.getElementById('exclude_list').style.display = "none";
	} else if (id == "list") {
		document.getElementById('exclude_off').style.display = "none";
		document.getElementById('exclude_single').style.display = "none";
		document.getElementById('exclude_list').style.display = "block";
	}
}

</script>
	

  
</head>
<body>

<? include "header.php" ?>

<p class="itemsMenu001"></p>

<div class="bloque">
<p class="title">Alerts Config</p>
<p class="itemsMenu001"></p>
</div>

<div class="centerbox">

<form action="alert_action.php" method="POST">
<?

include "conn.php";
include "security.php";

$id = sec_cleanTAGS($_GET["id"]);
$id = sec_addESC($id);

$action = sec_cleanTAGS($_GET["action"]);
$action = sec_addESC($action);

$style_color[0]="{background-color:maroon; color:white}";
$style_color[1]="{background-color: #FE9A2E; color:white}";
$style_color[2]="{background-color: #F5D0A9; color:black}";
$style_color[3]="{background-color: #FE9A2E; color:white}";
$style_color[4]="{background-color: #F5D0A9; color:black}";
$style_color[5]="{background-color: #FE9A2E; color:white}";
$style_color[6]="{background-color: #F5D0A9; color:black}";
$style_color[7]="{background-color: #FE9A2E; color:white}";
$style_color[8]="{background-color: #F5D0A9; color:black}";
$style_color[9]="{background-color: #FE9A2E; color:white}";
$style_color[10]="{background-color: #F5D0A9; color:black}";
$style_color[11]="{background-color: #FE9A2E; color:white}";
$style_color[12]="{background-color: #F5D0A9; color:black}";


$alert_type = "custom";

show_error();

$arrayFields = array('usuario', 'ip', 'detalle' );
//$arrayFields = array('usuario', 'ip' );

function checked($var1,$var2) {
    
    if ($var1 == $var2)
	return " checked";
}

function div_show($var1,$var2) {

    if ($var1 == 3 AND $var2 > 0) {
	return "block";
    }
    
    if ($var1 == $var2) {
	return "block";
    } else {
	return "none";
    }

}

if($action == "new")
{
?>
	Create Custom Alert 
	<br><br>
	
	Alert Name: <input name="name" type="text" value="<? echo $rs->name ?>">
	<br><br>

	<table border="0" cellpadding="4" cellspacing="1" >
		<tr>
			<td align="right" valign="top" >Server&nbsp;:</td>
			<td>
					
					<label>
					
					<input type="radio" name="server" value="0" onClick="showhide_server('off')" <? echo checked(0,$rs->server); ?> >
					all</label>
					
					<label>
					<input type="radio" name="server" value="1" onClick="showhide_server('select')" <? echo checked(1,$rs->server); ?> >
					select</label>
					
					<br><br>					
					
					<div id="server_off" style="display:block">
						all
					</div>
					
					<div id="server_select" style="display:none">
					<select class="field" style="width: 200px;" name="server_select" size="1" >
						<? 
							//$arraySeverity = array('Critical', 'Emergency', 'Error', 'Warning', 'Alert', 'Notice', 'Information', 'Debug', 'Success', 'Failure' );
							//for($i=0;$i<=sizeof($arraySeverity) - 1;$i++)
							//{
							$sql = "SELECT hostname FROM host_config ORDER BY hostname";
							$result = mysql_query($sql);
							while( $rs_server = mysql_fetch_object($result) ) { 
							
						?>
							<option value="<? echo $rs_server->hostname  ?>" <? if ($rs->severity == $rs_server->hostname  ) echo "selected" ?> ><? echo $rs_server->hostname  ?></option>
						<? } ?>
					</select>
					</div>
					
			</td>
			<td> </td>
		</tr>
<? /* ?>
		<tr>
			<td align="right" >LogType&nbsp;:</td>
			<td >
				<select class="field" style="width: 200px;" name="log_type" size="1" >
						<? 
							$sql = "SELECT distinct(tipo) as tipo from login";
							$sql = "SELECT distinct(tipo_name) as tipo from tipo_action_config";
							$sql = "SELECT distinct tipo_name as tipo from tipo_action_config ORDER BY tipo_name, action_name";
							
							$result = mysql_query($sql);
							
							$flag_color = "-";
							$contador = 0;
							
							 while ($rs = mysql_fetch_object($result))
							{
							     if ($rs->tipo != $flag_color OR $flag_action == "-") {
							             $action_color = $style_color[0];
							             $flag_color = $rs->tipo;
							             $contador = $contador + 1;
							    }
							
							    //if (is_array($array_tipo))
							    //         $valor = array_search($rs->tipo, $array_tipo);
							?>
							     <option style=" <?=$style_color[$contador]; ?> " value="<? echo $rs->tipo?>"><? echo $contador.". ".$rs->tipo ?></option>
							<?
							}
							
							
							 ?>
				</select>
			</td>
			<td> </td>
		</tr>
		<tr>
			<td align="right" >LogAction&nbsp;:</td>
			<td >
				<select class="field" style="width: 200px;" name="log_action" size="1" >
						<? 
							$sql = "SELECT distinct(action) as action from login";
							$sql = "SELECT distinct(action_name) as action from tipo_action_config";
							$sql = "SELECT distinct tipo_name as tipo, action_name as action from tipo_action_config ORDER BY tipo_name, action_name";
							
							$result = mysql_query($sql);
							
							$flag_color = "-";
							$contador = 0;
							
							while ($rs = mysql_fetch_object($result)) {
							    if ($rs->tipo != $flag_color OR $flag_action == "-") {
								$action_color = $style_color[0];
								$flag_color = $rs->tipo;
								$contador = $contador + 1;
							    }
							?>
							 <option style=' <?=$style_color[$contador];?> ' value="<?=$rs->action?>"><?echo $contador.". ".$rs->action ?></option>
							<?
							
							}
							
							 ?>
				</select>
			</td>
			<td> </td>
		</tr>
		<tr>
			<td align="right" >LogGroup&nbsp;:</td>
			<td>
					<select class="field" style="width: 200px;" name="log_group" size="1" >
						<? 
							$arrayGroup = array('ip', 'usuario');
							for($i=0;$i<=sizeof($arrayGroup) - 1;$i++)
							{
						?>
							<option value="<? echo $arrayGroup[$i]  ?>" <? if ($rs->log_group == $arrayGroup[$i] ) echo "selected" ?> ><? echo $arrayGroup[$i] ?></option>
						<? } ?>
					</select>
			</td>
			<td> </td>
		</tr>
<? */ ?>
		<tr>
			<td align="right" valign="top" >Log message contains&nbsp;:</td>
			<td>
					<label>
					<input type="radio" name="log_contains" value="0" onClick="showhide_contains('off')" checked>
					off</label>
					
					<label>
					<input type="radio" name="log_contains" value="1" onClick="showhide_contains('single')">
					single</label>
					
					<label>
					<input type="radio" name="log_contains" value="2" onClick="showhide_contains('list')">
					list</label>
					
					<br><br>		
					
					<div id="contains_off" style="display:block">
						off
					</div>
					
					<div id="contains_field" style="display:none">
						<select class="field" style="width: 140px;" name="log_contains_field" size="1" >
						<?
						//$arrayFields = array('usuario', 'ip', 'detalle' );
						for($i=0;$i<=sizeof($arrayFields) - 1;$i++)
						{
						?>
						    <option value="<? echo $arrayFields[$i]  ?>" <? if ($rs->log_contains == $arrayFields[$i] ) echo "selected" ?> ><? echo $arrayFields[$i] ?></option>
						<?
						}
						?>
					    </select>
					</div>
					
					<div id="contains_single" style="display:none">
					    <? /*
						<select class="field" style="width: 140px;" name="log_contains_field" size="1" >
						<?
						//$arrayFields = array('usuario', 'ip', 'detalle' );
						for($i=0;$i<=sizeof($arrayFields) - 1;$i++)
						{
						?>
						    <option value="<? echo $arrayFields[$i]  ?>" <? if ($rs->log_contains == $arrayFields[$i] ) echo "selected" ?> ><? echo $arrayFields[$i] ?></option>
						<?
						}
						?>
						</select> 
					    */ ?>
					    <br>
					    REGEX
					    <br><br>
						<textarea class="field" style="width: 200px;" name="log_contains_regex" value="<? echo $rs->log_contains ?>" rows="4" ></textarea>
					</div>
					
					<div id="contains_list" style="display:none">
					    <? /*
					    <select class="field" style="width: 140px;" name="log_contains_field" size="1" >
						<?
						//$arrayFields = array('usuario', 'ip', 'detalle' );
						for($i=0;$i<=sizeof($arrayFields) - 1;$i++)
						{
						?>
						    <option value="<? echo $arrayFields[$i]  ?>" <? if ($rs->log_group == $arrayFields[$i] ) echo "selected" ?> ><? echo $arrayFields[$i] ?></option>
						<?
						}
						?>
					    </select> 
					    */ ?>
					    <br>
					    IN
					    <br><br>
					    <select class="field" name="log_contains_list[]" MULTIPLE style="width: 200px;" SIZE=4>
						<?
						    $sql = "SELECT * FROM list_config";
						    $result = mysql_query($sql) or die("Couldn't execute query");
						    while ($rs_list = mysql_fetch_object($result)) {
						?>
							<option value="<?=$rs_list->id?>"><?=$rs_list->name?></option>
						<?
						    }
						?>
					    </select>
					</div>
					
			<br></td>
			<td></td>
		</tr>
		<tr>
			<td align="right" valign="top" >Exclude&nbsp;:&nbsp;</td>
			<td>
					<label>
					<input type="radio" name="log_exclude" value="0" onClick="showhide_exclude('off')" checked>
					off</label>
					
					<label>
					<input type="radio" name="log_exclude" value="1" onClick="showhide_exclude('single')">
					single</label>
					
					<label>
					<input type="radio" name="log_exclude" value="2" onClick="showhide_exclude('list')">
					list</label>
					
					<br><br>
					
					<div id="exclude_off" style="display:block">
						off
					</div>
					
					<div id="exclude_field" style="display:none">
						<select class="field" style="width: 140px;" name="log_exclude_field" size="1" >
						<?
						//$arrayFields = array('usuario', 'ip', 'detalle' );
						for($i=0;$i<=sizeof($arrayFields) - 1;$i++)
						{
						?>
						    <option value="<? echo $arrayFields[$i]  ?>" <? if ($rs->log_group == $arrayFields[$i] ) echo "selected" ?> ><? echo $arrayFields[$i] ?></option>
						<?
						}
						?>
					    </select>
					</div>
					
					<div id="exclude_single" style="display:none">
					    <? /*
						<select class="field" style="width: 140px;" name="log_exclude_field" size="1" >
						<?
						//$arrayFields = array('usuario', 'ip', 'detalle' );
						for($i=0;$i<=sizeof($arrayFields) - 1;$i++)
						{
						?>
						    <option value="<? echo $arrayFields[$i]  ?>" <? if ($rs->log_group == $arrayFields[$i] ) echo "selected" ?> ><? echo $arrayFields[$i] ?></option>
						<?
						}
						?>
						</select>
					    */?>
						<br>
						REGEX 
					    <br><br>
						<textarea class="field" style="width: 200px;" name="log_exclude_regex" value="<? echo $rs->log_exclude ?>" rows="4" ></textarea>
					</div>
					
					
					<div id="exclude_list" style="display:none">
					    <? /*
						<select class="field" style="width: 140px;" name="log_exclude_field" size="1" >
						<?
						//$arrayFields = array('usuario', 'ip', 'detalle' );
						for($i=0;$i<=sizeof($arrayFields) - 1;$i++)
						{
						?>
						    <option value="<? echo $arrayFields[$i]  ?>" <? if ($rs->log_group == $arrayFields[$i] ) echo "selected" ?> ><? echo $arrayFields[$i] ?></option>
						<?
						}
						?>
					    </select> 
					    */ ?>
					    <br>
					    IN
					    <br><br>
						<select class="field" name="log_exclude_list[]" MULTIPLE style="width: 200px;" SIZE=4>
						<?
						    $sql = "SELECT * FROM list_config";
						    $result = mysql_query($sql) or die("Couldn't execute query");
						    while ($rs_list = mysql_fetch_object($result)) {
						?>
							<option value="<?=$rs_list->id?>"><?=$rs_list->name?></option>
						<?
						    }
						?>
						</select>
					</div>
					
			
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
		</tr><tr>
			<td align="right" >Number of occurrences&nbsp;:&nbsp;</td>
			<td>
				<input type="text" name="occurrences_number" size="5" value="<? echo $rs->occurrences_number ?>"  > &nbsp; time(s)</td>
			</td>
			<td></td>
		</tr>
		<tr>
			<td align="right" >Occurring within &nbsp;:</td>
			<td>
				<input type="text" name="occurrences_within" size="5" maxlength="6" value="<? echo $rs->occurrences_within ?>"  >&nbsp;minute(s)&nbsp;
			</td>
			<td></td>
		</tr>
	</table>
	<br><br>
	

	<table border="0" cellpadding="1" cellspacing="0" >
	<tr>
		<td >Define Actions : </td>
	</tr>
	<tr>
		<td height="7"></td>
	</tr>
	<tr>
		<td align="left" nowrap="nowrap">
			Criticality &nbsp;:&nbsp;
			<select class="field" name="criticality" size="1" >
				<? 
					$arrayCriticality = array('High', 'Medium', 'Low' );
					for($i=0;$i<=sizeof($arrayCriticality) - 1;$i++)
					{
				?>
						<option value="<? echo  $arrayCriticality[$i] ?>" <? if ($rs->criticality == $arrayCriticality[$i] ) echo "selected" ?> ><? echo $arrayCriticality[$i] ?></option>
				<? } ?>
			</select>
			<span class="helpTxt">&nbsp;Choose the criticality of the alert</span>
		</td>
	</tr>
</table>
	
<br><br>

<input class="field" name="active_email" value="1" align="left" type="checkbox" <? if($rs->active_email == 1) echo "checked" ?>>Notify by E-mail
	<br>
	<br>
	&nbsp;E-mail Address :  <input name="email" value="<? echo $rs->email?>" size="45" type="text">
	<br>
	<br>


<?

} else if ($action == "edit") {
	
	$sql = "SELECT * FROM alert_config WHERE id = $id ";
	$result = mysql_query($sql);
	$rs = mysql_fetch_object($result);
	
	
?>
	Edit Custom Alert 
	<br><br>
	Alert Name: <input name="name" type="text" value="<? echo $rs->name ?>">
	<br><br>

	<table border="0" cellpadding="4" cellspacing="1" >
		<tr>
			<td align="right" valign="top" >Server&nbsp;:</td>
			<td>
					<label>
					<input type="radio" name="server" value="0" onClick="showhide_server('off')" <? echo checked(0,$rs->server); ?> >
					all</label>
					
					<label>
					<input type="radio" name="server" value="1" onClick="showhide_server('select')" <? echo checked(1,$rs->server); ?> >
					select</label>
					
					<br><br>					
					
					<div id="server_off" style="display:<?=div_show(0,$rs->server);?>">
						all
					</div>
					
					<div id="server_select" style="display:<?=div_show(1,$rs->server);?>">
					<select class="field" style="width: 200px;" name="server_select" size="1" >
						<? 
							//$arraySeverity = array('Critical', 'Emergency', 'Error', 'Warning', 'Alert', 'Notice', 'Information', 'Debug', 'Success', 'Failure' );
							//for($i=0;$i<=sizeof($arraySeverity) - 1;$i++)
							//{
							$sql = "SELECT hostname FROM host_config ORDER BY hostname";
							$result = mysql_query($sql);
							while( $rs_server = mysql_fetch_object($result) ) { 
							
						?>
							<option value="<? echo $rs_server->hostname  ?>" <? if ($rs->server_select == $rs_server->hostname  ) echo "selected" ?> ><? echo $rs_server->hostname  ?></option>
						<? } ?>
					</select>
					</div>
					
			</td>
			<td> </td>
		</tr>
<? /* ?>
		<tr>
			<td align="right" >LogType&nbsp;:</td>
			<td >
				<select class="field" style="width: 200px;" name="log_type" size="1" >
						<? 
							$sql = "SELECT distinct(tipo) as tipo from login";
							$sql = "SELECT distinct(tipo_name) as tipo from tipo_action_config";
							$sql = "SELECT distinct(tipo_name) as tipo from tipo_action_config ORDER BY tipo_name, action_name";
							
							$result = mysql_query($sql);
							
							$flag_color = "-";
							$contador = 0;
							
							while ($rs_tipo = mysql_fetch_object($result))
							{
							    if ($rs_tipo->tipo != $flag_color OR $flag_action == "-") {
								$action_color = $style_color[0];
								$flag_color = $rs_tipo->tipo;
							        $contador = $contador + 1;
							    }
							?>
							<option style=" <?=$style_color[$contador]; ?> " value="<? echo $rs_tipo->tipo?>" <? if ($rs->log_type == $rs_tipo->tipo ) echo "selected" ?>><? echo $contador.". ".$rs_tipo->tipo ?></option>
							<?
							
							}
							
							 ?>
				</select>
			</td>
			<td> </td>
		</tr>
		<tr>
			<td align="right" >LogAction&nbsp;:</td>
			<td >
				<select class="field" style="width: 200px;" name="log_action" size="1" >
						<? 
							$sql = "SELECT distinct(action) as action from login";
							$sql = "SELECT distinct(action_name) as action from tipo_action_config";
							$sql = "SELECT distinct(tipo_name) as tipo, action_name as action from tipo_action_config ORDER BY tipo_name, action_name";
							
							$result = mysql_query($sql);
							
							$flag_color = "-";
							$contador = 0;
							
							while ($rs_action = mysql_fetch_object($result)) {
							    if ($rs_action->tipo != $flag_color OR $flag_action == "-") {
								$action_color = $style_color[0];
								$flag_color = $rs_action->tipo;
								$contador = $contador + 1;
							    }
							?>
							<option style=' <?=$style_color[$contador];?> ' value="<?=$rs_action->action?>" <? if ($rs->log_action == $rs_action->action ) echo "selected" ?> ><?echo $contador.". ".$rs_action->action ?></option>
							<?
							}
							 ?>
				</select>
			</td>
			<td> </td>
		</tr>
		<tr>
			<td align="right" >LogGroup&nbsp;:</td>
			<td>
					<select class="field" style="width: 200px;" name="log_group" size="1" >
						<? 
							$arrayGroup = array('usuario', 'ip');
							for($i=0;$i<=sizeof($arrayGroup) - 1;$i++)
							{
						?>
							<option value="<? echo $arrayGroup[$i]  ?>" <? if ($rs->log_group == $arrayGroup[$i] ) echo "selected" ?> ><? echo $arrayGroup[$i] ?></option>
						<? } ?>
					</select>
			</td>
			<td> </td>
		</tr>
<? */ ?>
		<tr>
			<td align="right" valign="top" >Log message contains&nbsp;:</td>
			<td>
					<label>
					<input type="radio" name="log_contains" value="0" onClick="showhide_contains('off')" <? echo checked(0,$rs->log_contains); ?> >
					off</label>
					
					<label>
					<input type="radio" name="log_contains" value="1" onClick="showhide_contains('single')" <? echo checked(1,$rs->log_contains); ?> >
					single</label>
					
					<label>
					<input type="radio" name="log_contains" value="2" onClick="showhide_contains('list')" <? echo checked(2,$rs->log_contains); ?> >
					list</label>
					
					<br><br>		
					
					<div id="contains_off" style="display:<?=div_show(0,$rs->log_contains);?>">
						off
					</div>
					
					<div id="contains_field" style="display:<?=div_show(3,$rs->log_contains);?>">
						<select class="field" style="width: 140px;" name="log_contains_field" size="1" >
						<?
						//$arrayFields = array('usuario', 'ip', 'detalle' );
						for($i=0;$i<=sizeof($arrayFields) - 1;$i++)
						{
						?>
						    <option value="<? echo $arrayFields[$i]  ?>" <? if ($rs->log_contains_field == $arrayFields[$i] ) echo "selected" ?> ><? echo $arrayFields[$i] ?></option>
						<?
						}
						?>
					    </select>
					</div>
					
					<div id="contains_single" style="display:<?=div_show(1,$rs->log_contains);?>">
					    <? /*
						<select class="field" style="width: 140px;" name="log_contains_field" size="1" >
						<?
						//$arrayFields = array('usuario', 'ip', 'detalle' );
						for($i=0;$i<=sizeof($arrayFields) - 1;$i++)
						{
						?>
						    <option value="<? echo $arrayFields[$i]  ?>" <? if ($rs->log_contains_field == $arrayFields[$i] ) echo "selected" ?> ><? echo $arrayFields[$i] ?></option>
						<?
						}
						?>
						</select> 
					    */ ?>
					    <br>
					    REGEX
					    <br><br>
						<textarea class="field" style="width: 200px;" name="log_contains_regex" value="<? echo $rs->log_contains_regex ?>" rows="4" ><? echo $rs->log_contains_regex ?></textarea>
					</div>
					
					<div id="contains_list" style="display:<?=div_show(2,$rs->log_contains);?>">
					    <? /*
						<select class="field" style="width: 140px;" name="log_contains_field" size="1" >
						<?
						//$arrayFields = array('usuario', 'ip', 'detalle' );
						for($i=0;$i<=sizeof($arrayFields) - 1;$i++)
						{
						?>
						    <option value="<? echo $arrayFields[$i]  ?>" <? if ($rs->log_contains_field == $arrayFields[$i] ) echo "selected" ?> ><? echo $arrayFields[$i] ?></option>
						<?
						}
						?>
						</select> 
					    */ ?>
					    <br>
					    IN
					    <br><br>
					    <select class="field" name="log_contains_list[]" MULTIPLE style="width: 200px;" SIZE=4>
						<?
						    $sql = "SELECT * FROM list_config";
						    $result = mysql_query($sql) or die("Couldn't execute query");
						    while ($rs_list = mysql_fetch_object($result)) {
						?>
							<option value="<?=$rs_list->id?>"><?=$rs_list->name?></option>
						<?
						    }
						?>
					    </select>
					</div>
					
			<br></td>
			<td></td>
		</tr>
		
		<tr>
			<td align="right" valign="top" >Exclude&nbsp;:&nbsp;</td>
			<td>
					<label>
					<input type="radio" name="log_exclude" value="0" onClick="showhide_exclude('off')" <? echo checked(0,$rs->log_exclude); ?> >
					off</label>
					
					<label>
					<input type="radio" name="log_exclude" value="1" onClick="showhide_exclude('single')" <? echo checked(1,$rs->log_exclude); ?> >
					single</label>
					
					<label>
					<input type="radio" name="log_exclude" value="2" onClick="showhide_exclude('list')" <? echo checked(2,$rs->log_exclude); ?> >
					list</label>
					
					<br><br>
					
					<div id="exclude_off" style="display:<?=div_show(0,$rs->log_exclude);?>">
						off
					</div>
					
					<div id="exclude_field" style="display:<?=div_show(3,$rs->log_exclude);?>">
						<select class="field" style="width: 140px;" name="log_exclude_field" size="1" >
						<?
						//$arrayFields = array('usuario', 'ip', 'detalle' );
						for($i=0;$i<=sizeof($arrayFields) - 1;$i++)
						{
						?>
						    <option value="<? echo $arrayFields[$i]  ?>" <? if ($rs->log_exclude_field == $arrayFields[$i] ) echo "selected" ?> ><? echo $arrayFields[$i] ?></option>
						<?
						}
						?>
					    </select>
					</div>
					
					<div id="exclude_single" style="display:<?=div_show(1,$rs->log_exclude);?>">
					    <? /*
						<select class="field" style="width: 140px;" name="log_exclude_field" size="1" >
						<?
						//$arrayFields = array('usuario', 'ip', 'detalle' );
						for($i=0;$i<=sizeof($arrayFields) - 1;$i++)
						{
						?>
						    <option value="<? echo $arrayFields[$i]  ?>" <? if ($rs->log_exclude_field == $arrayFields[$i] ) echo "selected" ?> ><? echo $arrayFields[$i] ?></option>
						<?
						}
						?>
						</select> 
					    */ ?>
					    <br>
					    REGEX
					    <br><br>
						<textarea class="field" style="width: 200px;" name="log_exclude_regex" value="<? echo $rs->log_exclude_regex ?>" rows="4" ><? echo $rs->log_exclude_regex ?></textarea>
					</div>
					
					
					<div id="exclude_list" style="display:<?=div_show(2,$rs->log_exclude);?>">
					    <? /*
						<select class="field" style="width: 140px;" name="log_exclude_field" size="1" >
						<?
						//$arrayFields = array('usuario', 'ip', 'detalle' );
						for($i=0;$i<=sizeof($arrayFields) - 1;$i++)
						{
						?>
						    <option value="<? echo $arrayFields[$i]  ?>" <? if ($rs->log_exclude_field == $arrayFields[$i] ) echo "selected" ?> ><? echo $arrayFields[$i] ?></option>
						<?
						}
						?>
						</select> 
					    */ ?>
					    <br>
					    IN
					    <br><br>
						<select class="field" name="log_exclude_list[]" MULTIPLE style="width: 200px;" SIZE=4>
						<?
						    $sql = "SELECT * FROM list_config";
						    $result = mysql_query($sql) or die("Couldn't execute query");
						    while ($rs_list = mysql_fetch_object($result)) {
						?>
							<option value="<?=$rs_list->id?>"><?=$rs_list->name?></option>
						<?
						    }
						?>
						</select>
					</div>
					
			
			<td></td>
		</tr>
		
		<tr>
			<td></td>
			<td></td>
			<td></td>
		</tr><tr>
			<td align="right" >Number of occurrences&nbsp;:&nbsp;</td>
			<td>
				<input type="text" name="occurrences_number" size="5" value="<? echo $rs->occurrences_number ?>"  > &nbsp; time(s)</td>
			</td>
			<td></td>
		</tr>
		<tr>
			<td align="right" >Occurring within &nbsp;:</td>
			<td>
				<input type="text" name="occurrences_within" size="5" maxlength="6" value="<? echo $rs->occurrences_within ?>"  >&nbsp;minute(s)&nbsp;
			</td>
			<td></td>
		</tr>
	</table>
	<br><br>
	

	<table border="0" cellpadding="1" cellspacing="0" >
	<tr>
		<td >Define Actions : </td>
	</tr>
	<tr>
		<td height="7"></td>
	</tr>
	<tr>
		<td align="left" nowrap="nowrap">
			Criticality &nbsp;:&nbsp;
			<select class="field" name="criticality" size="1" >
				<? 
					$arrayCriticality = array('High', 'Medium', 'Low' );
					for($i=0;$i<=sizeof($arrayCriticality) - 1;$i++)
					{
				?>
						<option value="<? echo  $arrayCriticality[$i] ?>" <? if ($rs->criticality == $arrayCriticality[$i] ) echo "selected" ?> ><? echo $arrayCriticality[$i] ?></option>
				<? } ?>
			</select>
			<span class="helpTxt">&nbsp;Choose the criticality of the alert</span>
		</td>
	</tr>
</table>
	
<br><br>

<input class="field" name="active_email" value="1" align="left" type="checkbox" <? if($rs->active_email == 1) echo "checked" ?>>Notify by E-mail
<br><br>
<?
    $email = $rs->email;

    $sql = "SELECT email from users WHERE email IS NOT NULL AND email != ''";
    $result = mysql_query($sql);
    while( $rs_email_user = mysql_fetch_object($result) ) {
?>
    &nbsp;&nbsp;&nbsp;&nbsp;<input class="field" name="list_email[]" value="<?=$rs_email_user->email?>" align="left" type="checkbox" <? if(substr_count($email,$rs_email_user->email) > 0) echo "checked" ?>><?=$rs_email_user->email?>
    <br>
<?
	$email = str_replace($rs_email_user->email,"",$email);
    }
    
    $email = str_replace(" ","",$email);
    $email = str_replace(",","",$email);
?>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;other: <input name="email" value="<? echo $email?>" size="45" type="text">
<br>
<br>


<? } ?>



<input type="hidden" name="id" value="<? echo $id?>">
<input type="hidden" name="action" value="<? echo $action?>">
<input type="submit" value="save alert">
</form>

</div>
</html>
