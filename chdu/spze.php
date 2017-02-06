<?php session_start(); require("functions.php"); ?>
<html>
<head>
	<meta http-equiv=content-type content="text/html; charset=UTF-8"/>
</head>
<body>
<?php
	if(!isset($_REQUEST["mode"]) or !is_numeric($_REQUEST["mode"]))
		exit();

	if(isset($_REQUEST["spr"]) and $action == 0)
		setRequest("spz", array("spr" => $_REQUEST["spr"]));

	if($_REQUEST["action"] == 1)
	{
		switch($_REQUEST["mode"])
		{
			case 1: //add
				$query = "INSERT INTO chdu.spz (spr, naim, tkspr, pr_period, pr_char1, n_char1, pr_char2, n_char2, pr_koef1, n_koef1, pr_koef2, n_koef2, sid_a, date_a)
					values('".$_REQUEST["spr"]."', '".$_REQUEST["naim"]."', '".$_REQUEST["tkspr"]."', '"
						.$_REQUEST["pr_period"]."', '"
						.$_REQUEST["pr_char1"]."', "
						.strNull($_REQUEST["n_char1"]).", '"
						.$_REQUEST["pr_char2"]."', "
						.strNull($_REQUEST["n_char2"]).", '"
						.$_REQUEST["pr_koef1"]."', "
						.strNull($_REQUEST["n_koef1"]).", '"
						.$_REQUEST["pr_koef2"]."', "
						.strNull($_REQUEST["n_koef2"]).", "
						.$_SESSION["sid"].", now());";
				$db->query($query);
				//echo $query;
				echo "<script>document.location.href='spv.html'</script>";
			break;
			case 2: //edit
				$query = "update spz set naim='".$_REQUEST["naim"]
					."', pr_period='".$_REQUEST["pr_period"]
					."', pr_char1='".$_REQUEST["pr_char1"]
					."', n_char1=".strNull($_REQUEST["n_char1"])
					.", pr_char2='".$_REQUEST["pr_char2"]
					."', n_char2=".strNull($_REQUEST["n_char2"])
					.", pr_koef1='".$_REQUEST["pr_koef1"]
					."', n_koef1=".strNull($_REQUEST["n_koef1"])
					.", pr_koef2='".$_REQUEST["pr_koef2"]
					."', n_koef2=".strNull($_REQUEST["n_koef2"])
					." where spr='".$_REQUEST["spr"]."'";
				$db->query($query);
				echo "<script>document.location.href='spv.html'</script>";
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
		echo "<form action='spze.php' method='".$GLOBALS["form_method"]."'>";
		echo "Код: <input type='text' name='spr' value='".$_REQUEST["spr"]."'></input><br/>";
		echo "Наименование: <input type='text' name='naim' value='".$_REQUEST["naim"]."'></input><br/>";
		echo "Тип кода: <select name='tkspr'><option value='c'".($_REQUEST["tkspr"]=="c" ? " selected" : "").">Символьный</option><option value='n'".($_REQUEST["tkspr"]=="N" ? " selected" : "").">Числовой</option></select><br/><br/>";
		
		echo "Период действия: <input type='checkbox' name='pr_period' value='+'".($_REQUEST["pr_period"] == "+" ? " checked" : "")."></input><br/>";
		echo "Необходимость ввода символьного признака 1: <input type='checkbox' name='pr_char1' value='+'".($_REQUEST["pr_char1"] == "+" ? " checked" : "")."></input><br/>";
		echo "Наименование символьного признака 1: <input type='text' name='n_char1' value='".$_REQUEST["n_char1"]."'></input><br/><br/>";
		echo "Необходимость ввода символьного признака 2: <input type='checkbox' name='pr_char2' value='+'".($_REQUEST["pr_char2"] == "+" ? " checked" : "")."></input><br/>";
		echo "Наименование символьного признака 2: <input type='text' name='n_char2' value='".$_REQUEST["n_char2"]."'></input><br/><br/>";
		echo "Необходимость ввода коеффициента 1: <input type='checkbox' name='pr_koef1' value='+'".($_REQUEST["pr_koef1"] == "+" ? " checked" : "")."></input><br/>";
		echo "Наименование коеффициента 1: <input type='text' name='n_koef1' value='".$_REQUEST["n_koef1"]."'></input><br/><br/>";
		echo "Необходимость ввода коеффициента 2: <input type='checkbox' name='pr_koef2' value='+'".($_REQUEST["pr_koef2"] == "+" ? " checked" : "")."></input><br/>";
		echo "Наименование коеффициента 2: <input type='text' name='n_koef2' value='".$_REQUEST["n_koef2"]."'></input><br/><br/>";
		
		echo "<input type='hidden' name='mode' value='".$_REQUEST["mode"]."'/>";	
		echo "<input type='hidden' name='action' value='1'/>";
		echo "<input type='hidden' name='sid_a' value='".$_REQUEST["sid_a"]."'/>";
		echo "<input type='hidden' name='date_a' value='".$_REQUEST["date_a"]."'/>";
		echo "<br/><input type='submit' value='".$editmode[$_REQUEST["mode"]]."'></input></form>";
	}
	$db->close();
?>
</body>
</html>

