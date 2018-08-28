# creator

#### 项目介绍
creator是一款为php框架odp的脚手架工具，主要用于生成dao层，dataService层，pageService层,controller层,action层,避免重复性劳动和提高工作效率

#### 软件架构
软件架构说明

#### 使用说明 
###### (ps:配置已支持当前项目组文件路径,放置在app同级目录即可)

1.创建conf
> ` php creator build conf -u xxx -p xxx -a xxx -t xxx`
> 
>  说明： ` 创建配置文件 -u 数据库用户名称 -p 数据库密码 -a author -t app名称 `

2.创建app
> ` php creator build app app_name –c namespace`   

> 栗 : ` 创建app结构，app_name项目名称 -c 命名空间 `  

3.创建dao
> ` php creator create dao dao_name -p [mod | mul] `
>
> ` 说明：dao文件名 -p 分表 mod 取模分表 mul 固定大小分表 （可不加p参数表示不分表）`

4.创建dataservice
> ` php creator create ds dataservice_name `
>
> ` 说明：创建ds层 dataservice_name 为class name`
> 
> 栗 : `php creator create ds Service_Data_Message` 

5.创建pageservice
> ` php creator create ps pageservice_name `
>
> ` 说明：创建ds层 pageservice_name 为class name`
> 
> 栗 : `php creator create ps Service_Page_Message`
 
6.创建controller
> ` php creator create controller c_name `
>
> ` 说明：创建controller文件 c_name为class name`
> 
> 栗 : `php creator create controller Controller_Message` 

7.创建action
> ` php creator create action a_name -n middle_name `
>
> ` 说明：创建action文件 a_name为class name -n 分表？`
> 
> 栗 : `php creator create action Action_MessageList -n message` 

8.创建all
> ` php creator create all name`
>
> ` 说明：创建所有文件`
> 
> 栗 : `php creator create all assistantDesk` 

#### 安装教程

1. 将creator文件夹放置于odp项目的根目录下
2. 配置连接数据库参数,根据注释设置数据库连接参数,注意不要随意更改DB_NAME

```
vim ./creator/Creator/Conf/Conf.php
$conf = array(
    //pdo数据库配置文件
    'PDO' => [
        'DB_TYPE'    => 'mysql',           //数据库类型
        'DB_HOST'    => '127.0.0.1',       //服务器地址
        'DB_PORT'    => '3306',            //端口
        'DB_USER'    => 'root',            //用户名
        'DB_PWD'     => '123456root',      //密码
        'DB_NAME'    => DB_NAME,           //数据库名称
        'DB_CHARSET' => 'utf8',            //数据库编码
    ],

    //基础配置
    'FRAME' => FRAME,              //框架

    //文件注释
    'NOTE' => [
        'AUTHOR' => 'chenzhiwen',  //文件头作者
    ],
);
```

    

3. 修改适合你的odp参数

 **可修改的部分（以dao为例）：** 
1. 分表操作参数 -p 
2. 生成文件路径
3. 默认继承的父类
4. 默认的DB_NAME
5. 默认的DB
6. 默认的日志文件存储
7. 默认的取模分表分母数
8. 默认的分表类型
9. 默认的JSON类型CLOUMN_COMMENT标识符

