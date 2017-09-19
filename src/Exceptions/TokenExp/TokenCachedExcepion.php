<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/19
 * Time: 8:27
 */

namespace Chenzeshu\ChenUtils\Exceptions\TokenExp;


use App\Exceptions\BaseException;

class TokenCachedExcepion extends BaseException
{
    // 错误具体信息
    public $msg = "Token缓存错误";
    // 自定义的错误码
    public $code = -9007; //通用类型错误号10000
}