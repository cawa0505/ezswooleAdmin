<?php
/**
 * mysql配置
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/18 13:25
 */

return [
    "MYSQL"  => [
        'host'          => 'mysql',
        'port'          => '3306',
        'user'          => 'root',
        'timeout'       => '5',
        'charset'       => 'utf8mb4',
        'password'      => '123456',
        'database'      => 'test',
        'POOL_MAX_NUM'  => '20',
        'POOL_MIN_NUM'  => '1',
        'POOL_TIME_OUT' => '0.1',
    ],
    "REDIS"  => [
        'host'          => 'redis',
        'port'          => '6379',
        'auth'          => 'root',
        'POOL_MAX_NUM'  => '20',
        'POOL_MIN_NUM'  => '1',
        'POOL_TIME_OUT' => '0.1',
    ]
];
