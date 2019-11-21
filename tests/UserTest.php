<?php
include_once ('index.php');

use PHPUnit\Framework\TestCase;
use \Test\Model\User;

class UserTest extends TestCase{
	public function testEmail()
	{
		$user = new User();
		$user->set('email','');
		$this->assertSame(false,$user->set('email','testwrogemail'));
		$this->assertTrue($user->set('email','pent6400@gmail.com'));
	}

	public function testSave()
	{
		$user = new User();
		$user->set('id',999);
		$user->set('email','pent6400@gmail.com');
		$user->set('password','1111');
		$user->save();
		$id = $user->get('id');
		$this->assertIsInt($id);
		unset($user);
		$user = new User(999);
		$this->assertNotEquals(false,$user);
		$this->assertTrue($user->delete());
	}
}