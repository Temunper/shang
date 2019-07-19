<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/18
 * Time: 13:55
 */

namespace app\admin\model;


use think\Db;

class ArticleModel
{
    protected $table = 'article';

    //根据类型type_id 显示文章咨询
    public function show_article($article_id, $limit)
    {
        //根据排序sort
        $result = Db::table($this->table)
            ->where('type', $article_id)
            ->where('status', '=', 1)
            ->order('time', 'desc')
            ->paginate($limit);
        return $result;
    }


}