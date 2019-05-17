<?php
/**
 * ClassDescription
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/16 16:16
 */

namespace App\Service;

use App\Traits\Hash;
use App\Traits\JWT;
use App\Traits\RedisTrait;
use App\Traits\ResponseTrait;

class BaseService
{
    use ResponseTrait, JWT, Hash, RedisTrait;
}
