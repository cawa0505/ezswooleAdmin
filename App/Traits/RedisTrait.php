<?php
/**
 * Redis工具类
 * @category Category
 * @package Package
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 19-5-17 下午4:20
 */

namespace App\Traits;


use App\Utility\Pool\RedisObject;
use App\Utility\Pool\RedisPool;
use EasySwoole\Component\Context\ContextManager;
use EasySwoole\Component\Context\Exception\ModifyError;
use EasySwoole\Component\Pool\AbstractPool;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Config;
use Swoole\Coroutine;

trait RedisTrait
{
    /**
     * Redis 初始化
     * @return mixed|null
     *
     * @throws PoolEmpty
     * @throws PoolException
     * @throws ModifyError
     * @author qap <qiuapeng921@163.com>
     * @date 19-5-17 下午3:54
     */
    protected function getRedis(): RedisObject
    {
        $key = md5(static::class);
        $obj = ContextManager::getInstance()->get($key);
        if ($obj) {
            return $obj;
        }
        $pool = PoolManager::getInstance()->getPool(RedisPool::class);
        if ($pool instanceof AbstractPool) {
            $obj = $pool->getObj(Config::getInstance()->getConf('REDIS.POOL_TIME_OUT'));
            if ($obj) {
                Coroutine::defer(function () use ($pool, $obj) {
                    $pool->recycleObj($obj);
                });
                ContextManager::getInstance()->set($key, $obj);
                return $obj;
            } else {
                throw new PoolEmpty(RedisPool::class . " pool is empty");
            }
        } else {
            throw new PoolException(RedisPool::class . " convert to pool error");
        }
    }

}
