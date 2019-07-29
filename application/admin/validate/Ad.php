<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/20
 * Time: 14:15
 */

namespace app\admin\validate;


use think\Validate;

class Ad extends Validate
{
    protected $rule = [
        ['name|广告位名', 'require|length:2,50'],
        ['theme_id', 'require'],
        ['image', 'require|length:1,255'],
    ];

}