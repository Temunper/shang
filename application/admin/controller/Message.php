<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/17
 * Time: 8:55
 */

namespace app\admin\controller;

use app\admin\model\MessageModel;
use \think\Session;
use \think\Request;

class Message extends Base
{
    protected $message_model = null;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->message_model = new MessageModel();

    }

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
        $data = Request::instance()->param(['project_id', 'content', 'phone']);
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
            $res = $this->message_model->new_message($new_info);
            if ($res) {
                //执行成功，修改状态值和返回信息
                $code = 200;
                $result = "留言成功";
            } else {
                $result = "留言失败";
            }
        }
        //未通过验证，返回错误信息
        return ['code' => $code, 'msg' => $result];
    }


}