<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/16
 * Time: 10:22
 */

namespace app\customer\controller;

use think\Controller;
use think\Session;
use think\Request;
use app\customer\model\Client as ClientModel;

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
        //验证是否已经登录，存在session
        $this->already_login();
        $data = $request->param();
        $user = $data['user'];
        $pass = $data['pass'];
        $verify = $data['verify'];

        //简单验证
        if (empty($user)) {
            return $this->return_info(0, '用户名不能为空');
        }
        if (empty($pass)) {
            return $this->return_info(0, '密码不能为空');
        }
        if (empty($verify)) {
            return $this->return_info(0, '验证码不能为空');
        }
        //验证码验证是否正确
        $captcha = new \think\captcha\Captcha();
        if (!$captcha->check(input('post.verify'))) {
            return $this->return_info(0, '验证码错误');
        } else { //通过验证，则查询是否存在该用户名

            $db = new ClientModel();
            //通过用户名获取账户秘钥

            $verify = $db->get_verify($user);
            if (empty($verify)) {
                return $this->return_info(1, '用户名或密码错误，请检查');
            }
            $map = [
                'user' => htmlentities($user),
                'pass' => md5($data['pass'] . $verify['verify'])
            ];
            //验证用户名和密码是否正确
            $cus_info = $db->check_login($map);

            if ($cus_info == null) {
                return $this->return_info(1, '用户名或密码错误，请检查');
            }
            //判断账户状态
            if ($cus_info['status'] == 0) {
                return $this->return_info(1, '禁用的账户，请联系管理员');
            }
            //登录成功，开启session
            //开启session
            Session::set('client_id', $cus_info['client_id']);
            Session::set('client_info', $cus_info);
            $this->success('登录成功', 'index/index');
            return true;
        }
    }


    //注销
    public function logout()
    {
        Session::delete('client_id');
        Session::delete('client_info');
        $this->success('注销登录，正在返回', 'client/login');
    }

    //修改密码
    public function show_change_pass()
    {
        //渲染修改页面
        return $this->view->fetch('', ['info' => $info = Session::get('client_info')]);
    }

    public function change_pass(Request $request)
    {

        //通过session 获取当前用户密码和秘钥
        $info = Session::get('client_info');
        //与提交的原密码验证
        $old_pass = $request->post('pass');
        $new_pass = $request->post('new_pass');
        $old_pass = md5($old_pass . $info['verify']);
        if ($old_pass != $info['pass']) {
            return $this->return_info(0, '原密码错误');
        }
        if (empty($new_pass)) {
            return $this->return_info(0, '新密码不能为空');
        }
        if (strlen($new_pass) < 3 || strlen($new_pass) > 22) {
            return $this->return_info(0, '密码长度为4-21');
        }
        $db = new ClientModel();

        $result = $db->save(['pass' => $new_pass], ['client_id' => $info['client_id']]);
        if ($result) {
            return $this->return_info(1, '更新成功');
        }
        return $this->return_info(0, '更新失败');
    }

    //随机生成用户账号
    private function get_user_number()
    {
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $username = "";
        for ($i = 0; $i < 6; $i++) {
            $username .= $chars[mt_rand(0, strlen($chars))];
        }
        return strtoupper(base_convert(time() - 1420070400, 10, 36)) . $username;
    }


}