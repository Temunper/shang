<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/17
 * Time: 10:11
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class ClientprojectModel extends Model
{
    protected $table = 'client_project';

//查询
    function get_cp_by_id($cp)
    {
        $result = Db::table($this->table)
            ->where('client_id', '=', $cp['client_id'])
            ->where('project_id', '=', $cp['project_id'])
            ->find();
        return $result;
    }

//插入
    function add_cp($cp)
    {
        $result = Db::table($this->table)
            ->insert($cp);
        return $result;
    }

//    查询所有未绑定的项目
    function get_pro_no_bind()
    {
        $result = Db::table('view_pro')
            ->where('client_id is null')
            ->field('project_id,name')
            ->select();
        return $result;
    }

//    删除
    function del_cp($cp)
    {
        $result = Db::table($this->table)
            ->where('client_id = ' . $cp['client_id'] . ' and ' . ' project_id = ' . $cp['project_id'])
            ->delete();
        return $result;
    }
}