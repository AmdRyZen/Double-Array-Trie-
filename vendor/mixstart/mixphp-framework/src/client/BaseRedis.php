<?php

namespace mix\client;

use mix\base\Component;

/**
 * BaseRedis组件
 * @author 刘健 <coder.liu@qq.com>
 *
 * @method select($index)
 * @method set($key, $value)
 * @method setex($key, $seconds, $value)
 * @method setnx($key, $value)
 * @method get($key)
 * @method del($key)
 * @method hmset($key, $array)
 * @method hmget($key, $array)
 * @method hgetall($key)
 * @method hlen($key)
 * @method hset($key, $field, $value)
 * @method hsetnx($key, $field, $value)
 * @method hget($key, $field)
 * @method lpush($key, $value)
 * @method rpop($key)
 * @method brpop($key, $timeout)
 * @method rpush($key, $value)
 * @method lpop($key)
 * @method blpop($key, $timeout)
 * @method sadd($key, $value)
 * @method lrange($key, $start, $end)
 * @method llen($key)
 * @method subscribe($channel)
 * @method publish($channel, $message)
 * @method ttl($key)
 */
class BaseRedis extends Component
{

    // 主机
    public $host = '';

    // 端口
    public $port = '';

    // 数据库
    public $database = '';

    // 密码
    public $password = '';

    // redis对象
    protected $_redis;

    // 创建连接
    protected function createConnection()
    {
        $redis = new \Redis();
        // connect 这里如果设置timeout，是全局有效的，执行brPop时会受影响
        if (!$redis->connect($this->host, $this->port)) {
            throw new \mix\exceptions\ConnectionException('redis connection failed.');
        }
        $redis->auth($this->password);
        $redis->select($this->database);
        return $redis;
    }

    // 连接
    protected function connect()
    {
        $this->_redis = $this->createConnection();
    }

    // 关闭连接
    public function disconnect()
    {
        $this->_redis = null;
    }

    // 自动连接
    protected function autoConnect()
    {
        if (!isset($this->_redis)) {
            $this->connect();
        }
    }

    // 执行命令
    public function __call($name, $arguments)
    {
        // 自动连接
        $this->autoConnect();
        // 执行命令
        return call_user_func_array([$this->_redis, $name], $arguments);
    }

}
