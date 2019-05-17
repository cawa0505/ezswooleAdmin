<?php

namespace App\HttpController;

use EasySwoole\EasySwoole\Config;
use EasySwoole\Http\AbstractInterface\Controller;
use duncan3dc\Laravel\BladeInstance;

/**
 * 视图控制器
 *
 * @package App\HttpController
 */
abstract class Template extends Controller
{
    /**
     * @var BladeInstance
     */
    protected $view;

    /**
     * 初始化模板引擎
     */
    function __construct()
    {
        $tempPath   = Config::getInstance()->getConf('Blade_TEMP_DIR');
        $viewPath   = EASYSWOOLE_ROOT . '/App/Views';
        $this->view = new BladeInstance($viewPath, "{$tempPath}");
        parent::__construct();
    }

    /**
     * 输出模板到页面
     * @param string $view
     * @param array $params
     * @author : evalor <master@evalor.cn>
     */
    function render($view, $params = [])
    {
        $viewTemplate = $this->view->render($view, $params);
        $this->response()->write($viewTemplate);
        $this->response()->end();
    }
}