<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/11
 * Time: 8:37
 */

namespace app\admin\controller;


use app\admin\model\AdminModel;
use think\captcha\Captcha;
use think\Controller;
use think\Model;
use think\Request;
use think\Session;

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
    public function login()
    {
        return $this->fetch('login/login');
    }

//登陆
    public function admin_login()
    {

        $params = Request::instance()->post();
        $captcha = new Captcha();
        if (!$captcha->check($params['code'])) {
            $this->error('验证码错误');
        }
        $result = $this->admin_model->get_admin($params['user']);

        if (!empty($result)) {
            if (md5($params['pass'] . $result['verify']) == $result['pass']) {
                if ($result['status'] == 1) {
                    Session::set('admin', ['admin_name' => $result['name'], 'admin_user' => $result['user'], 'admin_id' => $result['id']]);

                    return $this->redirect('Index/index');
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

//    登出
    public function admin_logout()
    {
        session_start();
        Session::destroy();
        return $this->redirect('Login/login');
    }
}