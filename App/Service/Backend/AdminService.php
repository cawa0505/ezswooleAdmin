<?php
/**
 * ClassDescription
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/15 16:46
 */

namespace App\Service\Backend;

use App\Constants\TimeConst;
use App\Constants\UserConst;
use App\Model\AdminModel;
use App\Model\PermissionModel;
use App\Model\RoleModel;
use App\Model\StoreModel;
use App\Service\BaseService;
use App\Validates\AdminValidate;
use Exception;

/**
 * ClassDescription
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/18 15:25
 */
class AdminService extends BaseService
{
    /**
     * 获取管理员列表
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
        $page = isset($request['page']) ? $request['page'] : 1;
        $limit = isset($request['limit']) ? $request['limit'] : 10;
        $response = loader(AdminModel::class)->getSearchList($keywords, $page, $limit);
        return $response;
    }


    /**
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
            $validate = loader(AdminValidate::class);
            if (!$validate->scene('create')->check($request)) {
                throw new \Exception($validate->getError(), 201);
            }
            $result = loader(AdminModel::class)->getUserByUserName($request['username']);
            if ($result) {
                throw new \Exception('该数据已存在', 201);
            }
            $response = loader(AdminModel::class)->save($request);
            return $this->success($response);
        }
        catch (\Exception $exception) {
            return $this->fail($exception->getCode(), $exception->getMessage());
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
                throw new Exception('id不能为空', 201);
            }
            $response = loader(AdminModel::class)->getOne($id);
            if ($response) {
                $response['store'] = loader(StoreModel::class)->getNameById($response['store_id']);
                $response['role'] = loader(AdminModel::class)->getRoleIdsByAdminId($id);
            }
            return $this->success($response);
        }
        catch (Exception $exception) {
            return $this->fail($exception->getCode(), $exception->getMessage());
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
            $validate = loader(AdminValidate::class);
            if (!$validate->scene('update')->check($request)) {
                throw new Exception($validate->getError(), 201);
            }
            $result = loader(AdminModel::class)->getUserByUserName($request['username']);
            if ($result && $result['admin_id'] != $request['admin_id']) {
                throw new Exception('该数据已存在', 201);
            }
            $response = loader(AdminModel::class)->update($request);
            return $this->success($response);
        }
        catch (Exception $exception) {
            return $this->fail($exception->getCode(), $exception->getMessage());
        }

    }

    /**
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
            if (!$id) {
                throw new Exception('id不能为空', 201);
            }
            if ($id == 1) {
                throw new Exception('当前管理员不能被删除！！！', 201);
            }
            $response = loader(AdminModel::class)->delete($id);
            return $this->success($response);
        }
        catch (Exception $exception) {
            return $this->fail($exception->getCode(), $exception->getMessage());
        }
    }

    /**
     * 管理员登录
     * @param $request
     *
     * @return array
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/15 18:14
     */
    public function login($request)
    {
        try {
            $validate = loader(AdminValidate::class);
            if (!$validate->scene('login')->check($request)) {
                throw new Exception($validate->getError(), 201);
            }
            if ($request['code'] != get_context('code')) {
                throw new Exception(UserConst::CODE_ERROR, 201);
            }
            $result = loader(AdminModel::class)->getUserByUserName($request['username']);
            if (!$result) {
                throw new Exception(UserConst::USER_NOT_FOUND, 201);
            }
            if (encrypt($request['password']) != $result['password']) {
                throw new Exception(UserConst::PASSWORD_ERROR, 201);
            }
            if ($result['status'] == 1) {
                throw new Exception(UserConst::USER_DISABLE, 201);
            }
            loader(AdminModel::class)->updateLoginTime($request['username']);
            $response['token'] = $this->jwtEncode($result);
            $response['admin_id'] = $result['admin_id'];
            $this->getRedis()->set('access_token_' . $result['admin_id'], $response['token'], TimeConst::TOKEN_TIME);
            // 判断超管拥有所有权限
            if ($result['admin_id'] == 1) {
                $permissionUrl = loader(PermissionService::class)->getAllPermissionUrl();
            } else {
                // 通过admin_id查询角色
                $roleIds = loader(AdminModel::class)->getRoleIdsByAdminId($result['admin_id']);
                // 通过角色id获取权限id
                $permissionIds = loader(RoleModel::class)->getPermissionIdsByRoleIds($roleIds);
                // 通过权限id 获取权限路由
                $permissionUrl = loader(PermissionModel::class)->getPermissionUrlByPermissionIds($permissionIds);
            }
            $this->getRedis()->set('user_' . $result['admin_id'] . '_permission', json_encode($permissionUrl), TimeConst::PERMISSION_TIME);
            return $this->success($response);
        }
        catch (Exception $exception) {
            return $this->fail($exception->getCode(), $exception->getMessage());
        }
        catch (\Throwable $exception) {
            return $this->fail($exception->getCode(), $exception->getMessage());
        }
    }
}
