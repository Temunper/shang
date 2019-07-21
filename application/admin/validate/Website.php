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
//        ['theme_id','require'],
//        ['filing_number','require'],
//        ['logo','require'],
//        ['company_name','require'],
//        ['company_addr','require'],
//        ['phone','require'],
//        ['type','require'],
//        ['keywords','require'],
//        ['description','require'],
    ];
}