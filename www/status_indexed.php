<? include "verify.php"; ?>
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
<HTML>
<HEAD>
<title>Auth2DB</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
<link href="diff.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="preLoadingMessage.js"></script>
</HEAD>
<body>
<? include "conn.php"; ?>
<? include "security.php"; ?>
<? include "header.php"; ?>
<? include "menu_general.php"; ?>
<?
$fecha = date("Y")."_".date("m")."_".date("d");
?>
<p class="itemsMenu001"></p>


<table>
	<tr>
		<td valign="top" valign="top" >
			TOTAL INDEXED EVENTS
			 <table>
				<tr class="filasTituloMain01">
				      <td width="120" nowrap>_time</td>
				      <td width="120" nowrap>source</td>
				      <td width="120" nowrap>events</td>
				</tr>
				<?
				      $sql = "SELECT * FROM source ORDER BY events DESC LIMIT 10 ";
				      $result = mysql_query($sql, $link);
				      while ($rs = mysql_fetch_object($result) ){
				?>
				<tr style="background: #555555">
				      <td><?=$rs->id?></td>
				      <td><?=$rs->source?></td>
				      <td><?=$rs->events?></td>
				</tr>
				<?
				      }
				?>
			  </table>
<br>
		</td>
		<td valign="top" valign="top" >

			TOTAL HOST EVENTS
			 <table>
				<tr class="filasTituloMain01">
				      <td width="120" nowrap>_time</td>
				      <td width="120" nowrap>host</td>
				      <td width="120" nowrap>events</td>
				</tr>
				<?
				      $sql = "SELECT * FROM host ORDER BY value DESC LIMIT 10 ";
				      $result = mysql_query($sql, $link);
				      while ($rs = mysql_fetch_object($result) ){
				?>
				<tr style="background: #555555">
				      <td><?=$rs->id?></td>
				      <td><?=$rs->host?></td>
				      <td><?=$rs->value?></td>
				</tr>
				<?
				      }
				?>
			  </table>
<br>
		</td>
	</tr>
	<tr>
		<td valign="top" valign="top" >
			 TODAY LOG EVENTS x SOURCE
			 <table>
				<tr class="filasTituloMain01">
				      <td width="120" nowrap>_time</td>
				      <td width="120" nowrap>source</td>
				      <td width="120" nowrap>events</td>
				</tr>
				<?
				      $sql = "SELECT max(fecha) as fecha, count(source) as events, source FROM log_$fecha GROUP BY source ORDER BY events DESC LIMIT 10 ";
				      $result = mysql_query($sql, $link);
				      while ($rs = mysql_fetch_object($result) ){
				?>
				<tr style="background: #555555">
				      <td><?=$rs->fecha?></td>
				      <td><?=$rs->source?></td>
				      <td><?=$rs->events?></td>
				</tr>
				<?
				      }
				?>
			  </table>
<br>
		</td>
		<td valign="top" valign="top" >
			 TODAY JUNK EVENTS x SOURCE
			 <table>
				<tr class="filasTituloMain01">
				      <td width="120" nowrap>_time</td>
				      <td width="120" nowrap>source</td>
				      <td width="120" nowrap>events</td>
				</tr>
				<?
				      $sql = "SELECT max(fecha) as fecha, count(source) as events, source FROM junk_$fecha GROUP BY source LIMIT 10 ";
				      $result = mysql_query($sql, $link);
				      while ($rs = mysql_fetch_object($result) ){
				?>
				<tr style="background: #555555">
				      <td><?=$rs->fecha?></td>
				      <td><?=$rs->source?></td>
				      <td><?=$rs->events?></td>
				</tr>
				<?
				      }
				?>
			  </table>
<br>
		</td>
	</tr>
	<tr>
		<td valign="top" valign="top" >
			 TODAY LOG EVENTS x HOTS
			 <table>
				<tr class="filasTituloMain01">
				      <td width="120" nowrap>_time</td>
				      <td width="120" nowrap>source</td>
				      <td width="120" nowrap>events</td>
				</tr>
				<?
				      $sql = "SELECT max(fecha) as fecha, count(host) as events, host FROM log_$fecha GROUP BY host ORDER BY events DESC LIMIT 10 ";
				      $result = mysql_query($sql, $link);
				      while ($rs = mysql_fetch_object($result) ){
				?>
				<tr style="background: #555555">
				      <td><?=$rs->fecha?></td>
				      <td><?=$rs->host?></td>
				      <td><?=$rs->events?></td>
				</tr>
				<?
				      }
				?>
			  </table>
<br>
		</td>
		<td valign="top" valign="top" >
			 TODAY JUNK EVENTS x HOTS
			 <table>
				<tr class="filasTituloMain01">
				      <td width="120" nowrap>_time</td>
				      <td width="120" nowrap>source</td>
				      <td width="120" nowrap>events</td>
				</tr>
				<?
				      $sql = "SELECT max(fecha) as fecha, count(host) as events, host FROM junk_$fecha GROUP BY host  ORDER BY events DESC LIMIT 10 ";
				      $result = mysql_query($sql, $link);
				      while ($rs = mysql_fetch_object($result) ){
				?>
				<tr style="background: #555555">
				      <td><?=$rs->fecha?></td>
				      <td><?=$rs->host?></td>
				      <td><?=$rs->events?></td>
				</tr>
				<?
				      }
				?>
			  </table>
<br>
		</td>
	</tr>
</table>