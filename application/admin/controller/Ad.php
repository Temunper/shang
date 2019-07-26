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
        $ad_model = new AdModel();     //创建一个ad的model类对象
        $request = Request::instance()->param();   //获取客户端数据
        isset($request['status']) ? $status = $request['status'] : $status = 1;  //判断下列数据是否存在
        isset($request['name']) ? $name = $request['name'] : $name = null;
        isset($request['theme_id']) ? $theme_id = $request['theme_id'] : $theme_id = null;
        $ad = $ad_model->get_all_ad($status, $theme_id, $name);       //根据判断到model层获取ad数据
        $this->assign('ad', $ad);//将数据赋值到模板
        return $this->fetch();//返回模板
    }

//添加页
    public function plus()
    {
        $theme = ThemeModel::all();//从model层获取所有的主题，返回对象类型
        $this->assign('theme', $theme->toArray());//将主题对象转数组并赋值到模板
        return $this->fetch("ad/add");//返回模板
    }

//内容页
    public function content()
    {
        $theme = ThemeModel::all();//从model层获取所有的主题，返回对象类型
        $request = Request::instance()->param();//获取客户端数据
        $ad = AdModel::get($request['ad_id']);//从model层获取ad表中主键值为返回数据‘ad_id’的字段
        $this->assign('theme', $theme->toArray());//将数据赋值模板
        $this->assign('ad', $ad->toArray());
        return $this->fetch();
    }

//    删除（修改状态）
    public function update_status()
    {
        $request = Request::instance()->param();
        $d_ad = AdModel::get($request['ad_id']);
        if ($d_ad) {
            $d_ad->status = 2;   //将状态置为2，删除
            if ($d_ad->isUpdate(true)->save()) {    //tp5模型：调用model层更新数据，
                $data = ['code' => 200, 'data' => '删除成功'];    //根据结果返回相应的json数据
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
        $file = $request->file('file');//获取前端上传文件
        if (empty($file))//判断文件是否为空
            $file_path = $request->param('file_path');//如果为空则获取cdn地址
        else {
            $file_path = Upload::file($file, $request->domain(), 'ad');//否则将文件保存到/public/upload/ad文件夹
        }
        if ($file_path == "false") {  //若保存文件失败，返回原因到前端
            $data = ['code' => 202, 'data' => '文件格式只能为jpg和png'];
            return json_encode($data);
        }
        $ad = new AdModel();//实例化一个ad的model类对象
        $d_ad = $ad->where('theme_id', '=', $request->param('theme_id'))->where("status = 1")->select();//获取同一主题下的所有广告
        foreach ($d_ad as $value) {
            if ($value['name'] == $request->param('name')) {  //判断是否有相同广告
                $data = ['code' => 202, 'data' => '同主题下广告位重复'];
                return json_encode($data);
            }
        }
        $ad->name = $request->param('name');            //tp5模型：对一条数据的字段赋值
        $ad->theme_id = $request->param('theme_id');
        $ad->image = $file_path;
        $ad->status = 1;
        $result = $this->validate($ad->toArray(), 'Ad');   //tp5：验证方法
        if ($result === true && $ad->save()) { //返回验证结果，并插入数据
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

        $file = $request->file('file');//同理添加
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
            if ($value['name'] == $request->param('name') && $ad->name != $request->param('name')) {//判断同一主题下是否有更新过后还是相同的广告位名
                $data = ['code' => 202, 'data' => '同主题下广告位重复'];
                return json_encode($data);
            }
        }
        $ad->name = $request->param('name');
        $ad->theme_id = $request->param('theme_id');
        if ($file_path) $ad->image = $file_path;
        $result = $this->validate($ad->toArray(), 'Ad');
        if ($ad->isUpdate(true)->save() && $result === true) {//修改时需要加入isUpdate字段以说明是更新数据
            $data = ['code' => 200, 'data' => '修改成功'];
            return json_encode($data);
        } else {
            $data = ['code' => 202, 'data' => '修改失败,' . $result];
            return json_encode($data);
        }
    }


}