```
vim ./creator/Creator/Conf/OdpConf/Conf.php
return array(
    //odp的模板类型
    'TEMPLATES' => [
        'dao'           => 'dao.tmpl',
        'dataservice'   => 'dataservice.tmpl',
        'pageservice'   => 'pageservice.tmpl',
        'controller'    => 'controller.tmpl',
        'action'        => 'action.tmpl',
    ],
    'DB' => [
        'PREFIX' => 'tbl',
    ],
    //odp文件路径分割
    'DS' => '_',
    //dao层相关配置
    'DAO' => [
        'BASE_CONFIG'  => [
            //分表
            'partion'  => [
                'MUL' => '-pl',//固定大小分表
                'MOD' => '-pd',//取模分表
            ],
        ],
        'DOCUMENT_PATH' => '../desktc/models/',                      //基础路径
        'PARENT_CLASS'  => 'Hk_Common_BaseDao',                      //父类
        'DB_NAME'       => 'flipped/zyb_flipped',                    //DB_NAME
        'DB'            => 'Hk_Service_Db::getDB( $this->_dbName )', //DB
        'LOG_FILE'      => 'Hkzb_Util_FuDao::DBLOG_FUDAO',           //日志文件
        'TYPE_JSON'      => 'Hk_Service_Db::TYPE_JSON',              //JSON
        'TYPE_JSON_FLAG' => 'json',                                  //JSON标示符
        'TYPES_MAP'     => [
            'bigint'     => 'Hk_Service_Db::TYPE_INT',
            'blob'       => 'Hk_Service_Db::TYPE_INT',
            'char'       => 'Hk_Service_Db::TYPE_STR',
            'date'       => 'Hk_Service_Db::TYPE_STR',
            'datetime'   => 'Hk_Service_Db::TYPE_STR',
            'int'        => 'Hk_Service_Db::TYPE_INT',
            'longblob'   => 'Hk_Service_Db::TYPE_INT',
            'mediumblob' => 'Hk_Service_Db::TYPE_INT',
            'smallint'   => 'Hk_Service_Db::TYPE_INT',
            'text'       => 'Hk_Service_Db::TYPE_STR',
            'time'       => 'Hk_Service_Db::TYPE_STR',
            'timestamp'  => 'Hk_Service_Db::TYPE_STR',
            'tinyint'    => 'Hk_Service_Db::TYPE_INT',
            'varchar'    => 'Hk_Service_Db::TYPE_STR',
        ],
        'FILE_NAME_TEMP'=> [
            'Fz' => 'phplib',
        ],
        'PARTION'        => [
            //取模分表
            'MOD' => [
                'PARTION_NUM'   => '20',
                'PARTION_TYPE'  => 'self::TYPE_TABLE_PARTION_MOD',
            ],
            //固定大小分表
            'MUL' => [
                'PARTION_NUM'   => '3000',
                'PARTION_TYPE'  => 'self::TYPE_TABLE_PARTION_MUL',
            ],
        ],
    ],
    //dataservice层相关配置
    'DATASERVICE' => [
        'DOCUMENT_PATH'=> '../desktc/models/',//ROOT_PATH . 'Fz' . DS
        'PARENT_CLASS' => '',   //父类
    ],
    //pageservice层相关配置
    'PAGESERVICE' => [
        'DOCUMENT_PATH'=> '../desktc/models/',//ROOT_PATH . 'Fz' . DS
        'PARENT_CLASS' => '',   //父类
    ],
    //构建模块
    'MODULE' => [
        'DOCUMENT_PATH' => '../',
        'BASE_CONFIG'   => [
            'NAMESPACE' => '-c'
        ],
    ],
    //all
    'ALL' => [
        'DOCUMENT_PATH' => [
            'ACTION'      => '../desktc/',
            'CONTROLLER'  => '../desktc/',
            'DAO'         => '../desktc/models/dao/',       //../phplib/dao/
            'DATASERVICE' => '../desktc/models/service/data/',  //../phplib/ds/
            'PAGESERVICE' => '../desktc/models/service/page/',
        ]
    ],
    //controller
    'CONTROLLER' => [
        'DOCUMENT_PATH' => '../desktc/',
        'PARENT_CLASS'  => 'Ap_Controller_Abstract',   //父类
    ],
    //action
    'ACTION' => [
        'DOCUMENT_PATH' => '../desktc/',
        'PARENT_CLASS'  => 'DeskTc_Action_Base',   //父类
        'BASE_CONFIG'  => [
            'MIDDLE_NAME' => '-n',
        ],
    ],

);

```

#### 参与贡献

1. Fork 本项目
2. 新建 Feat_xxx 分支
3. 提交代码
4. 新建 Pull Request
