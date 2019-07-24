<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/12
 * Time: 16:33
 */

namespace app\front\model;


use think\Db;
use think\Model;

class ArticleModel extends Model
{
    protected $table = 'article';
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';


    //根据类型type_id 显示文章咨询
    public function show_article($type)
    {
        //根据排序sort
        return Db::table($this->table)
            ->where('type', $type)
            ->where('status', '=', '2')
            ->order('update_time', 'desc')
            ->paginate(15);

    }

    //搜索一篇文章，传入文章id
    public function show_one_article($article_id)
    {
        //根据排序sort
        return Db::table($this->table)->where('article_id', $article_id)->find();

    }

    //得到所有的文章
    function get_all_article()
    {
        $result = Db::table($this->table)
            ->where('status', '=', '2')
            ->field('type,title,article_id,lis_img,create_time')
            ->order('article_id', 'desc')
            ->select();
        return $result;
    }

    //查询某项目下所有相关的项目咨询，传入项目id
    public function get_some_article($project_id)
    {
        return Db::table($this->table)->where('status', '=', 2)
            ->where('type', '=', 1)
            ->where('project_id', $project_id)
            ->order('article_id', 'desc')
            ->paginate(10);

    }
}