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

class Article extends Model
{
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    protected $field = true;


    //根据类型type_id 显示文章咨询
    public function show_article($type)
    {
        //根据排序sort
        return self::where('type', $type)
            ->where('status', '=', '2')
            ->order('update_time', 'desc')
            ->paginate(10);
    }

    //搜索一篇文章，传入文章id
    public function show_one_article($article_id)
    {
        //根据排序sort
        return self::where('article_id', $article_id)->find();

    }

    //项目type id 返回10条咨询
    public function get_ten_article($type)
    {
        return self::where('type', $type)->where('status', 2)
            ->order('update_time', 'desc')
            ->limit(10)->column('article_id,type,title,lis_img,brief,update_time');
    }


//得到所有的文章
    function get_all_article()
    {
        $result = self::where('status', '=', '2')
            ->field('type,title,article_id,lis_img,create_time')
            ->order('article_id', 'desc')
            ->select();
        return $result;
    }

//查询某项目下所有相关的项目咨询，传入项目id
    public function get_some_article($project_id)
    {
        return self::where('status', '=', 2)
            ->where('type', '=', 1)
            ->where('project_id', $project_id)
            ->order('update_time', 'desc')
            ->paginate(10);
        // dump($this->getLastSql());die;
    }

//文章时间修改器
    public function getCreateTimeAttr($create_time)
    {
        return date('Y-m-d H:i:s', $create_time);
    }

//文章时间修改器
    public function getUpdateTimeAttr($update_time)
    {
        return date('Y-m-d', $update_time);
    }

//文章类型获取器
    public function getTypeAttr($type)
    {
        //状态：1未审核，2审核通过，3用户删除，4管理员删除
        $value = [1 => '项目资讯', 2 => '创业资讯', 3 => '新闻资讯', 4 => '热门专题', 5 => '创业故事'];
        return $value[$type];
    }

//作者名称获取器
    public function getAuthorAttr($author)
    {
        return Db::table('client')->where('client_id', $author)->value('name');
    }

}