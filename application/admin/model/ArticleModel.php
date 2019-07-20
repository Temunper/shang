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
        $db = model('article');
        $db::all(function ($query) {
            $query->field('a.article_id,a.type,p.name,a.title,a.lis_img,a.brief,a.content,a.source,
                    a.create_time,a.update_time,a.status')
                ->join('project p', 'a.project_id = p.project_id', 'LEFT')
                ->where('a.status', 'in(1,2)');
        });
    }

    /**
     * 传入$params 文章id数组
     * 审批文章，修改status =》2
     * 返回受影响行数
     */
    public function change_status($params, $status)
    {
        return Db::table($this->table)->where($params)->update(['status' => $status]);
    }


    //显示所有状态为1,2的留言，按照时间倒序
    public function all_article()
    {
        $re = Db::table($this->table)->alias('a')
            ->field('a.article_id,a.type,p.name,a.title,a.lis_img,a.brief,a.content,a.source,
                    a.create_time,a.update_time,a.status')
            ->join('project p', 'a.project_id = p.project_id', 'LEFT')
            ->where('a.status', 'in(1,2)')
            ->order('create_time', 'DESC')
            ->paginate(20);
        return $re;
    }

    //搜索查询文章
    public function search_article($params)
    {
        $re = Db::table($this->table)->alias('a')
            ->field('a.article_id,a.type,p.name,a.title,a.lis_img,a.brief,a.content,a.source,
                    a.create_time,a.update_time,a.status')
            ->join('project p', 'a.project_id = p.project_id', 'LEFT')
            ->where('a.status', 'in(1,2)')
            ->order('create_time', 'DESC')
            ->where($params)
            ->paginate(20);
        return $re;
    }


    //文章时间修改器
    public function getCreateTimeAttr($create_time)
    {
        return date('Y-m-d H:i:s', $create_time);
    }

    //文章时间修改器
    public function getUpdateTimeAttr($update_time)
    {
        return date('Y-m-d H:i:s', $update_time);
    }

    //文章类型获取器
    public function getTypeAttr($type)
    {
        //状态：1未审核，2审核通过，3用户删除，4管理员删除
        $value = [1 => '项目资讯', 2 => '创业资讯', 3 => '新闻资讯', 4 => '热门专题', 5 => '创业故事'];
        return $value[$type];
    }
}