<?php
/**
 * Created by PhpStorm.
 * Clientw: vpanda
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
    protected $field = true;

    //检查用户是否拥有id为$id的文章
    public function check_own($own, $id)
    {
        $data = ['article_id' => $id, 'author' => $own];
        return self::where($data)->find()->toArray();

    }

    //通过文章id 获取当前文章图片路径
    public function get_image_path($article)
    {
        return self::where('article_id', $article)->value('lis_img');
    }

    //更新文章信息
    public function update_article($params)
    {
        return self::update($params);
    }

    //删除文章，修改状态
    public function change_status($article_id)
    {
        $statue = 3;
        return self::where('article_id', 'in', $article_id)->update(['status' => $statue]);
    }

    //精确搜索文章
    public function accurate_article($time1, $time2, $params, $status)
    {
        return $re = self::where('update_time', '>', $time1)
            ->where('update_time', '<', $time2)
            ->where($params)
            ->where('status', 'in', $status)
            ->order('update_time', 'desc')
            ->paginate(15);
    }


    //查询用户自己发布的所有文章状态为1,2
    public function select_self_article($author)
    {
        return self::order('update_time', 'desc')
            ->where('status', 'in', '1,2')
            ->where('author', $author)
            ->paginate(15);

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

    //项目名称获取器
    public function getProjectIdAttr($project_id)
    {
        return $this->check_project_name2($project_id);
    }

    //根据项目id查询项目名称，返回项目名称
    public function check_project_name2($project_id)
    {
        return Db::table('project')->where('project_id', $project_id)->value('name');

    }

    //文章状态获取器
    public function getStatusAttr($status)
    {
        //状态：1未审核，2审核通过，3用户删除，4管理员删除
        $value = [-1=>'驳回',1 => '未审核', 2 => '审核通过', 3 => '用户删除', 4 => '管理员删除'];
        return $value[$status];
    }

    //文章类型获取器
    public function getTypeAttr($type)
    {
        //状态：1未审核，2审核通过，3用户删除，4管理员删除
        $value = [1 => '项目资讯', 2 => '创业资讯', 3 => '新闻资讯', 4 => '热门专题', 5 => '创业故事'];
        return $value[$type];
    }


    //通过id 查询所拥有的项目
    public function check_project($client_id)
    {
        return $re = Db::table('client_project')->field('project_id')
            ->where('client_id', $client_id)->select();

        //dump($this->getLastSql());die;
    }

    //返回用户所拥有的项目id
    public function select_project($id)
    {
        return Db::table('project')->field('project_id,name')->where('project_id', 'in', $id)->select();

    }

    /**
     * 新增文章
     * 传入一个数组@param
     * 成功返回受影响行数
     */
    public function add_artilce($params)
    {
        return self::insert($params);

    }

    //获取当前文章的type 字段值
    public function get_type($client_id, $article_id)
    {
        return Db::table('article')->where('article_id', $article_id)
            ->where('author', $client_id)
            ->value('type');

    }
}