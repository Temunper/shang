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


}