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
        ['domain|域名','require|length:1,255'],
        ['theme_id','require'],
        ['filing_number|备案号','require|length:1,50'],
        ['logo','require|length:1,255'],
        ['company_name|公司名','require|length:1,100'],
        ['company_addr|公司简称','require|length:1,255'],
        ['phone|手机号码','require|checkPhone','客户号码不为空|手机号码格式不正确'],
        ['type','require'],
        ['keywords|关键词','require|length:1,255'],
        ['description|描述','require|length:1,500'],
        ['title|标题','require|length:1,45'],
    ];
    public function checkPhone($value)
    {
        return 1 === preg_match('/^1[34578]\d{9}$/', $value);
    }
}