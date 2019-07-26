<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/20
 * Time: 16:38
 */

namespace app\front\controller;


use app\front\model\MessageModel;
use app\front\model\ProjectModel;
use think\Controller;
use think\Request;

class Message extends Controller
{
    //
    public function check_message()
    {
        $code = 1;  //设置初始返回状态值
        $result = ""; //设置初始返回信息
        //获取当前用户信息
        $client_info = Session::get('admin');
        $client_name = $client_info['admin_name'];
        $ip = Request::instance()->server('ip'); //获取当前用户的ip
        $data = Request::instance()->param(); //接收指定的信息
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


    //记录提交留言信息
    public function record_number()
    {
        $code = 202;
        $result = "";
        //1、获取表单信息，
        $data = Request::instance()->only(['project_id', 'phone']);
        //验证手机号
        if (strlen($data['phone']) != 11) {
            return ['code' => $code, 'msg' => '请输入合法的手机号'];
        }
        //验证是否存在的project_id
        $db = new ProjectModel();
        $res = $db->get_project_info($data['project_id']);
        if (empty($res)) {
            return ['code' => $code, 'msg' => '不存在的项目'];
        }
        $ip = Request::instance()->ip();  //获取当前用户ip
        $time_now = time(); //获取当前时间戳
        $data = array_merge($data, ['ip' => $ip, 'time' => $time_now]);//拼接用的ip和当前时间
        //2、写入message_log表单
        $db = new MessageModel();
        $db->insert_message_log($data);
        $res = $this->verify($data);   //验证是否可以录入
        if (!$res) {
            //返回为false ，不允许录入，则返回录入成功，不执行录入
            return ['code' => 200, 'msg' => '留言录入成功'];
        }
        //如果$res 为真，则允许录入留言到message
        $info = Request::instance()->only(['project_id', 'phone', 'client', 'content']);
        $info = array_merge($info, ['ip' => $ip, 'time' => $time_now]);//拼接用的ip和当前时间
        $result = $db->insert_message($info); //录入留言到message
        if ($result) {
            $code = 200;
            $result = "留言录入成功";
        } else {
            $result = "留言录入失败，请检查";
        }
        return ['code' => $code, 'msg' => $result];
    }

    //限制录入留言方法
    public function verify($params)
    {
        //1、获取表单信息
        //2、验证当前项目id留言下是否存在当前表单中的手机号
        $db = new MessageModel();
        $re = $db->check_exist_phone($params['project'], $params['phone']);
        if (empty($re)) {
            //如果为空，则没有记录，返回true
            return true;
        }
        //3、如果存在，则计算上次留言时间是否已经过了3个月
        //不为空，存在记录，则判断记录中的时间戳+上三个月的时间戳是否大于当前时间戳
        $shot_clock = 7948800;   //三个月的秒数。默认最长92天
        if ($re['time'] + $shot_clock > time()) {  //如果记录的时间+3个月的秒数大于当前时间戳，则说明未过3个月，
            //则不允许留言
            return false;
        }
        //通过判断，则已过三个月

        //4、如果没有过三个月，直接返回成功，但不执行写入操作
        //5、如果已经过了三个月，则检查当天当前ip在所有项目中的留言次数，也就是有多少条记录的ip 是当前ip,如果超过5条 ，则返回成功，但不执行
        $res = $db->check_message_number($params['ip']);
        if ($res > 5) {
            return false;  //今天到现在的记录已超过5天，则返回false
        }
        return true;   //通过验证。返回true，允许录入留言
        //6、如果ip的记录数量不超过5条，则执行插入message
    }

}