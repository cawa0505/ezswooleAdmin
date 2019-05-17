<?php
/**
 * ClassDescription
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/15 15:20
 */

namespace App\HttpController\Frontend;

class Index extends Base
{
    public function index()
    {
        $this->render('welcome');
    }
}