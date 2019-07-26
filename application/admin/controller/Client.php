<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/17
 * Time: 10:22
 */

namespace app\admin\controller;


use app\admin\common\Account;
use app\admin\model\ClientModel;
use think\Request;

class Client extends Base
{
    protected $client_model = null;

    public function __construct(Request $request = null)
    {
        $this->client_model = new ClientModel();
        parent::__construct($request);
    }

//    查找客户
    public function get_client_by_id($id)
    {
        $data = $this->client_model->get_client_by_id($id);
        return $data;
    }

//    得到所有用户名
    public function get_all_client()
    {
        $result = $this->client_model->get_all_client();
        return $result;
    }

//      创建用户
    public function add($client)
    {
        $result = $this->client_model->add($client);
        return $result;
    }

//     更改用户密码
    public function pass()
    {
        $request = Request::instance()->param();
         $url =  $_SERVER["HTTP_REFERER"];                      //获取跳转前分页页面
        $cl = Account::create($request['client_name']);          //创建账号
        $client = $cl['client'];
        $password = $cl['password'];
        $d_client = ClientModel::get($request['client_id']);      //根据返回的客户id获取客户对象
        $d_client->pass = $client['pass'];                        //给对象的成员变量赋值
        $d_client->verify = $client['verify'];
        if ($d_client->isUpdate(true)->save()) {          //返回model层插入对象的数据
            $this->success('修改成功！   账号：' . $d_client->user . '       密码：' . $password,$url,"","60"); //返回页面，有60s查看账号密码
        } else {
            $this->error('修改失败');
        }
    }
}