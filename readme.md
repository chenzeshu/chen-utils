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
用法:
1. 建议创建一个`ApiController`, 继承`Controller`, 然后将其他`Restful Controller`
继承本Controller;
2. `ApiController`中`use PageTrait;`

### API:
1. getPaginator($page, $pageSize)
2. getSearch($page, $pageSize, $needleName, $needle)

参数|意义
----|----
`$page` | 页码
`$pageSize` | 每页数量
`$needleName` | 模糊搜索的字段名
`$needle` | 模糊搜索的内容

配合`ReturnTrait`返回数据格式:
```
code:$code,
msg:$msg,
data:[
    'data'=>$data,
    'total'=>$total //页码总数
]
```

注意: 本Trait默认`User.php`在`app/`目录下
1. 使用了递归, 可以下探Models文件夹下所有模型文件
2. wating to do:
~~问题1: 目前就做了三层, 从app目录下, 到app/models目录下, 再到下一级
暂时没有递归文件夹的思路~~

    问题2: 如分类连表查询等还没有做
