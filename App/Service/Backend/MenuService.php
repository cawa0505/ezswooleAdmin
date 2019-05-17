<?php
/**
 * ClassDescription
 * @category Category
 * @package Package
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 19-4-29 上午9:51
 */

namespace App\Service\Backend;

use App\Constants\TimeConst;
use App\Model\MenuModel;
use App\Service\BaseService;
use App\Validates\MenuValidate;
use Exception;

/**
 * Class MenuService
 * @package App\Service\Backend
 */
class MenuService extends BaseService
{

    /**
     * 菜单列表页
     * @param $request
     *
     * @return mixed
     *
     * @author qap <qiuapeng921@163.com>
     * @date 19-4-30 上午9:02
     */
    public function getSearchList($request)
    {
        $keywords = isset($request['keywords']) ? $request['keywords'] : [];
        $page     = isset($request['page']) ? $request['page'] : 1;
        $limit    = isset($request['limit']) ? $request['limit'] : 10;
        $response = loader(MenuModel::class)->getSearchList($keywords,$page,$limit);
        return $response;
    }

    /**
     * 通过父级id获取归属菜单
     *
     * @param $parentId
     *
     * @return array
     *
     * @author qap <qiuapeng921@163.com>
     * @date 19-5-5 上午9:32
     */
    public function getSubordinateList($parentId)
    {
        try {
            $response = loader(MenuModel::class)->getSubordinateList($parentId);
            return $this->success($response);
        } catch (Exception $exception) {
            return $this->fail($exception->getCode(),$exception->getMessage());
        }

    }

    /**
     * @param $id
     *
     * @return array
     *
     * @author qap <qiuapeng921@163.com>
     * @date 19-4-30 上午9:02
     */
    public function getOne($id)
    {
        try {
            if (!$id) {
                throw new Exception('id不能为空',201);
            }
            $response = loader(MenuModel::class)->getOne($id);
            return $this->success($response);
        } catch (Exception $exception) {
            return $this->fail($exception->getCode(),$exception->getMessage());
        }
    }

    /**
     * 添加数据
     * @param $request
     *
     * @return array
     *
     * @author qap <qiuapeng921@163.com>
     * @date 19-4-30 上午9:02
     */
    public function create($request)
    {
        try {
            $validate = loader(MenuValidate::class);
            if (!$validate->scene('create')->check($request)) {
                throw new Exception($validate->getError(),201);
            }
            $result = loader(MenuModel::class)->getMenuByName($request['menu_name']);
            if ($result) {
                throw new Exception('该数据已存在',201);
            }
            // 处理菜单分组
            if ($request['parent_id'][1]) {
                $request['parent_id'] = $request['parent_id'][1];
            } else {
                $request['parent_id'] = $request['parent_id'][0];
            }
            $response = loader(MenuModel::class)->save($request);
            return $this->success($response);
        } catch (Exception $exception) {
            return $this->fail($exception->getCode(),$exception->getMessage());
        }
    }

    /**
     * 修改数据
     * @param $request
     *
     * @return array
     *
     * @author qap <qiuapeng921@163.com>
     * @date 19-4-30 上午9:02
     */
    public function update($request)
    {
        try {

            $validate = loader(MenuValidate::class);
            if (!$validate->scene('update')->check($request)) {
                throw new Exception($validate->getError(),201);
            }
            $result = loader(MenuModel::class)->getMenuByName($request['menu_name']);
            if ($result && $result['menu_id'] != $request['menu_id']) {
                throw new Exception('该数据已存在',201);
            }
            // 处理菜单分组
            if ($request['parent_id'][1]) {
                $request['parent_id'] = $request['parent_id'][1];
            } else {
                $request['parent_id'] = $request['parent_id'][0];
            }
            $response = loader(MenuModel::class)->update($request);
            return $this->success($response);
        } catch (Exception $exception) {
            return $this->fail($exception->getCode(),$exception->getMessage());
        }

    }

    /**
     * 删除数据
     * @param $id
     *
     * @return array
     *
     * @author qap <qiuapeng921@163.com>
     * @date 19-4-30 上午9:02
     */
    public function delete($id)
    {
        try {
            if (!$id) {
                throw new Exception('id不能为空',201);
            }
            $response = loader(MenuModel::class)->delete($id);
            return $this->success($response);
        } catch (Exception $exception) {
            return $this->fail($exception->getCode(),$exception->getMessage());
        }
    }

    /**
     * 首页菜单列表
     * @return mixed
     *
     * @author qap <qiuapeng921@163.com>
     * @date 19-4-30 上午9:02
     */
    public function getMenuList()
    {
        $menuCache = $this->getRedis()->get('menu');
        if ($menuCache) {
            return json_decode($menuCache,true);
        }
        $menu = $this->getSearchList(['page' => 1,'limit' => 1000]);
        if ($menu['count']) {
            $this->getRedis()->set('menu',json_encode($menu['data']),TimeConst::MENU_TIME);
            return $menu['data'];
        }
        return [];
    }
}