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
    public function __construct(\Redis $redis, $prefix = 'PHPREDIS_SESSION: ', $lifetime = 1440)
    {
        $this->redis = $redis;
        $this->prefix = $prefix;

        $this->ttl = $lifetime;
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
        return true;
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
        $key = $this->getRedisKey($session_id);
        $this->redis->del($key);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function read($session_id)
    {
        $key = $this->getRedisKey($session_id);

        return $this->redis->get($key);
    }

    /**
     * @inheritdoc
     */
    public function write($session_id, $session_data)
    {
        $key = $this->getRedisKey($session_id);

        $this->redis->expire($key, $this->ttl);

        $this->redis->set($key, $session_data);

        return true;
    }

    /**
     * Get session key
     *
     * @param string $id
     * @return string
     */
    protected function getRedisKey(string $id)
    {
        return $this->prefix . $id;     // PHPREDIS_SESSION . session_id
    }

    /**
     * SessionHandler destructor magic method
     */
    public function __destruct()
    {
        $this->close();
    }
}