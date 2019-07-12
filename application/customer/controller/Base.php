<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/11
 * Time: 8:47
 */

namespace app\customer\controller;

use think\Controller;
use think\Session;

class Base extends Controller
{
    protected $status = 0; //默认登录失败;
    protected $result = '';

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        define('CLIENT_ID', Session::get('client_id'));
    }

    public function return_info($stauts, $result)
    {
        $this->status = $stauts;
        $this->result = $result;
        return json(['status' => $this->status, 'message' => $this->result]);
    }

    protected function is_login()
    {
        if (empty(CLIENT_ID)) {
            $this->error('用户未登录，无权访问', url('user/login'));
        }
    }

    protected function already_login()
    {
        if (!empty(CLIENT_ID)) {
            $this->error('用户已登录，请勿重复登录', url('index/index'));
        }
    }


}