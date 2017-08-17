<?php
/**
 * This file needs for MySQL request.
 * Use SINGLETON.
 */
class DB_Mysqli {
	/**
	 * Default Location DB
	 * @var int
	 */
	const DEFAULT_DB_LOCATION = 'localhost';
	
	/**
	 * Default User name DB
	 * @var int
	 */
	const DEFAULT_DB_USER     = 'root';
	
	/**
	 * Default User password DB
	 * @var int
	 */
	const DEFAULT_DB_PASSWORD = '';
	
	/**
	 * Default name DB
	 * @var int
	 */
	const DEFAULT_DB_NAME     = 'light_it';
	
	/**
	 * Default port
	 * @var int
	 */
	const DEFAULT_PORT = 3306;

	/**
	 * Table name escaper
	 * @var string
	 */
	const TABLE_ESCAPER = '`';

	/**
	 * Value escaper
	 * @var unknown_type
	 */
	const VALUE_ESCAPER = '"';

	/**
	 * Connection
	 * @var mysqli
	 */
	private $conn;
	/**
	 * Instance (SINGLETON)
	 * @var mysqli
	 */
	static private $instance = null;
	/**
	 * Fetch mode
	 * @var unknown_type
	 */
	private $fetch_mode = MYSQLI_ASSOC;


	/**
	 * Constructor SINGLETON
	 *
	 * Connect
	 * @param string $host
	 * @param string $user
	 * @param string $paswd
	 * @param string $db
	 * @param int $port
	 * @return mixed
	 */
	private function __construct($paswd = self::DEFAULT_DB_PASSWORD,
								 $host  = self::DEFAULT_DB_LOCATION,
								 $user  = self::DEFAULT_DB_USER,
								 $db    = self::DEFAULT_DB_NAME,
								 $port =  self::DEFAULT_PORT) {
		$port = (int) $port;
		@$this->conn = new mysqli($host, $user, $paswd, $db, $port);
		@$this->conn->set_charset("utf8");
		if ($this->conn->connect_errno) {
			echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}
	}
	/**
	 * Clone
	 */
	private function __clone (){}

	/**
	 * Get class exemplar (SINGLETON)
	 */
	static function getInstance() {
		if(self::$instance == null) {
			self::$instance = new DB_Mysqli(config::$db_password);
		}
		return self::$instance;
	}

	/**
	 * Select database
	 * @param $db db name
	 * @return mixed
	 */
	public function selectDb($db) {
		$res = mysqli_select_db($this->conn, $db);
		return $res;
	}

	/**
	 * Escape string
	 * @param string $str
	 * @return string
	 */
	public function escapeString($str) {
		return mysqli_real_escape_string($this->conn, $str);
	}

	/**
	 * Escape table name
	 * @param string $table
	 * @return string
	 */
	public function escapeTableName($table) {
		return self::TABLE_ESCAPER.$this->escapeString($table).self::TABLE_ESCAPER;
	}

	/**
	 * Escape field name
	 * @param stirng $fld
	 * @return string
	 */
	public function escapeFieldName($fld) {
		return self::TABLE_ESCAPER.$this->escapeString($fld).self::TABLE_ESCAPER;
	}

	/**
	 * Escape field value
	 * @param $data
	 * @return string
	 */
	public function escapeFieldValue($data) {
		return self::VALUE_ESCAPER.$this->escapeString($data).self::VALUE_ESCAPER;
	}

	/**
	 * Fetch all rows
	 * @param $sql
	 * @return array
	 */
	public function fetchAll($sql) {
		$res = $this->query($sql);
		for($out = array(); $row = $res->fetch_array($this->fetch_mode); $out[] = $row);
		return $out;
	}

	/**
	 * Fetch one row
	 * @param $sql
	 * @return array
	 */
	public function fetchOne($sql) {
		$res = $this->query($sql);
		return $res->fetch_array($this->fetch_mode);
	}

