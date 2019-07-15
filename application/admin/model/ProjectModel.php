<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/13
 * Time: 16:45
 */

namespace app\admin\model;

use think\Db;
use think\Exception;
use think\Model;

class ProjectModel extends Model
{
    protected $table = 'project';
    protected $insert = ['status'];

    //根据分类查询项目
    function get_project_by_class($class_id)
    {
        $class_id ? $where = 'class_id =' . $class_id : $where = null;
        $result = Db::table($this->table)
            ->where($where)
            ->order('project_id', 'desc')
            ->paginate(20, 1000);
        return $result;
    }

    //精确查询项目
    function get_project_by_name($name)
    {
        $result = Db::table($this->table)
            ->where('name', '=', $name)
            ->find();
        return $result;
    }

    //    添加项目
    function add_project($project)
    {
        $result = Db::table($this->table)
            ->insert($project);
        return $result;
    }

    //    更改状态，批量
    function update_status($status)
    {
        try {
            $result = $this->isUpdate()->saveAll($status);
            Db::commit();
        } catch (Exception $e) {
            // 回滚事务
            Db::rollback();
        }
        return $result;
    }

    //更改项目内容
    function update_project($project,$id){
        $result = Db::table($this->table)
            ->where('project_id','=',$id)
            ->update($project);
        return $result;
    }


    // status属性修改器
    protected function setStatusAttr($value, $data)
    {
        return '正常' == $data['status'] ? 1 : 2;
    }

    // status属性读取器
    protected function getStatusAttr($value)
    {
        $status = [1 => '正常', 2 => '删除'];
        return $status[$value];
    }
}