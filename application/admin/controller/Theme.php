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
    public function theme(Request $request)
    {
        isset($request['status']) ? $status = $request['status'] : $status = null;
        $d_theme = $this->get_all_theme($status);
        $this->assign('theme', $d_theme);
        return $this->fetch();
    }

//    查看所有主题
    public function get_all_theme($status)
    {
        return $this->theme_model->get_all_theme($status);
    }

//    添加主题
    public function add_theme(Request $request)
    {

        $user = ThemeModel::getByName($request['name']);
        if (!$user) {
            $this->theme_model->name = $request['name'];
            if ($this->theme_model->save()) {
                $data = ['code' => 200, 'data' => '添加成功'];
                echo json_encode($data);
            } else {
                $data = ['code' => 202, 'data' => '添加失败'];
                echo json_encode($data);
            }
        } else {
            $data = ['code' => 202, 'data' => '主题已存在'];
            echo json_encode($data);
        }
    }

//    更改主题状态
    public function update_theme(Request $request)
    {
        $theme = $request['theme'];
        $d_theme = ThemeModel::get($theme['theme_id']);
        $d_theme->status = $theme['status'];
        $d_theme->isUpdate(true)->save();
        $data = ['code' => 200, 'data' => '更改成功'];
        echo json_encode($data);
    }

//    删除主题
//    public function del_theme(Request $request)
//    {
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