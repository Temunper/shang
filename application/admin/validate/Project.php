<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/20
 * Time: 15:00
 */

namespace app\admin\validate;


use think\Validate;

class Project extends Validate
{
    protected $rule = [
        ['name', 'require'],
//        ['abbr', 'require'],
//        ['pattern', 'require'],
//        ['crowd', 'require'],
//        ['area', 'require'],
//        ['client_phone', 'require|checkPhone','客户号码不为空|手机号码格式不正确'],
//        ['400', 'require'],
//        ['company_name', 'require'],
//        ['company_addr', 'require'],
//        ['superiority', 'require'],
//        ['analysis', 'require'],
//        ['prospect', 'require'],
//        ['summary', 'require'],
//        ['contact', 'require'],
//        ['class_id', 'require'],
//        ['money', 'require'],
//        ['keywords', 'require'],
//        ['description', 'require'],
//        ['kf_type', 'require|integer']
    ];

    public function checkPhone($value)
    {
        return 1 === preg_match('/^1[34578]\d{9}$/', $value);
    }
}