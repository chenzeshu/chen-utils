<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/26
 * Time: 15:46
 */

namespace Chenzeshu\ChenUtils\Traits;


trait PageTrait
{
    /**
     * @param $page 开始的页码, 不用减一
     * @param $pageSize 单页显示数据数目
     * @return mixed
     */
    public function getPaginator($page, $pageSize){
        $begin = ($page - 1) * $pageSize;

        $arr = explode('\\',get_class($this));
        $start = strpos($arr[count($arr)-1] ,'Controller' );
        $classname = substr_replace($arr[count($arr)-1], '', $start);

        return $this->matchFile($classname, $begin, $pageSize);
    }

    /**
     * @param $classname 我们想要找的文件(无.php, 要加)
     * @param $begin 开始的数据条数
     * @param $pageSize 单页显示数据数目
     * @return mixed 一般返回的是分页API数据
     */
    public function matchFile($classname, $begin, $pageSize){
        if($classname == 'User'){
            $myModel = "App\\User";
        }
        else {
            $filename = $classname. '.php';
            $modeldir = dirname(app_path('/models'))."/models";
            //todo 首先得到app目录下的文件目录结构数组
            $absolutePaths = $this->getDir($modeldir);

            foreach ($absolutePaths as $k =>$path){
                $absolutePaths[$k] = str_replace($modeldir,'',$path);
                //todo 得到.php文件并与filename比对
                $arr = explode('/',$absolutePaths[$k]);
                if($arr[count($arr)-1] == $filename){
                    $myModel = str_replace('/', '', "App\\Models\\".str_replace('.php', '', $absolutePaths[$k]));
                }
            }
        }

        $data = (new $myModel)->offset($begin)->limit($pageSize)->get();
        return $data;
    }

    /**
     * 可以查到目录下所有文件的绝对路径, 形成数组返回
     * @param $path 要查文件的目录路径(绝对路径)
     * @param $data 一个空数组
     * 注意: 这里在$data前加引用, 主要是引用了getDir()的$data, 保证了数据是数组格式
     */
    public function searchDir($path,&$data){
        if(is_dir($path)){
            $dp=dir($path);
            while($file=$dp->read()){
                if($file!='.'&& $file!='..'){
                    $this->searchDir($path.'/'.$file,$data);
                }
            }
            $dp->close();
        }
        if(is_file($path)){
            $data[]=$path;
        }
    }

    /**
     * 简单的调用
     * @param $dir  要查的目录路径
     * @return array
     */
    public function getDir($dir){
        $data=array();
        $this->searchDir($dir,$data);
        return $data;
    }
}