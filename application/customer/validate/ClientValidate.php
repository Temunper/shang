<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/29
 * Time: 15:55
 */

namespace app\customer\validate;


use think\Validate;

class ClientValidate extends Validate
{
    protected $rule = [
        'user|用户名' => 'require',
        'pass|密码' => 'require',
        'verify|验证码' => 'require|captcha',
    ];

}