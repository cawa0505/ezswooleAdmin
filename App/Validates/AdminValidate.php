<?php

namespace App\Validates;


use think\Validate;

/**
 * Class Admin
 * @package App\Validates
 */
class AdminValidate extends Validate
{
    protected $rule = [
        'username' => 'require',
        'password' => 'require',
        'mobile'   => 'require',
        'code'     => 'require',
    ];

    protected $message = [
        'username.require' => '用户名不能为空',
        'password.require' => '密码不能为空',
        'mobile.require'   => '手机号不能为空',
        'code.require'     => '验证码不能为空',
    ];

    protected $scene = [
        'login'  => ['username', 'password', 'code'],
        'create' => ['username', 'password', 'mobile', 'store_id'],
        'update' => ['admin_id', 'username', 'password', 'mobile', 'store_id'],
    ];
}
