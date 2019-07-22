<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/16
 * Time: 10:22
 */

namespace app\customer\controller;

use app\customer\model\Client as ClientModel;
use think\Request;
use think\Session;

class Client extends Base
{
    //渲染登录页面
    public function login()
    {
        return $this->fetch();
    }

    //验证登录
    public function check_login(Request $request)
    {
        $this->already_login();//判断用户是否已经登录，防止重复登录
        $status = 0;            //设置初始返回状态值
        $result = "";           //设置初始返回信息
        $data = $request->param();
        //简单验证
        //验证规则
        $rule = [
            'user|用户名' => 'require',
            'pass|密码' => 'require',
            'verify|验证码' => 'require|captcha',
        ];
        //自定义错误信息
        $msg = [
            'user' => ['require' => '用户名不能为空，请检查'],
            'pass' => ['require' => '密码不能为空，请检查'],
            'verify' => ['require' => '验证码不能为空，请检查',
                'captcha' => '验证码错误'],
        ];
        //验证信息
        $result = $this->validate($data, $rule, $msg);
        if ($result === true) {  //全等于true 则验证通过
            $user = $data['user'];
            $pass = $data['pass'];
            $db = new ClientModel();
            //通过用户名获取账户秘钥
            $verify = $db->get_verify($user);  //通过用户名获取用户秘钥
            if (empty($verify)) {    //如果秘钥为空，返回错误信息
                return ['status' => $status, 'message' => '用户名或密码错误，请检查'];
            }
            //查找到秘钥
            $map = [
                'user' => htmlentities($user),
                'pass' => md5($pass . $verify['verify'])
            ];
            //验证用户名和密码是否正确
            $cus_info = $db->check_login($map);
            if ($cus_info == null) {
                return ['status' => $status, 'message' => '用户名或密码错误，请检查'];
            }
            //判断账户状态
            if ($cus_info['status'] == 0) {
                return ['status' => $status, 'message' => '禁用的账户，请联系管理员'];
            }
            //   return dump($cus_info);
            //登录成功，开启session
            //开启session
            Session::set('client_id', $cus_info['client_id']);
            Session::set('client_info', $cus_info);
            $status = 1;
            $result = "登录成功";
        }
        return ['status' => $status, 'message' => $result,];
    }

//注销
    public function logout()
    {
        Session::delete('client_id');
        Session::delete('client_info');
        $this->redirect('client/login');  //重定向到登录页
    }

//渲染修改密码页
    public function show_change_pass()
    {
        //渲染修改页面
        return $this->view->fetch('', ['info' => $info = Session::get('client_info')]);
    }

//修改密码
    public function change_pass(Request $request)
    {
        $status = 0;
        //通过session 获取当前用户密码和秘钥
        $info = Session::get('client_info');
        //获取提交的数据
        $data = $request->post();

        //验证规则
        $rule = [
            'pass|原密码' => 'require',
            'new_pass|新密码' => 'require|length:8,20'
        ];
        //验证错误返回信息
        $msg = [
            'pass' => ['require' => '原密码不能为空'],
            'new_pass' => ['require' => '新密码不能为空',
                'length' => '密码长度需为8至20位']
        ];

        $result = $this->validate($data, $rule, $msg);
        if ($result !== true) {
            //未通过验证，返回错误信息
            return ['status' => $status, 'message' => $result];
        }
        //通过验证。
        //
        $old_pass = md5($data['pass'] . $info['verify']);
        //与session中的用户密码验证
        if ($old_pass != $info['pass']) {
            //原密码错误，返回错误信息
            return ['status' => $status, 'message' => '原密码错误'];
        }
        $db = new ClientModel();
        $new = md5($data['new_pass'] . $info['verify']);
        $result = $db->save(['pass' => $new], ['client_id' => $info['client_id']]);
        if ($result) {
            $status = 1;
            $result = "更新成功,请重新登录";
        }
        {
            $result = "更新失败";
        }
        $this->logout();
        return ['status' => $status, 'message' => $result];

    }

//随机生成用户账号
    private
    function get_user_number()
    {
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $username = "";
        for ($i = 0; $i < 6; $i++) {
            $username .= $chars[mt_rand(0, strlen($chars))];
        }
        return strtoupper(base_convert(time() - 1420070400, 10, 36)) . $username;
    }


}