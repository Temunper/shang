<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/17
 * Time: 8:55
 */

namespace app\admin\controller;


class Message extends Base
{
    //渲染留言页面
    public function message_page()
    {
        return $this->view->fetch();
    }

    //接收留言信息，验证信息，写入信息
    public function check_message()
    {
        $code = 1;

        //获取当前用户信息
        $client_info = Session::get('admin');
        $client_name = $client_info['admin_name'];
        $ip = Request::instance()->server('ip');
        $data = Request::instance()->param();
        $time = time();
        $rule = [
            'project_id' => 'require',
            'content' => 'require',
            'phone' => 'require|length:11'
        ];
        $msg = [
            'project_id' => ['require' => '项目不能为空'],
            'content' => ['require' => '内容不能为空'],
            'phone' => ['require' => '手机号不能为空',
                'length' => '请输入合法的手机号'],
        ];
        $result = $this->validate($data, $rule, $msg);
    }


}