<?php
/**
 * ClassDescription
 * @category Category
 * @package Package
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 19-4-29 上午10:17
 */

namespace App\Validates;


use think\Validate;

class MenuValidate extends Validate
{
    protected $rule = [
        'menu_id'   => 'require',
        'menu_name' => 'require',
        'menu_url'  => 'require',
        'parent_id' => 'require',
    ];

    protected $message = [
        'menu_id.require'   => 'id不能为空',
        'menu_name.require' => '菜单名不能为空',
        'menu_url.require'  => '菜单地址不能为空',
        'parent_id.require' => '上级id不能为空',
    ];

    protected $scene = [
        'create' => ['menu_name', 'menu_url', 'parent_id'],
        'update' => ['menu_id', 'menu_name', 'menu_url', 'parent_id'],
    ];
}