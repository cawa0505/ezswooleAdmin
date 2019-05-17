<?php
/**
 * ClassDescription
 * @category Category
 * @package Package
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 19-4-29 上午9:52
 */

namespace App\Model;

use EasySwoole\Component\Context\Exception\ModifyError;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;

/**
 * Class MenuModel
 * @package App\Model
 */
class MenuModel extends BaseModel
{
    /**
     * 菜单表
     * @var string
     */
    private $menuTable = 'rd_menu';

    /**
     * 搜索列表
     *
     * @param $keywords
     * @param $page
     * @param $limit
     * @param string $columns
     * @return array
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:42
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
        $data  = $this->getMysql()->withTotalCount()->get($this->menuTable, [$limit * ($page - 1), $limit], $columns);
        $total = $this->getMysql()->getTotalCount();
        return ['count' => $total, 'data' => $data];
    }

    /**
     * 通过id获取单条数据
     *
     * @param $id
     * @return array
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:42
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getOne($id)
    {
        $result = $this->getMysql()->where('menu_id', $id)->getOne($this->menuTable);
        return $result ? $result : [];
    }

    /**
     * 通过名称获取单条数据（唯一验证）
     *
     * @param $menu_name
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:42
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getMenuByName($menu_name)
    {
        return $this->getMysql()->where('menu_name', $menu_name)->getOne($this->menuTable);
    }

    /**
     * 添加数据
     *
     * @param $request
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:43
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function save($request)
    {
        $data = [
            'menu_name'     => $request['menu_name'],
            'menu_url'      => $request['menu_url'],
            'parent_id'     => $request['parent_id'],
            'menu_identify' => isset($request['menu_identify']) ? $request['menu_identify'] : '',
            'menu_icon'     => isset($request['menu_icon']) ? $request['menu_icon'] : '',
            'create_time'   => time(),
            'update_time'   => time()
        ];
        $this->getMysql()->insert($this->menuTable, $data);
        return $this->getMysql()->getAffectRows();
    }

    /**
     * 数据修改
     *
     * @param $request
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:43
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function update($request)
    {
        $data = [
            'menu_name'     => $request['menu_name'],
            'menu_url'      => $request['menu_url'],
            'parent_id'     => $request['parent_id'],
            'menu_identify' => isset($request['menu_identify']) ? $request['menu_identify'] : '',
            'menu_icon'     => isset($request['menu_icon']) ? $request['menu_icon'] : '',
            'update_time'   => time()
        ];
        $this->getMysql()->where('menu_id', $request['menu_id'])->update($this->menuTable, $data);
        return $this->getMysql()->getAffectRows();
    }


    /**
     * 数据删除
     *
     * @param $id
     * @return mixed
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:43
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function delete($id)
    {
        if (is_array($id)) {
            return $this->getMysql()->whereIn('menu_id', $id)->delete($this->menuTable);
        }
        return $this->getMysql()->where('menu_id', $id)->delete($this->menuTable);
    }

    /**
     * 通过父级id获取归属菜单
     *
     * @param $parentId
     * @return array
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:43
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getSubordinateList($parentId)
    {
        $result = $this->getMysql()->where('parent_id', $parentId)->get($this->menuTable);
        return $result ? $result : [];
    }
}