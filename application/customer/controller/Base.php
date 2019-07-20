<?php
/**
 * Created by PhpStorm.
 * Clientw: vpanda
 * Date: 2019/7/11
 * Time: 8:47
 */

namespace app\customer\controller;

use think\Controller;
use think\Session;

class Base extends Controller
{

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
            define('CLIENT_ID', Session::get('client_id'));
    }

    protected function is_login()
    {
        if (empty(CLIENT_ID)) {
            $this->error('用户未登录，无权访问', url('client/login'));
        }
    }

    protected function already_login()
    {
        if (!empty(CLIENT_ID)) {
            $this->error('用户已登录，请勿重复登录', url('index/index'));
        }
    }


}