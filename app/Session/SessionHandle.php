<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 24.03.2017
 * Time: 11:44
 */
namespace App\Session;

/**
 * Class SessionHandle
 * @package App\Session
 */
class SessionHandle implements \SessionHandlerInterface
{
    /**
     * Instance Redis
     *
     * @var \Redis
     */
    protected $redis;

    /**
     * The lifetime of the session
     *
     * @var string
     */
    protected $ttl;

    /**
     * Prefix
     *
     * @var string
     */
    protected $prefix;

    /**
     * SessionHandle constructor.
     * @param \Redis $redis
     * @param string $prefix
     */
    public function __construct(\Redis $redis, $prefix = 'PHPREDIS_SESSION: ')
    {
        $this->redis = $redis;
        $this->prefix = $prefix;

        $this->ttl = ini_get('session.gc_maxlifetime');
    }

    /**
     * @inheritdoc
     */
    public function open($save_path, $name)
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function close()
    {
        $this->redis = null;
        unset($this->redis);
    }

    /**
     * @inheritdoc
     */
    public function gc($maxlifetime)
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function destroy($session_id)
    {
        $key = $this->prefix . $session_id;

        return $this->redis->del($key);
    }

    /**
     * @inheritdoc
     */
    public function read($session_id)
    {
        $key = $this->prefix . $session_id;

        $this->redis->expire($key, $this->ttl);
        return $this->redis->get($key);
    }

    /**
     * @inheritdoc
     */
    public function write($session_id, $session_data)
    {
        $key = $this->prefix . $session_id;

        $this->redis->set($key, $session_data);
    }
}