<?php

// Apache/PHP-FPM 传统环境下运行的 HTTP 服务配置（传统模式）
return [

    // 基础路径
    'basePath'            => dirname(__DIR__),

    // 控制器命名空间
    'controllerNamespace' => 'apps\httpd\controllers',

    // 中间件命名空间
    'middlewareNamespace' => 'apps\httpd\middleware',

    // 全局中间件
    'middleware'          => [],

    // 组件配置
    'components'          => [

        // 路由
        'route'    => [
            // 类路径
            'class'          => 'mix\http\Route',
            // 默认变量规则
            'defaultPattern' => '[\w-]+',
            // 路由变量规则
            'patterns'       => [
                'id' => '\d+',
            ],
            // 路由规则
            'rules'          => [
                // 一级路由
                ':controller/:action' => [':controller', ':action', 'middleware' => ['Before']],
            ],
            // URL后缀
            'suffix'         => '.html',
        ],

        // 请求
        'request'  => [
            // 类路径
            'class' => 'mix\http\compatible\Request',
        ],

        // 响应
        'response' => [
            // 类路径
            'class'         => 'mix\http\compatible\Response',
            // 默认输出格式
            'defaultFormat' => mix\http\Response::FORMAT_HTML,
            // json
            'json'          => [
                // 类路径
                'class' => 'mix\http\Json',
            ],
            // jsonp
            'jsonp'         => [
                // 类路径
                'class' => 'mix\http\Jsonp',
                // callback键名
                'name'  => 'callback',
            ],
            // xml
            'xml'           => [
                // 类路径
                'class' => 'mix\http\Xml',
            ],
        ],

        // 错误
        'error'    => [
            // 类路径
            'class'  => 'mix\http\Error',
            // 输出格式
            'format' => mix\http\Error::FORMAT_HTML,
            // 错误级别
            'level'  => E_ALL,
        ],

        // 日志
        'log'      => [
            // 类路径
            'class'       => 'mix\base\Log',
            // 日志记录级别
            'level'       => ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'],
            // 日志目录
            'dir'         => 'logs',
            // 日志轮转类型
            'rotate'      => mix\base\Log::ROTATE_DAY,
            // 最大文件尺寸
            'maxFileSize' => 0,
        ],

        // Token
        'token'    => [
            // 类路径
            'class'         => 'mix\http\Token',
            // 保存处理者
            'saveHandler'   => [
                // 类路径
                'class'    => 'mix\client\Redis',
                // 主机
                'host'     => env('REDIS.HOST'),
                // 端口
                'port'     => env('REDIS.PORT'),
                // 数据库
                'database' => env('REDIS.DATABASE'),
                // 密码
                'password' => env('REDIS.PASSWORD'),
            ],
            // 保存的Key前缀
            'saveKeyPrefix' => 'TOKEN:',
            // 有效期
            'expiresIn'     => 604800,
            // token键名
            'name'          => 'access_token',
        ],

        // Session
        'session'  => [
            // 类路径
            'class'          => 'mix\http\Session',
            // 保存处理者
            'saveHandler'    => [
                // 类路径
                'class'    => 'mix\client\Redis',
                // 主机
                'host'     => env('REDIS.HOST'),
                // 端口
                'port'     => env('REDIS.PORT'),
                // 数据库
                'database' => env('REDIS.DATABASE'),
                // 密码
                'password' => env('REDIS.PASSWORD'),
            ],
            // 保存的Key前缀
            'saveKeyPrefix'  => 'SESSION:',
            // 生存时间
            'maxLifetime'    => 7200,
            // session键名
            'name'           => 'session_id',
            // 过期时间
            'cookieExpires'  => 0,
            // 有效的服务器路径
            'cookiePath'     => '/',
            // 有效域名/子域名
            'cookieDomain'   => '',
            // 仅通过安全的 HTTPS 连接传给客户端
            'cookieSecure'   => false,
            // 仅可通过 HTTP 协议访问
            'cookieHttpOnly' => false,
        ],

        // Cookie
        'cookie'   => [
            // 类路径
            'class'    => 'mix\http\Cookie',
            // 过期时间
            'expires'  => 31536000,
            // 有效的服务器路径
            'path'     => '/',
            // 有效域名/子域名
            'domain'   => '',
            // 仅通过安全的 HTTPS 连接传给客户端
            'secure'   => false,
            // 仅可通过 HTTP 协议访问
            'httpOnly' => false,
        ],

        // 数据库
        'pdo'      => [
            // 类路径
            'class'         => 'mix\client\PDO',
            // 数据源格式
            'dsn'           => env('DB.DSN'),
            // 数据库用户名
            'username'      => env('DB.USERNAME'),
            // 数据库密码
            'password'      => env('DB.PASSWORD'),
            // 驱动连接选项: http://php.net/manual/zh/pdo.setattribute.php
            'driverOptions' => [
                // 设置默认的提取模式: \PDO::FETCH_OBJ | \PDO::FETCH_ASSOC
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ],
        ],

        // redis
        'redis'    => [
            // 类路径
            'class'    => 'mix\client\Redis',
            // 主机
            'host'     => env('REDIS.HOST'),
            // 端口
            'port'     => env('REDIS.PORT'),
            // 数据库
            'database' => env('REDIS.DATABASE'),
            // 密码
            'password' => env('REDIS.PASSWORD'),
        ],

    ],

    // 类库配置
    'libraries'           => [

    ],

];
