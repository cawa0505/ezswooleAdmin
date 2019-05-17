<?php
/**
 * ClassDescription
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/22 16:02
 */

namespace App\Model;

use EasySwoole\Component\Context\Exception\ModifyError;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\Mysqli\Exceptions\ConnectFail;
use EasySwoole\Mysqli\Exceptions\Option;
use EasySwoole\Mysqli\Exceptions\OrderByFail;
use EasySwoole\Mysqli\Exceptions\PrepareQueryFail;

/**
 * Class RoleModel
 * @package App\Model
 */
class RoleModel extends BaseModel
{
    /**
     * 角色表
     * @var string
     */
    private $roleTable = 'rd_role';

    /**
     * 角色权限关系表
     * @var string
     */
    private $rolePermissionTable = 'rd_role_permission';

    /**
     * 搜索列表
     *
     * @param $keywords
     * @param $page
     * @param $limit
     * @return array
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:44
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getSearchList($keywords, $page, $limit)
    {
        if ($keywords) {
            foreach ($keywords as $k => $v) {
                $this->getMysql()->whereOr($k, $v);
            }
        }
        $data  = $this->getMysql()
            ->withTotalCount()
            ->orderBy('role_id', 'DESC')
            ->get($this->roleTable, [$limit * ($page - 1), $limit]);
        $total = $this->getMysql()->getTotalCount();
        return ['count' => $total, 'data' => $data];
    }


    /**
     * 获取所有数据
     *
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:45
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getAll()
    {
        return $this->getMysql()->orderBy('role_id', 'DESC')->get($this->roleTable);
    }

    /**
     * 添加数据
     *
     * @param $request
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:45
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function save($request)
    {
        //事物开启
        $this->getMysql()->startTransaction();
        $roleData = [
            'role_name' => $request['role_name'],
            'role_desc' => $request['role_desc'],
        ];
        $result   = $this->getMysql()->insert($this->roleTable, $roleData);
        if (!$result) {
            $this->getMysql()->rollback();
        }
        $roleId = $this->getMysql()->getInsertId();
        if (isset($request['permission_id'])) {
            foreach ($request['permission_id'] as $item) {
                $rolePermissionData['role_id']       = $roleId;
                $rolePermissionData['permission_id'] = $item;
                $rolePermissionResult                = $this->getMysql()->where('role_id', $roleId)->where('permission_id', $item)->getOne($this->rolePermissionTable);
                if ($rolePermissionResult) {
                    continue;
                }
                $rolePermission = $this->getMysql()->insert($this->rolePermissionTable, $rolePermissionData);
                if (!$rolePermission) {
                    $this->getMysql()->rollback();
                }
            }
        }
        //提交事务
        $this->getMysql()->commit();
        return $result;
    }

    /**
     * 修改数据
     *
     * @param $request
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:45
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function update($request)
    {
        //事物开启
        $this->getMysql()->startTransaction();
        $roleData = [
            'role_name' => $request['role_name'],
            'role_desc' => $request['role_desc'],
        ];
        $result   = $this->getMysql()->where('role_id', $request['role_id'])->update($this->roleTable, $roleData);
        if (!$result) {
            $this->getMysql()->rollback();
        }
        //通过角色id删除角色权限关系表中所有数据
        $rolePermissionResult = $this->getMysql()->where('role_id', $request['role_id'])->delete($this->rolePermissionTable);
        if (!$rolePermissionResult) {
            $this->getMysql()->rollback();
        }
        if (isset($request['permission_id'])) {
            foreach ($request['permission_id'] as $item) {
                $rolePermissionData['role_id']       = $request['role_id'];
                $rolePermissionData['permission_id'] = $item;
                $rolePermission                      = $this->getMysql()->insert($this->rolePermissionTable, $rolePermissionData);
                if (!$rolePermission) {
                    $this->getMysql()->rollback();
                }
            }
        }
        //提交事务
        $this->getMysql()->commit();
        return $result;
    }

    /**
     * description
     *
     * @param $role_id
     * @return array
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:45
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getOne($role_id)
    {
        $result = $this->getMysql()->where('role_id', $role_id)->getOne($this->roleTable);
        if ($result) {
            $result['permission_id'] = $this->getMysql()->where('role_id', $role_id)->get($this->rolePermissionTable, null, 'permission_id');
            $result['permission_id'] = array_column($result['permission_id'], 'permission_id');
        }
        return $result ? $result : [];
    }


    /**
     * 删除数据
     *
     * @param $id
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:45
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function delete($id)
    {
        //事物开启
        $this->getMysql()->startTransaction();

        if (is_array($id)) {
            $result = $this->getMysql()->whereIn('role_id', $id)->delete($this->roleTable);
        } else {
            $result = $this->getMysql()->where('role_id', $id)->delete($this->roleTable);
        }
        if (!$result) {
            $this->getMysql()->rollback();
        }
        //通过角色id删除角色权限关系表中所有数据
        $rolePermissionResult = $this->getMysql()->whereIn('role_id', $id)->delete($this->rolePermissionTable);
        if (!$rolePermissionResult) {
            $this->getMysql()->rollback();
        }
        //提交事务
        $this->getMysql()->commit();
        return $result;
    }

    /**
     * 通过名称获取单条数据（唯一验证）
     *
     * @param $role_name
     * @return array
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:45
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getRoleByName($role_name)
    {
        $result = $this->getMysql()->where('role_name', $role_name)->getOne($this->roleTable);
        return $result ? $result : [];
    }

    /**
     * 通过角色id获取权限id
     *
     * @param $roleIds
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:46
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getPermissionIdsByRoleIds($roleIds)
    {
        return $this->getMysql()->whereIn('role_id', implode(',', $roleIds))->getColumn($this->rolePermissionTable, 'permission_id');
    }
}