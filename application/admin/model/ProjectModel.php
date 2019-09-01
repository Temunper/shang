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
        if ($bind_code) {                                       //是否绑定
            switch ($bind_code) {
                case 1 :
                    $bind = "client.client_id is not null";
                    break;
                case 2 :
                    $bind = "client.client_id is null";
                    break;
            }
        } else $bind = null;
        $status ? $c_status = "status = " . $status : $c_status = "status = 1";         //其他条件

        $project_name ? $and = "name like '%" . $project_name . "%'" : $and = null;
        $class_id ? $where = 'class_id in(' . $class_id . ')' : $where = null;
        $client_name ? $client = "client_name like '%" . $client_name . "%'" : $client = null;

     /*   dump($where);echo "<br>";
        dump($and);echo "<br>";
        dump($client);echo "<br>";
        dump($bind);echo "<br>";
        die;*/

        $result = Db::table("project")
            ->field([
                'project . project_id AS project_id',
                'project . yw_name AS yw_name',
                'project . name AS name',
                'project . abbr AS abbr',
                'project . pattern AS pattern',
                'project . crowd AS crowd',
                'project . area AS area',
                'project . client_phone AS client_phone',
                'project . num_400 AS num_400',
                'project . company_name AS company_name',
                'project . company_addr AS company_addr',
                'project . superiority AS superiority',
                'project . analysis AS analysis',
                'project . prospect AS prospect',
                'project . summary AS summary',
                'project . contact AS contact',
                'project . class_id AS class_id',
                'project . money AS money',
                'project . keywords AS keywords',
                'project . description AS description',
                'project . kf_id AS kf_id',
                'project . kf_type AS kf_type',
                'project . status AS status',
                'client . client_id AS client_id',
                'client . name AS client_name',
                'admin . name AS kf_name '])
            ->join('client_project', 'project.project_id=client_project.project_id', 'LEFT')
            ->join('client', 'client_project.client_id=client.client_id', 'LEFT')
            ->join('admin', 'project.kf_id=admin.id')
            ->where($where)
            ->where($and)
            ->where($client)
            ->where($bind)
            ->where('project.'.$c_status)
            ->order('project.project_id', 'desc')
            ->paginate(10 ,false,[
                'query'=>request()->param()
            ])
            ->each(function ($item, $key) {
                if ($item['status'] == 1) {
                    $item['status'] = '正常';
                } else $item['status'] = '已删除';                      //将返回信息遍历，更改状态信息 ，1-》正常，2-》删除
                switch ($item['money']) {                            //将返回信息遍历，更改money信息 ，1-》1-10，2-》10-20，3-》20-50，4-》50-100
                    case 1:
                        $item['money'] = "1万以下";
                        break;
                    case 2:
                        $item['money'] = "1-10万";
                        break;
                    case 3:
                        $item['money'] = "10-20万";
                        break;
                    case 4:
                        $item['money'] = "20-50万";
                        break;
                    case 5:
                        $item['money'] = "50-100万";
                        break;
                }
                return $item;
            });
        return $result;
    }

    //精确查询项目
    function get_project_by_name($name)//字符串
    {
        $result = Db::table($this->table)
            ->where('name', '=', $name)
            ->find();
        return $result;
    }

    //    添加项目
    function add_project($project)//project数组
    {
        $result = Db::table($this->table)->insert($project);
        return $result;
    }

    //    删除
    function update_status($project_id)//project_id值
    {
        try {
            Db::startTrans();
            Db::table($this->table)->where('project_id', 'in', $project_id)->update(['status' => '2']);
            Db::table('ad_position')->where('project_id', 'in', $project_id)->update(['status' => '2']);
            Db::commit();
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    //更改项目内容
    function update_project($project)//project数组
    {
        $result = Db::table($this->table)
            ->where('project_id', '=', $project['project_id'])
            ->update($project);
        return $result;
    }


//    通过项目id获取项目
    function get_project_by_id($project_id)//id值
    {
        $result = Db::table($this->table)
            ->where('project_id', '=', $project_id)
            ->find();
        return $result;
    }

    //获得所有项目id和项目名
    public function get_all_project()
    {
        return Db::table($this->table)->field('project_id,name')->select();

    }

    public function like_project($key)
    {

        return Db::table($this->table)->where('name', 'like', '%' . $key . '%')
            ->field('project_id,name')->select();

    }


}