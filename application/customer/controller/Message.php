<?php
/**
 * Created by PhpStorm.
 * Clientw: vpanda
 * Date: 2019/7/11
 * Time: 11:46
 */

namespace app\customer\controller;

use app\customer\common\Csv2;
use app\customer\model\Message as MessageModel;
use think\Request;
use think\Session;

class Message extends Base
{
    protected $model = null;

    function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->model = new MessageModel();
    }

    //客户查看自己的留言
    public function see_msg()
    {

        //1.获取搜索栏信息
        $data = Request::instance()->post();
        $client_id = Session::get('client_id'); //获得当前用户Id
        $project_info = $this->get_client_project($client_id);  //通过用户id 查询用户所有项目信息


        //2. 判断是否存在search 字段
        //如果存在search 字段，则处理页面上端搜索
        if (isset($data['search'])) {
            $time1 = !empty($data['date1']) ? strtotime($data['date1']) : 0;
            $time2 = !empty($data['date2']) ? strtotime($data['date2']) : time();
            if ($time1 > $time2) {   //如果输入的时间1大于时间2 则交换两个时间
                $time3 = $time1;
                $time1 = $time2;
                $time2 = $time3;
            }
            unset($time3);
            unset($data['date1']);
            unset($data['date2']);   //删除数组中的时间字段
            $data = array_filter($data);  //删除字段值为空的字段
            //执行搜索，返回搜索信息
            $message_info = $this->model->check_exact($time1, $time2, $data);
            //  dump($message_info);die;
        } else {
            //不存在search字段，则返回用户所有留言
            $project_id = $this->model->check_project($client_id);
            $project_id = implode(',', $project_id);  //将数组用逗号分隔成字符串
            $message_info = $this->model->show_client_message($project_id);
        }

        //3.声明变量
        $this->assign('message_info', $message_info);
        $this->assign('project', $project_info);

        //4.渲染查看留言视图
        return $this->fetch();
    }


    //客户下载留言信息 ,传入要下载留言id数组
    public function down_message()
    {
        //1.设置默认返回值
        $status = 0;
        $result = "";
        //2.获取要下载的留言id ，字符串形式
        $data = Request::instance()->param('ids');  //获取要下载的文件id集
        //3.根据id查询信息
        $message_info = $this->model->to_download($data);

        //4.设置下载文件的title
        $this->model->cheange_status($data);     //修改留言数据的状态
        $csv_title = array('客户名称', '留言时间', '项目名称', '地区', '留言内容', '手机号');

        //5.执行下载.
        $csv = new Csv2();
        $res = $csv->put_csv($message_info, $csv_title);  //执行下载
        if (!$res) {
            //执行失败
            $result = "导出失败";
            return ['status' => $status, 'message' => $result];
        }
        return true;
    }

    //用户删除留言，传入留言message_id
    public function delete_message()
    {
        //1.设置默认返回值
        $status = 0;
        $result = "";

        //2.获取要删除的留言ids,字符串形式
        $data = Request::instance()->param('ids');

        //3.删除留言
        $result = $this->model->delete_message_m($data);

        if ($result) {
            //执行成功，修改返回状态和返回信息
            $status = 1;
            $result = "删除成功";
        } else {
            $result = "删除失败";
        }
        return ['status' => $status, 'message' => $result];

    }

    //通过项目ip查询当天留言总数
    public function check_message_count($project_id)
    {
        return $this->model->check_count($project_id);
    }

    //通过用户id 查询用户所有项目信息
    public static function get_client_project($client_id)
    {
        $db = new MessageModel();
        $project_id = $db->check_project($client_id);  //获得当前用户所有项目id
        if (!empty($project_id)) {
            //通过项目id查询所有项目名，
            return $db->check_project_name($project_id); //获得所有项目名，返回给前端
        } else {
            return [];
        }
    }

}