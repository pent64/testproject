<?php
namespace Test\Model;

use Test\Collection;

class Tasks extends Collection {
	private $results;

	public function __construct() {
		$table_name = 'tasks';
		$this->results = [];
		parent::__construct( $table_name );
	}

	public function getByUser($user_id) {
		$result = $this->select([['user','=',$user_id],['parent','=','0']],['date','DESC']);
		while ($row = $result->fetch_assoc()) {
			$this->results[] = new Task($row['id']);
		}
		return $this->results;
	}

	public function getByParent($parent_id,$user_id) {
		$result = $this->select([['user','=',$user_id],['parent','=',$parent_id]],['date','DESC']);
		while ($row = $result->fetch_assoc()) {
			$this->results[] = new Task($row['id']);
		}
		return $this->results;
	}
}