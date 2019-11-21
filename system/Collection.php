<?php
namespace Test;

use Test\Model\Task;

class Collection {

	private $mysqli;

	private $errorno;

	private $error;

	private $table_name;

	private $results;

	public function __construct($table_name) {
		global $db;
		$this->mysqli = $db->mysqli;
		$this->table_name = $table_name;
	}

	public function getAll() {
		$sql = 'SELECT * FROM '.DB_PREFIX.$this->table_name;
		$res = $this->select($sql);
		if ($res) {
			$result = [];
			foreach ($res as $r) {
				$result[] = (object)$r;
			}
			$this->results = $result;
			return $this->results;
		} else {
			return false;
		}
	}

	public function select($where = [], $sort = []){
		$sql = 'SELECT * FROM '.DB_PREFIX.$this->table_name;
		if (!empty($where)){
			$sql .= ' WHERE ';
			$tmp = [];
			foreach ($where as $w) {
				$tmp[] = "`".$w[0]."` ".$w[1]." ?";
			}
			$sql .= implode(' AND ',$tmp);
		}
		$sql .= ' ORDER BY '.$sort[0].' '.$sort[1];
		$res = false;
		if ($stmt = $this->mysqli->prepare($sql)) {
			$types = '';
			$vals  = [];
			foreach ( $where as $w ) {
				$types  .= 's';
				$vals[] = $w[2];
			}
			$stmt->bind_param( $types, ...$vals );
			$stmt->execute();
			$res = $stmt->get_result();
			$stmt->fetch();
			$stmt->close();
		}
		if ($res !== false) {
			return $res;
		} else {
			return false;
		}
	}

	function refValues($arr){
		if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
		{
			$refs = array();
			foreach($arr as $key => $value)
				$refs[$key] = &$arr[$key];
			return $refs;
		}
		return $arr;
	}
}