	/**
	 * Fetch rows grouped by key
	 * @param $sql
	 * @param $key
	 * @param $arrayMode force Array mode
	 * @return array
	 */
	public function fetchGroupedArrayByKey($sql, $key, $arrayMode = true) {
		$res = $this->query($sql);
		$out = array();
		while($row = $res->fetch_array(MYSQLI_ASSOC)) {
			if($arrayMode) {
				if(!isset($out[$row[$key]])) {
					$out[$row[$key]] = array();
				}
				$out[$row[$key]][] = $row;
			} else {
				$out[$row[$key]] = $row;
			}
		}
		return $out;
	}

	/**
	 * Fetch one field from all rows and place to list
	 * @param string $sql
	 * @param string $fld
	 * @return array
	 */
	public function fetchOneFieldAll($sql, $fld) {
		$res = $this->query($sql);
		for($out = array(); $row = $res->fetch_array($this->fetch_mode); $out[] = $row[$fld]);
		return $out;
	}

	/**
	 * List one item
	 * @param $table
	 * @param $condition
	 * @return array
	 */
	public function listOne($table, $condition) {
		$table = $this->escapeTableName($table);
		$sql = "SELECT * FROM {$table} WHERE {$condition}";
		return $this->fetchOne($sql);
	}

	/**
	 * List items in table by condition
	 * @param string $table        table name
	 * @param string $condition    optional, if empty 1=1 is used
	 * @return array
	 */
	public function listAll($table, $condition = '1=1') {
		$table = $this->escapeTableName($table);
		$sql = "SELECT * FROM {$table} WHERE {$condition}";
		return $this->fetchAll($sql);
	}

	/**
	 * List by key single entry
	 * @param string $table   table name
	 * @param string $value   field value
	 * @param string $key     field name
	 * @return array
	 */
	public function listByKeyOne($table, $value, $key = 'id') {
		$table = $this->escapeTableName($table);
		$key = $this->escapeFieldName($key);
		$value = $this->escapeFieldValue($value);
		$sql = "SELECT * FROM {$table} WHERE {$key} = {$value}";
		return $this->fetchOne($sql);
	}
	/**
	 * List by key single entry, you choose field
	 * @param string $table   table name
	 * @param array $field    selected field name
	 * @param string $value   field value
	 * @param string $key     field name
	 * @return array
	 */
	public function chooseListByKeyOne($table, $field = '*', $value, $key ='id') {
		$table = $this->escapeTableName($table);
		$field = implode(",", $this->escapeFieldNames($field));
		$value = $this->escapeFieldValue($value);
		$key = $this->escapeFieldName($key);
		$sql = "SELECT {$field} FROM {$table} WHERE {$key} = {$value}";
		return $this->fetchOne($sql);
	}
	/**
	 * List by key all rows in table and sort, return last
	 * @param string $table   table name
	 * @param string $value   value of key field
	 * @param string $key     key field name
	 * @param string $add     additional conditions
	 * @return array
	 */
	public function listByKeyOneSortLast($table, $value, $key = 'id', $sort) {
		$table = $this->escapeTableName($table);
		$key = $this->escapeFieldName($key);
		$value = $this->escapeFieldValue($value);
		$sort = $this->escapeFieldValue($sort);
		$sql = "SELECT * FROM {$table} WHERE {$key} = {$value} ORDER BY {$sort} DESC LIMIT 1";
		return $this->fetchOne($sql);
	}
	/**
	 * List by key all rows in table
	 * @param string $table   table name
	 * @param string $value   value of key field
	 * @param string $key     key field name
	 * @param string $add     additional conditions
	 * @return array
	 */
	public function listByKeyAll($table, $value, $key = 'id', $add = '') {
		$table = $this->escapeTableName($table);
		$key = $this->escapeFieldName($key);
		$value = $this->escapeFieldValue($value);
		$sql = "SELECT * FROM {$table} WHERE {$key} = {$value} {$add}";
		return $this->fetchAll($sql);
	}

	/**
	 * List by key all rows in table and sort
	 * @param string $table   table name
	 * @param string $value   value of key field
	 * @param string $key     key field name
	 * @param string $add     additional conditions
	 * @return array
	 */
	public function listByKeyAllSort($table, $value, $key = 'id', $add = '', $sort) {
		$table = $this->escapeTableName($table);
		$key = $this->escapeFieldName($key);
		$value = $this->escapeFieldValue($value);
		$sort = $this->escapeFieldValue($sort);
		$sql = "SELECT * FROM {$table} WHERE {$key} = {$value} {$add} ORDER BY {$sort} DESC";
		return $this->fetchAll($sql);
	}

