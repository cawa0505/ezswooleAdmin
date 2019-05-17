<?php

/**
 * TpModel 模型
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/25 9:40
 */

namespace App\Utility;

use App\Utility\Pool\MysqlObject;
use App\Utility\Pool\MysqlPool;
use EasySwoole\Mysqli\TpORM;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Config;

/**
 * Class Model
 * @package App\Model
 */
class TpModel extends TpORM
{
    /**
     * 数据库前缀
     * @var string
     */
    protected $prefix;
    /**
     * 对象的表名。默认情况下将使用类名
     *
     * @var string
     */
    protected $dbTable;
    /**
     * 不带前缀的表名
     * @var string
     */
    protected $tableName;
    /**
     * @var string
     */
    protected $modelPath = '\\App\\Model';
    /**
     * 过滤字段
     * @var array
     */
    protected $fields = [];

    /**
     * 条数或者开始和结束
     * @var
     */
    protected $limit;
    /**
     * 异常
     * @var
     */
    protected $throwable;
    /**
     * @var bool
     */
    protected $createTime = false;
    /**
     * @var string
     */
    protected $createTimeName = 'create_time';
    /**
     * @var bool
     */
    protected $softDelete = false;
    /**
     * @var string
     */
    protected $softDeleteTimeName = 'delete_time';

    /**
     * Model constructor.
     * @param null $data
     */
    public function __construct($data = null)
    {
        $this->prefix = Config::getInstance()->getConf('MYSQL.prefix');
        $db           = PoolManager::getInstance()->getPool(MysqlPool::class)->getObj(Config::getInstance()->getConf('MYSQL.POOL_TIME_OUT'));
        if ($db instanceof MysqlObject) {
            parent::__construct($data);
            $this->setDb($db);
        } else {
            return null;
        }
    }

    /**
     *
     */
    public function __destruct()
    {
        $db = $this->getDb();
        if ($db instanceof MysqlObject) {
            echo "SQL:----------------" . $this->getLastQuery() . "----------------" . PHP_EOL;
            $db->gc();
            PoolManager::getInstance()->getPool(MysqlPool::class)->recycleObj($db);
            $this->setDb(null);
        }
    }

    /**
     * 添加
     * @param null $data
     *
     * @return bool|int|mixed
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/25 9:45
     */
    protected function add($data = null)
    {
        try {
            if ($this->createTime === true) {
                $data[$this->createTimeName] = time();
            }
            return parent::insert($data);
        } catch (\EasySwoole\Mysqli\Exceptions\ConnectFail $e) {
            $this->throwable = $e;
            return false;
        } catch (\EasySwoole\Mysqli\Exceptions\PrepareQueryFail $e) {
            $this->throwable = $e;
            return false;
        } catch (\Throwable $t) {
            $this->throwable = $t;
            return false;
        }
    }


    /**
     * 编辑
     * @param null $data
     *
     * @return bool|mixed
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/25 9:45
     */
    protected function edit($data = null)
    {
        try {
            return $this->update($data);
        } catch (\EasySwoole\Mysqli\Exceptions\ConnectFail $e) {
            $this->throwable = $e;
            return false;
        } catch (\EasySwoole\Mysqli\Exceptions\PrepareQueryFail $e) {
            $this->throwable = $e;
            return false;
        } catch (\Throwable $t) {
            $this->throwable = $t;
            return false;
        }
    }

    /**
     * 删除
     * @return bool|mixed|null
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/25 9:45
     */
    public function del()
    {
        try {
            if ($this->softDelete === true) {
                $data[$this->softDeleteTimeName] = time();
                return $this->update($data);
            } else {
                return parent::delete();
            }
        } catch (\EasySwoole\Mysqli\Exceptions\ConnectFail $e) {
            $this->throwable = $e;
            return false;
        } catch (\EasySwoole\Mysqli\Exceptions\PrepareQueryFail $e) {
            $this->throwable = $e;
            return false;
        } catch (\Throwable $t) {
            $this->throwable = $t;
            return false;
        }
    }

    /**
     * 查询多条
     * @return array|bool|false|null
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/25 9:45
     */
    public function select()
    {
        try {
            return parent::select();
        } catch (\EasySwoole\Mysqli\Exceptions\ConnectFail $e) {
            $this->throwable = $e;
            return false;
        } catch (\EasySwoole\Mysqli\Exceptions\PrepareQueryFail $e) {
            $this->throwable = $e;
            return false;
        } catch (\Throwable $t) {
            $this->throwable = $t;
            return false;
        }
    }


    /**
     * 查询一列数据
     *
     * @param string $name
     * @return array|bool
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:53
     */
    public function column(string $name)
    {
        try {
            return parent::column();
        } catch (\EasySwoole\Mysqli\Exceptions\ConnectFail $e) {
            $this->throwable = $e;
            return false;
        } catch (\EasySwoole\Mysqli\Exceptions\PrepareQueryFail $e) {
            $this->throwable = $e;
            return false;
        } catch (\Throwable $t) {
            $this->throwable = $t;
            return false;
        }
    }


    /**
     * 查询一个值
     * @param string $name
     *
     * @return array|bool|null
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/25 9:45
     */
    public function value(string $name)
    {
        try {
            return parent::value();
        } catch (\EasySwoole\Mysqli\Exceptions\ConnectFail $e) {
            $this->throwable = $e;
            return false;
        } catch (\EasySwoole\Mysqli\Exceptions\PrepareQueryFail $e) {
            $this->throwable = $e;
            return false;
        } catch (\Throwable $t) {
            $this->throwable = $t;
            return false;
        }
    }


    /**
     * 查询单条
     * @param null $id
     *
     * @return TpModel|array|bool|\EasySwoole\Mysqli\DbObject|\EasySwoole\Mysqli\Mysqli|mixed|null
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/25 11:44
     */
    protected function find($id = null)
    {
        try {
            if ($id) {
                return $this->byId($id);
            }
            return parent::find();
        } catch (\EasySwoole\Mysqli\Exceptions\ConnectFail $e) {
            $this->throwable = $e;
            return false;
        } catch (\EasySwoole\Mysqli\Exceptions\PrepareQueryFail $e) {
            $this->throwable = $e;
            return false;
        } catch (\Throwable $t) {
            $this->throwable = $t;
            return false;
        }
    }
}