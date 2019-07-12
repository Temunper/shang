<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/11
 * Time: 8:45
 */

namespace app\customer\controller;

use think\Request;

class Index extends Base
{
    //渲染客户后台登录页
    public function index()
    {
        $this->is_login();
        return $this->fetch();
    }
}