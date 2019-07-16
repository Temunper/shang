<?php
/**
 * Created by PhpStorm.
 * Clientw: vpanda
 * Date: 2019/7/11
 * Time: 11:56
 */

namespace app\customer\validate;
class UserValidate extends BaseValidate
{
    protected $rule = [
        'name|用户名' => 'require',
        'client|账户' => 'require',
        'password|用户名' => 'require',
    ];

}

