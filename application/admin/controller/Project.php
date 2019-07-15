<?php
/**
 * Created by PhpStorm.
 * User: TEM
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
        $params = Request::instance()->get();
        isset($params['class_id']) ? $class_id = $params['class_id'] : $class_id = null;
        $result = $this->project_model->get_project_by_class($class_id);
        $this->assign('project',$result);
        return $this->fetch();
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


























