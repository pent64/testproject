<?php
include_once ('index.php');

use PHPUnit\Framework\TestCase;
use \Test\Model\Tasks;

class TasksTest extends TestCase {
	public function testGet()
	{
		$tasks = new Tasks();
		$tasks = $tasks->getByUser(4);
		$this->assertTrue(is_array($tasks));
		$this->assertGreaterThan(0,count($tasks));
		unset($tasks);
		$tasks = new Tasks();
		$tasks = $tasks->getByUser(5);
		$this->assertTrue(is_array($tasks));
		$this->assertEquals(0,count($tasks));
	}
}