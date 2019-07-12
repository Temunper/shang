<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/11
 * Time: 11:16
 */

namespace app\front\model;


use think\Db;
use think\Model;

class ClasModel extends Model
{
    protected $table = 'class';
    function get_all_clas(){
        $result = Db::table($this->table)->order('sort')->select();
        return $result;
    }
}