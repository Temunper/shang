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
use app\admin\model\AdPositionModel;
use think\Request;

class AdPosition extends Base
{
//    广告位页面
    public function ad_position()
    {
        $request = Request::instance()->param();
        isset($request['sort']) ? $sort = $request['sort'] : $sort = null;
        isset($request['status']) ? $status = $request['status'] : $status = null;
        isset($request['name']) ? $name = $request['name'] : $name = null;
        isset($request['theme_id']) ? $theme_id = $request['theme_id'] : $theme_id = null;
        $ad = AdModel::all()->toArray();
        $ad_id = array();
        foreach ($ad as $item) {
            if ($item['theme_id'] == $theme_id) $ad_id[] = $item['ad_id'];
        }
        $adp = new AdPositionModel();
        $ad_position = $adp->get_all_adp($status, $name, $sort, $ad_id);
        $this->assign($ad_position);
        dump($ad_position);die;
        return $this->fetch();
    }

//    删除广告位--项目
    public function update_status(Request $request)
    {
        $adp = $request['ad_position'];
        $d_adp = AdPositionModel::get('ad_position_id');
        $d_adp->status = $adp['status'];
        $d_adp->isUpdate(true)->save();
        $data = ['code' => 200, 'data' => '更改成功'];
        echo json_encode($data);
    }

//    广告位--项目添加
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
            $file_path = Upload::file($file, $request->domain(), 'ad_position');
        }
        $adp = new AdPositionModel();
        $adp->ad_id = $request['ad_id'];
        $adp->project_id = $request['project_id'];
        $adp->name = $request['name'];
        $adp->image = $file_path;
        $adp->abbr = $request['abbr'];
        $adp->sort = $request['sort'];
        $adp->click_num = $request['click_num'];
        $adp->attention = $request['attention'];
        if ($adp->save()) {
            $data = ['code' => 200, 'data' => '添加成功'];
            echo json_encode($data);
        } else {
            $data = ['code' => 202, 'data' => '添加失败'];
            echo json_encode($data);
        }
    }

//    广告位--项目修改
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
            $file_path = Upload::file($file, $request->domain(), 'ad_position');
        }
        $adp = AdPositionModel::get($request['ad_position_id']);
        $adp->ad_id = $request['ad_id'];
        $adp->project_id = $request['project_id'];
        $adp->name = $request['name'];
        if ($file_path) $adp->image = $file_path;
        $adp->abbr = $request['abbr'];
        $adp->sort = $request['sort'];
        $adp->click_num = $request['click_num'];
        $adp->attention = $request['attention'];
        if ($adp->isUpdate(true)->save()) {
            $data = ['code' => 200, 'data' => '修改成功'];
            echo json_encode($data);
        } else {
            $data = ['code' => 202, 'data' => '修改失败'];
            echo json_encode($data);
        }
    }
}