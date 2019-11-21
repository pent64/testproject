<?php
include_once ('index.php');

use PHPUnit\Framework\TestCase;
use \Test\Model\Task;

class TaskTest extends TestCase {
	public function testSave()
	{
		$task = new Task();
		$task->set('id',999);
		$task->set('user',4);
		$task->set('date','2019-11-21 09:41:00');
		$task->set('heading','111');
		$task->set('content','222');
		$task->save();
		$id = $task->get('id');
		$this->assertIsInt($id);
		unset($task);
		$task = new Task(999);
		$this->assertNotEquals(false,$task);
		$this->assertTrue($task->delete());
	}
}