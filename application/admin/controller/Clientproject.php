<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/17
 * Time: 10:11
 */

namespace app\admin\controller;


use app\admin\model\ClientModel;
use app\admin\model\ClientprojectModel;

use app\admin\model\ProjectModel;
use think\Request;

class Clientproject extends Base
{
    protected $clientproject_model = null;

    public function __construct(Request $request = null)
    {
        $this->clientproject_model = new ClientprojectModel();
        parent::__construct($request);
    }

//返回所有项目
    public function clientproject()
    {
        $client = new Client();
        $client = $client->get_all_client();
        $project = $this->clientproject_model->get_pro_no_bind();
        $this->assign('client',json_encode($client));
        $this->assign('project',json_encode($project));
        return $this->fetch();
    }

//  添加绑定
    public function add()
    {
        $client = new ClientModel();
        $project = new ProjectModel();
        $params = Request::instance()->param();
        $client = $client->get_client_by_id($params['client_id']);
        $project = $project->get_project_by_id($params['project_id']);
        $bool = $this->clientproject_model->get_cp_by_id($params);
        if ($client && $project) {
            if (!$bool) {
                $result = $this->clientproject_model->add_cp($params);
                $result ? $data = "绑定成功" : $data = "绑定失败";
                $data = ['code' => 200, 'data' => $data];
                echo json_encode($data);
            } else {
                $data = ['code' => 202, 'data' => "项目已绑定，请先解绑"];
                echo json_encode($data);
            }
        } else {
            $data = ['code' => 202, 'data' => "传输数据有错"];
            echo json_encode($data);
        }
    }

//    删除（解除）绑定
    public function unbinding()
    {
        $params = Request::instance()->post();
        $result = $this->clientproject_model->del_cp($params);
        if ($result) {
            $data = ['code' => 200, 'data' => "解绑成功"];
            echo json_encode($data);
        } else {
            $data = ['code' => 202, 'data' => "解绑失败"];
            echo json_encode($data);
        }
    }
}