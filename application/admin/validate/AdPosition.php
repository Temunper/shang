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
        ['name|广告位项目名', 'require|length:1,255'],
        ['image', 'require|length:1,255'],
        ['sort', 'require|integer'],
    ];

}