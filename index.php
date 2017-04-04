<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.03.2017
 * Time: 17:30
 */

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

use App\Router;

require_once __DIR__ . '/vendor/autoload.php';

$rootDir = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);

define('ROOT', dirname(__FILE__));

// define variable which equality path to project directory
define('ROOT_DIR', $rootDir);

$router = new Router();
$router->run();