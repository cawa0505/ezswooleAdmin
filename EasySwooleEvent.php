<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;

use App\Crontab\TestTask;
use App\Process\HotReload;
use App\Process\MyProcess;
use App\Utility\Pool\MysqlPool;
use App\Utility\Pool\RedisPool;
use EasySwoole\Component\Di;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\EasySwoole\Crontab\Crontab;
use Exception;

class EasySwooleEvent implements Event
{
    /**
     * es初始化
     *
     * @return void
     *
     * @author  邱阿朋 <apqiu@suntekcorps.com>
     * @date    2019/5/17 23:34
     * @throws Exception
     */
    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');
        // 初始化mysql
        self::initMysql();
        // 初始化redis
        self::initRedis();
        // 载入项目 Conf 文件夹中所有的配置文件
        self::loadConf(EASYSWOOLE_ROOT . '/config.php');
        // 异常捕捉
        Di::getInstance()->set(SysConst::HTTP_EXCEPTION_HANDLER, [\App\Exception\ExceptionHandler::class, 'handle']);
    }

    public static function mainServerCreate(EventRegister $register)
    {
        // 开启热重启进程
        ServerManager::getInstance()->getSwooleServer()->addProcess((new HotReload('HotReload', ['disableInotify' => false]))->getProcess());
        /*
         * mysql redis 预加载
         */
        $register->add($register::onWorkerStart, function (\swoole_server $server, int $workerId) {
            if ($server->taskworker == false) {
                //每个worker进程都预创建连接
                PoolManager::getInstance()->getPool(MysqlPool::class)->preLoad(Config::getInstance()->getConf('MYSQL.POOL_MAX_NUM'));//最小创建数量
                PoolManager::getInstance()->getPool(RedisPool::class)->preLoad(Config::getInstance()->getConf('REDIS.POOL_MAX_NUM'));//最小创建数量
            }
        });
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {

    }

    /**
     * 加载自定义配置
     * @param $ConfPath
     *
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/30 17:07
     *
     */
    public static function loadConf($ConfPath)
    {
        $Conf = Config::getInstance();
        $data = require_once $ConfPath;
        foreach ($data as $key => $val) {
            $Conf->setConf((string)$key, (array)$val);
        }
    }
    /**
     * 注册mysql线程池
     *
     * @return void
     *
     * @author  邱阿朋 <apqiu@suntekcorps.com>
     * @date    2019/5/17 23:33
     * @throws Exception
     */
    public static function initMysql()
    {
        $mysqlConf = PoolManager::getInstance()->register(MysqlPool::class, Config::getInstance()->getConf('MYSQL.POOL_MAX_NUM'));
        if ($mysqlConf === null) {
            throw new Exception('注册失败!');
        }
    }

    /**
     * 注册redis线程池
     *
     * @return void
     *
     * @author  邱阿朋 <apqiu@suntekcorps.com>
     * @date    2019/5/17 23:32
     * @throws Exception
     */
    public static function initRedis()
    {
        $redisConf = PoolManager::getInstance()->register(RedisPool::class, Config::getInstance()->getConf('REDIS.POOL_MAX_NUM'));
        if ($redisConf === null) {
            throw new Exception('注册失败!');
        }
    }
}
