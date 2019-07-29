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
        ['name|项目名', 'require|length:1,255'],
        ['abbr|项目简称', 'require|length:1,255'],
        ['pattern|经营模式', 'require|length:1,255'],
        ['crowd|经营模式2', 'require|length:1,255'],
        ['area|地区', 'checkArea','地区信息不为空'],
        ['client_phone|手机号', 'require|checkPhone', '客户号码不为空|手机号码格式不正确'],
        ['num_400|400电话号码', 'require|integer'],
        ['company_name|公司名称', 'require|length:1,100'],
        ['company_addr|公司简称', 'require|length:1,255'],
        ['superiority|加盟优势', 'require|length:1,2000'],
        ['analysis|利润分析', 'require|length:1,2000'],
        ['prospect|市场前景', 'require|length:1,2000'],
        ['summary|企业介绍', 'require|length:1,2000'],
        ['contact|联系我们', 'require|length:1,500'],
        ['class_id|分类', 'require'],
        ['money|投资金额', 'require'],
        ['keywords|关键字', 'require|length:1,500'],
        ['description|描述', 'require|length:1,1000'],
        ['kf_type|客服类型', 'require|integer']
    ];

    public function checkPhone($value)
    {
        return 1 === preg_match('/^1[34578]\d{9}$/', $value);
    }

    public function checkArea($data)
    {
        return 1000000000 != $data;
    }
}