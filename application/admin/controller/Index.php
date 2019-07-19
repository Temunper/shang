<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/13
 * Time: 15:05
 */

namespace app\admin\controller;


class Index extends Base
{

    public function index()
    {
        return $this->fetch('index/index');
    }

    public function index2()
    {
        return $this->fetch('index/index2');
    }

    public function get_msg()
    {
        return $this->fetch('/seeMsg');
    }
}