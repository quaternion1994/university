<?php session_start(); require("functions.php"); ?>
<html>
<head>
	<meta http-equiv=content-type content="text/html; charset=UTF-8"/>
</head>
<body>
<?php
	if(!isset($_REQUEST["mode"]) or !is_numeric($_REQUEST["mode"]) or !isset($_REQUEST["krap"]) or ($_REQUEST["mode"] == 1 and !isset($_REQUEST["uid_pr"])) or ($_REQUEST["mode"] == 2 and !isset($_REQUEST["uid"])))
		exit();

	// if($mode == 2)
	// {
		// if(!is_numeric($_REQUEST["uid"]) or !is_numeric($_REQUEST["npp"]))
			// exit();
		// setRequest("unit", array("uid" => $_REQUEST["uid"], "npp" => $_REQUEST["npp"]));
	// }
	
	//echo "<a href='unithe.php?mode=1&krap=$krap&uid_pr=".$row["uid"]."'>Д</a> <a href='unithe.php?mode=2&krap=$krap&uid=".$row["uid"]."'>К</a>";

	if(Isset($_REQUEST["action"]))
	{
		switch($_REQUEST["mode"])
		{
			case 1: //add
				$query = "INSERT INTO chdu.uhier (kth, uid, uid_pr, date_from, date_to, sid_a, date_a)
					values('".$_REQUEST["krap"]."', "
					.$_REQUEST["uid"].", "
					.numNull($_REQUEST["uid_pr"]).", '"
					.$_REQUEST["date_from"]."', '"
					.$_REQUEST["date_to"]."', "
					.$_SESSION["sid"].", now());";
				$db->query($query);
				echo "<script>document.location.href='unithv.php'</script>";
				//echo $result;
				//$result->free();
			break;
			case 2: //edit
				/*$query = "set @npp=(select max(npp)+1 from chdu.unit where unit.uid=".$_REQUEST["uid"].");
					update unit set date_to=DATE_ADD('".$_REQUEST["date_from"]."', INTERVAL -1 DAY) where uid=".$_REQUEST["uid"]." and npp=".$_REQUEST["npp"].";
					INSERT INTO chdu.unit (uid, npp, ktu, naim, naim_s, date_from, date_to, sid_nach, sid_otv, sid_ved, oldkod, sid_a, date_a)
					values(".$_REQUEST["uid"].", @npp, '"
						.$_REQUEST["ktu"]."', '"
						.$_REQUEST["naim"]."', '"
						.$_REQUEST["naim_s"]."', '"
						.$_REQUEST["date_from"]."', '"
						.$_REQUEST["date_to"]."', "
						.($_REQUEST["sid_nach"] == 0 ? "null" : $_REQUEST["sid_nach"]).", "
						.($_REQUEST["sid_otv"] == 0 ? "null" : $_REQUEST["sid_otv"]).", "
						.($_REQUEST["sid_ved"] == 0 ? "null" : $_REQUEST["sid_ved"]).", "
						.($_REQUEST["oldkod"] == "" ? "null" : "'".$_REQUEST["oldkod"])."', "
						.$_SESSION["sid"].", now());
					select @npp;";
				$db->multi_query($query);
				echo "<script>document.location.href='unitv.php'</script>";*/
				echo "edit disabled";
			break;
			case 3: //delete
				echo "delete disabled";
			break;
		}
	}
	else
	{
		echo "<form action='unithe.php' method='".$GLOBALS["form_method"]."'>";
		echo "Разрез: ".$_REQUEST["krap"]."<br/>";
		echo "Подразделение: "; inputSelect("uid", "unit", "uid", "naim", "not exists(select 1 from uhier where uhier.uid=unit.uid and uhier.kth='".$_REQUEST["krap"]."' and ".dateBetween().") and ".dateBetween(), $_REQUEST["uid"], True);
		echo "<br>Подразделение-предок: "; inputSelect("uid_pr", "unit", "uid", "naim", dateBetween(), $_REQUEST["uid_pr"], True);
		echo "<br>Дата с: <input type='text' name='date_from' value='".$_REQUEST["date_from"]."'></input><br/>";
		echo "<br>Дата по: <input type='text' name='date_to' value='".$_REQUEST["date_to"]."'></input><br/>";

		echo "<input type='hidden' name='mode' value='".$_REQUEST["mode"]."'/>";	
		echo "<input type='hidden' name='action' value='1'/>";
		echo "<input type='hidden' name='krap' value='".$_REQUEST["krap"]."'/>";
		echo "<input type='hidden' name='sid_a' value='".$_REQUEST["sid_a"]."'/>";
		echo "<input type='hidden' name='date_a' value='".$_REQUEST["date_a"]."'/>";
		echo "<input type='submit' value='".$editmode[$_REQUEST["mode"]]."'></input></form>";
	}
	$db->close();
?>
</body>
</html>

