<?php
/**
 * Created by PhpStorm.
 * userw: TEM
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
        $captcha = new Captcha();     //实例化验证码对象
        if (!$captcha->check($params['code'])) {        //判断验证码是否正确
            $this->error('验证码错误');
        }
        $result = $this->admin_model->get_admin($params['user']);           //获取密钥
        if (!empty($result)) {
            $result = $this->admin_model->login($params['user'], md5($params['pass'] . $result['verify']));          //根据密钥和用户名账号查询
            if ($result) {
                if ($result['status'] == 1) {
                    //数据保存到session对象中
                    Session::set('admin', ['admin_name' => $result['name'], 'admin_user' => $result['user'], 'admin_id' => $result['id']]);
                    //重定向到首页
                    $this->redirect('Index/index');
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
        Session::destroy();                 //销毁session对象所有数据
        $this->redirect('Login/login');//重定向到登陆页
    }
}