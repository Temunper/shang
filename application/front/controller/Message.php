<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/20
 * Time: 16:38
 */

namespace app\front\controller;


class Message
{
    public function check_message()
    {
        $code = 1;  //设置初始返回状态值
        $result = ""; //设置初始返回信息
        //获取当前用户信息
        $client_info = Session::get('admin');
        $client_name = $client_info['admin_name'];
        $ip = Request::instance()->server('ip'); //获取当前用户的ip
        $data = Request::instance()->param(['project_id', 'content', 'phone']); //接收指定的信息
        //验证规则
        $rule = [
            'project_id' => 'require',
            'content' => 'require',
            'phone' => 'require|length:11'
        ];
        //设置验证错误返回信息
        $msg = [
            'project_id' => ['require' => '项目不能为空'],
            'content' => ['require' => '内容不能为空'],
            'phone' => ['require' => '手机号不能为空',
                'length' => '请输入合法的手机号'],
        ];
        //验证信息中。。。。
        $result = $this->validate($data, $rule, $msg);
        if ($result === true) {
            //通过验证。执行新增留言操作
            $new_info = [
                'client' => $client_name,
                'time' => time(),
                'project_id' => $data['project_id'],
                'ip' => $ip,
                'content' => $data['content'],
                'phone' => $data['phone'],
                'status' => 1,
            ];
            $res = $this->message_model->new_message($new_info);  //执行插入留言
            if ($res) {
                //执行成功，修改状态值和返回信息
                $code = 200;
                $result = "留言成功";
            } else {
                //执行直白，修改错误信息
                $result = "留言失败";
            }
        }
        //返回信息
        return ['code' => $code, 'msg' => $result];
    }
}