<?php

namespace App\Validates;


use think\Validate;

/**
 * Class Admin
 * @package App\Validates
 */
class PermissionValidate extends Validate
{
    protected $rule = [
        'permission_id' => 'require',
        'name'          => 'require',
        'url'           => 'require',
        'parent_id'     => 'require',
    ];

    protected $message = [
        'permission_id.require' => '权限名字不能为空',
        'name.require'          => '权限名称不能为空',
        'url.require'           => '访问地址不能为空',
        'parent_id.require'     => '权限组不能为空',
    ];

    protected $scene = [
        'create' => ['name', 'url', 'parent_id'],
        'update' => ['permission_id', 'name', 'url', 'parent_id'],
    ];
}
