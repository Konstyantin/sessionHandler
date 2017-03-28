<?php
/**
 * Created by PhpStorm.
 * User: kostya
 * Date: 25.03.17
 * Time: 18:27
 */

namespace App\Session;

use App\Redis\RedisClient;
use App\Session\SessionHandle;

/**
 * Class SessionWrapper
 * @package App\Session
 */
class SessionManager
{
    /**
     * SessionWrapper constructor.
     * @param RedisClient $redis
     */
    public function __construct(RedisClient $redis, string $prefix = 'PHPREDIS_SESSION: ')
    {
        $this->setConfig($redis, $prefix);

        $this->start();
    }


    /**
     * Starting session
     *
     * If session status is not active call session start
     */
    public function start()
    {
        if ($this->status() !== 2) {
            session_start();
        }
    }
    /**
     * Session stop
     *
     * Unset all exists data in session and delete session seanse
     */
    public function stop()
    {
        if ($this->status() !== 0) {
            $this->unsetSession();
            setcookie(session_name(),'',0,'/');
        }
    }

    /**
     * Set session lifetime
     *
     * @param int $value
     */
    public function setLifetime(int $value)
    {
        session_set_cookie_params($value);
    }

    /**
     * Get session lifetime default value 1440
     *
     * @return string
     */
    public function getLifetime()
    {
        return ini_get('session.gc_maxlifetime');
    }

    /**
     * Get session status
     *
     * Default session status
     * PHP_SESSION_DISABLED = 0
     * PHP_SESSION_NONE = 1
     * PHP_SESSION_ACTIVE = 2
     *
     * @return int
     */
    public function status()
    {
        return session_status();
    }

    /**
     * Set session the value to key
     *
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;

        $setterValue = 'key = ' . $key . ', value = ' . $value;
    }

    /**
     * Get session value
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        $getterValue = 'key = ' . $key;

        return $this->checkExist($key) ? $_SESSION[$key] : false;
    }

    /**
     * Check exist session by key
     *
     * @param string $key
     * @return bool
     */
    public function checkExist(string $key)
    {
        return isset($_SESSION[$key]) ? true : false;
    }

    /**
     * Free all session variables
     */
    public function unsetSession()
    {
        return session_unset();
    }

    /**
     * Unset session value
     *
     * @param string $key
     */
    public function unsetKey(string $key)
    {
        if ($this->checkExist($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Set configuration for session work
     *
     * @param RedisClient $redis
     */
    protected function setConfig(RedisClient $redis, string $prefix)
    {
        $host = $redis->getHost();  // 127.0.0.1
        $port = $redis->getPort();  // 6379

        $redis = $redis->createClient(); // return redis client with set params

        $handler = new SessionHandle($redis, $prefix);

        // set redis session handler as tool work with session
        session_set_save_handler($handler, true);

        $this->savePath($host, $port);
    }

    /**
     * Set path to save session data
     *
     * Build path to save session data in Redis store
     *
     * @param string $host
     * @param int $port
     */
    protected function savePath(string $host, int $port)
    {
        $sessionPath = 'tcp://' . $host . ':' . $port;  // tcp://127.0.0.1:6379

        ini_set('session.save_path', $sessionPath);
    }

    /**
     * Get data list, which store session
     *
     * @return array $data session data
     */
    public function getListData()
    {
        $data = session_encode();

        session_decode($data);

        return $_SESSION;
    }

    /**
     * Destroys all data registered to a session
     *
     * @return bool
     */
    public function destroy()
    {
        return session_destroy();
    }
}