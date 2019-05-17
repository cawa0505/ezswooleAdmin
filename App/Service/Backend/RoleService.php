<?php
/**
 * ClassDescription
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/22 17:25
 */

namespace App\Service\Backend;

use App\Model\RoleModel;
use App\Service\BaseService;
use App\Validates\RoleValidate;

class RoleService extends BaseService
{
    /**
     * 获取角色列表
     * @param $request
     *
     * @return mixed
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/22 16:08
     */
    public function getSearchList($request)
    {
        try {
            $keywords = isset($request['role_name']) ? $request['role_name'] : '';
            $page     = isset($request['page']) ? $request['page'] : '1';
            $limit    = isset($request['limit']) ? $request['limit'] : '10';
            $response = loader(RoleModel::class)->getSearchList($keywords,$page,$limit);
            return $response;
        } catch (\Exception $exception) {
            return $this->fail($exception->getCode(),$exception->getMessage());
        }
    }

    public function getAll()
    {
        $response = loader(RoleModel::class)->getAll();
        return $response;
    }

    public function getOne($id)
    {
        try {
            if (!$id) {
                throw new \Exception('id不能为空',201);
            }
            $response = loader(RoleModel::class)->getOne($id);
            return $this->success($response);
        } catch (\Exception $exception) {
            return $this->fail($exception->getCode(),$exception->getMessage());
        }
    }

    /**
     * 创建角色
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
            $validate = loader(RoleValidate::class);
            if (!$validate->scene('create')->check($request)) {
                throw new \Exception($validate->getError(),201);
            }
            $result = loader(RoleModel::class)->getRoleByName($request['role_name']);
            if ($result) {
                throw new \Exception('该数据已存在',201);
            }
            $response = loader(RoleModel::class)->save($request);
            return $this->success($response);
        } catch (\Exception $exception) {
            return $this->fail($exception->getCode(),$exception->getMessage());
        }
    }


    public function update($request)
    {
        try {
            $validate = loader(RoleValidate::class);
            if (!$validate->scene('update')->check($request)) {
                throw new \Exception($validate->getError(),201);
            }
            $result = loader(RoleModel::class)->getRoleByName($request['role_name']);
            if ($result && $result['role_id'] != $request['role_id']) {
                throw new \Exception('该数据已存在',201);
            }
            $response = loader(RoleModel::class)->update($request);
            return $this->success($response);
        } catch (\Exception $exception) {
            return $this->fail($exception->getCode(),$exception->getMessage());
        }

    }

    public function delete($id)
    {
        try {
            if (!$id) {
                throw new \Exception('id不能为空',201);
            }
            $response = loader(RoleModel::class)->delete($id);
            return $this->success($response);
        } catch (\Exception $exception) {
            return $this->fail($exception->getCode(),$exception->getMessage());
        }
    }

}
