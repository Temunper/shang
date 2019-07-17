<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/17
 * Time: 10:22
 */

namespace app\admin\controller;


use app\admin\model\ClientModel;
use think\Request;

class Client extends Base
{
    protected $client_model = null;

    public function __construct(Request $request = null)
    {
        $this->client_model = new ClientModel();
        parent::__construct($request);
    }

//    查找客户
    public function get_client_by_id($id)
    {
        $data = $this->client_model->get_client_by_id($id);
        return $data;
    }
//    得到所有用户名
      public function get_all_client(){
        $result = $this->client_model->get_all_client();
        return $result;
      }

}