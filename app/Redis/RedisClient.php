<?php

/**
 * Created by PhpStorm.
 * User: kostya
 * Date: 25.03.17
 * Time: 15:56
 */
namespace App\Redis;

/**
 * Class RedisConnect
 * @package App\Redis
 */
class RedisClient
{
    /**
     * @var string $hots
     */
    private $host = '127.0.0.1';

    /**
     * @var int $port
     */
    private $port = 6379;

    /**
     * @var int $timeout
     */
    private $timeout = 0.0;

    /**
     * @var int $interval
     */
    private $interval = 0;

    /**
     * Get host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set host
     *
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * Get port
     *
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set port
     *
     * @param int $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * Get timeout
     *
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Set timeout
     *
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * Get interval
     *
     * @return int
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * Set interval
     *
     * @param int $interval
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;
    }

    /**
     * Create Redis client
     *
     * @return \Redis
     */
    public function createClient()
    {
        try {
            $redis = new \Redis();

            // Redis param connect
            $connect = $redis->connect(
                $this->host,        // 127.0.0.1
                $this->port,        // 6379
                $this->timeout,     // 0.0
                $this->interval     // 0
            );

            // throw RedisException if there is an error when connection Redis
            if (!$connect) {
                throw new \RedisException('Redis connection is fail');
            }

            return $redis;
        } catch (\RedisException $e) {
            die($e->getMessage());
        }
    }
}