<?php
/**
 * Created by PhpStorm.
 * User: TEM
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

    public function add_pro()
    {
        return $this->fetch('/addItem');
    }

    public function get_msg()
    {
        return $this->fetch('/seeMsg');
    }
}