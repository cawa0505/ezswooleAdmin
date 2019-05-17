<?php
/**
 * 管理员登录相关
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/15 18:16
 */

namespace App\HttpController\Backend;

use App\HttpController\Common;
use App\Service\Backend\AdminService;
use App\Utility\Pool\RedisPool;
use EasySwoole\Component\Context\Exception\ModifyError;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\VerifyCode\Conf;
use EasySwoole\VerifyCode\VerifyCode;

/**
 * Class Auth
 * @package App\HttpController\Backend
 */
class Auth extends Common
{
    /**
     *
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/18 17:42
     */
    public function index()
    {
        $this->render('backend.auth.login', ['title' => 'EsAdmin']);
    }

    /**
     * 验证处理
     *
     * @param string|null $action
     * @return bool|null
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:49
     * @throws PoolEmpty
     * @throws PoolException
     */
    function onRequest(?string $action): ?bool
    {
        if (parent::onRequest($action)) {
            if ($this->handlerToken()) {
                $this->response()->redirect('/backend/index');
            }
            return true;
        }
        return false;
    }

    /**
     *
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/18 17:42
     */
    public function login()
    {
        $request  = $this->request()->getParsedBody();
        $response = loader(AdminService::class)->login($request);
        $this->responseJson($response);
    }

    /**
     * 管理员退出
     * 清除redis存储的用户token
     *
     * @return void
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:50
     * @throws PoolEmpty
     * @throws PoolException
     * @throws ModifyError
     */
    public function logout()
    {
        $this->getRedis()->del('access_token_' . $this->adminInfo['admin_id']);
        $this->getRedis()->del('user_' . $this->adminInfo['admin_id'] . '_permission');
        $response['code']    = 200;
        $response['message'] = '退出成功';
        $this->responseJson($response);
    }

    /**
     * 验证码生成
     * @return void
     *
     * @throws ModifyError
     * @author qap <qiuapeng921@163.com>
     * @date 19-4-28 上午9:16
     */
    public function code()
    {
        $config = new Conf();
        $config->setImageWidth(130)->setImageHeight(40)->setLength(4)->setFontSize(20)->setUseNoise();
        $code = new VerifyCode($config);
        $this->response()->withHeader('Content-Type', 'image/png');
        $image = $code->DrawCode();
        $code  = $image->getImageByte();
        set_context('code', strtolower($image->getImageCode()));
        $this->response()->write($code);
    }
}