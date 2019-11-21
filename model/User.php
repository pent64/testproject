<?php
namespace Test\Model;

use Test\DB;

class User extends DB {

	public $fields;
	public $email;
	public $password;
	public $session;

	public function __construct($id = 0) {
		$table_name = 'users';
		$this->fields = [
			'email',
			'password',
			'session'
		];
		parent::__construct( $table_name, $id );
		$this->id = $id;
	}
}