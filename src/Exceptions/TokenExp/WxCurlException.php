<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/19
 * Time: 8:28
 */

namespace Chenzeshu\ChenUtils\Exceptions\TokenExp;


use App\Exceptions\BaseException;

class WxCurlException extends BaseException
{
    // 错误具体信息
    public $msg = "Curl获取错误";
    // 自定义的错误码
    public $code = -9006; //通用类型错误号10000
}