<?php
/**
 * ClassDescription
 * @category Category
 * @package  Package
 * @author   邱阿朋 <apqiu@suntekcorps.com>
 * @license  http://wiki.com/index.php
 * @link     http://127.0.0.1:8000/index
 * @Date     2019/4/15 21:05
 */

namespace App\HttpController\Backend;

use App\HttpController\Common;
use App\Utility\Pool\RedisPool;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use Exception;


abstract class Base extends Common
{

    /**
     * @var string 错误信息
     */
    private $message = '';

    /**
     * 验证处理
     *
     * @param string|null $action
     * @return bool|null
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:47
     * @throws PoolEmpty
     * @throws PoolException
     */
    protected function onRequest(?string $action): ?bool
    {
        if (parent::onRequest($action)) {
            // 判断是否登录
            if (!$this->handlerToken()) {
                $this->response()->redirect('/auth/login');
                return false;
            }
            // 判断权限
            $route = $this->request()->getUri()->getPath();
            if (!$this->check($route)) {
                $method = $this->request()->getMethod();
                // post请求返回json格式
                if ($method == 'POST') {
                    $this->writeJson(201, '', $this->message);
                } else {
                    $this->render('backend.error', [
                        'result' => $this->message
                    ]);
                }
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 判断是否有权限
     * @param null $route
     *
     * @return bool
     *
     * @author qap <qiuapeng921@163.com>
     * @date 19-4-29 下午4:09
     */
    private function check($route = null)
    {
        try {
            if (!$route) {
                throw new Exception('路由地址不能为空');
            }
            // 当前路由统一使用小写
            $route = strtolower($route);
            // 获取用户权限
            $redis   = RedisPool::defer();
            $adminId = $this->request()->getCookieParams('admin_id');
            if ($adminId == 1) {
                return true;
            }
            $permission = $redis->get('user_' . $adminId . '_permission');
            $permission = json_decode($permission, true);
            $permission = array_merge($permission, $this->whiteList());
            if (empty($permission)) {
                throw new Exception('权限为空');
            }
            if (!in_array($route, $permission)) {
                throw new Exception('没有操作权限');
            }
            return true;
        } catch (Exception $e) {
            $this->message = $e->getMessage();
            return false;
        }
    }

    /**
     * 白名单路由
     *
     * @return array
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/5/5 17:01
     *
     */
    public function whiteList()
    {
        return [
            '/backend/index',
            '/backend/index/content',
        ];
    }
}
