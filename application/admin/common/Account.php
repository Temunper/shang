<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/20
 * Time: 17:07
 */

namespace app\admin\common;


use Overtrue\Pinyin\Pinyin;

class Account
{
    public static function create($name)
    {
//        dump(chr(48,99));
//        dump(rand(chr(49),chr(122)));
        $pass = null;
        $user = null;
        $verify = null;
        $password = null;
        for ($i = 0; $i < 21; $i++) {
            switch (rand(0, 2)) {
                case 0:
                    $pass .= chr(rand(48, 57));
                    break;
                case 1:
                    $pass .= chr(rand(64, 90));
                    break;
                case 2:
                    $pass .= chr(rand(97, 122));
                    break;
            }
        }
        $pinyin = new pinyin();

        $password = substr($pass, 0, 12);
        $verify = substr($pass, 12, 18);
        $num = substr($pass, 18, 21);
        $pass = md5($pass);
        $user = $pinyin->convert($name);
        $user = implode('', $user);
        if (!$user) $user = $name . $num;
        else $user .= $num;
        $client = ['client' => ['name' => $name, 'user' => $user, 'verify' => $verify, 'pass' => $pass], 'password' => $password];
        return $client;
    }

}