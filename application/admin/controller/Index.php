<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/13
 * Time: 15:05
 */

namespace app\admin\controller;


use app\admin\common\Account;

class Index extends Base
{

    public function index()
    {
        return $this->fetch('index/index');         //首页
    }

    public function index2()
    {
        echo md5("9I8UaEKpy8Sg". "@8aj8E");
        return $this->fetch('index/index2');
    }

    public function test()
    {
        $name = 'min';
        Account::create($name);
    }
}