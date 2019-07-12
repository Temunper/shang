<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/11
 * Time: 11:16
 */

namespace app\front\controller;


use app\front\model\ClasModel;
use think\Controller;
use think\Request;

class Clas extends Controller
{
    protected $clas_model = null;

    public function __construct(Request $request = null)
    {
        $this->clas_model = new ClasModel();
        parent::__construct($request);
    }
//    查询所有分类

    /**
     * @return null
     */
    public function get_all_clas()
    {
        $result = $this->clas_model->get_all_clas();
        $result = $this->set_tree($result);
        return $result;

    }

//    将数组变为生成树,仅限两层
    public function set_tree($data)
    {
        foreach ($data as $all_list) {
            $temp[$all_list['class_id']] = $all_list;     //以id为索引
        }
        foreach ($temp as $all_list) {
            $pid = $all_list['f_class_id'];
            $son_id = $all_list['class_id'];
            if (isset($temp[$pid])) {
                $temp[$pid]['son'][] = &$temp[$son_id];      //前后共用一个地址。即将加入到当前父级的二级数组中
            } else {
                $temp[$son_id]['son'] = null;
                $tree[] = &$temp[$son_id];               //数组引用没有父级的条目的地址。
            }
        }
        return $tree;
    }
}