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
        ['sort|排序', 'require|integer'],
        ['name|分类名', 'require|length:1,255'],
        ['describe|描述', 'require|length:1,255'],
        ['keywords|关键词', 'require|length:1,255'],
        ['image', 'require|length:1,255'],
        ['pinyin', 'require'],
        ['f_class_id', 'require']
    ];
}