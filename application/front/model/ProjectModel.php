<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/11
 * Time: 16:31
 */

namespace app\front\model;


use think\Db;
use think\Model;

class ProjectModel extends Model
{
    protected $table = 'project';

//按类型查询
    function get_project_with_class()
    {
        $result = Db::table($this->table)
            ->alias('p')
            ->join('class c', 'p.class_id = c.class_id', 'left')
            ->field('p.project_id,p.name,p.class_id')
            ->select();
        return $result;
    }

    //通过项目id  查询项目的所有信息
    public function get_project_info($project_id)
    {
        return Db::table('project')->where('project_id', $project_id)
            ->where('status', 1)->find();

    }

    public function getMoneyAttr($money)
    {
        $real_money = [1 => "", 2 => "1-10万", 3 => "1-20万", 4 => "20-50万", 5 => "20-50万"];
        return $real_money[$money];
    }


}