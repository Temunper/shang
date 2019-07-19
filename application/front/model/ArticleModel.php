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
    public function show_article($param, $limit)
    {
        //根据排序sort
        $result = Db::table($this->table)
            ->where($param)
            ->where('status', '=', 1)
            ->order('update_time', 'desc')
            ->paginate($limit);
        //   return dump($this->getLastSql());
        return $result;
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
}