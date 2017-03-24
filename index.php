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

require_once __DIR__ . '/vendor/autoload.php';

use App\Session\SessionHandle;

$redis = new Redis();
$redis->connect('dcodeit.net');

session_start();

session_save_path('tcp://127.0.0.1:6379');

$handler = new SessionHandle($redis);

session_set_save_handler($handler, true);