	/**
	 * List by key all rows in table and sort
	 * @param string $table   table name
	 * @param array $field    selected field name
	 * @param string $value   value of key field
	 * @param string $key     key field name
	 * @param string $value2   another multi value of key field
	 * @param string $key2     another key field name
	 * @return array
	 */
	public function listByKeyAllDoubleOptionMultiKey($table, $field = '*', $value, $key = 'id', $value2, $key2) {
		$table = $this->escapeTableName($table);
		$field = implode(",", $this->escapeFieldNames($field));
		$key = $this->escapeFieldName($key);
		$value = $this->escapeFieldValue($value);
		$key2 = $this->escapeFieldName($key2);
		$valueMulti = implode(",", $this->escapeFieldValue($value2));
		
		$sql = "SELECT {$field} FROM {$table} WHERE {$key} = {$value} AND {$key2} IN({$valueMulti})";
		return $this->fetchAll($sql);
	}
	public function chooseListByKeyAllJoinSort($table, $field = '*', $joinTable , $joinKeyTable, $joinKeyField, $joinValTable, $joinValField, $value, $key = 'id', $sortCol, $sortType = "DESC") {
		$table = $this->escapeTableName($table);
		foreach ($field as $key => &$value) {
			$handler = explode(".", $value);
			$value = $this->escapeTableName($handler['0']) .".".$this->escapeFieldName($handler['1']);
		}
		$field = implode(",", $field);
		$joinTable = $this->escapeTableName($joinTable);
		$joinKey = $this->escapeTableName($joinKeyTable) .".".$this->escapeFieldName($joinKeyField);
		$joinVal = $this->escapeTableName($joinValTable) .".".$this->escapeFieldName($joinValField);
		$key = $this->escapeFieldName($key);
		$value = $this->escapeFieldValue($value);
		$sortCol = $this->escapeFieldValue($sortCol);
		$sql = "SELECT {$field}
					FROM {$table}
						INNER JOIN {$joinTable} ON {$joinKey} = {$joinVal}
 					 WHERE {$key} = {$value} ORDER BY {$sortCol} {$sortType}";
 		return $this->fetchAll($sql);
	}

	/**
	 * List by key grouped
	 * @param string $table
	 * @param string $key
	 * @param bool $forcedArrayMode
	 * @return array
	 */
	public function listByKeyGrouped($table,  $key = 'id', $forcedArrayMode = false) {
		$table = $this->escapeTableName($table);
		$sql = "SELECT * FROM {$table}";
		return $this->fetchGroupedArrayByKey($sql, $key, $forcedArrayMode);
	}


	/**
	 * Escape field names
	 * @param array $arrNames
	 * @return array
	 */
	public function escapeFieldNames(array $arrNames) {
		$out = array();
		for ($i=0, $c = count($arrNames) ; $i<$c; $i++) {
			$out[] = $this->escapeFieldName($arrNames[$i]);
		}
		return $out;
	}

	/**
	 * Escape field values
	 * @param array $arrNames
	 * @return array
	 */
	public function escapeFieldValues(array $arrNames) {
		$out = array();
		for ($i=0, $c = count($arrNames) ; $i<$c; $i++) {
			if($arrNames[$i] !== 'LAST_INSERT_ID()') {
				$out[] = $this->escapeFieldValue($arrNames[$i]);
			} else {
				$out[] = $arrNames[$i];
			}
		}
		return $out;
	}


	/**
	 * Throw connect exception
	 * @throws DB_Exception
	 * @return void
	 */
	protected function throwConnectException() {
		// echo "MySQL connect error: (" . $this->conn->connect_errno . ") " . $this->conn->connect_error;
		// throw new DB_Exception($this->conn->connect_error);
	}

