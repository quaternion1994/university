<?php session_start(); require("functions.php"); ?>
<html>
<head>
	<meta http-equiv=content-type content="text/html; charset=UTF-8"/>
</head>
<body>
<?php
	echo "Учебные планы:<br>";
	echo "<a target='_parent' href='planze.php?mode=1'>".$editmode[1]."</a>";
	echo "<table border=1>";
	echo "<tr>
		<td>Специальность</td>
		<td>Кафедра</td>
		<td>Год с</td>
		<td>Уровень образования</td>
		<td>Форма обучения</td>
		<td>К</td>
		<td>У</td>
	</tr>";
	$result = $db->query("select planz.id, spc.naim as spcnaim, unit.naim as kafnaim, planz.year_from, planz.kosv, planz.kfob "
						."from planz "
						."left join sps spc on spc.spr='SPC' and spc.kod_c='' and spc.kod_n=planz.kspc "
						."left join unit on unit.uid=planz.uid");
	while($row = $result->fetch_assoc())
	{
		echo "<tr>";
		echo "<td><a target='right' href='planrv.php?id_plan=".$row["id"]."'>".$row["spcnaim"]."</a></td>";
		echo "<td><a target='right' href='planrv.php?id_plan=".$row["id"]."'>".$row["kafnaim"]."</a></td>";
		echo "<td><a target='right' href='planrv.php?id_plan=".$row["id"]."'>".$row["year_from"]."</a></td>";
		echo "<td><a target='right' href='planrv.php?id_plan=".$row["id"]."'>".$row["kosv"]."</a></td>";
		echo "<td><a target='right' href='planrv.php?id_plan=".$row["id"]."'>".$row["kfob"]."</a></td>";
		
		echo "<td><a target='_parent' href='planze.php?mode=2&id=".$row["id"]."'>К</a></td>";
		echo "<td><a target='_parent' href='planze.php?mode=3&id=".$row["id"]."'>У</a></td>";
		echo "</tr>";
	}
	$result->free();
	$db->close();
	echo "</table>";
?>
</body>
</html>
