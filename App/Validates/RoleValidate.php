<?php

namespace App\Validates;


use think\Validate;

/**
 * Class Admin
 * @package App\Validates
 */
class RoleValidate extends Validate
{
    protected $rule = [
        'role_id'   => 'require',
        'role_name' => 'require',
        'role_desc' => 'require',
    ];

    protected $message = [
        'role_id.require'   => '角色ID不能为空',
        'role_name.require' => '角色名称不能为空',
        'role_desc.require' => '角色描述不能为空',
    ];

    protected $scene = [
        'create' => ['role_name', 'act_list', 'role_desc'],
        'update' => ['role_id', 'role_name', 'role_desc'],
    ];
}
