<?php
namespace Test\Model;

use Test\DB;

class Task extends DB {

	public $fields;
	public $user;
	public $date;
	public $heading;
	public $content;
	public $parent;
	public $status;
	public $tagged;
	private $children;

	public function __construct($id = 0) {
		$table_name = 'tasks';
		$this->fields = [
			'user',
			'date',
			'heading',
			'content',
			'parent',
			'status',
			'tagged'
		];
		parent::__construct( $table_name, $id );
		if ($id != 0) {
			$tasks = new Tasks();
			$this->children = $tasks->getByParent($this->id,$this->user);
		} else {
			$this->children = [];
		}
	}

	public function getChildren() {
		return $this->children;
	}
}