	/**
	 * Query - perform with throwing exception on error
	 * @param sting $sql query
	 * @throws DB_Exception
	 * @return mixed
	 */
	public function query($sql) {
		$res = $this->unsafeQuery($sql);
		if(!$res) {
			 $mysql_error['SQL_PARSE'] = "MySQL error: (" . $this->conn->errno . ") " . $this->conn->error;
			 $res = $mysql_error;
			// throw new DB_Exception($this->conn->error);
		}
		return $res;
	}

	/**
	 * Unsafe query - perform without error checking
	 * @param string $sql query
	 * @return mixed
	 */
	public function unsafeQuery($sql) {
		return $this->conn->query($sql);
	}

	/**
	 * Insert assoc array to table
	 * @param string $table
	 * @param array $data
	 * @param bool $replace
	 * @return mixed
	 */
	public function insertAssocOne($table, array $data, $replace = false) {
		$keys = $this->escapeFieldNames(array_keys($data));
		$keys = "(" . implode (",", $keys) . ")";
		$table = $this->escapeTableName($table);
		$sql = $replace ? "REPLACE INTO {$table} " : "INSERT INTO {$table} ";
		$values = $this->escapeFieldValues(array_values($data));
		$values = " VALUES (" . implode (",", $values) . ")";
		$sql .= $keys . $values;
		return $this->query($sql);
	}

	/**
	 * Insert several records to table
	 * @param string $table
	 * @param array $data
	 * @param bool $replace   use REPLACE INTO instead of INSERT INTO
	 * @return array
	 */
	public function insertAssocMultiple($table, array $data, $replace = false, $excludeFields = array()) {
		$table = $this->escapeTableName($table);
		$sql = $replace ? "REPLACE INTO {$table} " : "INSERT INTO {$table} ";
		$keys = array_keys($data[0]);
		$excluded = array();
		for($i = 0, $c = count($excludeFields); $i < $c; $i++) {
			$k = $excludeFields[$i];
			if(isset($keys[$k])) {
				$excluded [] = $k;
				unset($keys[$k]);    
			}            
		}
		
		$keys = $this->escapeFieldNames($keys);
		$sql .= " ( ";
		for($i = 0, $c = count($keys); $i<$c; $i++) {
			$sql .= $keys[$i];
			if($i!=$c-1) {
				$sql .= ",";
			}
		}
		$sql .= " ) VALUES ";
		for($i = 0, $c = count($data); $i<$c; $i++) {
			$row = $data[$i];
			for ($j = 0, $jc = count($excluded); $j<$jc; $j++) {
				unset($data[$excluded[$j]]);
			}
			$values = $this->escapeFieldValues(array_values($row));
			$sql .= "( ";
			for ($j = 0, $jc = count($values); $j < $jc; $j++) {
				$sql .= $values[$j];
				if($j != $jc-1) {
					$sql .= ",";
				}
			}
			$sql .= " )";
			if($i!=$c-1) {
				$sql .= ",";
			}
		}        
		return $this->query($sql);
	}


	/**
	 * Set table data by condition
	 * @param $table
	 * @param $data
	 * @param $condition
	 * @return mixed
	 */
	public function updateAssoc($table, array $data, $condition = '1=1') {
		$table = $this->escapeTableName($table);
		$set = array();
		foreach($data as $k=>$v) {
			$k = $this->escapeFieldName($k);
			$v = $this->escapeFieldValue($v);
			$set[] = $k . " = " . $v;
		}
		$set = implode(",", $set);
		$sql = "UPDATE {$table} SET {$set} WHERE {$condition}";
		return $this->query($sql);
	}


	/**
	 * Update entry by pk
	 * @param string $table
	 * @param array $data
	 * @param string $value
	 * @param string $key
	 * @return mixed
	 */
	public function updateAssocByKey($table, array $data, $value, $key = 'id') {
		$table = $this->escapeTableName($table);
		$key = $this->escapeFieldName($key);
		$value = $this->escapeFieldValue($value);
		$set = array();
		foreach($data as $k=>$v) {
			$k = $this->escapeFieldName($k);
			$v = $this->escapeFieldValue($v);
			$set[] = $k . " = " . $v;
		}
		$set = implode(",", $set);
		$sql = "UPDATE {$table} SET {$set} WHERE {$key} = {$value}";
		return $this->query($sql);
	}

