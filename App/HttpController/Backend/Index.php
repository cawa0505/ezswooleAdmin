<?php
/**
 * ClassDescription
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/15 12:14
 */

namespace App\HttpController\Backend;

use App\Service\Backend\MenuService;
use App\Utility\Pool\RedisPool;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;

class Index extends Base
{
    /**
     * Description
     *
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/30 17:40
     *
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function index()
    {
        $menu       = loader(MenuService::class)->getMenuList();
        $adminId    = $this->request()->getCookieParams('admin_id');
        $redis      = RedisPool::defer();
        $permission = $redis->get('user_' . $adminId . '_permission');
        $permission = json_decode($permission, true);
        $this->render('backend.layout', [
            'result'     => $this->adminInfo,
            'menu'       => $menu ? $this->arrayToTree($menu) : [],
            'permission' => $permission
        ]);
    }

    public function content()
    {
        $this->render('backend.index.index');
    }

    private function arrayToTree($array)
    {
        $items = array();
        foreach ($array as $value) {
            $items[$value['menu_id']] = $value;
        }
        $tree = array();
        foreach ($items as $key => $item) {
            if (isset($items[$item['parent_id']])) {
                $items[$item['parent_id']]['son'][] = &$items[$key];
            } else {
                $tree[] = &$items[$key];
            }
        }
        return $tree;
    }
}