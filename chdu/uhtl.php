<?php session_start(); require("functions.php"); ?>
<html>
<head>
	<meta http-equiv=content-type content="text/html; charset=UTF-8"/>
</head>
<body>
<?php
	$spr="UHT";
	$spz = $db->query("select * from spz where spr='".$spr."'");
	$spzr = $spz->fetch_assoc();
	
	$result = $db->query("select * from sps where spr='".$spr."' order by kod_".$spzr["tkspr"]);
	while($row = $result->fetch_assoc())
		echo "<a target='right' href='unithv.php?kuht=".$row["kod_".$spzr["tkspr"]]."'>".$row["naim"]."</a><br>";
	$spz->free();
	$result->free();
	$db->close();
?>
</body>
</html>
