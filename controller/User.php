<?php
namespace Test\Controller;

use Test\Model\User as UserModel;

class User extends \Test\Controller {

	public function __construct() {
		parent::__construct();
	}

	public function login() {
		$email = '';
		$password = '';
		if (isset($_POST['email'])) {
			if (filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) {
				$email = $_POST['email'];
			}
		}
		if (isset($_POST['password'])) {
			$password = $_POST['password'];
		}
		$user = new UserModel();
		$user->getBy('email',$email);
		if (password_verify($password,$user->get('password'))) {
			$cookiehash = md5(sha1($user->get('email') . $password.time()));
			setcookie("uname",$cookiehash,time()+3600*24*365,'/',$this->router->getSite());
			$user->set('session',$cookiehash);
			$user->save();
			$this->router->redirect('tasks');
		} else {
			unset($_COOKIE['uname']);
			setcookie("uname", '', time() - 3600);
			$this->router->redirect('');
		}
	}

	public function register() {
		$email = '';
		$password = '';
		if (isset($_POST['email'])) {
			if (filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) {
				$email = $_POST['email'];
			}
		}
		if (isset($_POST['password'])) {
			$password = password_hash($_POST['password'],PASSWORD_DEFAULT);
		}
		if (!empty($email) && !empty($password)) {
			$user = new UserModel();
			$user->set('email',$email);
			$user->set('password',$password);
			$user->save();
			$this->router->redirect('');
		} else {
			//$this->router->redirect('');
		}
	}

}