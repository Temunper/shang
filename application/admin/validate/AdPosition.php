<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/20
 * Time: 9:18
 */

namespace app\admin\validate;


use think\Validate;

class AdPosition extends Validate
{
    protected $rule = [
        ['ad_id', 'require'],
        ['project_id', 'require'],
        ['name', 'require'],
        ['image', 'require'],
        ['sort', 'require|integer'],
    ];

}