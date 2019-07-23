<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/11
 * Time: 9:44
 */

namespace app\admin\controller;

use app\admin\model\ThemeModel;
use think\Request;

class Theme extends Base
{
    protected $theme_model = null;

    public function __construct()
    {
        parent::__construct();
        $this->theme_model = new ThemeModel();
    }

//    主题页
    public function theme()
    {
        $d_theme = ThemeModel::all(function ($query) {
            $query->where('status', '1');
        })->toArray();
        $this->assign('theme', $d_theme);
        return $this->fetch();
    }

//    添加页
    public function plus()
    {
        return $this->fetch("theme/add");
    }

//    查看所有主题
    public function get_all_theme($status)
    {
        return $this->theme_model->get_all_theme($status);
    }

//    添加主题
    public function add()
    {
        $request = Request::instance()->param();
        $request = special_filter($request);
        $theme = new ThemeModel();
        if ($request['name']) {
            $theme = $theme->where('name', '=', $request['name'])->where('status = 1')->select()->toArray();
        } else {
            $data = ['code' => 202, 'data' => '主题名不为空'];
            return json_encode($data);
        }
        if (!$theme) {
            $this->theme_model->name = $request['name'];
            $this->theme_model->status = 1;
            if ($this->theme_model->save()) {
                $data = ['code' => 200, 'data' => '添加成功'];
                return json_encode($data);
            } else {
                $data = ['code' => 202, 'data' => '添加失败'];
                return json_encode($data);
            }
        } else {
            $data = ['code' => 202, 'data' => '主题已存在'];
            return json_encode($data);
        }
    }

//    修改主题名
    public function update()
    {
        $request = Request::instance()->param();
        $d_theme = ThemeModel::all();
        $theme = ThemeModel::get($request['theme_id']);
        if ($theme) {
            foreach ($d_theme as $value) {
                if ($value['name'] == $request['name'] && $request['name'] != $theme['name']) {
                    $data = ['code' => 202, 'data' => '主题名已存在'];
                    return json_encode($data);
                }
            }
            $theme->name = $request['name'];
            if ($theme->save()) {
                $data = ['code' => 200, 'data' => '修改成功'];
                return json_encode($data);
            } else {
                $data = ['code' => 200, 'data' => '修改失败'];
                return json_encode($data);
            }
        } else {
            $data = ['code' => 202, 'data' => '被修改主题不存在'];
            return json_encode($data);
        }


    }

//    更改主题状态(删除)
    public function update_status()
    {
        $request = Request::instance()->param();
        $theme = $request['theme'];
        $d_theme = ThemeModel::get($theme['theme_id']);
        $d_theme->status = 2;
        if ($d_theme->isUpdate(true)->save()) {
            $data = ['code' => 200, 'data' => '更改成功'];
            return json_encode($data);
        } else {
            $data = ['code' => 202, 'data' => '更改失败'];
            return json_encode($data);
        }
    }

//    删除主题
//    public function del_theme()
//    {
//$request = Request::instance()->param();
//        $theme = ThemeModel::get($request['theme_id']);
//        if ($theme) {
//            $theme->delete();
//            $data = ['code' => 200, 'data' => '删除成功'];
//            echo json_encode($data);
//        } else {
//            $data = ['code' => 202, 'data' => '删除失败'];
//            echo json_encode($data);
//        }
//    }

}