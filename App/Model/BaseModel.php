<?php
/**
 * ClassDescription
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/30 15:04
 */

namespace App\Model;

use App\Traits\MysqlTrait;
use App\Traits\RedisTrait;
use App\Traits\ResponseTrait;

class BaseModel
{
    use ResponseTrait, RedisTrait, MysqlTrait;
}
