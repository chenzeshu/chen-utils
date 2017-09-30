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
    protected $model;
    protected $classname;
    protected $begin;
    protected $page;
    protected $pageSize;

    /**
     * @param $page
     * @param $pageSize
     * 得到
     * $this->classname 类名
     * $this->begin 起始位置
     * $this->page
     * $this->pageSize
     */
    public function init($page, $pageSize)
    {
        $this->page = $page;
        $this->pageSize = $pageSize;
        //todo get 起始页面
        $this->begin = $this->getBegin();
        //todo get Classname
        $this->classname = $this->getClassname();
        //todo get Model
        $this->getModel();
    }

    private function getBegin(){
        $begin = ($this->page - 1) * $this->pageSize;
        return $begin;
    }

    private function getClassname(){
        $arr = explode('\\',get_class($this));
        $start = strpos($arr[count($arr)-1] ,'Controller' );
        $classname = substr_replace($arr[count($arr)-1], '', $start);
        return $classname;
    }

    /** *******************初始化结束**************************************/


    /**
     * @param $page 开始的页码, 不用减一
     * @param $pageSize 单页显示数据数目
     * @return mixed 分页数据 带total
     */
    public function getPaginator($page, $pageSize){
        $this->init($page, $pageSize);
        $model = new $this->model;

        $info = $model->offset($this->begin)->limit($pageSize)->get();
        $total = ceil($model->count() / $pageSize);
        $data = [
            'data' => $info,
            'total' => $total
        ];

        return $data;
    }

    /**
     * 返回模糊查询模型的分页数据
     * @param $page
     * @param $pageSize
     */
    public function getSearch($page, $pageSize, $needleName, $needle)
    {
        $this->init($page, $pageSize);
        $model = new $this->model;

        $info = $model->where('name', 'like', "%".$needle."%")->offset($this->begin)->limit($this->pageSize)->get();
        $total = ceil($model->where($needleName, 'like', "%".$needle."%")->count()/$this->pageSize);
        $data = [
            'data' => $info,
            'total' => $total
        ];
        return $data;
    }

    /**
     * @return mixed model的命名空间
     */
    private function getModel(){
        if($this->classname == 'User'){
            $myModel = "App\\User";
        }
        else {
            $filename = $this->classname. '.php';
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

        $this->model = $myModel;
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