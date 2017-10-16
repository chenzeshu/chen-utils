<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/15
 * Time: 15:42
 */

namespace Chenzeshu\ChenUtils\Traits;


trait ReturnTrait
{
    protected function res($code, $msg, $data = []){
        return response()->json([
            'code'=>$code,
            'msg'=>$msg,
            'data'=>$data
        ]);
    }
}