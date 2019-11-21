<?php
namespace Test;

class Controller {

	protected $router;

	public function __construct() {
		global $router;
		$this->router = $router;
	}

}