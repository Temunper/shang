<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/11
 * Time: 9:44
 */

namespace app\admin\controller;




use app\admin\model\ThemeModel;

class Theme extends Base
{
    public function __construct()
    {
        parent::__construct();
        config('before', 'beforeAction');
        $this->Theme_model = new ThemeModel();
    }



}