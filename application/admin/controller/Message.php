<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/17
 * Time: 8:55
 */

namespace app\admin\controller;

use app\admin\model\Message as MessageModel;
use think\Request;

class Message extends Base
{
    protected $message_model = null;
    protected $code = 202;
    protected $result = ""; //设置初始返回信息

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->message_model = new MessageModel();   //创建模型实例
    }

    //渲染后台查看留言页面
    public function message_page()
    {
        //获取留言信息，按照时间倒序
        //设置返回状态值
        $data = Request::instance()->param('search');  //提交按钮button 命名为search  如果提交中存在search 则为搜索留言事件，否则正常输出所有信息
        if (isset($data['search'])) {
            //存在search ，执行搜索事件
            //取出时间字段
            $time = $data['time1'] ? $data['time1'] : 0;
            $time2 = $data['time2'] ? $data['time2'] : 0;
            //删除字段中的时间字段
            unset($data['time1']);
            unset($data['time2']);
            $data = array_filter($data);  //删去data数组中值为false的的项，清除值为空的搜索字段
            //执行搜索
            $message_info = $this->message_model->search_message($time, $time2, $data);

        } else {
            //不存在search 字段 ，则执行搜索所有留言
            //显示所有留言，按照时间的排序
            $message_info = $this->message_model->show_all_message();
        }
        //渲染模板
        return $this->view->fetch('', ['message_info' => $message_info]);
    }

    //系统删除留言功能
    public function system_delete()
    {
        $data = Request::instance()->param('ids');
        //如果$data['ids']为空，则返回错误信息
        if (empty($data['ids'])) {
            return ['code' => $this->code, 'msg' => '请选择要删除的数据'];
        }
        //不为空，则执行
        $result = $this->message_model->system_do_delete($data);  //执行删除动作
        if ($result) {
            //为真，则执行成功，修改状态值和返回信息
            $this->code = 200;
            $this->result = "删除成功";
        }
        {
            //执行失败，修改返回信息
            $this->result = "删除失败";
        }
        //返回信息
        return ['code' => $this->code, 'msg' => $this->result];
    }


}