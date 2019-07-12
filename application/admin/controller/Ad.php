<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/11
 * Time: 10:05
 */

namespace app\admin\controller;


use app\admin\model\AdModel;

class Ad
{
    public function __construct()
    {
        parent::__construct();
        config('before', 'beforeAction');
        $this->ad_model = new AdModel();
    }
//

}