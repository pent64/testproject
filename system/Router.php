<?php
namespace Test;

use Test;

class Router {

	private $params;
	private $controller;
	private $action;
	private $user;

	public function __construct() {
		$url = $_SERVER['REQUEST_URI'];
		$explode = explode('?',$url);
		$explode = explode('/',$explode[0]);
		array_shift($explode);
		if (isset($explode[0]) && !empty($explode[0])) {
			$this->controller = $explode[0];
		} else {
			$this->controller = 'index';
		}
		if (isset($explode[0]) && !empty($explode[1])) {
			$this->action = $explode[1];
		} else {
			$this->action = 'index';
		}
		if (count($explode) > 2) {
			for ($i = 2; $i < count($explode); $i+=2) {
				if (isset($explode[$i+1]) && !empty($explode[$i+1])) {
					$this->params[ $explode[ $i ] ] = $explode[ $i + 1 ];
				}
			}
		}
	}

	public function runController(){
		if (!($this->controller == 'user' && $this->action == 'login') && !($this->controller == 'user' && $this->action == 'register')) {
			$uname = $_COOKIE['uname'];
			if ( $uname == null || empty( $uname ) ) {
				if ( $this->controller != 'index' || ( $this->controller == 'index' && $this->action != 'index' ) ) {
					$this->redirect( 'index/index' );
				}
			} else {
				$user = new Test\Model\User();
				if (!$user->getBy('session',$uname)) {
					if ( $this->controller != 'index' || ( $this->controller == 'index' && $this->action != 'index' ) ) {
						$this->redirect( 'index/index' );
					}
				} else {
					$this->user = $user;
				}
			}
		}
		if (file_exists(dirname(__DIR__).'/controller/'.ucfirst($this->controller).'.php')) {
			require_once (dirname(__DIR__).'/controller/'.ucfirst($this->controller).'.php');
			$action_str = ucfirst($this->action);
			$str = 'Test\\Controller\\'.ucfirst($this->controller);
			$controller = new $str();
			if (method_exists($controller,$action_str)) {
				$controller->$action_str();
			} else {
				echo 'no method '.ucfirst($this->action);
			}
		} else {
			echo 'no controller file '.dirname(__DIR__).'/controller/'.ucfirst($this->controller).'.php';
		}
	}

	public function getParam($name) {
		if (isset($this->params[$name])) {
			return $this->params[$name];
		} elseif (isset($_GET[$name])) {
			return $_GET[$name];
		} elseif (isset($_POST[$name])) {
			return $_POST[$name];
		}
		return false;
	}

	public function getSite(){
		return $_SERVER['HTTP_HOST'];
	}

	public function getUser() {
		return $this->user;
	}

	public function redirect($link){
		header("Location: ".(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].'/'.$link);
		die();
	}
}