<?php

namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\AbstractRouter;
use FastRoute\RouteCollector;

/**
 * Class Router
 * @package App\HttpController
 */
class Router extends AbstractRouter
{

    /**
     * 初始化
     * @param RouteCollector $routeCollector
     *
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/30 16:57
     *
     */
    function initialize(RouteCollector $routeCollector)
    {
        $routeCollector->get('/','Frontend/Index/index');
        $routeCollector->addGroup('/Frontend',function (RouteCollector $routeCollector) {
        });

        $routeCollector->get('/auth/login','Backend/Auth/index');
        $routeCollector->post('/auth/login','Backend/Auth/login');
        $routeCollector->get('/auth/code','Backend/Auth/code');
        $routeCollector->post('/auth/logout','Backend/Auth/logout');
        $routeCollector->get('/backend/index','Backend/Index/index');
    }
}