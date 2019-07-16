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
use app\customer\common\Csv;
use think\Session;

class Message extends Base
{
    //客户查看自己的留言
    public function see_msg(Request $request)
    {

        $str = [];
        $db = new MessageModel();
        $clinet_id = Session::get('client_id');
        $client_info = Session::get('client_info');
        $project_id = $db->check_project($clinet_id);  //获得项目id
        //拼接成字符串
        if (!empty($project_id)) {
            $str = "";
            for ($i = 0; $i < count($project_id); $i++) {
                $str .= $project_id[$i]['project_id'] . ',';
            }
            $str = rtrim($str, ',');
            $limit = $request->param('limit') ? $request->param('limit') : 15;
            $message_info = $db->show_client_message($str, $limit);
//        return dump($message_info);
            //处理ip,转换成地区
            $str = [];
            foreach ($message_info as $key => $value) {
                $str[$key]['message_id'] = $value['message_id'];
                $str[$key]['client'] = $value['client'];
                $str[$key]['time'] = $value['time'];
                $str[$key]['name'] = $value['name'];
                $str[$key]['ip'] = $this->get_city($value['ip']);
                $str[$key]['content'] = $value['content'];
                $str[$key]['phone'] = $value['phone'];
                $str[$key]['status'] = $value['status'];
            }
        } else {

            $str = "未发布任何项目";
        }
        //渲染查看留言视图
        return $this->fetch('', ['message_info' => $str,    'client_info' => $client_info,]);
    }


    //客户下载留言信息 ,传入要下载留言id数组
    public function down_message(Request $request)
    {
        $data = $request->param('message_id');
        $csv = new Csv();
        //根据id查询信息
        $db = new MessageModel();
        $message_info = $db->to_download($data);
        $csv_title = array('留言ID', '客户名称', '留言时间', '项目名称', '地区', '留言内容', '手机号', '状态');
        $csv = new Csv2();
        $csv->put_csv($message_info, $csv_title);
        //修改数据的状态
    }

    //用户删除留言，传入留言message_id
    public function delete_message(Request $request)
    {
        $data = $request->param();
        if (empty($data['message_id'])) {
            return '非法操作';
        }
        //删除留言
        $db = new Message();
        $result = $db->delete_message($data['message_id']);
        if ($result) {
            return $this->return_info(1, '删除成功');
        }
        return $this->return_info(0, '删除失败');

    }

    //模糊查询
    public function vague_select(Request $request)
    {

    }

    //通过ip获取地区
    function get_city($ip)
    {
        if (empty($ip)) {
            return null;
        }
        $url = "http://ip.taobao.com/service/getIpInfo.php?ip=" . $ip;
        $ipinfo = json_decode(file_get_contents($url));
        if ($ipinfo->code == '1') {
            return false;
        }
        $city = $ipinfo->data->region . $ipinfo->data->city;
        return $city;
    }

}