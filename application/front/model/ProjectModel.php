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
        return Db::table('view_pro')->where('project_id', $project_id)
            ->where('status', 1)->find();
    }

    //通过class_id 查找类名
    public function get_class_name_m($class_id)
    {

     return Db::table('class')->field('class_id,name,f_class_id')->where('class_id',$class_id)->find();


    }


}