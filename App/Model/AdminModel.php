<?php
/**
 * ClassDescription
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/15 15:58
 */

namespace App\Model;

use App\Traits\Hash;
use EasySwoole\Component\Context\Exception\ModifyError;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;

/**
 * Class AdminModel
 * @package App\Model
 */
class AdminModel extends BaseModel
{
    use Hash;
    /**
     * 用户表
     * @var string
     */
    private $adminTable = 'rd_admin';

    /**
     * 用户角色关系表
     * @var string
     */
    private $adminRoleTable = 'rd_admin_role';

    /**
     * 角色表
     * @var string
     */
    private $roleTable = 'rd_role';

    /**
     * 获取列表数据
     *
     * @param $keywords
     * @param $page
     * @param $limit
     * @param string $columns
     * @return array|bool
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:39
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getSearchList($keywords, $page, $limit, $columns = '*')
    {
        if ($keywords) {
            foreach ($keywords as $k => $v) {
                $this->getMysql()->whereOr($k, $v);
            }
        }
        $data  = $this->getMysql()->withTotalCount()
            ->orderBy('admin_id', 'DESC')
            ->get($this->adminTable, [$limit * ($page - 1), $limit], $columns);
        $total = $this->getMysql()->getTotalCount();
        if (!$total) return false;
        return ['count' => $total, 'data' => $data];
    }

    /**
     * 通过id获取单条信息
     *
     * @param $id
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:39
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getOne($id)
    {
        return $this->getMysql()->where('admin_id', $id)->getOne($this->adminTable);
    }


    /**
     * 通过id获取特定字段
     *
     * @param $id
     * @param string $column
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:39
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getValue($id, $column = '*')
    {
        return $this->getMysql()->where('admin_id', $id)->getValue($this->adminTable, $column);
    }

    /**
     * 添加数据
     *
     * @param $request
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:39
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function save($request)
    {
        $adminData = [
            'username'    => $request['username'],
            'password'    => encrypt($request['password']),
            'mobile'      => $request['mobile'],
            'status'      => $request['status'],
            'salt'        => $request['username'],
            'create_time' => time(),
            'update_time' => time(),
        ];

        //开启事务
        $this->getMysql()->startTransaction();
        $result = $this->getMysql()->insert($this->adminTable, $adminData);
        if (!$result) {
            $this->getMysql()->rollback();
        }
        $adminId = $this->getMysql()->getInsertId();
        if (isset($request['role_id'])) {
            foreach ($request['role_id'] as $item) {
                $adminRoleData['admin_id'] = $adminId;
                $adminRoleData['role_id']  = $item;
                $adminRoleResult           = $this->getMysql()->where('admin_id', $adminId)->where('role_id', $item)->getOne($this->adminRoleTable);
                if ($adminRoleResult) {
                    continue;
                }
                $rolePermission = $this->getMysql()->insert($this->adminRoleTable, $adminRoleData);
                if (!$rolePermission) {
                    $this->getMysql()->rollback();
                }
            }
        }
        $this->getMysql()->commit();
        return $this->getMysql()->getAffectRows();
    }


    /**
     * 更新数据
     *
     * @param $request
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:39
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function update($request)
    {
        //开启事务
        $this->getMysql()->startTransaction();

        $userInfo = $this->getMysql()->where('admin_id', $request['admin_id'])->getOne($this->adminTable);
        if ($this->validatePasswordHash($request['password'], $userInfo['password'])) {
            $data['password'] = $this->makePasswordHash($request['password']);
        }
        $data   = [
            'username'    => $request['username'],
            'mobile'      => $request['mobile'],
            'status'      => $request['status'],
            'update_time' => time(),
        ];
        $result = $this->getMysql()->where('admin_id', $request['admin_id'])->update($this->adminTable, $data);
        if (!$result) {
            $this->getMysql()->rollback();
        }
        //通过用户id删除用户角色关系表中所有数据
        $adminRoleResult = $this->getMysql()->where('admin_id', $request['admin_id'])->delete($this->adminRoleTable);
        if (!$adminRoleResult) {
            $this->getMysql()->rollback();
        }
        if (isset($request['role_id'])) {
            foreach ($request['role_id'] as $item) {
                $adminRoleData['admin_id'] = $request['admin_id'];
                $adminRoleData['role_id']  = $item;
                $adminRole                 = $this->getMysql()->insert($this->adminRoleTable, $adminRoleData);
                if (!$adminRole) {
                    $this->getMysql()->rollback();
                }
            }
        }
        $this->getMysql()->commit();
        return $this->getMysql()->getAffectRows();
    }

    /**
     * 通过主键id删除
     *
     * @param $id
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:40
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function delete($id)
    {
        //事物开启
        $this->getMysql()->startTransaction();

        if (is_array($id)) {
            $result = $this->getMysql()->whereIn('admin_id', $id)->delete($this->adminTable);
        } else {
            $result = $this->getMysql()->where('admin_id', $id)->delete($this->adminTable);
        }
        if (!$result) {
            $this->getMysql()->rollback();
        }
        //通过用户id删除用户角色关系表中所有数据
        $adminRoleResult = $this->getMysql()->whereIn('admin_id', $id)->delete($this->adminRoleTable);
        if (!$adminRoleResult) {
            $this->getMysql()->rollback();
        }
        //提交事务
        $this->getMysql()->commit();
        return $result;
    }

    /**
     * 修改登录时间
     *
     * @param $username
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:40
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function updateLoginTime($username)
    {
        $data ['last_login_time'] = time();
        $data ['last_login_ip']   = getIp();

        $this->getMysql()->where('username', $username)->update($this->adminTable, $data);
        return $this->getMysql()->getAffectRows();
    }

    /**
     * 通过名称获取单条数据（唯一验证）
     *
     * @param $username
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:40
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getUserByUserName($username)
    {
        return $this->getMysql()->where('username', $username)->getOne($this->adminTable);
    }

    /**
     * 通过角色id获取名称
     *
     * @param $where
     * @param string $columns
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:40
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getNameByRoleId($where, $columns = '*')
    {
        foreach ($where as $k => $v) {
            $this->getMysql()->where($k, $v);
        }
        return $this->getMysql()->get($this->adminTable, null, $columns);
    }

    /**
     * 通过管理员id获取所有角色id
     *
     * @param $adminId
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:41
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getRoleIdsByAdminId($adminId)
    {
        return $this->getMysql()->where('admin_id', $adminId)->getColumn($this->adminRoleTable, 'role_id');
    }
}