<?php
include_once ('conf.php');
include_once ('system/MySql.php');
include_once ('system/DB.php');
include_once ('system/Collection.php');
include_once ('system/Router.php');
include_once ('system/Controller.php');
include_once ('system/View.php');
include_once ('model/User.php');
include_once ('model/Task.php');
include_once ('model/Tasks.php');

use \Test\Router;

$db = new \Test\MySql();
$router = new Router();
$router->runController();