	/**
	 * Update entry by pk
	 * @param string $table
	 * @param array $data
	 * @param string $value
	 * @param string $key
	 * @param string $value2
	 * @return mixed
	 */
	public function updateAssocByMultiKey($table, array $data, $value, $key = 'id', $value2, $key2 = 'id') {
		$table = $this->escapeTableName($table);
		$key = $this->escapeFieldName($key);
		$value = $this->escapeFieldValue($value);
		$set = array();
		foreach($data as $k=>$v) {
			$k = $this->escapeFieldName($k);
			$v = $this->escapeFieldValue($v);
			$set[] = $k . " = " . $v;
		}
		$set = implode(",", $set);
		$sql = "UPDATE {$table} SET {$set} WHERE {$key} = {$value} AND {key2} = {$value2}";
		return $this->query($sql);
	}


	/**
	 * Convert ids to string
	 * @param array|string $ids
	 * @return string
	 */
	public function idsToString($ids) {
		if(is_scalar($ids)) {
			return $this->escapeFieldValue(strval($ids));
		}
		$out = array();
		foreach ($values as $id) {
			$out .= $this->escapeFieldValue($id);
		}
		return implode(",", $out);
	}

	/**
	 * Ids equality condition
	 * @param mixed $ids   array or string
	 * @return string
	 */
	public function idsEqualCondition($ids) {
		$vals = $this->idsToString($ids);
		$condition = is_scalar($ids) ? " = {$vals} " : " IN ({$vals}) ";
		return $condition;
	}


	/**
	 * Delete items by id
	 * @param string $table
	 * @param mixed $ids      array or string
	 * @param string $key     key field
	 * @return mixed
	 */
	public function deleteById($table, $ids, $key = 'id') {
		$key = $this->escapeFieldName($key);
		$cond = $this->idsEqualCondition($ids);
		$table = $this->escapeTableName($table);
		$sql = "DELETE FROM {$table} WHERE {$key} {$cond}";
		return $this->query($sql);
	}

	/**
	 * Delete items by multi condition
	 * @param string $table
	 * @param mixed $ids      array or string
	 * @param string $key     key field
	 * @param mixed $ids2      array or string
	 * @param string $key2   key field
	 * @return mixed
	 */
	public function deleteByMultiID($table, $ids, $key = 'id', $ids2, $key2 = 'id') {
		$key = $this->escapeFieldName($key);
		$cond = $this->idsEqualCondition($ids);
		$key2 = $this->escapeFieldName($key2);
		$cond2 = $this->idsEqualCondition($ids2);
		$table = $this->escapeTableName($table);
		$sql = "DELETE FROM {$table} WHERE {$key} {$cond} AND {$key2} {$cond2}";
		return $this->query($sql);
	}

	/**
	 * Count items in table by condition
	 * @param string $table
	 * @param string $condition ex: "a>0"
	 * @return int
	 */
	public function simpleCount($table, $condition) {
		$sql = "SELECT count(*) AS `cnt` FROM {$table} WHERE {$condition}";
		$data = $this->fetchOne($sql);
		if(empty($data['cnt'])) {
			return 0;
		}
		return intval($data['cnt']);

	}
	
	public function lastInsertId() {
		$sql = "SELECT LAST_INSERT_ID() as `id`";
		$data = $this->fetchOne($sql);
		return $data['id'];        
	}
	public function customGetMessage($type='normal') {
		$sql = "SELECT `user`.`id_user`,
		`user`.`id`,
		`user`.`email`,
		`user`.`given_name`,
		`user`.`family_name`,
		`user`.`link`,
		`user`.`picture`,
		`message`.`id_message`,
		`message`.`message`,
		`message`.`time`,
		`message`.`parent_id_message`
			FROM `message` 
				INNER JOIN `user` ON `message`.`id_user` = `user`.`id_user` 
		WHERE `message`.`parent_id_message`";
		if($type=='normal') {
			$sql .="IS NULL ORDER BY `time` DESC";
		}
		else { 
			$sql .= "ORDER BY `time` ASC";
		}
		return $this->fetchAll($sql);
	}
}
