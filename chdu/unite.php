<?php session_start(); require("functions.php"); ?>
<html>
<head>
	<meta http-equiv=content-type content="text/html; charset=UTF-8"/>
</head>
<body>
<?php
	if(!isset($_REQUEST["mode"]) or !is_numeric($_REQUEST["mode"]))
		exit();

	if(isset($_REQUEST["uid"]) and isset($_REQUEST["npp"]) and $action == 0)
	{
		if(!is_numeric($_REQUEST["uid"]) or !is_numeric($_REQUEST["npp"]))
			exit();
		setRequest("unit", array("uid" => $_REQUEST["uid"], "npp" => $_REQUEST["npp"]));
	}

	if($_REQUEST["action"] == 1)
	{
		switch($_REQUEST["mode"])
		{
			case 1: //add
				$query = "set @uid=(select if(max(uid) is null, 0, max(uid))+1 from chdu.unit);
					INSERT INTO chdu.unit (uid, npp, ktu, naim, naim_s, date_from, date_to, sid_nach, sid_otv, sid_ved, oldkod, sid_a, date_a)
					values(@uid, 1, '"
					.$_REQUEST["ktu"]."', '"
					.$_REQUEST["naim"]."', '"
					.$_REQUEST["naim_s"]."', '"
					.$_REQUEST["date_from"]."', '"
					.$_REQUEST["date_to"]."', "
					.($_REQUEST["sid_nach"] == 0 ? "null" : $_REQUEST["sid_nach"]).", "
					.($_REQUEST["sid_otv"] == 0 ? "null":$_REQUEST["sid_otv"]).", "
					.($_REQUEST["sid_ved"] == 0 ? "null":$_REQUEST["sid_ved"]).", "
					.($_REQUEST["oldkod"] == "" ? "null" : "'".$_REQUEST["oldkod"]."'").", "
					.$_SESSION["sid"].", now());
					select @uid;";
				$db->multi_query($query);
				//echo $query;
				echo "<script>document.location.href='unitv.php'</script>";
				//echo $result;
				//$result->free();
			break;
			case 2: //edit
				$query = "set @npp=(select max(npp)+1 from chdu.unit where unit.uid=".$_REQUEST["uid"].");
					update unit set date_to=DATE_ADD('".$_REQUEST["date_from"]."', INTERVAL -1 DAY) where uid=".$_REQUEST["uid"]." and npp=".$_REQUEST["npp"].";
					INSERT INTO chdu.unit (uid, npp, ktu, naim, naim_s, date_from, date_to, sid_nach, sid_otv, sid_ved, oldkod, sid_a, date_a)
					values(".$_REQUEST["uid"].", @npp, '"
						.$_REQUEST["ktu"]."', '"
						.$_REQUEST["naim"]."', '"
						.$_REQUEST["naim_s"]."', '"
						.$_REQUEST["date_from"]."', '"
						.$_REQUEST["date_to"]."', "
						.($_REQUEST["sid_nach"] == 0 ? "null, " : $_REQUEST["sid_nach"]).", "
						.($_REQUEST["sid_otv"] == 0 ? "null, " : $_REQUEST["sid_otv"]).", "
						.($_REQUEST["sid_ved"] == 0 ? "null, " : $_REQUEST["sid_ved"]).", "
						.($_REQUEST["oldkod"] == "" ? "null, " : "'".$_REQUEST["oldkod"])."', "
						.$_SESSION["sid"].", now());
					select @npp;";
				$db->multi_query($query);
				echo "<script>document.location.href='unitv.php'</script>";
			break;
			case 3: //delete
				$db->query("delete from unit where uid=".$_REQUEST["uid"]." and npp=".$_REQUEST["npp"].";");
				echo "<script>document.location.href='unitv.php'</script>";
			break;
		}
	}
	else
	{
		echo "<form action='unite.php' method='".$GLOBALS["form_method"]."'>";
		echo "Тип подразделения: "; inputSelect("ktu", "sps", "kod_c", "naim", "sps.spr='TU'", $_REQUEST["ktu"]);
		echo "<br/>Наименование: <input type='text' name='naim' value='".$_REQUEST["naim"]."'></input><br/>";
		echo "Наименование сокращенное: <input type='text' name='naim_s' value='".$_REQUEST["naim_s"]."'></input><br/>";
		echo "Дата с: <input type='text' name='date_from' value='".$_REQUEST["date_from"]."'></input><br/>";
		echo "Дата по: <input type='text' name='date_to' value='".$_REQUEST["date_to"]."'></input><br/>";
		echo "Начальник: <input type='text' name='sid_nach' value='".$_REQUEST["sid_nach"]."'></input><br/>";
		echo "Ответственный: <input type='text' name='sid_otv' value='".$_REQUEST["sid_otv"]."'></input><br/>";
		echo "Ведущий специалист: <input type='text' name='sid_ved' value='".$_REQUEST["sid_ved"]."'></input><br/>";
		echo "Старый код: <input type='text' name='oldkod' value='".$_REQUEST["oldkod"]."'></input><br/>";

		echo "<input type='hidden' name='mode' value='".$_REQUEST["mode"]."'/>";	
		echo "<input type='hidden' name='action' value='1'/>";
		echo "<input type='hidden' name='uid' value='".$_REQUEST["uid"]."'/>";
		echo "<input type='hidden' name='npp' value='".$_REQUEST["npp"]."'/>";
		echo "<input type='hidden' name='sid_a' value='".$_REQUEST["sid_a"]."'/>";
		echo "<input type='hidden' name='date_a' value='".$_REQUEST["date_a"]."'/>";
		echo "<input type='submit' value='".$editmode[$_REQUEST["mode"]]."'></input></form>";
	}
	$db->close();
?>
</body>
</html>

