<?php
/**
 * 公用控制器
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/18 15:51
 */

namespace App\HttpController;

use App\Traits\JWT;
use App\Traits\RedisTrait;
use App\Traits\ResponseTrait;
use App\Utility\Pool\RedisPool;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\Http\Message\Status;

/**
 * Class Common
 * @package App\HttpController
 */
abstract class Common extends Template
{
    use JWT, ResponseTrait, RedisTrait;

    /**
     * @var string  令牌
     */
    protected $accessToken = '';

    /**
     * @var array  管理员信息
     */
    protected $adminInfo = [];

    /**
     * 判断 accessToken 是否携带
     * @return bool
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/18 10:11
     */
    private function checkAccessToken()
    {
        $token = $this->request()->getCookieParams('token');
        if (!empty($token)) {
            $this->accessToken = $token;
            return true;
        }
        $this->accessToken = null;
        return false;
    }

    /**
     * 判断token并处理
     *
     * @return bool
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/30 16:55
     *
     * @throws PoolEmpty
     * @throws PoolException
     */
    protected function handlerToken()
    {
        // 是否携带Token
        if (empty($this->checkAccessToken())) {
            return false;
        }
        // 判断Token 验证token
        $decodeToken = $this->jwtDecode($this->accessToken);
        if ($decodeToken['status'] == 0) {
            return false;
        }
        $useInfo         = $decodeToken['data']['data'];
        $user            = object_array($useInfo);
        $this->adminInfo = $user;
        // 判断 Token 是否伪造
        $redis    = RedisPool::defer();
        $jwtToken = $redis->get('access_token_' . $user['admin_id']);
        if (strcmp($this->accessToken, $jwtToken) != 0) {
            //帐户已在其它设备登录
            return false;
        }
        return true;
    }

    /**
     * 自定义返回json
     * @param $result
     * @param string $msg
     *
     * @return bool
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/19 11:16
     */
    protected function responseJson($result, $msg = '请求成功')
    {
        if (!$this->response()->isEndResponse()) {
            $data         = ['code' => 0, 'msg' => $msg];
            $data['code'] = 0;
            if (isset($result['code'])) {
                $data['code'] = $result['code'];
            }
            if (isset($result['count'])) {
                $data['count'] = $result['count'];
            }
            if (isset($result['data'])) {
                $data['data'] = $result['data'];
            }
            if (isset($result['message'])) {
                $data['msg'] = $result['message'];
            }
            $this->response()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
            $this->response()->withStatus(STATUS::CODE_OK);
            $this->response()->end();
            return true;
        }
        return false;
    }
}