<?php
/**
 * Created by PhpStorm.
 * Clientw: vpanda
 * Date: 2019/7/11
 * Time: 11:55
 */

namespace app\customer\validate;

use think\Exception;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck($data = '')
    {
        //实例化请求对象
        $requestObj = Request::instance();
        empty($data) && $data = $requestObj->param();
        if ($this->check($data)) {
            return true;
        } else {
            $error = $this->getError();
            throw  new Exception($error);
        }
    }
}