<?php
/**
 * Created by PhpStorm.
 * Clientw: vpanda
 * Date: 2019/7/12
 * Time: 15:32
 */

namespace app\customer\validate;
class ArticleValidate extends BaseValidate
{
    protected $rule = [
        'type | 项目类型' => 'require ',
        'project_id|项目id' => 'require',
        'title|标题' => 'require|length:2,25',
        'lis_img|列表图片' => 'require|image',
        'brief|简介' => 'require',
        'content|内容' => 'require',
        'source|来源' => 'require',
        'author|作者' => 'require',
    ];

    protected $msg = [
        'type.require' => '项目类型必须',
        'project_id.require' => '项目名称必须',
        'title.require' => '标题名称必须',
        'title.length' => '标题长度为2-25',
        'lis_img.require' => '列表图片必须',
        'lis_img.image' => '必须是图片类型',
        'brief.require' => '简介必须',
        'content.require' => '内容必须',
        'source.require' => '来源必须',
        'author.require' => '作者称必须',
    ];



}