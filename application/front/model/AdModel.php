<?php
/**
 * Created by PhpStorm.
 * Clientw: TEM
 * Date: 2019/7/11
 * Time: 10:40
 */

namespace app\front\model;


use think\Db;
use think\Model;

class AdModel extends Model
{
    public $table = 'ad';
    protected $autoWriteTimestamp = false;
    protected $resultSetType = 'collection';             //使对象可以直接转换为字符串数组
}