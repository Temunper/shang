<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/11
 * Time: 10:40
 */

namespace app\front\controller;


use app\front\model\AdModel;
use think\Controller;
use think\Request;

class Ad extends Controller
{
    protected $ad_model = 'ad';

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->ad_model = new AdModel();
    }
    public function index(){
        return $this->fetch('/index');
    }
}