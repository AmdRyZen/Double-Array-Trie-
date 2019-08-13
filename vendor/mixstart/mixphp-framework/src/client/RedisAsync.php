<?php

namespace mix\client;

use mix\base\BaseObject;

/**
 * RedisAsync类
 * @author 刘健 <coder.liu@qq.com>
 */
class RedisAsync extends BaseObject
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

    // 初始化事件
    public function onInitialize()
    {
        parent::onInitialize();
        // 建立对象
        $this->_redis = new \Swoole\Redis();
    }

    // 连接
    public function connect($closure)
    {
        $database = $this->database;
        $password = $this->password;
        $this->_redis->connect($this->host, $this->port, function (\Swoole\Redis $client, $result) use ($closure, $database, $password) {
            if (!$result) {
                $closure($client, $result);
                return;
            }
            if ($password != '') {
                $client->auth($password, function (\Swoole\Redis $client, $result) use ($closure, $database) {
                    if (!$result) {
                        $closure($client, $result);
                        return;
                    }
                    $client->select($database, function (\Swoole\Redis $client, $result) use ($closure) {
                        $closure($client, $result);
                    });
                });
            } else {
                $client->select($database, function (\Swoole\Redis $client, $result) use ($closure) {
                    $closure($client, $result);
                });
            }
        });
    }

    // 注册事件回调函数，必须在 connect 前被调用
    public function on($event, $closure)
    {
        switch ($event) {
            case 'Message':
                $this->_redis->on('message', $closure);
                break;
            case 'Close':
                $this->_redis->on('close', $closure);
                break;
        }
    }

    // 关闭连接
    public function close()
    {
        $this->_redis->close();
    }

}
