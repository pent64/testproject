<?php
namespace Test\Controller;

use Test\Controller;
use Test\View;

class Index extends Controller {
	public function Index() {
		$view = new View('login',[]);
	}
}