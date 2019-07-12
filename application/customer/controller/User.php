<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/11
 * Time: 9:02
 */

namespace app\customer\controller;

use app\customer\model\User as UserModel;
use app\customer\validate\UserValidate;
use think\Loader;
use think\Request;
use think\Session;

class User extends Base
{
    //渲染登录页面
    public function login()
    {
        return $this->fetch();
    }

    //登录
    public function check_login(Request $request)
    {
        $this->already_login();
        //初始化登录状态
        $data = $request->post();

        $rule = [
            'user|账号' => 'require ',
            'pass|密码' => 'require',
            'verify|验证码' => 'require|captcha',
        ];
        $msg = [
            'user' => ['require' => '账户不能为空，请检查'],
            'pass' => ['require' => '密码不能为空，请检查'],
            'verify' => ['require' => '验证码不能为空，请检查',
                'captcha' => '验证码错误'],
        ];
        $result = $this->validate($data, $rule, $msg);
        if ($result === true) {
            //通过验证，则查询是否存在该用户名
            //通过用户名获取账户秘钥
            $db = new UserModel();
            $verify = $db->field('verify')->where('user', $data['user'])->find();

            if (!$verify) {
                return $this->return_info(1, '用户名或密码错误，请检查');
            }
            $map = [
                'user' => htmlentities($data['user']),
                'pass' => md5($data['pass'] . $verify)
            ];

            $cus_info = UserModel::get($map);
            if ($cus_info == null) {
                return $this->return_info(1, '用户名或密码错误，请检查');
            }
            //判断账户状态
            if ($cus_info['status'] == 0) {
                return $this->return_info(1, '禁用的账户');
            }
            //开启session
            Session::set('client_id', $cus_info['client_id']);
            Session::set('client_info', $db->getData());
            return $this->return_info(1, '登录成功');
        }
        return $this->return_info(0, '登录失败，请检查');

    }

    //注销
    public function logout()
    {
        Session::delete('client_id');
        Session::delete('client_info');
        $this->success('注销登录，正在返回');
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
        $data = $request->param();
        $verify = rand(100000, 999999);

        /*        $rule = [
                    'name|名称' => 'require |max:10',
                    'user|账号' => 'require',
                    'pass|密码' => 'require',
                ];

                $msg = [
                    'name' => ['require' => '账户不能为空，请检查',
                        'unique' => '该名称已存在，请更换名称'],
                    'user' => ['require' => '账户不能为空，请检查'],
                    'password' => ['require' => '密码不能为空，请检查'],
                ];*/


        $rule = [
            'name|用户名' => 'require |length:2,25',
            'password|密码' => 'require|length:6,20',
        ];

        $result = $this->validate($data, $rule);

        if ($result === true) {
            //验证成功，开始加密数据
            //验证是否存在的用户名
            $db = new UserModel();
            $check_name = $db->field('name')->find($data['user']);
            if ($check_name) { //存在秘钥，则表明已经存在的用户名
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
                'name' => htmlentities($data['name']),
                'user' => $client_user,
                'pass' => md5($data['password'] . $verify),
                'verify' => $verify
            ];
            //新增用户.
            if ($db->add_client($map)) {
                return $this->return_info(1, '新增成功');
            }
            return $this->return_info(0, '新增失败');
        }
        return $this->return_info(0, '验证失败，请检查');
    }

    //删除用户  传入需要删除的client_id
    public function delete_client(Request $request)
    {
        $data = $request->param();
        $client_id = $data['client_id'] ? $data['client_id'] : 0;
        //验证要删除的用户是否存在的用户
        $db = new UserModel();
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


}