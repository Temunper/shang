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

    /**
     * 传入$params 文章id数组
     * 审批文章，修改status =》2
     * 返回受影响行数
     */
    public function change_status($params,$status)
    {
        return Db::table($this->table)->where($params)->update(['status' => $status]);
    }


}