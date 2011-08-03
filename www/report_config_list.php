<? 
include "verify.php"; 
include "security.php";
?>
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

#  Copyright (c) 2007,2008 Ezequiel Vera

?>
<html>
<head>
	<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
	<title>Auth2DB</title>
</head>
<body>

<? include "header.php" ?>
<? include "menu_general.php" ?>

<p class="itemsMenu001"></p>

<?
include "conn.php";

$sql = "SELECT * FROM reports ORDER BY id DESC";
$result = mysql_query($sql);

?>

<div class="bloque">
<p class="title">Report Config</p>
<p class="itemsMenu001"></p>
</div>

<div class="centerbox">
<br>
<a href="report_add.php?action=new"><img src="icons/edit.png" border=0 ></a> Add New Report
<br><br>
<table>
	<tr class="filasTituloMain01">
		<td width="80" nowrap><b>Report Name</b></td>
		<td width="80" nowrap><b>Description</b></td>
		<td width="20"></td>
		<td width="20"></td>
		<td width="20"></td>
	</tr>
<?
while($rs = mysql_fetch_object($result))
{
?>
	<tr style="background: #555555">
		<td nowrap><? echo $rs->report_name ?></td>
		<td nowrap><? echo $rs->description ?></td>
		<td align=center><a href="report_add.php?action=edit&id=<? echo $rs->id ?>"><img src="icons/edit.png" border=0 height=12></a></td>
		<td align=center><a href="?id=<? echo $rs->id ?>"><img src="icons/cancel.png" border=0 height=12></a></td>
		<td nowrap><a href="report_show.php?id=<? echo $rs->id ?>">View Report</a></td>
	</tr>
<?
}
?>
</div>