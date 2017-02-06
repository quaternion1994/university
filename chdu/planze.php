<?php session_start(); require("functions.php"); ?>
<html>
<head>
	<meta http-equiv=content-type content="text/html; charset=UTF-8"/>
</head>
<body>
<?php
	if(!isset($_REQUEST["mode"]) or !is_numeric($_REQUEST["mode"]))
		exit();
		
		
	$helper = new editHelper("plan");
	if(isset($_REQUEST["id"]) and $_REQUEST["action"] == 0)
		$helper->setRequestByPk();

	if($_REQUEST["action"] == 1)
	{
		switch($_REQUEST["mode"])
		{
			case 1: //add
				$helper->insertRequestToDb("plan");
				echo "<script>document.location.href='spv.html'</script>";
			break;
			case 2: //edit
				$helper->updateDbFromRequest("plan");
				//echo "<script>document.location.href='spv.html'</script>";*/
				echo "<br>disabled";
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
		$helper->getCustomForm("planze.php")->show();
		/*echo "<form action='planze.php' method='".$GLOBALS["form_method"]."'>";
		echo "<br>Специальность: "; inputSelect("kspc", "sps", "kod_n", "naim", "spr='SPC' and kod_c=''", $_REQUEST["kspc"]);
		echo "<br>Кафедра: "; inputSelect("uid", "unit", "uid", "naim", dateBetween(), $_REQUEST["uid"]);
		echo "<br>Год с: <input type='text' name='year_from' value='".$_REQUEST["year_from"]."'></input>";
		echo "<br>Уровень образования: "; inputSelect("kosv", "sps", "kod_c", "naim", "spr='OSV' and kod_n=0", $_REQUEST["kosv"]);
		echo "<br>Форма обучения: "; inputSelect("kfob", "sps", "kod_c", "naim", "spr='FOB' and kod_n=0", $_REQUEST["kfob"]);
		echo "<br>?Срок обучения: <input type='text' name='srok' value='".$_REQUEST["srok"]."'></input>";
		echo "<br>Деление года: "; inputSelect("kdel", "sps", "kod_n", "naim", "spr='DEL' and kod_c=''", $_REQUEST["kdel"]);
		echo "<br>Недель в году: <input type='text' name='winyear' value='".$_REQUEST["winyear"]."'></input>";
		
		echo "<input type='hidden' name='mode' value='".$_REQUEST["mode"]."'/>";	
		echo "<input type='hidden' name='action' value='1'/>";
		echo "<input type='hidden' name='sid_a' value='".$_REQUEST["sid_a"]."'/>";
		echo "<input type='hidden' name='date_a' value='".$_REQUEST["date_a"]."'/>";
		echo "<br/><input type='submit' value='".$editmode[$_REQUEST["mode"]]."'></input></form>";*/
	}
	$db->close();
?>
</body>
</html>

