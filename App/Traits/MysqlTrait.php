<?php
/**
 * Mysql工具类
 * @category Category
 * @package Package
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 19-5-17 下午4:20
 */

namespace App\Traits;

use App\Utility\Pool\MysqlObject;
use App\Utility\Pool\MysqlPool;
use EasySwoole\Component\Context\ContextManager;
use EasySwoole\Component\Context\Exception\ModifyError;
use EasySwoole\Component\Pool\AbstractPool;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Config;
use Swoole\Coroutine;

trait MysqlTrait
{
    /**
     * Mysql初始化
     * @return mixed|null
     *
     * @throws ModifyError
     * @throws PoolEmpty
     * @throws PoolException
     * @author qap <qiuapeng921@163.com>
     * @date 19-5-17 下午3:56
     */
    protected function getMysql()
    {
        $key = md5(static::class);
        $obj = ContextManager::getInstance()->get($key);
        if ($obj) {
            return $obj;
        }
        $pool = PoolManager::getInstance()->getPool(MysqlPool::class);
        if ($pool instanceof AbstractPool) {
            $obj = $pool->getObj(Config::getInstance()->getConf('Mysql.POOL_TIME_OUT'));
            if ($obj) {
                Coroutine::defer(function () use ($pool, $obj) {
                    $pool->recycleObj($obj);
                });
                ContextManager::getInstance()->set($key, $obj);
                return $obj;
            } else {
                throw new PoolEmpty(MysqlPool::class . " pool is empty");
            }
        } else {
            throw new PoolException(MysqlPool::class . " convert to pool error");
        }
    }

}
