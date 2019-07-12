<?php
namespace app\customer\common;
class Csv
{
    /**
     * 导出CSV文件
     * @param array $table_head 表头
     * @param array $data 数据
     * @param string $filename 文件名
     * @return string
     */
    public function exportCsv($table_head, $data, $filename = "")
    {
        //设置超时
        set_time_limit(0);
        //设置内存占用
        ini_set('memory_limit', '512M');
        //为fputcsv()函数打开文件句柄
        $output = fopen('php://output', 'w') or die("can't open php://output");
        //设置文件名
        if ($filename != "") {
            $filename = $filename . "-" . date('YmdHis', time());
        } else {
            $filename = date('YmdHis', time());
        }
        //检查表头数据格式是否正确
        if (!is_array($table_head) || !is_array($data)) {
            exit("parameters error");
        }
        //告诉浏览器这个是一个csv文件
        header("Content-Type: application/csv");
        header("Content-Disposition: attachment; filename=$filename.csv");
        //输出表头
        foreach ($table_head as $k => $val) {
            // CSV的Excel支持GBK编码，一定要转换，否则乱码
            $table_head[$k] = iconv('utf-8', 'gbk', $table_head[$k]);
        }
        // 将数据通过fputcsv写到文件句柄
        fputcsv($output, $table_head);
        // 计数器
        $cnt = 0;
        // 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
        $limit = 100000;
        // 逐行取出数据，不浪费内存
        $count = count($data);
        // 逐行取出数据，不浪费内存
        for ($i = 0; $i < $count; $i++) {
            $cnt++;
            if ($limit == $cnt) { //刷新一下输出buffer，防止由于数据过多造成问题
                ob_flush();
                flush();
                $cnt = 0;
            }
            $row = $data[$i];
            foreach ($row as $k => $v) {
                $row[$k] = iconv('utf-8', 'gbk', $v);
            }
            fputcsv($output, $row);
        }
        //关闭文件句柄
        fclose($output) or die("can't close php://output");
    }

    /**
 * 导入CSV文件
 * @param string $filepath 文件路径
 * @return array
 */
    public function importCsv($filepath)
    {
        //检查文件后缀名
        if (pathinfo($filepath)["extension"] != "csv") {
            exit("请导入csv文件");
        }
        if (!$fp = fopen($filepath, 'r')) {
            exit("打开文件失败");
        }
        $out = [];
        $n = 0;
        while ($data = fgetcsv($fp)) {
            $num = count($data);
            for ($i = 0; $i < $num; $i++) {
                $out[$n][$i] = iconv('gbk', 'utf-8', $data[$i]);    //中文转码
            }
            $n++;
        }
        fclose($fp) or exit("can't close the file $filepath");
        return $out;
    }

}