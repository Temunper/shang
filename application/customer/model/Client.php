<?php
/**
 * Created by PhpStorm.
 * User: vpanda
 * Date: 2019/7/16
 * Time: 10:25
 */

namespace app\customer\model;

use think\Db;
use think\Model;

class Client extends Model
{
    protected $table = 'client';

    //通过账号查询秘钥
    public function get_verify($user)
    {
        return Db::table('client')->field('verify')->where('user=?', [$user])->find();
    }

    public function check_login($map)
    {
        return Db::table('client')->where('user=? and pass=?', [$map['user'], $map['pass']])->find();
    }
}