<?php
require_once("DBConfig.php");

class DatabaseConnection {
	# Create a singleton to store the connection for reuse
	private static $singleton,
				   $connec;
	const WHERE_CLAUE_PARAM_PREFIX = 'pref_';
	# save connection to singleton and return itself (the full object)
	public function __construct() {
		# If your singleton is not set
		if(!isset(self::$singleton))
			# assign it this class
			self::$singleton = $this;
		# return this class
		return self::$singleton;
	}
	# This is a connection method because your __construct
	# is not able to return the $pdo connection
	public function connection($host='hostname',$username='username',$password='password',$database='database') {
		# In the connection, you can assign the PDO to a static
		# variable to send it back if it's already set
		if(self::$connec instanceof \PDO)
			return self::$connec;
		# If not already a PDO connection, try and make one
		try {
			# PDO settings you can apply at connection time
			$opts = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES => false);
			# Assign your PDO Conneciton here.
			self::$connec =  new PDO("mysql:host={$host};dbname={$database}",$username,$password,$opts);
			# Return the connection
			return self::$connec;
		}
		catch (PDOException $e){
			exit('Database error');
		}   
	}
	
	public function connectionFromConfigPHPFile() {
		return $this->connection(DBConfig::$db_host, DBConfig::$db_username, DBConfig::$db_password, DBConfig::$db_name);
	}
	
    function getTableName() {
		if(array_key_exists('t', $_GET))
			return $_GET['t'];
		else if(array_key_exists('table', $_GET))
			return $_GET['table'];
		else if(array_key_exists('tab', $_GET))
			return $_GET['tab'];
		else
		    throw new Exception("No table query parameter is sent in query string");
	}
	public function getSelectSql() {
		$tableName = $this->getTableName();
		$this->validateTable($tableName);
		$sql = "SELECT * FROM `".$tableName."` ";
		return $this->addWhereClauseToQuery($sql);
	}
	
	public function getInsertSql($associativeArray) {
	    $tableName = $this->getTableName();
		$this->validateTable($tableName);
		$this->validateColumnArray(array_keys($associativeArray));
		$sql = sprintf("Insert into `".$tableName."` (%s) values (%s)",
			implode(", ", array_keys($associativeArray)),
			":" . implode(", :", array_keys($associativeArray))
		);
		return $sql;
	}
	public function getUpdateSql($associativeArray) {
	    $tableName = $this->getTableName();
		$this->validateTable($tableName);
		$this->validateColumnArray(array_keys($associativeArray));
		$sql = "Update `".$tableName."` set ";
		foreach(array_keys($associativeArray) as $columnName) {
			$sql = $sql." ".$columnName."=:".$columnName.",";
		}
		#remove last comma
		$sql = substr($sql, 0, -1);
		return $this->addWhereClauseToQuery($sql);
	}
	function getColumnKey($i) {
		if(array_key_exists('c'.$i, $_GET))
			return $_GET['c'.$i];
		if(array_key_exists('col'.$i, $_GET))
			return $_GET['col'.$i];
		if(array_key_exists('column'.$i, $_GET))
			return $_GET['column'.$i];
		return null;
	}
	function getColumnValue($i) {
		if(array_key_exists('v'.$i, $_GET))
			return $_GET['v'.$i];
		if(array_key_exists('val'.$i, $_GET))
			return $_GET['val'.$i];
		if(array_key_exists('value'.$i, $_GET))
			return $_GET['value'.$i];
		return null;
	}
	
	function getColumnKeyAndColumnValue($i) {
		$col = $this->getColumnKey($i);
		$val = $this->getColumnValue($i);
		return array($col, $val);
	}
	public function addWhereClauseToQuery($sql) {
		$firstColumnValue = $this->getColumnKeyAndColumnValue(1);
		if(is_null($firstColumnValue[0])) {
			return $sql;
		}
		#When value is null. 'Where col_name = null' doesn't work. Need 'Where col_name is null'
		if(! is_null($firstColumnValue[1])) {
		    $whereClause = ' where '.$firstColumnValue[0].'=:'.self::WHERE_CLAUE_PARAM_PREFIX.$firstColumnValue[0];
		} else {
		    $whereClause = ' where '.$firstColumnValue[0].' is NULL';
		}
		for($i = 2 ; $i<31000; $i++) {
			$cv = $this->getColumnKeyAndColumnValue($i);
			if(! is_null($cv[0])) {
			    if(! is_null($cv[1])) {
				    $whereClause = $whereClause.' and '.$cv[0].'=:'.self::WHERE_CLAUE_PARAM_PREFIX.$cv[0];
			    } else {
			        $whereClause = $whereClause.' and '.$cv[0].' is NULL';
			    }
			} else {
				break;
			}
		}
		return $sql.$whereClause;
	}
	public function remove0123etcFromResult($result) {
		for($j = 0 ; $j<31000; $j++) {
			if(array_key_exists($j, $result)) {
				for($i = 0 ; $i<31000; $i++) {
					if(array_key_exists($i, $result[$j]))
						unset($result[$j][$i]);
					else
						break;
				}
			} else {
				break;
			}
		}
		return $result;
	}
	public function bindParam($statement) {
		for($i = 1 ; $i<31000; $i++) {
			$cv = $this->getColumnKeyAndColumnValue($i);
			if(! is_null($cv[0])) {
			    CommonUtil::d($cv[0].' '.(is_null($cv[1])? 'NULL' : $cv[1]));
			    #No need to bind NULL values. They're already in the query during Where clause preparation
				if(! is_null($cv[1])) { 
				    $statement->bindParam(':'.self::WHERE_CLAUE_PARAM_PREFIX.$cv[0], $cv[1] );
				}
			} else {
				break;
			}
		}
	}
	public function bindParamForUpdateOrInsert($statement, $obj) {
		$this->bindParam($statement);
		foreach(array_keys($obj) as $k) {
			$statement->bindParam(':'.$k, $obj[$k]);
		}
	}
	public function get() {
		$sql = $this->getSelectSql();
		$statement = $this->executeSelectQuery($sql);
		$result = $statement->fetchAll();
		return $this->remove0123etcFromResult($result);
	}
	public function create() {
		$jsonData = file_get_contents("php://input");
		CommonUtil::debug("Post data : ".$jsonData, "verbose");
		$postData = json_decode($jsonData, true);
		return $this->createUsingassociativeArrayObject($postData);
	}
	public function createUsingassociativeArrayObject($associativeArray) {
		$sql = $this->getInsertSql($associativeArray);
		return $this->executeQueryPersistingObject($sql, $associativeArray);
	}
	public function update() {
		$jsonData = file_get_contents("php://input");
		CommonUtil::debug("Post data : ".$jsonData, "verbose");
		$postData = json_decode($jsonData, true);
		return $this->updateUsingassociativeArrayObject($postData);
	}
	public function updateUsingassociativeArrayObject($associativeArray) {
		$sql = $this->getUpdateSql($associativeArray);
		return $this->executeQueryPersistingObject($sql, $associativeArray);
	}
	public function executeSelectQuery($sql) {
	    $sql = $sql.";";
		CommonUtil::debug($sql, "query");
		$con = $this->connectionFromConfigPHPFile();
		$statement = $con->prepare($sql);
		$this->bindParam($statement);
		$statement->execute();
		return $statement;
	}
	public function executeQueryPersistingObject($sql, $obj) {
		$sql = $sql.";";
		CommonUtil::debug($sql, "query");
		$con = $this->connectionFromConfigPHPFile();
		$statement = $con->prepare($sql);
		$this->bindParamForUpdateOrInsert($statement, $obj);
		# Either call execute($obj), obj inside execute or bind the parameters but not both.
		return $statement->execute();
	}
	public function validateTable($tableName) {
		if( ! in_array($tableName, DBConfig::$allowed_table_names)) {
			throw new Exception("Table ".$tableName." doesn't exist or if it does. It's not accessible. Change DBConfig.php");
		}
	}
	public function validateColumn($columnName) {
		if( ! in_array($columnName, DBConfig::$allowed_column_names)) {
			throw new Exception("Column ".$columnName." doesn't exist or if it does. It's not accessible. Change DBConfig.php");
		}
	}

	public function validateColumnArray($columnNames) {
		foreach($columnNames as $c) {
			$this->validateColumn($c);
		}
	}
	
	
}
