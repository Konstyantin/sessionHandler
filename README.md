Session Handler
===============
Welcome to the Session Handler - a simple example Session handler 
that you can use for your new project.

Installation
============
Session Handler work with PHP 5.6 or later and MySQL 5.4 or later (please check requirements)

### From repository
Get Session Handler source files from GitHub repository:
```````````````````````````````````````````````````````````
git clone https://github.com/Konstyantin/session %path%
```````````````````````````````````````````````````````````

Download `composer.phar` to the project folder:
```````````````````````````````````````````````
cd %path%
curl -s https://getcomposer.org/installer | php
```````````````````````````````````````````````

Install composer dependencies with the following command:
`````````````````````````
php composer.phar install
`````````````````````````

Routes 
======================

Path to file route:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
app/config/routes.php
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Routes list:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
[
    'create'  => 'index/create',    // create sessiond data
    'list'    => 'index/list',      // view list session data
    'delete/' => 'index/delete/$1', // delete session by key
    'destroy' => 'index/destroy',   // destroy session
];
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Logger
================

Logger is PSR-3 compliant. This means it implements the Psr\Log\LoggerInterface.

  [**See Here for the interface definition**][1]
  

Session Handler
================

Session handler overwrite default setting `session_set_save_handler` and `session_save_path` :
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$handler = new SessionHandle();

session_set_save_handler($handler, true);

session_save_path(tcp://127.0.0.1:6379);
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Available query params:

* `timeout` (float), default 0.0, which means unlimited timeout

* `prefix` (string), default `PHPREDIS_SESSION:`

* `auth` (string), default `null`

* `database` (int), default `0`

### Redis connect param

For create redis client used next params:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
host: 127.0.0.1
port: 6379      // default
timeout: 0.0    // default
interval: 0     // default
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

### Test Redis session data

You can get session data from `Redis` use command line:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
127.0.0.1:6379> keys *
1) "PHPREDIS_SESSION: madeupkey"

127.0.0.1:6379> get PHPREDIS_SESSION:madeupkey
"key|s:5:\"value\";"
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You can get `madeupkey` using method:

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$session = new SessionManager();
$session->getPrefixKey()            //PHPREDIS_SESSION: df0bfee94aca288e9787c831d1a36de1
                             
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

or look to request header:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8
Accept-Encoding:gzip, deflate, sdch
Accept-Language:ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4
Cache-Control:max-age=0
Connection:keep-alive
Cookie:PHPSESSID=df0bfee94aca288e9787c831d1a36de1 <==============================
Host:localhost
Referer:http://dcodeit.net/kostya.nagula/project/sessionHandler/list
Upgrade-Insecure-Requests:1
User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
[1]:  https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#3-psrlogloggerinterface