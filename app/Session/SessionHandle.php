<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 24.03.2017
 * Time: 11:44
 */

namespace App\Session;

use App\Logger\Logger;

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
     * Logger component
     *
     * @var Logger
     */
    protected $logger;

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
        $this->logger = new Logger(ROOT . '/logs');
    }

    /**
     * @inheritdoc
     */
    public function open($save_path, $name)
    {
        $this->logger->info(SessionEventMessage::SESSION_START);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function close()
    {
        $this->logger->info(SessionEventMessage::SESSION_CLOSE);

        unset($this->redis);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function gc($maxlifetime)
    {
        $this->logger->info(SessionEventMessage::SESSION_CLEANUP);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function destroy($session_id)
    {
        $this->logger->info(SessionEventMessage::SESSION_DESTROY);

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

        $this->logger->info(SessionEventMessage::SESSION_READ . $key);

        return $this->redis->get($key) ? $this->redis->get($key) : '';
    }

    /**
     * @inheritdoc
     */
    public function write($session_id, $session_data)
    {
        $key = $this->getRedisKey($session_id);

        $this->redis->expire($key, $this->ttl);

        $this->redis->set($key, $session_data);

        $this->logger->info(SessionEventMessage::SESSION_WRITE . $session_data);

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
}