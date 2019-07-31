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
        $cl = new Clas();                                                                                       //实例化分类对象
        $d_class = $cl->get_all_clas();
        $params = Request::instance()->get();
        isset($params['bind_code']) ? $bind_code = $params['bind_code'] : $bind_code = null;
        isset($params['status']) ? $class_id = $params['status'] : $status = null;                              //判断获取的数据，若无则赋值默认值
        isset($params['class_id']) ? $class_id = $params['class_id'] : $class_id = null;
        isset($params['project_name']) ? $project_name = $params['project_name'] : $project_name = null;
        isset($params['client_name']) ? $client_name = $params['client_name'] : $client_name = null;            //客户名
        isset($params['bind_code']) ? $bind_code = $params['bind_code'] : $bind_code = null;
        $default_class = $cl->get_class_by_id($class_id);
        $c = [];
        foreach ($d_class as $item) {                                                                               //当分类id是一级分类时遍历出所有树叶
            if ($item['class_id'] == $class_id && $item['son'] != null) {
                foreach ($item['son'] as $value) {
                    $c [] = $value['class_id'];
                }
                $class_id = $c;
                $class_id = implode(',', $class_id);                                                            //将数组转为字符串，并用‘,’隔开
            }
        }
        $search = ['project_name' => $project_name, 'client_name' => $client_name, 'bind' => $bind_code];                                     //将查询的条件返回到模板
        $d_project = $this->project_model->get_project_by_class($status, $class_id, $project_name, $client_name, $bind_code);  //查询project数据
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
        $d_client = trim($params['client_name']);                                      //去空格
        $d_project = special_filter($params['project']);                               //过滤对象中的单双引号
        $project = $this->validate($d_project, 'Project');                    //验证project数组的值是否正确
        if ($project === true) {
            if (!$this->project_model->get_project_by_name($d_project['name'])) {      //判断项目是否存在
                if ($d_client) {            //判断是否需要绑定新的用户
                    try {
                        Db::startTrans();        //若需要添加客户，开启事务
                        $this->project_model->add_project($d_project + ['kf_id' => Session::get('admin')['admin_id']]);
                        $d_project = ProjectModel::getByName($d_project['name']);
                        $d_client = Account::create($d_client);         //创建账号
                        $pass = $d_client;
                        $client = new Client();
                        $client->add($d_client['client']);              //将客户信息插入客户表
                        $d_client = ClientModel::getByName($d_client['client']['name']);            //tp5模型：根据name字段获取project对象
                        $client_project = new Clientproject();               //实例化绑定项目和用户的类，绑定账号
                        $client_project->before_pro(['project_id' => $d_project->project_id, 'client_id' => $d_client->client_id]);
                        Db::commit();              //提交事务
                        $data = ['code' => 200, 'data' => "添加成功", 'pass' => json_encode($pass)];
                        return json_encode($data);
                    } catch (Exception $e) {
                        Db::rollback();
                        $data = ['code' => 202, 'data' => "添加失败"];
                        return json_encode($data);
                    }
                } else {                          //若不创建用户，则直接添加
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

        $result = $this->project_model->update_status($params['project_id']);       //根据返回的project_id将status置为2删除
        if ($result) {
            $data = ['code' => 200, 'data' => "删除成功"];
            return json_encode($data);
        } else {
            $data = ['code' => 202, 'data' => "删除失败"];
            return json_encode($data);
        }
    }

//更改项目内容
    public function update()
    {

        $params = Request::instance()->param();
        $params = special_filter($params);                                    //过滤单双引号
        $result = $this->validate($params, 'Project');              //验证数组内容
        if ($result === true) {                                             //内容正确则则更新
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

//    根据id获取project
    public function get_project_by_id($project_id)
    {
        return $this->project_model->get_project_by_id($project_id);
    }

//    项目详情
    public function content()
    {
        $cl = new Clas();
        $params = Request::instance()->get();
        $result = $this->project_model->table('view_pro')//获取id为返回信息的project对象
        ->where('project_id', '=', $params['project_id'])
            ->find();
        $d_class = $cl->get_all_clas();
        $this->assign("class", $d_class);
        $this->assign("project", $result);
        return $this->fetch();
    }

//    添加项目页
    public function plus()
    {
        $cl = new Clas();
        $d_class = $cl->get_all_clas();
        $this->assign("class", $d_class);                       //赋值所需的分类信息到模板
        return $this->fetch('project/add');
    }

    //获得所有项目id和名称
    public function getAllProject()
    {
        return $this->project_model->get_all_project();
    }


    //根据名称查询所有相似的项目名称和id
    public function select_like_project($key)
    {
        return $this->project_model->like_project($key);
    }

}


























