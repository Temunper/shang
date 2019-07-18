<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/13
 * Time: 16:45
 */

namespace app\admin\controller;


use app\admin\model\ProjectModel;
use think\Request;
use think\Session;


class Project extends Base
{
    protected $project_model = null;

    public function __construct(Request $request = null)
    {
        $this->project_model = new ProjectModel();
        parent::__construct($request);
    }

    //    查询项目列表，可以分类
    public function project()
    {
        $cl = new Clas();
        $d_class = $cl->get_all_clas();
        $params = Request::instance()->get();
        isset($params['status']) ? $class_id = $params['status'] : $status = null;
        isset($params['class_id']) ? $class_id = $params['class_id'] : $class_id = null;
        isset($params['project_name']) ? $project_name = $params['project_name'] : $project_name = null;
        isset($params['client_name']) ? $client_name = $params['client_name'] : $client_name = null;
        isset($params['bind_code']) ? $bind_code = $params['bind_code']:$bind_code = null;
        $default_class = $cl->get_class_by_id($class_id);
        $c = [];
        foreach ($d_class as $item) {
            if ($item['class_id'] == $class_id && $item['son'] != null) {
                foreach ($item['son'] as $value) {
                    $c [] = $value['class_id'];
                }
                $class_id = $c;
                $class_id = implode(',', $class_id);
            }
        }
        $search = ['project_name' => $project_name, 'client_name' => $client_name];
        $d_project = $this->project_model->get_project_by_class($status,$class_id, $project_name, $client_name,$bind_code);
        $this->assign('search', $search);
        $this->assign('default_class', $default_class);
        $this->assign('class', $d_class);
        $this->assign('project', $d_project);
        return $this->fetch();
    }

    //    项目详情
    public function project_content()
    {
        $params = Request::instance()->get();
        $result = $this->project_model->table('project')
            ->where('project_id', '=', $params['project_id'])
            ->find();
        $data = ['code' => 200, 'project' => $result];
        echo json_encode($data);
    }

    //添加项目
    public function add_project()
    {
        $params = Request::instance()->get();
        $project = $this->check_project($params);
        if ($project == "true") {
            if (!$this->project_model->get_project_by_name($params['name'])) {
                $this->project_model->add_project(array_merge($params, ['kf_id' => Session::get('admin')['admin_id']]));
                $data = ['code' => 200, 'msg' => "添加成功"];
                echo json_encode($data);
            } else {
                $data = ['code' => 202, 'msg' => "项目已存在"];
                echo json_encode($data);
            }
        } else {
            $data = ['code' => 202, 'msg' => $project];
            echo json_encode($data);
        }
    }

    //改变项目状态
    public function set_status()
    {
        $params = Request::instance()->get();
        $result = $this->project_model->update_status($params);
        if ($result) {
            $data = ['code' => 200, 'msg' => "更改成功"];
            echo json_encode($data);
        } else {
            $data = ['code' => 200, 'msg' => "更改失败"];
            echo json_encode($data);
        }
    }

    //更改项目内容
    public function update_project()
    {
        $params = Request::instance()->post();
        $result = $this->check_project($params);
        if ($result == "true") {
            $result = $this->project_model->update_project($params['project'], $params['id']);
            if ($result) {
                $data = ['code' => 200, 'msg' => "更改成功"];
                echo json_encode($data);
            } else {
                $data = ['code' => 200, 'msg' => "更改失败"];
                echo json_encode($data);
            }
        } else {
            $data = ['code' => 202, 'msg' => $result];
            echo json_encode($data);
        }
    }

//    根据id获取pro
    public function get_project_by_id($project_id){
       return $this->project_model->get_project_by_id($project_id);
    }

    //    项目验证
    public function check_project($project)
    {
        if (
//            array_key_exists('name', $project) ||
//            array_key_exists('company_name', $project) ||
//            array_key_exists('client_phone', $project) ||
//            array_key_exists('money', $project)||
            !empty($project['name']) ||
            !empty($project['company_name']) ||
            !empty($project['money']) ||
            !empty($project['client_phone'])
        ) {
            return "true";
        } else
            return "必填数据不能为空";
    }
}


























