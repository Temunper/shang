<?php
/**
 * Created by PhpStorm.
 * User: TEM
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

     //得到所有的文章
    function get_all_article()
    {
     $result = Db::table($this->table)
         ->where('status','=','2')
         ->field('type,title,article_id,lis_img,create_time')
         ->order('article_id','desc')
         ->select();
     return $result;
    }
}