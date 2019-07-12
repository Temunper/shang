<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/11
 * Time: 11:46
 */

namespace app\customer\controller;

use app\customer\common\Csv2;
use app\customer\model\Message as MessageModel;
use think\Request;
use app\customer\common\Csv;

class Message extends Base
{
    //客户查看自己的留言
    public function see_msg()
    {
        //通过session['client_info']['name']获得用户id ，查询留言的
   /*     $db = new MessageModel();
        $client_name = $_SESSION['client']['name'];
        $mesage_info = $db->show_client_message($client_name, 20);

        foreach ($mesage_info as $key => $value) {
            $info[$key]['message_id'] = $value->message_id;
            $info[$key]['client'] = $value->client;
            $info[$key]['time'] = $value->time;
            $info[$key]['project_id'] = $value->project_id;
            $info[$key]['ip'] = $value->ip;
            $info[$key]['content'] = $value->content;
            $info[$key]['phone'] = $value->phone;
            $info[$key]['status'] = $value->status;
        }*/

        return $this->fetch();


        //渲染查看留言视图

    }

    //客户下载留言信息 ,传入要下载留言id数组
    public function down_message(Request $request)
    {
        $data = $request->param('message_id');
        $csv = new Csv();
        //根据id查询信息
        $db = new MessageModel();
        $message_info = $db->to_download($data);
        $csv_title = array('留言ID', '客户名称', '留言时间', '项目名称', 'ip', '留言内容', '手机号', '状态');
        $csv = new Csv2();
        $csv->put_csv($message_info, $csv_title);
        //修改数据的状态
    }

    //用户删除留言，传入留言message_id
    public function delete_message(Request $request)
    {

        $status = 0;
        $result = '';
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
}