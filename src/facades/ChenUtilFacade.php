<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/18
 * Time: 16:55
 */

namespace Chenzeshu\ChenUtils\Facades;

use Illuminate\Support\Facades\Facade;

class ChenUtilFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'chenwechat';
    }
}