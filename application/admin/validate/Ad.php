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
    ['name','require'],
    ['theme_id','require'],
    ['image','require'],
];

}