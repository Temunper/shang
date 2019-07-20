<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/13
 * Time: 16:45
 */

namespace app\admin\controller;


use app\admin\common\Account;
use app\admin\model\ClientModel;
use app\admin\model\ProjectModel;
use think\Db;
use think\Exception;
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
        isset($params['bind_code']) ? $bind_code = $params['bind_code'] : $bind_code = null;
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
        $d_project = $this->project_model->get_project_by_class($status, $class_id, $project_name, $client_name, $bind_code);
        $this->assign('search', $search);
        $this->assign('default_class', $default_class);
        $this->assign('class', $d_class);
        $this->assign('project', $d_project);
        return $this->fetch();
    }

    //添加项目
    public function add()
    {
        $params = Request::instance()->post();
        $d_project = $params['project'];
        $d_client = $params['client_name'];
        $project = $this->validate($d_project, 'Project');
        if ($project === true) {
            if (!$this->project_model->get_project_by_name($d_project['name'])) {      //判断项目是否存在
                if ($d_client) {            //判断是否需要绑定新的用户
                    try {
                        Db::startTrans();
                        $this->project_model->add_project(array_merge($d_project, ['kf_id' => Session::get('admin')['admin_id']]));
                        $d_project = ProjectModel::getByName($d_project['name']);
                        $d_client = Account::create($d_client);
                        $pass = $d_client;
                        $client = new Client();
                        $client->add($d_client['client']);
                        $d_client = ClientModel::getByName($d_client['client']['name']);
                        $client_project = new Clientproject();
                        $client_project->before_pro(['project_id' => $d_project->project_id, 'client_id' => $d_client->client_id]);
                        Db::commit();
                        $data = ['code' => 200, 'data' => "添加成功", 'pass' => $pass];
                        return json_encode($data);
                    } catch (Exception $e) {
                        Db::rollback();
                        $data = ['code' => 202, 'data' => "添加失败"];
                        return json_encode($data);
                    }
                } else {
                    $this->project_model->add_project(array_merge($d_project, ['kf_id' => Session::get('admin')['admin_id']]));
                    $data = ['code' => 200, 'data' => "添加成功"];
                    return json_encode($data);
                }
            } else {
                $data = ['code' => 202, 'data' => "项目已存在"];
                return json_encode($data);
            }
        } else {
            $data = ['code' => 202, 'data' => $project];
            return json_encode($data);
        }
    }

    //改变项目状态(删除项目)
    public function update_status()
    {
        $params = Request::instance()->post();
        $result = $this->project_model->update_status($params['project_id']);
        if ($result) {
            $data = ['code' => 200, 'data' => "更改成功"];
            return json_encode($data);
        } else {
            $data = ['code' => 202, 'data' => "更改失败"];
            return json_encode($data);
        }
    }

    //更改项目内容
    public function update()
    {
        $params = Request::instance()->post();
        $result = $this->validate($params, 'Project');
        if ($result === true) {
            $result = $this->project_model->update_project($params);
            if ($result) {
                $data = ['code' => 200, 'data' => "更改成功"];
                return json_encode($data);
            } else {
                $data = ['code' => 200, 'data' => "更改失败"];
                return json_encode($data);
            }
        } else {
            $data = ['code' => 202, 'data' => $result];
            return json_encode($data);
        }
    }

//    根据id获取pro
    public function get_project_by_id($project_id)
    {
        return $this->project_model->get_project_by_id($project_id);
    }

    //    项目详情
    public function content()
    {
        $params = Request::instance()->get();
        $result = $this->project_model
            ->where('project_id', '=', $params['project_id'])
            ->find();
        $data = ['code' => 200, 'project' => $result];
        return json_encode($data);
    }
    //    项目验证
//    public function check_project($project)
//    {
//        if (
////            array_key_exists('name', $project) ||
////            array_key_exists('company_name', $project) ||
////            array_key_exists('client_phone', $project) ||
////            array_key_exists('money', $project)||
//            !empty($project['name']) ||
//            !empty($project['company_name']) ||
//            !empty($project['money']) ||
//            !empty($project['client_phone'])
//        ) {
//            return "true";
//        } else
//            return "必填数据不能为空";
//    }
}


























