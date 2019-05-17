<?php
/**
 * ClassDescription
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/23 12:02
 */

namespace App\Model;

use EasySwoole\Component\Context\Exception\ModifyError;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;

/**
 * Class PermissionModel
 * @package App\Model
 */
class PermissionModel extends BaseModel
{
    /**
     * @var string
     */
    private $table = 'rd_permission';

    /**
     * 搜索列表
     *
     * @param $keywords
     * @param $page
     * @param $limit
     * @return array
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:43
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getSearchList($keywords, $page, $limit)
    {

        if ($keywords) {
            foreach ($keywords as $k => $v) {
                $this->getMysql()->where($k, $v);
            }
        }
        $data  = $this->getMysql()
            ->withTotalCount()
            ->orderBy('permission_id', 'ASC')
            ->get($this->table, [$limit * ($page - 1), $limit]);
        $total = $this->getMysql()->getTotalCount();
        return ['count' => $total, 'data' => $data];

    }

    /**
     * 获取所有数据
     *
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:43
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getAll()
    {
        return $this->getMysql()->get($this->table);
    }

    /**
     * 添加数据
     *
     * @param $request
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:44
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function save($request)
    {
        $data = [
            'name'      => $request['name'],
            'url'       => $request['url'],
            'parent_id' => $request['parent_id']
        ];
        return $this->getMysql()->insert($this->table, $data);
    }

    /**
     * 修改数据
     *
     * @param $request
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:44
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function update($request)
    {

        $data = [
            'name'      => $request['name'],
            'url'       => $request['url'],
            'parent_id' => $request['parent_id']
        ];
        $this->getMysql()->where('permission_id', $request['permission_id'])->update($this->table, $data);
        return $this->getMysql()->getAffectRows();

    }

    /**
     * 删除数据
     *
     * @param $id
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:44
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function delete($id)
    {

        if (is_array($id)) {
            return $this->getMysql()->whereIn('permission_id', $id)->delete($this->table);
        }
        return $this->getMysql()->where('permission_id', $id)->delete($this->table);

    }

    /**
     * 获取单条数据
     *
     * @param $id
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:44
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getOne($id)
    {
        return $this->getMysql()->where('permission_id', $id)->getOne($this->table);
    }

    /**
     * 通过父级id获取归属
     *
     * @param $parentId
     * @return array
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:44
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getSubordinateList($parentId)
    {
        $result = $this->getMysql()->where('parent_id', $parentId)->get($this->table);
        return $result ? $result : [];
    }

    /**
     * 通过权限id获取所有权限
     *
     * @param $permissionIds
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:44
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getPermissionUrlByPermissionIds($permissionIds)
    {
        return $this->getMysql()->whereIn('permission_id', implode(',', $permissionIds))->getColumn($this->table, 'url');
    }

    /**
     * 通过名称获取单条数据（唯一验证）
     *
     * @param $name
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:44
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getPermissionByName($name)
    {
        return $this->getMysql()->whereIn('name', $name)->getOne($this->table);
    }

    /**
     * 获取所有权限的url
     *
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:44
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getAllPermissionUrl()
    {
        return $this->getMysql()->where('status', 0)->getColumn($this->table, 'url');
    }
}