<?php
/**
 * Created by PhpStorm.
 * Clientw: vpanda
 * Date: 2019/7/11
 * Time: 11:52
 */

namespace app\admin\model;

use think\Db;
use think\Model;

class Article extends Model
{
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    //系统删除删除文章，修改状态，可批量
    public function change_status($article_id, $status)
    {
        return self::where('article_id', 'in', $article_id)->update(['status' => $status]);
    }

    //精确搜索文章
    public function accurate_article($time1, $time2, $params, $status)
    {

        isset($params['title']) ? $title = "title like '%" . $params['title'] . "%'" : $title = null;
        isset($params['type']) ? $type = "type = " . $params['type'] : $type = null;
        isset($params['source']) ? $source = "source like '%" . $params['source'] . "%'" : $source = null;


        /*      dump($type);
              die;*/
        $result = self::where('update_time', '>', $time1)
            ->where('update_time', '<', $time2)
            ->where('status', 'in', $status)
            ->where($title)
            ->where($type)
            ->where($source)
            ->order('update_time', 'desc')
            ->paginate(15);

        if ($result) {
            return $result;
        } else {
            return [];
        }

    }

//查询所有状态值为1,2的文章，
    public
    function select_all_article()
    {
        return self::where('status', 'in', '1,2')
            ->order('status','asc')
            ->order('update_time', 'desc')
            ->paginate(15);
    }


//文章时间修改器
    public
    function getCreateTimeAttr($create_time)
    {
        return date('Y-m-d H:i:s', $create_time);
    }

//文章时间修改器
    public
    function getUpdateTimeAttr($update_time)
    {
        return date('Y-m-d H:i:s', $update_time);
    }

//项目名称获取器
    public
    function getProjectIdAttr($project_id)
    {
        return $this->check_project_name2($project_id);
    }

//根据项目id查询项目名称，返回项目名称
    public
    function check_project_name2($project_id)
    {
        return Db::table('project')->where('project_id', $project_id)->value('name');
    }

//文章状态获取器
    public
    function getStatusAttr($status)
    {
        //状态：1未审核，2审核通过，3用户删除，4管理员删除
        $value = [-1 => '驳回', 1 => '未审核', 2 => '审核通过', 3 => '用户删除', 4 => '管理员删除'];
        return $value[$status];
    }

//文章类型获取器
    public
    function getTypeAttr($type)
    {
        //状态：1未审核，2审核通过，3用户删除，4管理员删除
        $value = [1 => '项目资讯', 2 => '创业资讯', 3 => '新闻资讯', 4 => '热门专题', 5 => '创业故事'];
        return $value[$type];
    }

    public
    function add_artilce($params)
    {
        return self::insert($params);

    }

}