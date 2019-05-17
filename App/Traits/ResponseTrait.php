<?php

namespace App\Traits;

/**
 * Trait ResponseTrait
 * @package App\Traits
 */
trait ResponseTrait
{
    /**
     * 拼装成功数据
     * @param array $data
     *
     * @return array
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/16 12:01
     */
    public function success($data = [])
    {
        $response = [
            'code'    => 200,
            'data'    => empty($data) ? [] : $data,
            'message' => '执行成功'
        ];
        return $response;
    }

    /**
     * 异常返回
     * @param int $code
     * @param string $msg
     *
     * @return array
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/16 12:01
     */
    public function fail($code, $msg = '')
    {
        return ['code' => $code, 'message' => $msg];
    }

    /**
     * 错误返回
     * @param int $code
     * @param string $msg
     * @param array $error
     *
     * @return array
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/16 12:01
     */
    public function error($code, $msg = '', $error = [])
    {
        return ['code' => $code, 'message' => $msg, 'error' => $error];
    }
}
