<?php

/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2019-01-01
 * Time: 20:06
 */

return [
    'SERVER_NAME'    => "EasySwoole",
    'MAIN_SERVER'    => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT'           => 9501,
        'SERVER_TYPE'    => EASYSWOOLE_WEB_SERVER, //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER
        'SOCK_TYPE'      => SWOOLE_TCP,
        'RUN_MODEL'      => SWOOLE_PROCESS,
        'SETTING'        => [
            'worker_num'            => 8,
            'task_worker_num'       => 8,
            'reload_async'          => true,
            'task_enable_coroutine' => true,
            'document_root'         => EASYSWOOLE_ROOT . '/Public',  // 静态资源目录
            'enable_static_handler' => true,
        ],
    ],
    'TEMP_DIR'       => null,
    'LOG_DIR'        => null,
    'Blade_TEMP_DIR' => EASYSWOOLE_ROOT . '/Temp/BladeCache',
    'CONSOLE'        => [
        'ENABLE'         => true,
        'LISTEN_ADDRESS' => '127.0.0.1',
        'HOST'           => '127.0.0.1',
        'PORT'           => 9500,
        'USER'           => 'root',
        'PASSWORD'       => '123456'
    ],
    'FAST_CACHE'     => [
        'PROCESS_NUM' => 0,
        'BACKLOG'     => 256,
    ],
    'DISPLAY_ERROR'  => true,
    'PHAR'           => [
        'EXCLUDE' => ['.idea','Log','Temp','easyswoole','easyswoole.install']
    ]
];