<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/11
 * Time: 8:35
 */

namespace app\admin\controller;


use think\Controller;
use think\Session;

class Base extends Controller
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        //未登录跳转回登陆页面；
       $admin_name = Session::get('admin');
//        print_r($user_name. '11');die;
        if (empty($admin_name)) {
            $this->error("请登陆！", 'Login/login');
        }
    }
    //    文件上传

    /**
     * @param $file  上传的文件
     * @param $domain  域名
     * @param $pack    文件夹名
     * @return mixed|null
     */

}