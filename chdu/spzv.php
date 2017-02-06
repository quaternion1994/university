<?php session_start(); require("functions.php"); ?>
<html>
<head>
	<meta http-equiv=content-type content="text/html; charset=UTF-8"/>
</head>
<body>
<?php
	echo "Простые справочники:<br>";
	echo "<a target='_parent' href='spze.php?mode=1'>".$editmode[1]."</a>";
	echo "<table border=1>";
	echo "<tr>
		<td>Наименование</td>
		<td>К</td>
		<td>У</td>
	</tr>";
	$result = $db->query("select * from spz order by spr");
	while($row = $result->fetch_assoc())
	{
		echo "<tr>";
		echo "<td><a target='right' href='spsv.php?spr=".$row["spr"]."'>".$row["naim"]."</a></td>";
		echo "<td><a target='_parent' href='spze.php?mode=2&spr=".$row["spr"]."'>К</a></td>";
		echo "<td><a target='_parent' href='spze.php?mode=3&spr=".$row["spr"]."'>У</a></td>";
		echo "</tr>";
	}
	$result->free();
	$db->close();
	echo "</table>";
?>
</body>
</html>
