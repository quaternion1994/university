<?php session_start(); require("functions.php"); ?>
<html>
<head>
	<meta http-equiv=content-type content="text/html; charset=UTF-8"/>
</head>
<body>
<?php
	if(!isset($_REQUEST["spr"]))
		exit();
	echo "<a href='spse.php?mode=1&spr=".$_REQUEST["spr"]."'>".$editmode[1]."</a>";
	
	$spz = $db->query("select * from spz where spr='".$_REQUEST["spr"]."'");
	$spzr = $spz->fetch_assoc();
	
	echo "<table border=1>";
	echo "<thead><td>Код</td><td>наименование</td><td>наименование сокращенное</td>";
	if($spzr["pr_period"] == "+") echo "<td>Дата с</td>";
	if($spzr["pr_period"] == "+") echo "<td>Дата по</td>";
	if($spzr["pr_char1"] == "+") echo "<td>".$spzr["n_char1"]."</td>";
	if($spzr["pr_char2"] == "+") echo "<td>".$spzr["n_char2"]."</td>";
	if($spzr["pr_koef1"] == "+") echo "<td>".$spzr["n_koef1"]."</td>";
	if($spzr["pr_koef2"] == "+") echo "<td>".$spzr["n_koef2"]."</td>";
	echo "<td>кто добавил</td><td>когда добавил</td><td>К</td><td>У</td></thead>";
	
	$result = $db->query("select * from sps where spr='".$_REQUEST["spr"]."' order by kod_".$spzr["tkspr"]);
	while($row = $result->fetch_assoc())
	{
		echo "<tr>";
		echo "<td>".$row["kod_".$spzr["tkspr"]]."</td>";
		echo "<td>".$row["naim"]."</td>";
		echo "<td>".$row["naim_s"]."</td>";
		if($spzr["pr_period"] == "+") echo "<td>".$row["date_from"]."</td>";
		if($spzr["pr_period"] == "+") echo "<td>".$row["date_to"]."</td>";
		if($spzr["pr_char1"] == "+") echo "<td>".$row["char1"]."</td>";
		if($spzr["pr_char2"] == "+") echo "<td>".$row["char2"]."</td>";
		if($spzr["pr_koef1"] == "+") echo "<td>".$row["koef1"]."</td>";
		if($spzr["pr_koef2"] == "+") echo "<td>".$row["koef2"]."</td>";
		echo "<td>".$row["sid_a"]."</td>";
		echo "<td>".$row["date_a"]."</td>";
		echo "<td><a href='spse.php?mode=2&spr=".$row["spr"]."&kod_".$spzr["tkspr"]."=".$row["kod_".$spzr["tkspr"]]."'>К</a></td>";
		echo "<td><a href='spse.php?mode=3&spr=".$row["spr"]."&kod_".$spzr["tkspr"]."=".$row["kod_".$spzr["tkspr"]]."'>У</a></td>";
		echo "</tr>";
	}
	$spz->free();
	$result->free();
	$db->close();
	echo "</table>";
?>
</body>
</html>
