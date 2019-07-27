<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/21
 * Time: 9:50
 */

namespace app\admin\validate;


use think\Validate;

class Website extends Validate
{
    protected $rule = [
         ['domain','require'],
        ['theme_id','require'],
        ['filing_number','require'],
        ['logo','require'],
        ['company_name','require'],
        ['company_addr','require'],
        ['phone','require|checkPhone','客户号码不为空|手机号码格式不正确'],
        ['type','require'],
        ['keywords','require'],
        ['description','require'],
    ];
    public function checkPhone($value)
    {
        return 1 === preg_match('/^1[34578]\d{9}$/', $value);
    }
}