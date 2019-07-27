<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/26
 * Time: 19:58
 */

namespace app\front\controller;


use think\Controller;
use think\Request;

class Base extends Controller
{

    public function __construct(Request $request = null)
    {
        parent::__construct($request);

    }

    public function select_money()
    {
        $params = $this->request->param();
        isset($params['s_money']) ? $s_money = $params['s_money'] : $s_money = '';
        $this->assign('s_money', $s_money);

    }
}