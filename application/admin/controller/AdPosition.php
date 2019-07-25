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
use app\admin\model\ProjectModel;
use app\admin\model\ThemeModel;
use think\Request;


class AdPosition extends Base
{
//    添加页
    public function plus()
    {
        $theme = ThemeModel::all();    //实例化主题model层对象
        $ad = AdModel::all();
        $project = ProjectModel::all();
        $this->assign('project', json_encode($project)); //为模板赋值
        $this->assign('ad', json_encode($ad));
        $this->assign('theme', $theme->toArray());
        return $this->fetch("ad_position/add");  //返回模板
    }

//    内容页
    public function content()
    {
        $theme = ThemeModel::all();//同理添加页
        $ad = AdModel::all();
        $project = ProjectModel::all();
        $request = Request::instance()->param();
        $adp = AdPositionModel::get($request['ad_position_id']);
        $this->assign('adp', $adp->toArray());
        $this->assign('project', json_encode($project));
        $this->assign('ad', json_encode($ad));
        $this->assign('theme', $theme->toArray());
        return $this->fetch();
    }

//    广告位页面
    public function ad_position()
    {
        $request = Request::instance()->param();                                                    //获取模板返回数据
        isset($request['sort']) ? $sort = $request['sort'] : $sort = null;                          //判断值是否为空
        isset($request['status']) ? $status = $request['status'] : $status = 1;
        isset($request['name']) ? $name = $request['name'] : $name = null;
        isset($request['theme_id']) ? $theme_id = $request['theme_id'] : $theme_id = null;
        isset($request['ad_id']) ? $ad_id = $request['ad_id'] : $ad_id = null;
        isset($request['project_id']) ? $project_id = $request['project_id'] : $project_id = null;
        $ad = AdModel::all()->toArray();                                                             //或所有广告信息，并将对象转换成数组
        $id = [];
        $a = $ad_id;
        if (!$ad_id) {
            foreach ($ad as $item) {                                                                 //循环遍历出所有相同主题的广告位项目id
                if ($item['theme_id'] == $theme_id) $id[] = $item['ad_id'];
            }
            $ad_id = $id;                                                                            //将数组赋值给ad_id
        }
        $adp = new AdPositionModel();
        $ad_position = $adp->get_all_adp($status, $name, $sort, $ad_id, $project_id);                //根据条件获取所有的广告位项目信息
        $this->assign('ad_position', $ad_position);                                           //同理添加页，页面需要信息
        $theme = ThemeModel::all();
        $ad = AdModel::all();
        $project = ProjectModel::all();
        $search = ['sort' => $sort, 'theme_id' => $theme_id, 'ad_id' => $a, 'project_id' => $project_id];
        $this->assign('project', json_encode($project));
        $this->assign('ad', json_encode($ad));
        $this->assign('theme', $theme->toArray());
        $this->assign('search', json_encode($search));
        return $this->fetch();
    }

//    删除广告位--项目
    public function update_status()
    {
        $request = Request::instance()->param();                            //获取返回数据
        $adp_id = $request['ad_position_id'];
        $d_adp = AdPositionModel::get($adp_id);                             //实例化返回id的对象，
        if ($d_adp) {
            $d_adp->status = 2;                                             //状态置为2
            if ($d_adp->isUpdate(true)->save()) {
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

//    广告位--项目添加
    public function add(Request $request)
    {
        $file = $request->file('file');//获取文件，经过判断，保存或使用cdn地址
        if (empty($file))
            $file_path = $request->param('file_path');
        else {
            $file_path = Upload::file($file, $request->domain(), 'ad_position');
        }
        if ($file_path == "false") {
            $data = ['code' => 202, 'data' => '文件格式只能为jpg和png'];
            return json_encode($data);
        }
        $adp = new AdPositionModel();
        $d_adp = $adp->where('ad_id', '=', $request->param('ad_id'))->where(" status = 1")->select();
        foreach ($d_adp as $value) {
            if ($value['name'] == $request->param('name')) {                //判断广告位广告是否重复
                $data = ['code' => 202, 'data' => '同一个广告位下已存在该name'];
                return json_encode($data);
            }
        }
        $adp->ad_id = $request->param('ad_id');                                //给对象赋值
        $adp->project_id = $request->param('project_id');
        $adp->name = $request->param('name');
        $adp->image = $file_path;
        $adp->abbr = $request->param('abbr');
        $adp->sort = $request->param('sort');
        $adp->click_num = $request->param('click_num');
        $adp->attention = $request->param('attention');
        $adp->status = 1;
        $result = $this->validate($adp->toArray(), 'AdPosition');               //判断赋值对象数据是否正确
        if (true === $result && $adp->save()) {
            $data = ['code' => 200, 'data' => '添加成功'];
            return json_encode($data);
        } else {
            $data = ['code' => 202, 'data' => '添加失败,' . $result];
            return json_encode($data);
        }
    }

//    广告位--项目修改
    public function update(Request $request)
    {
        $file = $request->file('file');//获取文件，经过判断，保存或使用cdn地址
        if (empty($file))
            $file_path = $request->param('file_path');
        else {
            $file_path = Upload::file($file, $request->domain(), 'ad_position');
        }
        if ($file_path == "false") {
            $data = ['code' => 202, 'data' => '文件格式只能为jpg和png'];
            return json_encode($data);
        }
        $adp = new AdPositionModel();
        $d_adp = $adp->where('ad_id', '=', $request->param('ad_id'))->where(" status = 1")->select();
        $adp = AdPositionModel::get($request->param('ad_position_id'));
        foreach ($d_adp as $value) {                                                                   //判断广告位广告是否重复
            if ($value['name'] == $request->param('name') && $adp->name != $request->param('name')) {
                $data = ['code' => 202, 'data' => '同一个广告位下已存在该name'];
                return json_encode($data);
            }
        }
        $adp->ad_id = $request->param('ad_id');
        $adp->project_id = $request->param('project_id');
        $adp->name = $request->param('name');
        if ($file_path) $adp->image = $file_path;
        $adp->abbr = $request->param('abbr');
        $adp->sort = $request->param('sort');
        $adp->click_num = $request->param('click_num');
        $adp->attention = $request->param('attention');
        $result = $this->validate($adp->toArray(), 'AdPosition');   //判断赋值对象数据是否正确
        if ($result === true && $adp->isUpdate(true)->save()) {
            $data = ['code' => 200, 'data' => '修改成功'];
            return json_encode($data);
        } else {
            $data = ['code' => 202, 'data' => '修改失败' . $result];
            return json_encode($data);
        }
    }
}