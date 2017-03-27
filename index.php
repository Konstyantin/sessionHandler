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
ini_set('xdebug.var_display_max_depth', 5);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', 1024);

use App\Session\SessionManager;
use App\Redis\RedisClient;
use App\Logger\Logger;
use App\Router;
use Acme\Controller\IndexController;

require_once __DIR__ . '/vendor/autoload.php';

define('ROOT', dirname(__FILE__));

$redis = new RedisClient();

$session = new SessionManager($redis);

$router = new Router();
$router->run();