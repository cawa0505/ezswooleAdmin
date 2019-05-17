<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2019-01-01
 * Time: 20:06
 */

return [
    'SERVER_NAME'    => "EasySwoole",//服务名
    'MAIN_SERVER'    => [
        'LISTEN_ADDRESS' => '0.0.0.0',//监听地址
        'PORT'           => 9501,//监听端口
        'SERVER_TYPE'    => EASYSWOOLE_WEB_SERVER, //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER
        'SOCK_TYPE'      => SWOOLE_TCP,//该配置项当为SERVER_TYPE值为TYPE_SERVER时有效
        'RUN_MODEL'      => SWOOLE_PROCESS,// 默认Server的运行模式
        'SETTING'        => [// Swoole Server的运行配置（ 完整配置可见[Swoole文档](https://wiki.swoole.com/wiki/page/274.html) ）
            'worker_num'            => 8,//运行的  worker进程数量
            'max_request'           => 5000,// worker 完成该数量的请求后将退出，防止内存溢出
            'task_worker_num'       => 8,//运行的 task_worker 进程数量
            'task_max_request'      => 1000,// task_worker 完成该数量的请求后将退出，防止内存溢出
            'reload_async'          => true,
            'task_enable_coroutine' => true,
            'document_root'         => EASYSWOOLE_ROOT . '/Public',  // 静态资源目录
            'enable_static_handler' => true,
        ],
    ],
    'TEMP_DIR'       => null,
    'LOG_DIR'        => null,////日志文件存放的目录
    'Blade_TEMP_DIR' => EASYSWOOLE_ROOT . '/Temp/BladeCache',
    'CONSOLE'        => [//console控制台组件配置
        'ENABLE'         => true,//是否开启
        'LISTEN_ADDRESS' => '127.0.0.1',//监听地址
        'PORT'           => 9500,//监听端口
        'USER'           => 'root',//验权用户名
        'PASSWORD'       => '123456'//验权用户名
    ],
    'FAST_CACHE'     => [
        'PROCESS_NUM' => 0,
        'BACKLOG'     => 256,
    ],
    'DISPLAY_ERROR'  => true,
    'PHAR'           => [
        'EXCLUDE' => ['.idea','Log','Temp','easyswoole','easyswoole.install']
    ],
    "ES_HOST"=>'elasticsearch:9200'
];