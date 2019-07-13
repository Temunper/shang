<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/13
 * Time: 16:45
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class ProjectModel extends Model
{
    protected $table = 'project';

//根据分类查询项目
    function get_project_by_class($class_id)
    {
        $class_id ? $where = 'class_id =' . $class_id : $where = null;
        $result = Db::table($this->table)
            ->where($where)
            ->order('project_id','desc')
            ->select();
        return $result;
    }

}