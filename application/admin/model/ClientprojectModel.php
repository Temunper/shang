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
    function get_cp_by_id($cp)  //数组，客户id和项目id
    {
        $result = Db::table($this->table)//根据两个id条件查询
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
//        $result = Db::table('view_pro')   //视图
//            ->where('client_id is null')
//            ->field('project_id,name')
//            ->select();


        $result = Db::table('project')
            ->field(['project . project_id AS project_id',
                'project . name AS name',
            ])
            ->join('client_project', 'project.project_id=client_project.project_id', 'LEFT')
            ->join('client', 'client_project.client_id=client.client_id', 'LEFT')
            ->join('admin', 'project.kf_id=admin.id')
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