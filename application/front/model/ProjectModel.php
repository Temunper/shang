<?php
/**
 * Created by PhpStorm.
 * User: TEM
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
}