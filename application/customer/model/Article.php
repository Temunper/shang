<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/11
 * Time: 11:52
 */

namespace app\customer\model;

use think\Db;
use think\Model;

class Article extends Model
{
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    //检查用户是否拥有id为$id的文章
    public function check_own($own, $id)
    {
        $data = ['article_id' => $id, 'author' => $own];
        return self::where($data)->find();
    }

    //删除文章，修改状态
    public function change_status($article_id)
    {
        $statue = 3;
        return self::save(['status' => $statue], ['article_id' => $article_id]);
    }

    //查询用户自己发布的所有文章状态为1,2
    public function select_self_article($author)
    {
        $re = self::all(function ($query) use ($author) {
            $query->alias('a')
                ->field('a.type,p.name,a.title,a.lis_img,a.brief,a.content,a.source,c.name,
                    a.create_time,a.update_time,a.status')
                ->join('project p', 'a.project_id = p.project_id', 'LEFT')
                ->join('client c', 'a.author=c.client_id', 'LEFT')
                ->where('author', $author)
                ->where('a.status', 'EXP', 'in(1,2)');
        });
        foreach ($re as $k => $v) {
            $res [$k] = $v->toArray();
        }
        return $res;
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

    public function getStatusAttr($status)
    {
        //状态：1未审核，2审核通过，3用户删除，4管理员删除
        $value = [1 => '未审核', 2 => '审核通过', 3 => '用户删除', 4 => '客户删除', 5 => '管理员删除'];
        return $value[$status];
    }

    public function getTypeAttr($type)
    {
        //状态：1未审核，2审核通过，3用户删除，4管理员删除
        $value = [1 => '项目资讯', 2 => '创业资讯', 3 => '新闻资讯', 4 => '热门专题', 5 => '创业故事'];
        return $value[$type];
    }


}