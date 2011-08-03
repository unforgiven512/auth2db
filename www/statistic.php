<? include "verify.php" ?>
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

#  Copyright (c) 2007-2008 Ezequiel Vera


include("conn.php");
require('geoip_functions.php');

class Statistic 
{
		function show($tipo,$action,$campo,$color)
		{
                    $consulta_fecha = date("Y")."-".date("m")."-".date("d");
                    //$consulta_fecha_mas = date("Y")."-".date("m")."-".(date("d")+1);  
                    $consulta_fecha_mas = date("Y-m-d", time()+(1*24*60*60)); 
                    
				$sql = "SELECT $campo as total FROM login WHERE action = '$action' AND tipo = '$tipo'  AND fecha > '$consulta_fecha' AND fecha < '$consulta_fecha_mas'";
				$sql = "SELECT $campo as total FROM log_".date("Y")."_".date("m")."_".date("d")." WHERE action = '$action' AND tipo = '$tipo'  AND fecha > '$consulta_fecha' AND fecha < '$consulta_fecha_mas'";
				$result_total = mysql_query($sql);
				$total = mysql_num_rows($result_total);

				$sql = "SELECT count($campo) as cantidad, $campo FROM login WHERE action = '$action' AND tipo = '$tipo'   AND fecha > '$consulta_fecha' AND fecha < '$consulta_fecha_mas' GROUP BY $campo ORDER BY cantidad DESC LIMIT 10";
				$sql = "SELECT count($campo) as cantidad, $campo FROM log_".date("Y")."_".date("m")."_".date("d")." WHERE action = '$action' AND tipo = '$tipo'   AND fecha > '$consulta_fecha' AND fecha < '$consulta_fecha_mas' GROUP BY $campo ORDER BY cantidad DESC LIMIT 10";
				$result = mysql_query($sql);
				
				echo "<b>" . strtoupper($tipo) . "</b> " . $action . " ($campo)";
			?>
				<table border="0" cellpadding="1" cellspacing="1">
					<tr bgcolor="#CCCCCC" class="filasTituloMain01">
						<td width="80" ><? echo $campo; ?></td>
						<td width="40" >count</td>
						<td width="45" align="right" >%</td>
					</tr >
					<? while( $rs = mysql_fetch_object($result) ) {?>
					<tr class="filas" bgcolor=<? 	if ($color == "R") {
														echo "#FF9999";
													} else if ($color == "G") {
														echo "#00FF99";
													}
                                                                        echo "#".$color;
                                                                        ?> >
						<td><? echo $rs->$campo ?></td>
						<td align="right" ><? echo $rs->cantidad ?></td>
						<td align="right" nowrap><? echo number_format( ( $rs->cantidad / $total ) * 100, 2) . " %" ?></td>
					</tr>
					<? } ?>

				</table>
			
			<?
		}
	
}

?>

<html>
<head>
<title>Auth2DB</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
<link href="diff.css" rel="stylesheet" type="text/css">
</head>
<body>

<table background="images/top_ext2.jpg" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td valign="top">
      <table border="0" cellpadding="0" cellspacing="0" width="1000">
        <tr>
          <td width="1">
	    <img src="images/auth2db.jpg" alt="Auth2DB" height="110" width="324">
          </td>
          <td>


		  </td> 
        <tr>

      </table>
    </td>
  </tr>
</table>

<p class="itemsMenu001"></p>

<?

$consulta_fecha = date("Y")."-".date("m")."-".date("d");
//$consulta_fecha_mas = date("Y")."-".date("m")."-".(date("d")+1);  
$consulta_fecha_mas = date("Y-m-d", time()+(1*24*60*60)); 

$sql = "SELECT DISTINCT tipo, action, color FROM login 
LEFT JOIN action_config ON action_config.action_name = action
WHERE fecha > '$consulta_fecha' AND fecha < '$consulta_fecha_mas' GROUP BY tipo, action ORDER BY tipo";

$sql = "SELECT DISTINCT tipo, action, color FROM log_".date("Y")."_".date("m")."_".date("d")." 
LEFT JOIN action_config ON action_config.action_name = action
WHERE fecha > '$consulta_fecha' AND fecha < '$consulta_fecha_mas' GROUP BY tipo, action ORDER BY tipo";


$result_tipos = mysql_query($sql);

?>

<table>
        
<?

while ($rs_tipos = mysql_fetch_object($result_tipos))
{
    echo "<tr><td valign='top' valign='top' >";
    //echo $rs_tipos->tipo . " - ".$rs_tipos->action ."<br>";
    $template = new Statistic; 
    $template->show($rs_tipos->tipo,$rs_tipos->action,"usuario",$rs_tipos->color);
    echo "</td><td valign='top' valign='top' >";
    $template->show($rs_tipos->tipo,$rs_tipos->action,"ip",$rs_tipos->color);
    echo "</td></tr>";
    flush();
}

exit;
?>
</table>

<table>
	<tr>
		<td valign="top" valign="top" >
		

			<? 
				$template = new Statistic; 
				$template->show("sshd","Failed","ip","R");
			?>
			
		</td>
		<td valign="top" >
			<? 
				$template = new Statistic; 
				$template->show("sshd","Accepted","ip","G");
			?>

		</td>
		<td valign="top" >

			<? 
				$template = new Statistic; 
				$template->show("sshd","Failed","usuario","R");
			?>
			
		</td>
		<td valign="top" >
			<? 
				$template = new Statistic; 
				$template->show("sshd","Accepted","usuario","G");
			?>

		</td>
	</tr>
<? flush();?>
	<tr>
		<td></td>
	<tr>

	<tr>
	
		<td valign="top" >

			<? 
				$template = new Statistic; 
				$template->show("apache","failure","ip","R");
			?>
			
		</td>
		<td valign="top" >


		</td>
		<td valign="top" >

			<? 
				$template = new Statistic; 
				$template->show("apache","failure","usuario","R");
			?>
			
		</td>
		<td valign="top" >


		</td>

	</tr>
<? flush();?>
	<tr>
		<td></td>
	<tr>

	<tr>
	
		<td valign="top" >

			<? 
				$template = new Statistic; 
				$template->show("proftpd","no such user","ip","R");
			?>
			
		</td>
		<td valign="top" >
			<? 
				$template = new Statistic; 
				$template->show("proftpd","Login successful","ip","G");
			?>

		</td>
		<td valign="top" >

			<? 
				$template = new Statistic; 
				$template->show("proftpd","no such user","usuario","R");
			?>
			
		</td>
		<td valign="top" >
			<? 
				$template = new Statistic; 
				$template->show("proftpd","Login successful","usuario","G");
			?>

		</td>

	</tr>
</table>

</body>
</html>
