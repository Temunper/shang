<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/20
 * Time: 10:40
 */

namespace app\admin\validate;


use think\Validate;

class Clas extends Validate
{
    protected $rule = [
        ['sort', 'require|integer'],
        ['name', 'require'],
        ['describe', 'require'],
        ['image', 'require'],
        ['pinyin', 'require'],
        ['f_class_id', 'require']
    ];
}