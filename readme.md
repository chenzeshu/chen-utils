# Chen-utils
  
## traits
    1.use for time-style
    2.use for array
    3.use for curl
    4.use for value-check
    5.use for json-return-format
    6.use for auto_api_age
## facades
    1. ChenUtilFacade
       - getWechatOpenid & session-key
       - getAccess_token
    expect to do :
       - apis for wechatPay
   
## 各个traits的用法
### 1. PageTrait
用途: 在不同的**控制器**中, 通过调用一个方法直接得到适用于api的分页数据

```
use PageTrait;

/**
* $page int 起始页码, 无需减一
* $pageSize int, 每页展示的数据量
*/
$this->getPaginator($page, $pageSize);
```
注意: 本Trait默认`User.php`在`app/`目录下

1. 使用了递归, 可以下探Models文件夹下所有模型文件

2. wating to do:
~~问题1: 目前就做了三层, 从app目录下, 到app/models目录下, 再到下一级
暂时没有递归文件夹的思路~~

    问题2: 如分类连表查询等还没有做
