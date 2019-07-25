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
        $this->assign('client', json_encode($client));
        $this->assign('project', json_encode($project));
        return $this->fetch();
    }

//  添加绑定
    public function add()
    {
        $client = new ClientModel();
        $project = new ProjectModel();
        $params = Request::instance()->param();
        $client = $client->get_client_by_id($params['client_id']);         //利用查询判断client_id是否存在
        $project = $project->get_project_by_id($params['project_id']);     //同理判断project_id
        $bool = $this->clientproject_model->get_cp_by_id($params);          //判断绑定是否存在
        if ($client && $project) {
            if (!$bool) {
                $result = $this->clientproject_model->add_cp($params);          //不存在重复的绑定则插入
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
        if (true === $result = $this->validate($params, 'Clientproject')) {     //判断project_id和client_id是否存在
            $result = $this->clientproject_model->del_cp($params);                       //存在则解除绑定
            if ($result) {
                $data = ['code' => 200, 'data' => "解绑成功"];
                return json_encode($data);
            } else {
                $data = ['code' => 202, 'data' => "解绑失败"];
                return json_encode($data);
            }
        } else {
            $data = ['code' => 202, 'data' => "解绑失败," . $result];
            return json_encode($data);
        }
    }

//    添加项目的时候添加绑定
    public function before_pro($params)
    {
        $result = $this->clientproject_model->add_cp($params);
        return $result;
    }
}