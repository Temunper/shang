<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
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

    //根据分类查询项目
    function get_project_by_class($status, $class_id, $project_name, $client_name, $bind_code)
    {
        if ($bind_code) {
            switch ($bind_code) {
                case 1 :
                    $bind = "client_id is null";
                    break;
                case 2 :
                    $bind = "client_id is not null";
                    break;
            }
        } else $bind = null;
        $status ? $c_status = "status = " . $status : $c_status = "status = 1";
        $project_name ? $and = "name like '%" . $project_name . "%'" : $and = null;
        $class_id ? $where = 'class_id in(' . $class_id . ')' : $where = null;
        $client_name ? $client = "client_name like '%" . $client_name . "%'" : $client = null;
        $result = Db::table("view_pro")
            ->where($where)
            ->where($and)
            ->where($client)
            ->where($bind)
            ->where($c_status)
            ->order('project_id', 'desc')
            ->field('*', 'time')
            ->paginate(10)->each(function ($item, $key) {
                if ($item['status'] == 1) {
                    $item['status'] = '正常';
                } else $item['status'] = '已删除';
                return $item;
            });
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
        $result = Db::table($this->table)->insert($project);

        return $result;
    }

    //    删除
    function update_status($project_id)
    {
        $result = $this->where('project_id', 'in', $project_id)->update(['status' => '1']);
        return $result;
    }

    //更改项目内容
    function update_project($project)
    {
        $result = Db::table($this->table)
            ->where('project_id', '=', $project['project_id'])
            ->update($project);
        return $result;
    }


//    // status属性修改器
//    protected function setStatusAttr($data)
//    {
//        return '正常' == $data['status'] ? 1 : 2;
//    }

    // status属性读取器
//    protected function setStatus($data)
//    {
//        $status = [1 => '正常', 2 => '已删除'];
//        $result = [];
//        foreach ($data as $value) {
//            $value['status'] = $status[$value['status']];
//            $result[] = $value;
//        }
//        return $result;
//    }

//    通过项目id获取项目
    function get_project_by_id($project_id)
    {
        $result = Db::table($this->table)
            ->where('project_id', '=', $project_id)
            ->find();
        return $result;
    }
}