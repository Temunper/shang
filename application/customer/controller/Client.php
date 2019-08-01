<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/16
 * Time: 10:22
 */

namespace app\customer\controller;

use app\customer\model\Client as ClientModel;
use think\Controller;
use think\Request;
use think\Session;

class Client extends Controller
{
    //渲染登录页面
    public function login()
    {
        return $this->fetch();
    }

    //验证登录
    public function check_login()
    {
        $this->already_login();//判断用户是否已经登录，防止重复登录
        $status = 0;            //设置初始返回状态值
        $result = "";           //设置初始返回信息
        $data = Request::instance()->param();

        //验证信息
        $validate = 'app\customer\validate\ClientValidate';
        $result = $this->validate($data, $validate);
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
        //1.判断是否已经登录
        $this->is_login();

        //2.声明变量
        $this->assign('info', $info = Session::get('client_info'));

        //3.渲染修改页面
        return $this->view->fetch();
    }

//修改密码
    public function change_pass()
    {
        $this->is_login();
        $status = 0;
        $result = "";
        //通过session 获取当前用户密码和秘钥
        $info = Session::get('client_info');
        //获取提交的数据
        $data = Request::instance()->post();

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
        $old_pass = md5($data['pass'] . $info['verify']);
        //与session中的用户密码验证
        if ($old_pass != $info['pass']) {
            //原密码错误，返回错误信息
            return ['status' => $status, 'message' => '原密码错误'];
        }
        $new = md5($data['new_pass'] . $info['verify']);

        //新密码与原密码计较是否相同
        if ($info['pass'] == $new) {
            return ['status' => $status, 'message' => '新密码与原密码相同，请检查'];
        }

        $db = new ClientModel();
        $result = $db->save(['pass' => $new], ['client_id' => $info['client_id']]);
        if ($result) {
            $status = 1;
            $result = "更新成功,请重新登录";
        } else {
            $result = "更新失败";
        }
        return ['status' => $status, 'message' => $result];
    }

    /*
    //随机生成用户账号
        private function get_user_number()
        {
            $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $username = "";
            for ($i = 0; $i < 6; $i++) {
                $username .= $chars[mt_rand(0, strlen($chars))];
            }
            return strtoupper(base_convert(time() - 1420070400, 10, 36)) . $username;
        }*/

    protected function already_login()
    {

        if (Session::has('client_id')) {
            $this->error('用户已登录，请勿重复登录', url('index/index'));
        }
    }

    protected function is_login()
    {

        if (!Session::has('client_id')) {
            $this->error('用户未登录，无权访问', url('client/login'));
        }
    }

}