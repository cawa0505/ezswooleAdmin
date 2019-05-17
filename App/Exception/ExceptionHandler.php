<?php
/**
 * ClassDescription
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/30 15:37
 */

namespace App\Exception;

use App\Traits\DingTalkTrait;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Trace\Trigger;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\Http\Message\Status;

class ExceptionHandler
{
    use DingTalkTrait;

    /**
     * Description
     * @param \Throwable $exception
     * @param Request $request
     * @param Response $response
     *
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/30 19:15
     *
     */
    public static function handle(\Throwable $exception, Request $request, Response $response)
    {
        $msg = "[file:{$exception->getFile()}][line:{$exception->getLine()}]{$exception->getMessage()}";
        (new ExceptionHandler)->sendMarkdown('异常错误', $msg);
        $Trigger = new Trigger(Logger::getInstance());
        //记录错误异常日志,等级为Exception
        $Trigger->throwable($exception);
        //记录错误信息,等级为FatalError
        //$Trigger->error($exception->getMessage());
        //直接给前端响应500并输出系统繁忙
        $response->withStatus(Status::CODE_GATEWAY_TIMEOUT);
        $response->write('系统繁忙,请稍后再试');
    }
}