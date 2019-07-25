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

    //构造函数，获取模型实例
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->message_model = new MessageModel();   //创建模型实例
    }

    //渲染后台查看留言页面
    public function message()
    {
        //获取留言信息，按照时间倒序
        //设置返回状态值
        $data = Request::instance()->param();  //提交按钮button 命名为search  如果提交中存在search 则为搜索留言事件，否则正常输出所有信息

        if (isset($data['search'])) {
            //存在search ，执行搜索事件
            //取出时间字段
            //    dump($data);die;
            $date1 = !empty($data['date1']) ? $data['date1'] : 0;
            $date2 = !empty($data['date1']) ? $data['date1'] : time();
            if ($date1 > $date2) {   //如果输入的时间1大于时间2 则交换两个时间
                $date3 = $date1;
                $date1 = $date2;
                $date2 = $date3;
                unset($date3);
            }
            //删除字段中的时间字段
            unset($data['date1']);
            unset($data['date2']);
            $data = array_filter($data);  //删去data数组中值为false的的项，清除值为空的搜索字段
            //执行搜索
            $message_info = $this->message_model->search_message($date1, $date2, $data);
        } else {
            //不存在search 字段 ，则执行搜索所有留言
            //显示所有留言，按照时间的排序
            $message_info = $this->message_model->show_all_message();
        }
        //渲染模板
        // dump($message_info);die;
        return $this->view->fetch('', ['message_info' => $message_info]);
    }

    //系统删除留言功能
    public function system_delete()
    {
        $code = 202;
        $result = "删除失败";
        $data = Request::instance()->param('ids');

        //如果$data['ids']为空，则返回错误信息
        if (empty($data)) {
            return ['code' => $this->code, 'msg' => '请选择要删除的数据'];
        }
        //不为空，则执行
        $result = $this->message_model->system_do_delete($data);  //执行删除动作
        if ($result) {
            //为真，则执行成功，修改状态值和返回信息
            $code = 200;
            $result = "删除成功";
        }
        //返回信息
        return ['code' => $code, 'msg' => $result];
    }


}