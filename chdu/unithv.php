<?php session_start(); require("functions.php"); ?>
<html>
<head>
	<meta http-equiv=content-type content="text/html; charset=UTF-8"/>
</head>
<body>
<?php
	function buildUnitHierarchyRec($krap, $unit="null")
	{
		if($unit == "null")
			$unit = " is null";
		else 
			$unit = "=".$unit;
		$cmd = "select uhier.uid, unit.naim from uhier left outer join unit on unit.uid=uhier.uid and ".dateBetween("unit")." where ".dateBetween("uhier")." and uhier.kth='$krap' and uhier.uid_pr".$unit;
		$result = $GLOBALS["db"]->query($cmd);
		if($result->num_rows == 0) return;
		echo "<ul>";
		while($row = $result->fetch_assoc())
		{
			echo "<li><span><a href='#1'>".$row["naim"]."</a></span>";
			echo "<a href='unithe.php?mode=1&krap=$krap&uid_pr=".$row["uid"]."'> Д</a> <a href='unithe.php?mode=2&krap=$krap&uid=".$row["uid"]."'>К</a>";
			buildUnitHierarchyRec($krap, $row["uid"]);
			echo "</li>";
		}
		echo "</ul>";
		$result->free();
	}
	
	if(!isset($_REQUEST["kuht"]))
		exit();
	echo $_REQUEST["kuht"]."<br>";
	echo "<a href='unithe.php?mode=1&krap=".$_REQUEST["kuht"]."&uid_pr=null'>".$editmode[1]."</a><br/>";
	
	echo "<div id='multi-derevo'>";
	buildUnitHierarchyRec($_REQUEST["kuht"]);
	echo "</div>";
	
	$db->close();
?>
</body>
</html>
