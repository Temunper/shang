<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/11
 * Time: 10:05
 */

namespace app\admin\controller;


use app\admin\common\Upload;
use app\admin\model\AdModel;
use think\Controller;
use think\Request;

class Ad extends Base
{
//    广告位页面
    public function ad(Request $request)
    {
        $ad_model = new AdModel();
        isset($request['status']) ? $status = $request['status'] : $status = null;
        isset($request['name']) ? $name = $request['name'] : $name = null;
        isset($request['theme_id']) ? $theme_id = $request['theme_id'] : $theme_id = null;
        $ad = $ad_model->get_all_ad($status, $theme_id, $name);
        $this->assign($ad);
        return $this->fetch();
    }

//    删除（修改状态）
    public function update_status(Request $request)
    {
        $ad = $request['ad'];
        $d_ad = AdModel::get($ad['ad_id']);
        $d_ad->status = $ad['status'];
        $d_ad->isUpdate(true)->save();
        $data = ['code' => 200, 'data' => '更改成功'];
        echo json_encode($data);
    }

//    广告位添加
    public function add(Request $request)
    {
        $file = $request->file('file');
        if (!$file)
            $file_path = $request->param('file_path');
        else {
            if (mime_content_type($file) != ('image/gif' | 'image/png' | 'image/jpg')) {
                $data = ['code' => 202, 'data' => '上传文件格式不正确'];
                echo json_encode($data);
            }
            $file_path = Upload::file($file, $request->domain(), 'ad');
        }
        $ad = new AdModel();
        $d_ad = $ad->where('theme_id', '=', $request['theme_id'])->select();
        foreach ($d_ad as $value) {
            if ($value['name'] == $request['name']) {
                $data = ['code' => 202, 'data' => '同主题下广告位重复'];
                echo json_encode($data);
            }
        }
        $ad->name = $request['name'];
        $ad->theme_id = $request['theme_id'];
        $ad->image = $file_path;
        if ($ad->save()) {
            $data = ['code' => 200, 'data' => '添加成功'];
            echo json_encode($data);
        } else {
            $data = ['code' => 202, 'data' => '添加失败'];
            echo json_encode($data);
        }
    }

//    广告位修改
    public function update(Request $request)
    {
        $file = $request->file('file');
        if (!$file)
            $file_path = $request->param('file_path');
        else {
            if (mime_content_type($file) != ('image/gif' | 'image/png' | 'image/jpg')) {
                $data = ['code' => 202, 'data' => '上传文件格式不正确'];
                echo json_encode($data);
            }
            $file_path = Upload::file($file, $request->domain(), 'ad');
        }
        $ad = new AdModel();
        $d_ad = $ad->where('theme_id', '=', $request['theme_id'])->select();
        foreach ($d_ad as $value) {
            if ($value['name'] == $request['name']) {
                $data = ['code' => 202, 'data' => '同主题下广告位重复'];
                echo json_encode($data);
            }
        }
        $ad->name = $request['name'];
        $ad->theme_id = $request['theme_id'];
        if ($file_path) $ad->image = $file_path;
        if ($ad->isUpdate(true)->save()) {
            $data = ['code' => 200, 'data' => '添加成功'];
            echo json_encode($data);
        } else {
            $data = ['code' => 202, 'data' => '添加失败'];
            echo json_encode($data);
        }
    }


}