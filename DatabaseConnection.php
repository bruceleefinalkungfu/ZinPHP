<?php
require_once("DBConfig.php");

class DatabaseConnection {
	# Create a singleton to store the connection for reuse
	private static $singleton,
				   $connec;
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
	
	public function getSelectSql($tableName) {
		$this->validateTable($tableName);
		$sql = "SELECT * FROM `".$tableName."` ";
		return $sql;
	}
	
	public function getInsertSql($tableName, $associativeArray) {
		$this->validateTable($tableName);
		validateColumnArray(array_keys($associativeArray));
		$sql = sprintf("Insert into `".$tableName."` (%s) values (%s)",
			implode(", ", array_keys($associativeArray)),
			":" . implode(", :", array_keys($associativeArray))
		);
		return $sql;
	}
	
	public function get($tableName) {
		$sql = $this->getSelectSql($tableName);
		$statement = $this->executeQuery($sql);
		return $statement->fetchAll();
	}
	public function getWhereColumnValsAre($tableName, $columnNames, $columnVals) {
		$sql = $this->getSelectSql($tableName);
		$statement = $this->executeQuery($sql);
		return $statement->fetchAll();
	}
	public function create($tableName, $associativeArray) {
		$sql = $this->getInsertSql($tableName);
		$statement = $this->executeQueryPersistingObject($sql, $associativeArray);
	}
	public function executeQuery($sql) {
	    $sql = $sql.";";
		CommonUtil::debug($sql, "query");
		$con = $this->connectionFromConfigPHPFile();
		$statement = $con->prepare($sql);
		$statement->execute();
		return $statement;
	}
	public function executeQueryPersistingObject($sql, $obj) {
		$sql = $sql.";";
		CommonUtil::debug($sql, "query");
		$con = $this->connectionFromConfigPHPFile();
		$statement = $con->prepare($sql);
		$statement->execute($obj);
		return $statement;
	}
	public function validateTable($tableName) {
		if( ! in_array($tableName, DBConfig::$allowed_table_names)) {
			throw new Exception("Table ".$tableName." doesn't exist or if it does. It's not accessible");
		}
	}
	public function validateColumn($columnName) {
		if( ! in_array($columnName, DBConfig::$allowed_column_names)) {
			throw new Exception("Column ".$columnName." doesn't exist or if it does. It's not accessible");
		}
	}

	public function validateColumnArray($columnNames) {
		foreach($columnNames as $c) {
			$this->validateColumn($c);
		}
	}
	
	
}
