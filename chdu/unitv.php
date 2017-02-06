<?php session_start(); require("functions.php"); ?>
<html>
<head>
	<meta http-equiv=content-type content="text/html; charset=UTF-8"/>
</head>
<body>
<?php
	echo "Подразделения:<br>";
	echo "<a href='unite.php?mode=1'>".$editmode[1]."</a>";
	echo "<table border=1>";
	echo "<tr>
		<td>Уникальный номер</td>
		<td>Тип подразделения</td>
		<td>Наименование</td>
		<td>Наименование сокращенное</td>
		<td>Действие с</td>
		<td>Действие по</td>
		<td>Начальник</td>
		<td>Ответственный</td>
		<td>Ведущий специалист</td>
		<td>Старый код</td>
		<td>Кто добавил</td>
		<td>Когда добавил</td>
		<td>К</td>
		<td>У</td>
	</tr>";
	$result = $db->query("select * from unit order by uid");
	while($row = $result->fetch_assoc())
	{
		echo "<tr>";
		echo "<td>".$row["uid"]."</td>";
		echo "<td>".$row["ktu"]."</td>";
		echo "<td>".$row["naim"]."</td>";
		echo "<td>".$row["naim_s"]."</td>";
		echo "<td>".$row["date_from"]."</td>";
		echo "<td>".$row["date_to"]."</td>";
		echo "<td>".$row["sid_nach"]."</td>";
		echo "<td>".$row["sid_otv"]."</td>";
		echo "<td>".$row["sid_ved"]."</td>";
		echo "<td>".$row["oldkod"]."</td>";
		echo "<td>".$row["sid_a"]."</td>";
		echo "<td>".$row["date_a"]."</td>";
		echo "<td><a href='unite.php?mode=2&uid=".$row["uid"]."&npp=".$row["npp"]."'>К</a></td>";
		echo "<td><a href='unite.php?mode=3&uid=".$row["uid"]."&npp=".$row["npp"]."'>У</a></td>";
		echo "</tr>";
	}
	$result->free();
	$db->close();
	echo "</table>";
?>
</body>
</html>
