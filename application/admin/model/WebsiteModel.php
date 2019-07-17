<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/11
 * Time: 9:45
 */

namespace app\admin\model;


use think\Model;

class WebsiteModel extends Model
{

    protected $table = 'website';
    protected $resultSetType = 'collection';
//   关联主题
    public function theme()
    {
        return $this->hasOne('ThemeModel');
    }
}