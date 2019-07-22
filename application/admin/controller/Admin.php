<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/10
 * Time: 18:01
 */

namespace app\admin\controller;



use app\admin\model\AdminModel;
use think\Request;
use think\Session;

class Admin extends Base
{
//修改密码
    public function update_pass(Request $request)
    {
        $admin = AdminModel::get(Session::get('admin')['admin_id']);
        if (md5($request->param('old_pass') . $admin->verify) == $admin->pass) {
            $admin->pass = md5($request->param('new_pass') . $admin->verify);
            if ($admin->isUpdate(true)->save()) {
                $data = ['code' => 200, 'data' => '修改成功'];
                echo json_encode($data);
            } else {
                $data = ['code' => 202, 'data' => '修改失败'];
                echo json_encode($data);
            }
        } else {
            $data = ['code' => 202, 'data' => '旧密码不正确'];
            echo json_encode($data);
        }
    }

//查看个人信息
    public function admin()
    {
        $admin = AdminModel::get(Session::get('admin')['admin_id']);
        $admin = $admin->toArray();
        unset($admin['pass']);
        $this->assign('admin', $admin);
        return $this->fetch();
    }

//修改密码页
    public function pass()
    {
        return $this->fetch();
    }

//修改个人信息
    public function update()
    {
        $request = Request::instance()->param();
        $admin = AdminModel::get(Session::get('admin')['admin_id']);
        $admin->name = $request['name'];
        if ($admin->save()) {
            $data = ['code' => 200, 'data' => '修改成功'];
            echo json_encode($data);
        } else {
            $data = ['code' => 202, 'data' => '修改失败'];
            echo json_encode($data);
        }
    }


//注销账户
    public function update_status(Request $request)
    {
        $admin = AdminModel::get(Session::get('admin')['admin_id']);
        $admin->status = $request->param('status');
        if ($admin->isUpdate(true)->save()) {
            Session::destroy();
            $this->error('账号已注销');
        } else {
            $this->error('账号注销失败');
        }
    }
}