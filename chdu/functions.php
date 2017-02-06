<?php
	require("settings.php");

	$db = new mysqli($db_host, $db_username, $db_password, $db_database);
	if (mysqli_connect_errno())
	{
	    printf("Не удалось подключиться: %s\n", mysqli_connect_error());
	    exit();
	}
	if (!$db->set_charset($db_encoding))
	    printf("Ошибка при загрузке набора символов utf8: %s\n", $db->error);

	if(!isset($_SESSION["dateSelected"]))
		$_SESSION["dateSelected"] = date("Ymd");

	$_SESSION["userid"] = 'root';
	$_SESSION["sid"] = 0;

	function dateBetween($table = "", $onDate = "")
	{
		if($table != "")
			$table = $table.".";
		if($onDate == "")
			$onDate = $_SESSION["dateSelected"];
		return "str_to_date('".$onDate."', '%Y%m%d') between ".$table."date_from and ".$table."date_to";
	}

	function setRequest($table, $PK)
	{
		$columns = $GLOBALS["db"]->query("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_CATALOG is null and TABLE_SCHEMA='chdu' and TABLE_NAME='$table' and COLUMN_KEY<>'PRI'");
		$query = "select * from $table where 1=1";//uid=".$_REQUEST["uid"]." and npp=".$_REQUEST["npp"]
		foreach($PK as $key => $value)
			$query = $query." and $key='$value'";
		echo "<br><br>$query<br><br>";
		$unit = $GLOBALS["db"]->query($query);
		$rowUnit = $unit->fetch_assoc();
		while($rowColumn = $columns->fetch_row())
			$_REQUEST[$rowColumn[0]] = $rowUnit[$rowColumn[0]];
		$unit->free();
		$columns->free();
	}
	
	function inputSelect($name, $table, $codeField, $viewField, $whereCondition, $selectedCode="", $addEmpty=False, $htmloptions="")
	{
		$rows = $GLOBALS["db"]->query("select $codeField, $viewField from $table where $whereCondition");
		echo "<select name='$name' $htmloptions>";
		if($addEmpty)
			echo "<option value=''".("" == $selectedCode?" selected":"")."></option>";
		while($row = $rows->fetch_row())
			echo "<option value='".$row[0]."'".($row["0"]==$selectedCode?" selected":"").">".$row[1]."</option>";
		echo "</select>";
	}
	
	function strNull($val)
	{
		return $val == "" ? "null" : "'".$val."'";
	}
	
	function numNull($val)
	{
		return $val == 0 ? "null" : $val;
	}
	
	function numNotNull($val)
	{
		return $val == "" ? "0" : $val;
	}

	
	
	
	

	class hidden
	{
		private $name;
		
		public $value;
		
		public function __construct($name, $value)
		{
			$this->name = $name;
			$this->value = $value;
		}
		
		public function getName() { return $this->name; }
		
		public function show()
		{
			echo "<input type='hidden' name='$this->name' value='$this->value'/>";
		}
	}
	
	class text
	{
		private $name;
		
		public $value, $label, $required, $pattern;
		
		public function __construct($name, $value)
		{
			$this->name = $name;
			$this->value = $value;
		}
		
		public function show()
		{
			echo "<tr><td>$this->label:</td><td><input type='text' name='$this->name' value='$this->value'"
								.($this->required ? " required" : "")
								.($this->pattern == "" ? "" : " pattern='$this->pattern'")
								."/></td></tr>";
		}
	}
	
	class select
	{
		private $name;
		
		public $value, $label, $required, $table, $codeField, $viewField, $whereCondition;
		
		public function __construct($name, $value)
		{
			$this->name = $name;
			$this->value = $value;
		}
		
		public function show()
		{
			$rows = $GLOBALS["db"]->query("select $this->codeField, $this->viewField from $this->table".($this->whereCondition == "" ? "" : " where $this->whereCondition"));
			echo "<tr><td>$this->label:</td><td><select name='$this->name'".($this->required ? " required" : "").">";
			echo "<option/>";
			while($row = $rows->fetch_row())
				echo "<option value='".$row[0]."'".($row[0] == $this->value ? " selected" : "").">".$row[1]."</option>";
			echo "</select></td></tr>";
			$rows->free();
		}
	}
	
	class FormHelper
	{
		private $table, $action;
		//private $columnsInfo;
		
		//public $inTable;
		public $controls;
		
		public function __construct($table, $action)
		{
			//$this->inTable = false;
		
			$this->table = $table;
			$this->action = $action;
			$columnsInfo = $GLOBALS["db"]->query("SELECT COLUMNS.COLUMN_NAME, COLUMNS.COLUMN_KEY, COLUMNS.IS_NULLABLE, COLUMNS.DATA_TYPE, COLUMNS.CHARACTER_MAXIMUM_LENGTH, COLUMNS.EXTRA, addColumns.I_REQUIRED, addColumns.I_TEXT, addColumns.I_TYPE, addColumns.I_PATTERN, addColumns.S_TABLE, addColumns.S_CODEFIELD, addColumns.S_VIEWFIELD, addColumns.S_WHERECONDITION 
												FROM information_schema.COLUMNS 
												left join chdu.addColumns on addColumns.TABLE_NAME='$this->table' and addColumns.COLUMN_NAME=COLUMNS.COLUMN_NAME 
												WHERE COLUMNS.TABLE_SCHEMA='chdu' and COLUMNS.TABLE_NAME='$this->table'");
			$this->controls = array();
			while($column = $columnsInfo->fetch_assoc())
			{
				switch($column["I_TYPE"])
				{
					case "hidden":
						$this->controls[$column["COLUMN_NAME"]] = new hidden($column["COLUMN_NAME"], $_REQUEST[$column["COLUMN_NAME"]]);
					break;
					case "text":
						$control = new text($column["COLUMN_NAME"], $_REQUEST[$column["COLUMN_NAME"]]);
						$control->label = $column["I_TEXT"];
						$control->required = $column["I_REQUIRED"] == "+";
						$control->pattern = $column["I_PATTERN"];
						$this->controls[$column["COLUMN_NAME"]] = $control;
					break;
					case "select":
						$control = new select($column["COLUMN_NAME"], $_REQUEST[$column["COLUMN_NAME"]]);
						$control->label = $column["I_TEXT"];
						$control->required = ($column["I_REQUIRED"] == "+");
						$control->table = $column["S_TABLE"];
						$control->codeField = $column["S_CODEFIELD"];
						$control->viewField = $column["S_VIEWFIELD"];
						$control->whereCondition = $column["S_WHERECONDITION"];
						$this->controls[$column["COLUMN_NAME"]] = $control;
					break;
				}
			}
			$columnsInfo->free();
		}

		public function show()
		{
			echo "<form action='$this->action' method='".$GLOBALS["form_method"]."'>";
			echo "<table>";
			foreach($this->controls as $key => $value)
				$value->show();
			echo "</table>";
			echo "<input type='hidden' name='mode' value='".$_REQUEST["mode"]."'/>";
			echo "<input type='hidden' name='action' value='1'/>";
			echo "<br/><input type='submit' value='".$GLOBALS["editmode"][$_REQUEST["mode"]]."'></input></form>";
		}
	}
	
	class editHelper
	{
		private $table;
		private $columnsInfo;
		
		public function __construct($table)
		{
			$this->table = $table;
			$this->columnsInfo = $GLOBALS["db"]->query("SELECT COLUMN_NAME, COLUMN_KEY, IS_NULLABLE, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH, EXTRA "
												."FROM information_schema.COLUMNS "
												."WHERE TABLE_SCHEMA='chdu' and TABLE_NAME='$this->table'");
		}
		
		function __destruct()
		{
			$this->columnsInfo->free();
		}
		
		public function getCustomForm($action = "")
		{
			return new FormHelper($this->table, ($action == "" ? $this->table."e.php" : $action));
		}
		
		private function wherePkValue($value, $DATA_TYPE)
		{
			if($DATA_TYPE == "int" || $DATA_TYPE == "bigint" || $DATA_TYPE == "decimal")
				return $value;
			else
				return "'$value'";
		}
		
		private function insertColumnValue($value, $IS_NULLABLE, $DATA_TYPE, $CHARACTER_MAXIMUM_LENGTH)
		{
			switch($DATA_TYPE)
			{
				case "varchar":
					$len = strlen($value);
					$len = $len < $CHARACTER_MAXIMUM_LENGTH ? $len : $CHARACTER_MAXIMUM_LENGTH;
					if($IS_NULLABLE == "YES")
						return $value == "" ? "null" : $this->wherePkValue(substr($value, 0, $len), $DATA_TYPE);
					else
						return $this->wherePkValue(substr($value, 0, $len), $DATA_TYPE);
				break;
				case "datetime":
					if($IS_NULLABLE == "YES")
						return $value == "" ? "null" : $this->wherePkValue($value, $DATA_TYPE);
					else
						return $this->wherePkValue($value, $DATA_TYPE);
				break;
				case "int":
					if($IS_NULLABLE == "YES")
						return $value == 0 || $value == "" ? "null" : $this->wherePkValue($value, $DATA_TYPE);
					else
						return $value == "" ? "0" : $this->wherePkValue($value, $DATA_TYPE);
				break;
				default : return $this->wherePkValue($value, $DATA_TYPE);
			}
		}
		
		public function setRequestByPk()
		{
			//$columns = $GLOBALS["db"]->query("SELECT COLUMN_NAME, COLUMN_KEY, IS_NULLABLE, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH FROM information_schema.COLUMNS WHERE TABLE_CATALOG is null and TABLE_SCHEMA='chdu' and TABLE_NAME='$this->table'");
			$this->columnsInfo->data_seek(0);
			$pkExistsInRequest = true;
			$PK = "1=1";
			$fields = "";
			while($column = $this->columnsInfo->fetch_assoc())
			{
				if($column["COLUMN_KEY"] == "PRI")
				{
					if($pkExistsInRequest = $pkExistsInRequest && isset($_REQUEST[$column["COLUMN_NAME"]]))
						$PK = "$PK and ".$column["COLUMN_NAME"]."=".$this->wherePkValue($_REQUEST[$column["COLUMN_NAME"]], $column["DATA_TYPE"]);
				}
				else
					$fields = "$fields, ".$column["COLUMN_NAME"];
			}
			if($pkExistsInRequest)
			{
				$res = $GLOBALS["db"]->query("select ".substr($fields, 2, strlen($fields))." from $this->table where $PK");
				if($res->num_rows > 0)
				{
					$this->columnsInfo->data_seek(0);
					$row = $res->fetch_assoc();
					while($rowColumn = $this->columnsInfo->fetch_assoc())
						if($rowColumn["COLUMN_KEY"] != "PRI")
							$_REQUEST[$rowColumn["COLUMN_NAME"]] = $row[$rowColumn["COLUMN_NAME"]];
					$res->free();
				}
				else
					echo "<br>no data with this PK<br>";
			}
			else
				echo "<br>PK dont exists in request<br>";
			//$columns->free();
		}
		
		public function insertRequestToDb()
		{
			//$info = $GLOBALS["db"]->query("SELECT COLUMN_NAME, IS_NULLABLE, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH FROM information_schema.COLUMNS WHERE TABLE_CATALOG is null and TABLE_SCHEMA='chdu' and TABLE_NAME='$this->table' and EXTRA<>'auto_increment'");
			$this->columnsInfo->data_seek(0);
			$columns = "";
			$values = "";
			while($rowColumn = $this->columnsInfo->fetch_assoc())//$info->fetch_row
			{
				if($rowColumn["EXTRA"] == "auto_increment")
					continue;
				$columns = $columns.", ".$rowColumn["COLUMN_NAME"];
				$val = "";
				if($rowColumn["COLUMN_NAME"] == "sid_a")
					$val = $_SESSION["sid"];
				else if($rowColumn["COLUMN_NAME"] == "date_a")
					$val = "now()";
				else $val = $this->insertColumnValue($_REQUEST[$rowColumn["COLUMN_NAME"]], $rowColumn["IS_NULLABLE"], $rowColumn["DATA_TYPE"], $rowColumn["CHARACTER_MAXIMUM_LENGTH"]);
				$values = "$values, $val";
			}
			$columns = substr($columns, 2, strlen($columns));
			$values = substr($values, 2, strlen($values));

			$query = "INSERT INTO $this->table ($columns) values($values);";
			$GLOBALS["db"]->query($query);
			//$info->free();
		}
		
		public function updateDbFromRequest()
		{
			//$info = $GLOBALS["db"]->query("SELECT COLUMN_NAME, COLUMN_KEY, IS_NULLABLE, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH FROM information_schema.COLUMNS WHERE TABLE_CATALOG is null and TABLE_SCHEMA='chdu' and TABLE_NAME='$this->table'");
			$this->columnsInfo->data_seek(0);
			$pkExistsInRequest = true;
			$PK = "1=1";
			$upd = "";
			while($column = $this->columnsInfo->fetch_assoc())//$info->fetch_row()
			{
				if($column["COLUMN_KEY"] == "PRI")
				{
					if($pkExistsInRequest = $pkExistsInRequest && isset($_REQUEST[$column["COLUMN_NAME"]]))
						$PK = "$PK and ".$column["COLUMN_NAME"]."=".$this->wherePkValue($_REQUEST[$column["COLUMN_NAME"]], $column["DATA_TYPE"]);
				}
				else
				{
					$upd = "$upd, ".$column["COLUMN_NAME"]."=".$this->insertColumnValue($_REQUEST[$column["COLUMN_NAME"]], $column["IS_NULLABLE"], $column["DATA_TYPE"], $column["CHARACTER_MAXIMUM_LENGTH"]);
				}
			}
			if($pkExistsInRequest)
			{
				$query = "update $this->table set ".substr($upd, 2, strlen($upd))." where $PK";
				echo "<br>$query<br>";
			}
			else
				echo "<br>PK dont exists in request<br>";
		}
	}
?>
