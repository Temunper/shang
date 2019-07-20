<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/20
 * Time: 14:22
 */

namespace app\admin\validate;


use think\Validate;

class Clientproject extends Validate
{
    protected $rule = [
        ['client_id', 'require'],
        ['project_id', 'require']
    ];
}