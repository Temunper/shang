<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/11
 * Time: 11:16
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class ClasModel extends Model
{
    protected $table = 'class';

    function get_all_clas()
    {
        $result = Db::table($this->table)
            ->where('status', '=', '1')
            ->order('sort')
            ->select();
        foreach ($result as $key => $value) {                                 //将返回信息遍历，更改状态信息 ，1-》正常，2-》删除
            $value['status'] == 1 ? $result[$key]['status'] = '正常' : $result[$key]['status'] = '删除';
        }
        return $result;
    }

    //    查询分类
    function get_class_by_id($class_id)//分类id
    {
        $result = Db::table($this->table)                                   //左查询查询分类表，连接父节点
            ->alias('a')
            ->join('class b', 'a.f_class_id = b.class_id', 'left')
            ->where('a.class_id', '=', $class_id)
            ->field('a.class_id,a.name as class_name,a.f_class_id,b.name as f_class_name')
            ->find();
        return $result;
    }

    //    添加分类
    function add_clas($clas)//分类数组
    {
        $result = Db::table($this->table)
            ->insert($clas);
        return $result;
    }

    //    删除分类
    function del_clas($clas_id, $status)//分类id和状态值
    {
        $result = Db::table($this->table)
            ->where('class_id', 'in', $clas_id)
            ->update(['status' => $status]);
        return $result;
    }

    //    更改分类
    function update_clas($clas)//分类数组
    {
        $result = Db::table($this->table)
            ->where('class_id', '=', $clas['class_id'])
            ->update($this->class);
        return $result;
    }

////    完全删除
//      function  clear_clas($clas_id)
//      {
//          $result = Db::table($this->table)
//              ->where('class_id ', 'in', $clas_id)
//              ->delete();
//          return $result;
//      }
}