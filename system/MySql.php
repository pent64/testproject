<?php
namespace Test;

class MySql {
	public $mysqli;
	public function __construct() {
		$this->mysqli = new \mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if ($this->mysqli->connect_errno) {
			throw new \Exception('Database error');
		}
	}

	public function connect(){
		$this->mysqli = new \mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if ($this->mysqli->connect_errno) {
			throw new \Exception('Database error');
		}
	}

	public function __destruct() {
		$this->mysqli->close();
	}
}