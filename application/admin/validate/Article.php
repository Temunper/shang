<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/29
 * Time: 9:25
 */

namespace app\admin\validate;


use think\Validate;

class Article extends Validate
{
    protected $rule = [
        'type|类型' => 'require',
        'title|标题' => 'require|length:2,20',
        'image|列图' => 'require',
        'brief|简介' => 'require|length:5,20',
        'content|内容' => 'require',
        'source|来源' => 'require',
//        'keywords|关键词' => 'require |length:8,40',
        'description|描述' => 'require|length:40,160',
    ];
    //设置错误返回信息
    protected $msg = [
        'type' => ['require' => '项目类型必选'],
        'title' => ['require' => '标题不能为空',
            'length' => '标题长度需为2-20字符'],
        'image' => ['require' => '列图不能为空'],
        'brief' => ['require' => '简介不能为空',
            'length' => '简介需为5-20字符'],
        'content' => ['require' => '内容不能为空'],
        'source' => ['require' => '来源不能为空'],
        'keywords' => ['require' => '关键词不能为空',
            'length' => '关键词长度需为8-40个字符'],
        'description' => ['require' => '描述不能为空',
            'length' => '描述长度需为40-160个字符'],
    ];
}