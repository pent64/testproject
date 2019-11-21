<?php
namespace Test;

class DB {

	private $mysqli;

	private $errorno;

	private $error;

	private $table_name;

	private $results;

	private $result;

	public $id;

	public function __construct($table_name,$id) {
		global $db;
		if ($db->mysqli == null) {
			$db->connect();
		}
		$this->mysqli = $db->mysqli;
		$this->table_name = $table_name;
		if ($id != 0) {
			if (!$this->getById($id)) {
				throw new \Exception('No task with current id');
			}
		} else {
			foreach ($this->fields as $field) {
				$this->set($field,'');
			}
		}
	}

	public function get($name) {
		if (isset($this->{$name})) {
			return $this->{$name};
		}
		return false;
	}

	public function set($name, $val) {
		if ($name == 'email') {
			if (!filter_var($val,FILTER_VALIDATE_EMAIL)) {
				return false;
			}
		}
		$this->{$name} = $val;
		return true;
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

	public function getById($id) {
		$sql = "SELECT * FROM ".DB_PREFIX.$this->table_name." WHERE `id` = ?";
		return $this->selectOne($sql,[$id]);
	}

	public function getBy($field, $val) {
		$sql = "SELECT * FROM ".DB_PREFIX.$this->table_name." WHERE `".$field."` = ?";
		return $this->selectOne($sql,[$val]);
	}

	public function select($query){
		$result = $this->mysqli->query($query);
		if (!$result) {
			$this->error = $this->mysqli->error;
			$this->errorno = $this->mysqli->errno;
			return false;
		}
		if ($result->num_rows !== 0) {
			return $result->fetch_assoc();
		}
		return false;
	}

	protected function update($fields,$values,$id){
		$sql = "UPDATE ".DB_PREFIX.$this->table_name." SET ";
		$tmp = [];
		foreach ($fields as $field) {
			$tmp[] = "`".$field."` = ? ";
		}
		$sql .= implode(',',$tmp);
		$sql .= " WHERE `id` = ?";
		$res = false;
		if ($stmp = $this->mysqli->prepare($sql)) {
			$types = 's';
			$vals = [];
			foreach ($values as $value) {
				$types .= 's';
				$vals[] = $value;
			}
			$vals[] = $id;
			$stmp->bind_param($types,...$vals);
			$res = $stmp->execute();
			$stmp->close();
		}
		return $res;
	}

	protected function insert($fields, $values){
		$sql = "INSERT INTO ".DB_PREFIX.$this->table_name." (";
		$tmp = [];
		foreach ($fields as $field) {
			$tmp[] = "`".$field."`";
		}
		$sql .= implode(',',$tmp).") VALUES (";
		$tmp = [];
		foreach ($values as $value) {
			$tmp[] = "?";
		}
		$sql .= implode(',',$tmp).")";
		$last_id = false;
		if ($stmp = $this->mysqli->prepare($sql)) {
			$types = [];
			foreach ($values as $value) {
				$types[] = "s";
			}
			$stmp->bind_param(implode('',$types),...$values);
			$stmp->execute();
			$last_id = $stmp->insert_id;
			$stmp->close();
		}
		return $last_id;
	}

	public function delete(){
		$sql = "DELETE FROM `".DB_PREFIX.$this->table_name."` WHERE `id` = ?";
		if ($stmp = $this->mysqli->prepare($sql)) {
			$stmp->bind_param( 'd', $this->id );
			$stmp->execute();
			$stmp->close();
		}
		if ($this->mysqli->error) {
			return false;
		}
		return true;
	}

	public function selectOne($query,$vars){
		if ($stmp = $this->mysqli->prepare($query)) {
			$types = [];
			foreach ($vars as $value) {
				$types[] = "s";
			}
			$stmp->bind_param(implode('',$types),...$vars);
			$stmp->execute();
			$result = $stmp->get_result();
			while ($row = $result->fetch_assoc()) {
				$this->set('id',$row['id']);
				foreach ($this->fields as $value) {
					$this->set($value,$row[$value]);
				}
				break;
			}
			$stmp->fetch();
			$stmp->close();
			return true;
		}
		return false;
	}

	public function save() {
		$data = [];
		foreach ($this->fields as $field) {
			$data[] = $this->{$field};
		}
		if ($this->id == 0) {
			$this->id = $this->insert($this->fields,$data);
			return $this->id;
		} else {
			return $this->update($this->fields,$data,$this->id);
		}
	}
}