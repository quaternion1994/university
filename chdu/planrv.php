<?php session_start(); require("functions.php"); ?>
<html>
<head>
	<meta http-equiv=content-type content="text/html; charset=UTF-8"/>
</head>
<body>
<?php
	if(!isset($_REQUEST["id_plan"]))
		exit();
		
	$result1 = $db->query("select planz.*, spc.naim as spcnaim, unit.naim as kafnaim, del.naim as naimdel, del.koef1 as delkol "
						."from planz "
						."left join sps spc on spc.spr='SPC' and spc.kod_c='' and spc.kod_n=planz.kspc "
						."left join sps del on del.spr='DEL' and del.kod_c='' and del.kod_n=planz.kdel "
						."left join unit on unit.uid=planz.uid "
						."where planz.id=".$_REQUEST["id_plan"]);
	$planz = $result1->fetch_assoc();
	
	echo "Заголовок: <br>";
	echo "Специальность: ".$planz["spcnaim"]."<br>";
	echo "Кафедра: ".$planz["kafnaim"]."<br>";
	echo "Год с: ".$planz["year_from"]."<br>";
	echo "Уровень образования: ".$planz["kosv"]."<br>";
	echo "Форма обучения: ".$planz["kfob"]."<br>";
	echo "Деление года: ".$planz["naimdel"]."<br>";
	echo "Область знаний: ".$planz["oblz"]."<br>";
	echo "Квалификация: ".$planz["kvalif"]."<br>";
	echo "Лет обучения: ".$planz["srokY"]."<br>";
	echo "+месяцев обучения: ".$planz["srokM"]."<br>";
	echo "На основании: ".$planz["osnov"]."<br>";
	echo "Часов в кредите: ".$planz["hoursInCredit"]."<br>";
	$result1->free();

	
	echo "График: <br>";
	$cmd = "SELECT kurs, ";
	for($i = 1; $i <= 52; $i++)
	{
		$cmd = $cmd."GROUP_CONCAT(if(nweek = $i, kweek, NULL)) AS '$i'";
		if($i < 52) $cmd = $cmd.", ";
		else $cmd = $cmd." ";
	}
	$cmd = $cmd."FROM plang where id_plan=".$planz["id"]." GROUP BY kurs ";
	$result2 = $db->query($cmd);
	echo "<table border=1><tr><td>Курс</td>";
	for($i = 1; $i <= 52; $i++)
		echo "<td>$i</td>";
	echo "</tr>";
	while($row = $result2->fetch_row())
	{
		echo "<tr>";
		for($i = 0; $i < 53; $i++)
			echo "<td><a href='#'>".$row[$i]."</a></td>";
		echo "</tr>";
	}
	echo "</table>";
	$result2->free();


	
	$tr1 = $db->query("select sum(1) from windel where id_plan=".$planz["id"]);
	$tr2 = $tr1->fetch_row();
	$tr1->free();
	$kolDelF = $tr2[0]; // кол-во делений года факт
	
	$tr3 = $db->query("select min(ndel) from windel where id_plan=".$planz["id"]);
	$tr4 = $tr3->fetch_row();
	$tr3->free();
	$minDel = $tr4[0];

	$kolYears = (int)($kolDelF / $planz["delkol"]) + ($kolDelF % $planz["delkol"] == 0 ? 0 : 1); // количество курсов
	$kolDel = $kolYears * $planz["delkol"]; // кол-во триместров
	$fieldsInPlanc = 7;
	
	$windel = $db->query("select ndel, kol from windel where windel.id_plan=".$planz["id"]." order by ndel");

	echo "$kolYears курса, $kolDel триместров<br>курсов факт: ".$kolDelF / $planz["delkol"]." триместров факт: $kolDelF";
	echo "<br>Расчет:<table border=1>
	<tr>
		<td rowspan=7>№</td>
		<td rowspan=7>Дисципліна</td>
		<td rowspan=7>назва кафедри</td>
		<td rowspan=7>Кредити ECTS</td>
		<td colspan=6>Розподіл за триместрами</td>
		<td colspan=6>Аудиторних годин</td>
		<td colspan=".$fieldsInPlanc * $kolDel.">Розподіл аудиторних годин по курсах та триместрах/симестрах</td>
		<td colspan=2>Годин</td>
		<td rowspan=7>%</td>
		<td rowspan=7>Перевірка</td>
		<td rowspan=7>Кредити вичитано</td>
		<td rowspan=7>Перезараховано</td>
	</tr>
	<tr>
		<td rowspan=6>Екзамен</td>
		<td rowspan=6>Залік</td>
		<td rowspan=6>КП</td>
		<td rowspan=6>КР</td>
		<td rowspan=3 colspan=2>РГЗ</td>
		<td rowspan=6>Всього</td>
		<td rowspan=6>Лекції</td>
		<td rowspan=6>Групові</td>
		<td rowspan=6>Півгрупові</td>
		<td rowspan=6>% аудиторних годин</td>
		<td rowspan=6>Перевірка</td>";
	
	for($i = 0; $i < $kolYears; $i++) // номера курсов
		echo "<td colspan=".$fieldsInPlanc * $planz["delkol"].">".($minDel + $i)." курс</td>";
		
	echo "<td rowspan=6>Самостійна робота</td><td rowspan=6>Загальний обсяг</td>
	</tr>";
	
	echo "<tr>";
	for($i = 0; $i < $kolYears; $i++)
		echo "<td colspan=".$fieldsInPlanc * $planz["delkol"].">Триместр</td>";
	echo "</tr>";
	
	echo "<tr>";
	$tmp = 0;
	for($i = 1; $i <= $kolDel; $i++) // номера триместров
	{
		if($i <= $kolDelF)
		{
			$row = $windel->fetch_assoc();
			echo "<td colspan=$fieldsInPlanc>".$row["ndel"]."</td>";
			$tmp = $row["ndel"];
		}
		else
		{
			$tmp++;
			echo "<td colspan=$fieldsInPlanc>$tmp</td>";
		}
	}
	$windel->data_seek(0);
	echo "</tr>";

	echo "<tr><td rowspan=3>кол</td><td rowspan=3>распр</td>";
	for($i = 0; $i < $kolDel; $i++)
		echo "<td colspan=4>Тижнів</td><td rowspan=3>РГЗ</td><td rowspan=3>КР</td><td rowspan=3>Контроль</td>";
	echo "</tr>";
	
	echo "<tr>";
	for($i = 1; $i <= $kolDel; $i++) // недель в триместре
	{
		if($i <= $kolDelF)
		{
			$row = $windel->fetch_assoc();
			echo "<td colspan=4>".$row["kol"]."</td>";
		}
		else
			echo "<td colspan=4>&nbsp;</td>";
	}
	$windel->data_seek(0);
	echo "</tr>";
	
	echo "<tr>";
	for($i = 0; $i < $kolDel; $i++)
		echo "<td>Л</td><td>Г</td><td>П</td><td>&Sigma;</td>";
	echo "</tr>";
		
		//...
		
	echo "</table>";
	
	$windel->free();
	
// while($rowColumn = $columns->fetch_row())
			// $_REQUEST[$rowColumn[0]] = $rowUnit[$rowColumn[0]];
	
/*SELECT 
  kurs, 
  GROUP_CONCAT(if(nweek = 1, kweek, NULL)) AS '1',
  GROUP_CONCAT(if(nweek = 2, kweek, NULL)) AS '2',
  GROUP_CONCAT(if(nweek = 3, kweek, NULL)) AS '3'
FROM plang
where id_plan=3 and nweek<=3
GROUP BY kurs*/
	
	$db->close();
	echo "</table>";
?>
</body>
</html>
