<?php
/**
 * Created by PhpStorm.
 * Clientw: vpanda
 * Date: 2019/7/12
 * Time: 15:32
 */

namespace app\customer\validate;
use think\Validate;

class ArticleValidate extends  Validate
{
    protected   $rule = [
        'type|类型' => 'require',
        'title|标题' => 'require|length:2,20',
        'image|列图' => 'require',
        'brief|简介' => 'require|length:10,40',
        'content|内容' => 'require',
        'source|来源' => 'require',
        'keywords|关键词' => 'require|length:8,40',
        'description|描述' => 'require|length:20,160',
    ];




}