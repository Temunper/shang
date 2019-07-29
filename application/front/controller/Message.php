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
    protected $model = null;

    function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->model = new MessageModel();
    }
    //
    /*  public function check_message()
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
      }*/


    //记录提交留言信息
    public function record_number()
    {
        $code = 202;
        $result = "";
        //1、获取表单信息，
        $info = Request::instance()->only(['project_id', 'phone', 'client', 'content']);
        //验证手机号
        if (strlen($info['phone']) != 11) {
            return ['code' => $code, 'msg' => '请输入合法的手机号'];
        }
        $db = new ProjectModel();

        $res = $db->get_project_info($info['project_id']);        //验证是否存在的project_id
        if (empty($res)) {
            return ['code' => $code, 'msg' => '不存在的项目'];
        }
        $ip = Request::instance()->ip();  //获取当前用户ip
        $time_now = time(); //获取当前时间戳
        $data = ['project_id' => $info['project_id'], 'phone' => $info['phone'], 'ip' => $ip, 'time' => $time_now];//拼接用的ip和当前时间
        //2、写入message_log表单
        $info = array_merge($info, ['ip' => $ip, 'time' => $time_now, 'status' => 1]);//拼接用的ip和当前时间
        $this->model->insert_message_log($data);   //记录着一次的留言 到message_log
        $verify = $this->verify($info);   //然后验证是否可以录入
        //dump($verify);die;
        if (!$verify) {
            //返回为false ，不允许录入，则返回录入成功，不执行录入
            return ['code' => 200, 'msg' => '留言录入成功'];
        }
        //如果$res 为真，则允许录入留言到message
        $result = $this->model->insert_message($info); //录入留言到message
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
        $project_id = $params['project_id'];
        $phone = $params['phone'];
        $ip = $params['ip'];

        //2、验证当前项目id留言下是否存在当前表单中的手机号
        $re = $this->model->check_exist_phone($project_id, $phone);
        if (empty($re)) {
            //如果为空，则没有记录，则判断进入后续判断
            return $this->check_number($ip);  //判断今日留言是否超过5此 ，超过5此返回false
        }
        //3、如果存在，则计算上次留言时间是否已经过了3个月
        //不为空，存在记录，则判断记录中的时间戳+上三个月的时间戳是否大于当前时间戳
        $shot_clock = 7948800;   //三个月的秒数。默认最长92天
        if ($re['time'] + $shot_clock > time()) {  //如果记录的时间+3个月的秒数大于当前时间戳，则说明未过3个月，
            //则不允许留言
            return false;
        }
        //通过判断，则已过三个月

        //6、如果ip的记录数量不超过5条，则执行插入message
        return $this->check_number($ip);  //判断今日留言是否超过5此
        //通过验证。返回true，允许录入留言
    }


    //今天到现在的记录已超过5次，则返回false
    public function check_number($ip)
    {
        $res = $this->model->check_message_number($ip);
        if ($res >= 5) {  //如果总数大于5，则返回false
            return false;
        } else {
            return true;
        }
    }

}