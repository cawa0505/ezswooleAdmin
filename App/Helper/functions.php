<?php

use EasySwoole\Component\Context\ContextManager;
use EasySwoole\Component\Di;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\Http\Message\Request;

if (!function_exists('dd')) {
    /**
     * @param $data
     *
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/22 9:01
     */
    function dd($data)
    {
        echo '--------------------调试输出-------------------------' . PHP_EOL;
        print_r($data);
        echo '----------------------------------------------------' . PHP_EOL;
    }
}

if (!function_exists('array_map_recursive')) {
    /**
     * 输入数据过滤
     *
     * @param $filter
     * @param $data
     * @return array
     *
     * @author  邱阿朋 <apqiu@suntekcorps.com>
     * @date    2019/4/18 23:17
     */
    function array_map_recursive($filter, $data)
    {
        $result = array();
        foreach ($data as $key => $val) {
            $result[$key] = is_array($val)
                ? array_map_recursive($filter, $val)
                : call_user_func($filter, $val);
        }
        return $result;
    }
}

if (!function_exists('app')) {
    /**
     * 获取Di容器
     *
     * @param null $abstract
     * @return Di|null
     *
     * @throws Throwable
     * @author  邱阿朋 <apqiu@suntekcorps.com>
     * @date    2019/4/20 11:43
     */
    function app($abstract = null)
    {
        if (is_null($abstract)) {
            return Di::getInstance();
        }

        return Di::getInstance()->get($abstract);
    }
}
if (!function_exists('asset')) {
    /**
     * 转发静态文件
     * @param string $path
     *
     * @return string
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/16 11:43
     */
    function asset($path = '')
    {
        return \EasySwoole\EasySwoole\Config::getInstance()->getConf('document_root') . '/' . $path;
    }
}

if (!function_exists('object_array')) {
    /**
     * 对象转数组
     * @param $array
     *
     * @return array
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/18 17:48
     */
    function object_array($array)
    {
        if (is_object($array)) {
            $array = (array)$array;
        }
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = object_array($value);
            }
        }
        return $array;
    }
}


if (!function_exists('array_object')) {
    /**
     * 数组转对象
     * @param $array
     *
     * @return StdClass
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/18 17:50
     */
    function array_object($array)
    {
        if (is_array($array)) {
            $obj = new StdClass();
            foreach ($array as $key => $val) {
                $obj->$key = $val;
            }
        } else {
            $obj = $array;
        }
        return $obj;
    }
}
if (!function_exists('set_context')) {
    /**
     * 设置上下文
     * @param $key
     * @param $value
     * @param int $cid
     *
     * @return void
     *
     * @throws \EasySwoole\Component\Context\Exception\ModifyError
     * @author qap <qiuapeng921@163.com>
     * @date 19-4-28 上午9:16
     */
    function set_context($key, $value, $cid = 1)
    {
        ContextManager::getInstance()->set($key, $value, $cid);
    }
}

if (!function_exists('get_context')) {
    /**
     *获取上下文
     *
     * @param $key
     * @param int $cid
     * @return null
     *
     * @author  邱阿朋 <apqiu@suntekcorps.com>
     * @date    2019/4/18 23:17
     */
    function get_context($key, $cid = 1)
    {
        return ContextManager::getInstance()->get($key, $cid);
    }
}


if (!function_exists('del_context')) {

    /*
     * 删除上下文
     *
     * @param int $cid
     * @return void
     *
     * @author  邱阿朋 <apqiu@suntekcorps.com>
     * @date    2019/4/18 23:17
     */
    /**
     * @param int $cid
     *
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/22 8:50
     */
    function del_context($cid = 1)
    {
        ContextManager::getInstance()->destroy($cid);
    }
}

if (!function_exists('loader')) {

    /**
     * 实例化加载类
     *
     * @param $className
     * @return string | object
     *
     * @author  邱阿朋 <apqiu@suntekcorps.com>
     * @date    2019/4/20 11:43
     */
    function loader($className)
    {
        try {
            if (class_exists($className)) {
                return (new $className);
            }
            throw new Exception('class not exists:' . $className);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}

if (!function_exists('recordLog')) {
    /**
     * 日志记录
     * @param $content
     *
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/22 8:50
     */
    function recordLog($content)
    {
        print_r($content);
        Logger::getInstance()->log($content);
    }
}

if (!function_exists('encrypt')) {
    /**
     * 加密
     * @param $str
     *
     * @return string
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/22 15:48
     */
    function encrypt($str)
    {
        return md5($str);
    }
}

if (!function_exists('getIp')) {
    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    function getIp($type = 0, $adv = true)
    {
        $type = $type ? 1 : 0;
        static $ip = null;
        if (null !== $ip) {
            return $ip[$type];
        }
        if ($adv) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos = array_search('unknown', $arr);
                if (false !== $pos) {
                    unset($arr[$pos]);
                }
                $ip = trim(current($arr));
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip   = $long ? [$ip, $long] : ['0.0.0.0', 0];
        return $ip[$type];
    }
}

if (!function_exists('getUrlPath')) {
    /**
     * 获取请求地址
     * @return string
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/24 9:47
     */
    function getUrlPath()
    {
        $result = (new Request())->getUri()->getPath();
        return $result;
    }
}

if (!function_exists('arrayToTree')) {
    /**
     * 将数据格式转换成树形结构数组
     * @param $array
     *
     * @return array
     *
     * @author qap <qiuapeng921@163.com>
     * @date 19-4-28 上午11:38
     */

    function arrayToTree($array)
    {
        //第一步 构造数据
        $items = array();
        foreach ($array as $value) {
            $items[$value['permission_id']] = $value;
        }
        //第二步 遍历数据 生成树状结构
        $tree = array();
        foreach ($items as $key => $item) {
            //若不是顶级分类，则将其本身作为son放置在父类中，
            //注意：此时放置的是引用，
            //也就是说，当儿子再次出现儿子的时候，儿子与孙子重新组合成父亲与儿子的形象
            //但是，在顶级父类中存放的是儿子的引用，所以，当儿子与孙子被重新组合时，顶级父类中也被修改
            if (isset($items[$item['parent_id']])) {
                $items[$item['parent_id']]['son'][] = &$items[$key];
            } else {
                $tree[] = &$items[$key];
            }
        }
        return $tree;
    }
}

if (!function_exists('arraySort')) {
    /**
     * 二维数组根据某个字段排序
     * @param array $array 要排序的数组
     * @param string $keys 要排序的键字段
     * @param int $sort 排序类型  SORT_ASC     SORT_DESC
     *
     * @return mixed
     *
     * @author qap <qiuapeng921@163.com>
     * @date 19-4-29 上午11:49
     */
    function arraySort($array, $keys, $sort = SORT_DESC)
    {
        $keysValue = [];
        foreach ($array as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }
}
