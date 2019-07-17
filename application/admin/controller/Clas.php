<?php
/**
 * Created by PhpStorm.
 * User: TEM
 * Date: 2019/7/11
 * Time: 11:16
 */

namespace app\admin\controller;


use app\admin\model\ClasModel;
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
//    分类页
    public function clas()
    {
        return $this->fetch();
    }

//    得到所有的分类
    public function get_all_clas()
    {
        $result = $this->clas_model->get_all_clas();
        $result = $this->set_tree($result);
        return $result;

    }

//    查询分类
    public function get_class_by_id($class_id)
    {
        $result = $this->clas_model->get_class_by_id($class_id);
        if (empty($result['f_class_id'])) {
            $result = ['f_class_id' => $result['class_id'],
                'f_class_name' => $result['class_name'],
                'class_id' => '',
                'class_name' => ''
            ];
        }
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

//    添加分类
    public function add_clas()
    {
        $params = Request::instance()->post();
        if ($params['f_class_id'] != 0) {
            $result = $this->clas_model->get_class_by_id($params['f_class_id']);
        } else $result = true;
        if ($result) {
            $result = $this->clas_model->add_clas($params);
            if ($result) {
                $data = ['code' => 200, 'data' => "添加成功"];
                echo json_encode($data);
            } else {
                $data = ['code' => 202, 'data' => "添加失败"];
                echo json_encode($data);
            }
        } else {
            $data = ['code' => 202, 'data' => "一级分类不存在"];
            echo json_encode($data);
        }
    }

//    删除分类
    public function del_clas()
    {
        $params = Request::instance()->post();
        $d_clas = $this->get_all_clas();
        $class_id = [];
        $class_id[] = (int)$params['class_id'];
        foreach ($d_clas as $value) {
            if ($value['class_id'] == $params['class_id']) {
                foreach ($value['son'] as $item) {
                    $class_id[] = $item['class_id'];
                }
            }
        }
        $result = $this->clas_model->del_clas($class_id,$params['status']);
        if ($result) {
            $data = ['code' => 200, 'msg' => "修改成功"];
            echo json_encode($data);
        } else {
            $data = ['code' => 202, 'msg' => "修改失败"];
            echo json_encode($data);
        }
    }


//    修改分类
    public function update_clas()
    {
        $params = Request::instance()->post();
        if ($params['f_class_id'] != 0) {
            $result = $this->clas_model->get_class_by_id($params['f_class_id']);
        } else $result = true;
        if ($result) {
            $result = $this->clas_model->update_clas($params);
            if ($result) {
                $data = ['code' => 200, 'data' => "修改成功"];
                echo json_encode($data);
            } else {
                $data = ['code' => 202, 'data' => "修改失败"];
                echo json_encode($data);
            }
        } else {
            $data = ['code' => 202, 'data' => "一级分类不存在"];
            echo json_encode($data);
        }
    }
}