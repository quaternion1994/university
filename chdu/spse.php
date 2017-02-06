<?php session_start(); require("functions.php"); ?>
<html>
<head>
	<meta http-equiv=content-type content="text/html; charset=UTF-8"/>
</head>
<body>
<?php
	if(!isset($_REQUEST["mode"]) or !is_numeric($_REQUEST["mode"]) or !isset($_REQUEST["spr"]))
		exit();

	$spz = $db->query("select * from spz where spr='".$_REQUEST["spr"]."'");
	$spzr = $spz->fetch_assoc();
	
	if(!isset($_REQUEST["kod_".$spzr["tkspr"]]) and $_REQUEST["mode"] != 1)
		exit();
	
	if(!isset($_REQUEST["kod_c"])) $_REQUEST["kod_c"] = "";
	if(!isset($_REQUEST["kod_n"])) $_REQUEST["kod_n"] = 0;
	
	if($_REQUEST["mode"] != 1 and $_REQUEST["action"] == 0)
		setRequest("sps", array("spr" => $_REQUEST["spr"], "kod_c" => $_REQUEST["kod_c"], "kod_n" => $_REQUEST["kod_n"]));
	if($_REQUEST["action"] == 1)
	{
		switch($_REQUEST["mode"])
		{
			case 1: //add
				$query = "INSERT INTO chdu.sps (spr, kod_c, kod_n, naim, naim_s, "
						.($spzr["pr_period"] == "+" ? "date_from, " : "")
						.($spzr["pr_period"] == "+" ? "date_to, " : "")
						.($spzr["pr_char1"] == "+" ? "char1, " : "")
						.($spzr["pr_char2"] == "+" ? "char2, " : "")
						.($spzr["pr_koef1"] == "+" ? "koef1, " : "")
						.($spzr["pr_koef2"] == "+" ? "koef2, " : "")
						."sid_a, date_a) values('".$_REQUEST["spr"]."', '".$_REQUEST["kod_c"]."', '".$_REQUEST["kod_n"]."', '".$_REQUEST["naim"]."', "."'".$_REQUEST["naim_s"]."', "
						.($spzr["pr_period"] == "+" ? strNull($_REQUEST["date_from"]).", " : "")
						.($spzr["pr_period"] == "+" ? strNull($_REQUEST["date_to"]).", " : "")
						.($spzr["pr_char1"] == "+" ? strNull($_REQUEST["char1"]).", " : "")
						.($spzr["pr_char2"] == "+" ? strNull($_REQUEST["char2"]).", " : "")
						.($spzr["pr_koef1"] == "+" ? numNotNull($_REQUEST["koef1"]).", " : "")
						.($spzr["pr_koef2"] == "+" ? numNotNull($_REQUEST["koef2"]).", " : "")
						.$_SESSION["sid"].", now());";
						echo $query;
				$db->query($query);
				//echo $query;
				//echo "<script>document.location.href='spsv.php?spr=".$_REQUEST["spr"]."'</script>";
			break;
			case 2: //edit
				$query = "update sps set date_from=".strNull($_REQUEST["date_from"])
					.", date_to=".strNull($_REQUEST["date_to"])
					.", naim='".$_REQUEST["naim"]."'"
					.", naim_s='".$_REQUEST["naim_s"]."'"
					.", char1=".strNull($_REQUEST["char1"])
					.", char2=".strNull($_REQUEST["char2"])
					.", koef1=".strNull($_REQUEST["koef1"])
					.", koef2=".strNull($_REQUEST["koef2"])
					." where spr='".$_REQUEST["spr"]."' and kod_c='".$_REQUEST["kod_c"]."' and kod_n=".$_REQUEST["kod_n"];
				$db->query($query);
				echo "<script>document.location.href='spsv.php?spr=".$_REQUEST["spr"]."'</script>";
			break;
			case 3: //delete
				//$db->query("delete from  where uid=".$_REQUEST["uid"]." and npp=".$_REQUEST["npp"].";");
				//echo "<script>document.location.href='unitv.php'</script>";
				echo "delete disabled";
			break;
		}
	}
	else
	{
		echo "<form action='spse.php' method='".$GLOBALS["form_method"]."'>";
		echo "Код: <input type='text' name='kod_".$spzr["tkspr"]."' value='".$_REQUEST["kod_".$spzr["tkspr"]]."'></input><br/>";
		echo "Наименование: <input type='text' name='naim' value='".$_REQUEST["naim"]."'></input><br/>";
		echo "Наименование сокращенное: <input type='text' name='naim_s' value='".$_REQUEST["naim_s"]."'></input><br/>";
		if($spzr["pr_period"] == "+") echo "Дата с: <input type='text' name='date_from' value='".$_REQUEST["date_from"]."'></input><br/>";
		if($spzr["pr_period"] == "+") echo "Дата по: <input type='text' name='date_to' value='".$_REQUEST["date_to"]."'></input><br/>";
		if($spzr["pr_char1"] == "+") echo $spzr["n_char1"].": <input type='text' name='char1' value='".$_REQUEST["char1"]."'></input><br/>";
		if($spzr["pr_char2"] == "+") echo $spzr["n_char2"].": <input type='text' name='char2' value='".$_REQUEST["char2"]."'></input><br/>";
		if($spzr["pr_koef1"] == "+") echo $spzr["n_koef1"].": <input type='text' name='koef1' value='".$_REQUEST["koef1"]."'></input><br/>";
		if($spzr["pr_koef2"] == "+") echo $spzr["n_koef2"].": <input type='text' name='koef2' value='".$_REQUEST["koef2"]."'></input><br/>";
		
		echo "<input type='hidden' name='mode' value='".$_REQUEST["mode"]."'/>";	
		echo "<input type='hidden' name='spr' value='".$_REQUEST["spr"]."'/>";	
		echo "<input type='hidden' name='action' value='1'/>";
		echo "<input type='hidden' name='sid_a' value='".$_REQUEST["sid_a"]."'/>";
		echo "<input type='hidden' name='date_a' value='".$_REQUEST["date_a"]."'/>";
		echo "<br/><input type='submit' value='".$editmode[$_REQUEST["mode"]]."'></input></form>";
	}
	$spz->free();
	$db->close();
?>
</body>
</html>

