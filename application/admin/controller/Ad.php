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
use app\admin\model\ProjectModel;
use app\admin\model\ThemeModel;
use think\Controller;
use think\Request;
use think\Validate;

class Ad extends Base
{
//    广告位页面
    public function ad()
    {
        $ad_model = new AdModel();
        $request = Request::instance()->param();
        isset($request['status']) ? $status = $request['status'] : $status = null;
        isset($request['name']) ? $name = $request['name'] : $name = null;
        isset($request['theme_id']) ? $theme_id = $request['theme_id'] : $theme_id = null;
        $ad = $ad_model->get_all_ad($status, $theme_id, $name);
        $this->assign('ad', $ad);
        return $this->fetch();
    }

//添加页
    public function plus()
    {
        $theme = ThemeModel::all();
        $this->assign('theme', $theme->toArray());
        return $this->fetch("ad/add");
    }

//内容页
    public function content()
    {
        $theme = ThemeModel::all();
        $request = Request::instance()->param();
        $ad = AdModel::get($request['ad_id']);
        $this->assign('theme', $theme->toArray());
        $this->assign('ad', $ad->toArray());
        return $this->fetch();
    }

//    删除（修改状态）
    public function update_status()
    {
        $request = Request::instance()->param();
        $d_ad = AdModel::get($request['ad_id']);
        if ($d_ad) {
            $d_ad->status = 2;
            if ($d_ad->isUpdate(true)->save()) {
                $data = ['code' => 200, 'data' => '删除成功'];
                return json_encode($data);
            } else {
                $data = ['code' => 202, 'data' => '删除失败'];
                return json_encode($data);
            }
        } else {
            $data = ['code' => 202, 'data' => 'id不存在'];
            return json_encode($data);
        }
    }

//    广告位添加
    public function add(Request $request)
    {
        $file = $request->file('file');
        if (empty($file))
            $file_path = $request->param('file_path');
        else {
            $file_path = Upload::file($file, $request->domain(), 'ad');
        }
        if ($file_path == "false") {
            $data = ['code' => 202, 'data' => '文件格式只能为jpg和png'];
            return json_encode($data);
        }
        $ad = new AdModel();
        $d_ad = $ad->where('theme_id', '=', $request->param('theme_id'))->where("status = 1")->select();
        foreach ($d_ad as $value) {
            if ($value['name'] == $request->param('name')) {
                $data = ['code' => 202, 'data' => '同主题下广告位重复'];
                return json_encode($data);
            }
        }
        $ad->name = $request->param('name');
        $ad->theme_id = $request->param('theme_id');
        $ad->image = $file_path;
        $ad->status = 1;
        $result = $this->validate($ad->toArray(), 'Ad');
        if ($ad->save() && $result === true) {
            $data = ['code' => 200, 'data' => '添加成功'];
            return json_encode($data);
        } else {
            $data = ['code' => 202, 'data' => '添加失败,' . $result];
            return json_encode($data);
        }
    }

//    广告位修改
    public function update(Request $request)
    {

        $file = $request->file('file');
        if (empty($file))
            $file_path = $request->param('file_path');
        else {
            $file_path = Upload::file($file, $request->domain(), 'ad');
        }
        if ($file_path == "false") {
            $data = ['code' => 202, 'data' => '文件格式只能为jpg和png'];
            return json_encode($data);
        }
        $ad = new AdModel();
        $d_ad = $ad->where('theme_id', '=', $request->param('theme_id'))->where("status = 1")->select();
        $ad = AdModel::get($request->param('ad_id'));
        foreach ($d_ad as $value) {
            if ($value['name'] == $request->param('name') && $ad->name != $request->param('name')) {
                $data = ['code' => 202, 'data' => '同主题下广告位重复'];
                return json_encode($data);
            }
        }
        $ad->name = $request->param('name');
        $ad->theme_id = $request->param('theme_id');
        if ($file_path) $ad->image = $file_path;
        $result = $this->validate($ad->toArray(), 'Ad');
        if ($ad->isUpdate(true)->save() && $result === true) {
            $data = ['code' => 200, 'data' => '修改成功'];
            return json_encode($data);
        } else {
            $data = ['code' => 202, 'data' => '修改失败,' . $result];
            return json_encode($data);
        }
    }


}