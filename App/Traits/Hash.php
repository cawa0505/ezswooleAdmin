<?php
/**
 * ClassDescription
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/19 16:49
 */

namespace App\Traits;

/**
 * Trait Hash
 * @package App\Traits
 */
trait Hash
{
    /**
     * 生成密码
     * @param $string
     *
     * @return string
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/19 17:03
     */
    public function makePasswordHash($string)
    {
        return \EasySwoole\Utility\Hash::makePasswordHash($string);

    }

    /**
     * 检测是否匹配
     * @param $string
     * @param $hash
     *
     * @return bool
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/19 17:03
     */
    public function validatePasswordHash($string,$hash)
    {
        return \EasySwoole\Utility\Hash::validatePasswordHash($string,$hash);
    }
}