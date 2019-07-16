<?php
/**
 * Created by PhpStorm.
 * Clientw: vpanda
 * Date: 2019/7/11
 * Time: 9:02
 */

namespace app\customer\controller;

use app\customer\model\Client2 as ClientModel;
use think\Request;
use think\Session;

class Clientw extends Base
{
    //渲染登录页面
    public function login()
    {
        return $this->fetch();
    }

    //登录
    public function check_login(Request $request)
    {


        //初始化登录状态

        $data = $request->param();
        //return dump($data);

        /*       $rule = [
                    'client|账号' => 'require ',
                    'pass|密码' => 'require',
                    'verify|验证码' => 'require|captcha',
                ];
                $msg = [
                    'client' => ['require' => '账户不能为空，请检查'],
                    'pass' => ['require' => '密码不能为空，请检查'],
                    'verify' => ['require' => '验证码不能为空，请检查',
                        'captcha' => '验证码错误'],
                ];
                $result = $this->validate($check, $rule, $msg);*/
        $user = $data['user'];
        $pass = $data['pass'];
        $verify = $data['verify'];

        if (empty($user)) {
            return $this->return_info(0, '用户名不能为空');
        }
        if (empty($pass)) {
            return $this->return_info(0, '密码不能为空');
        }
        if (empty($verify)) {
            return $this->return_info(0, '验证码不能为空');
        }

        $captcha = new \think\captcha\Captcha();
        if (!$captcha->check(input('post.verify'))) {
            return $this->return_info(0, '验证码错误');
        } else {

            //通过验证，则查询是否存在该用户名
            $db = new ClientModel();
            //通过用户名获取账户秘钥
            $verify = $db->get_verify($user);
            //如果不存在的密钥，则说明用户不存在
            if (!empty($verify)) {
                return $this->return_info(1, '用户名或密码错误，请检查');
            }
            $map = [
                'client' => htmlentities($data['client']),
                'pass' => md5($data['pass'] . $verify[0]['verify'])
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
            //开启session
            Session::set('client_id', $cus_info['client_id']);
            Session::set('client_info', $db->getData());
            return $this->return_info(1, '登录成功');
        }
    }

    //注销
    public function logout()
    {
        Session::delete('client_id');
        Session::delete('client_info');
        $this->success('注销登录，正在返回', 'client/login');
    }

    //新增用户
    public function add_client()
    {
        //渲染新增用户页面
        return $this->fetch();
    }

    //验证新增操作信息
    public function check_add(Request $request)
    {
        //$data = $request->param();

        $verify = rand(100000, 999999);
        $user = 'admin_client';
        $pass = '123456';
        /*     $rule = [
                 'name|用户名' => 'require |length:2,25',
                 'password|密码' => 'require|length:6,20',
             ];*/

        //    $result = $this->validate($data, $rule);

        //if ($result === true) {
        //验证成功，开始加密数据
        //验证是否存在的用户名
        $db = new ClientModel();
        // $check_name = $db->field('name')->find($data['client']);
        $check_name = $db->check_name($user);
        if (!empty($check_name)) { //存在秘钥，则表明已经存在的用户名
            return $this->return_info(0, '已存在的用户名');
        }
        $client_user = $this->get_user_number();  // 通过函数获得随机账号

        //验证是否存在的账号
        $check_user = $db->get_verify($client_user);
        while (!empty($check_user)) {
            $client_user = $this->get_user_number();
            $check_user = $db->get_verify($client_user);
        }

        $map = [
            'name' => htmlentities($user),
            'client' => $client_user,
            'pass' => md5($pass . $verify),
            'verify' => $verify
        ];
        $info=$db->getData(17);
        return dump($info);
        $insert = new ClientModel($map);
        $re = $insert->save();
        return dump($re);
        //新增用户.
        if ($db->add_client($map)) {
            return $this->return_info(1, '新增成功');
        }
        return $this->return_info(0, '新增失败');
        return 1234;
        /*      }
              return $this->return_info(0, '验证失败，请检查');*/
    }

    //删除用户  传入需要删除的client_id
    public function delete_client(Request $request)
    {
        $data = $request->param();
        $client_id = $data['client_id'] ? $data['client_id'] : 0;
        //验证要删除的用户是否存在的用户
        $db = new ClientModel();
        $client_info = $db->find($client_id);
        if (!$client_info) {
            //不存在的client_id
            return $this->return_info(0, '删除失败，请检查');
        }
        //存在，则执行软删除
        $result = $db->soft_delete($client_id);
        if ($result) {
            return $this->return_info(1, '删除用户成功');
        }
        return $this->return_info(0, '删除失败');
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


}