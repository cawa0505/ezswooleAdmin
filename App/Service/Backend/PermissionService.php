<?php
/**
 * ClassDescription
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/15 16:46
 */

namespace App\Service\Backend;

use App\Model\PermissionModel;
use App\Service\BaseService;
use App\Validates\PermissionValidate;

/**
 * ClassDescription
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/18 15:25
 */
class PermissionService extends BaseService
{
    /**
     * 获取权限列表
     * @param $request
     *
     * @return mixed
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/22 16:08
     */
    public function getSearchList($request)
    {
        $keywords = isset($request['keywords']) ? $request['keywords'] : [];
        $page     = isset($request['page']) ? $request['page'] : 1;
        $limit    = isset($request['limit']) ? $request['limit'] : 9;
        $response = loader(PermissionModel::class)->getSearchList($keywords,$page,$limit);
        return $response;
    }

    public function getAll()
    {
        $response = loader(PermissionModel::class)->getAll();
        return $response;
    }

    /**
     * 添加数据
     * @param $request
     *
     * @return array
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/19 16:33
     */
    public function create($request)
    {
        try {
            $validate = loader(PermissionValidate::class);
            if (!$validate->scene('create')->check($request)) {
                throw new \Exception($validate->getError(),201);
            }
            $result = loader(PermissionModel::class)->getPermissionByName($request['name']);
            if ($result) {
                throw new \Exception('该数据已存在',201);
            }
            // 处理分组
            if ($request['parent_id'][1]) {
                $request['parent_id'] = $request['parent_id'][1];
            } else {
                $request['parent_id'] = $request['parent_id'][0];
            }
            $response = loader(PermissionModel::class)->save($request);
            return $this->success($response);
        } catch (\Exception $exception) {
            return $this->fail($exception->getCode(),$exception->getMessage());
        }
    }

    /**
     * 获取单个详情
     * @param $id
     *
     * @return array
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/19 16:33
     */
    public function getOne($id)
    {
        try {
            if (!$id) {
                throw new \Exception('id不能为空',201);
            }
            $response = loader(PermissionModel::class)->getOne($id);
            return $this->success($response);
        } catch (\Exception $exception) {
            return $this->fail($exception->getCode(),$exception->getMessage());
        }
    }


    /**
     * 更新管理员信息
     * @param $request
     *
     * @return array
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/19 16:33
     */
    public function update($request)
    {
        try {
            $validate = loader(PermissionValidate::class);
            if (!$validate->scene('update')->check($request)) {
                throw new \Exception($validate->getError(),201);
            }
            $result = loader(PermissionModel::class)->getPermissionByName($request['name']);
            if ($result && $result['permission_id'] != $request['permission_id']) {
                throw new \Exception('该数据已存在',201);
            }
            // 处理分组
            if ($request['parent_id'][1]) {
                $request['parent_id'] = $request['parent_id'][1];
            } else {
                $request['parent_id'] = $request['parent_id'][0];
            }
            $response = loader(PermissionModel::class)->update($request);
            return $this->success($response);
        } catch (\Exception $exception) {
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
     * @date 2019/4/19 16:33
     */
    public function delete($id)
    {
        try {
            $response = loader(PermissionModel::class)->delete($id);
            return $this->success($response);
        } catch (\Exception $exception) {
            return $this->fail($exception->getCode(),$exception->getMessage());
        }
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
            $response = loader(PermissionModel::class)->getSubordinateList($parentId);
            return $this->success($response);
        } catch (\Exception $exception) {
            return $this->fail($exception->getCode(),$exception->getMessage());
        }

    }

    /**
     * 获取所有权限路由
     *
     * @return array
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/5/5 17:59
     *
     */
    public function getAllPermissionUrl()
    {
        try {
            $response = loader(PermissionModel::class)->getAllPermissionUrl();
            return $response;
        } catch (\Exception $exception) {
            return $this->fail($exception->getCode(),$exception->getMessage());
        }

    }
}
