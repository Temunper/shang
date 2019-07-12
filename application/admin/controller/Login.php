<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/11
 * Time: 8:37
 */

namespace app\admin\controller;


use app\admin\model\AdminModel;
use think\Controller;
use think\Model;
use think\Request;

class Login extends Controller
{
    protected $admin_model = null;

//实例化模型对象
    public function __construct()
    {
        parent::__construct();
        config('before', 'beforeAction');
        $this->admin_model = new AdminModel();
    }

//后台登陆页
    public function admin_login_page()
    {
        $this->fetch('');
    }

//登陆
    public function admin_login()
    {
        $params = Request::instance()->post();
        $result = $this->admin_model->admin_login($params['user']);
        if (!empty($result)) {
            if (md5($params['pass']) . $result['verify'] == $result['pass']) {
                if ($result['status'] == 1) {
                    $_SESSION['user'] = ['admin_name' => $result['name'], 'admin_user' => $result['user']];
                    return $this->fetch('test/text');
                } else {
                    $this->error('账户已删除');
                }
            } else {
                $this->error('密码错误');
            }
        } else {
            $this->error('用户名不存在');
        }
    }
}