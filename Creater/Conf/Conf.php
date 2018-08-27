<?php
/**
 * file  : Conf.php
 * author: sunlei01@zuoyebang.com
 * date  : 2018/08/26
 * brief : ODP配置文件
 */

const FRAME   = 'ODP';
const DB_NAME = 'information_schema';

switch (FRAME)
{
    case 'ODP' :
        $APP = 'assistantdesk';
        $odp = require_once(CONF_PATH."OdpConf".DS."Conf.php");
        break;
}

$conf = array(
    //pdo数据库配置文件
    'PDO' => [
        'DB_TYPE'    => 'mysql',           //数据库类型
        'DB_HOST'    => '127.0.0.1',       //服务器地址
        'DB_PORT'    => '3306',            //端口
        'DB_USER'    => 'sunlei',          //用户名
        'DB_PWD'     => '123456',          //密码
        'DB_NAME'    => DB_NAME,           //数据库名称
        'DB_CHARSET' => 'utf8',            //数据库编码
    ],

    //基础配置
    'FRAME' => FRAME,                      //框架

    //文件注释
    'NOTE' => [
        'AUTHOR' => 'sunlei01@zuoyebang.com',
    ],
    'ODP'  => $odp,
);

if (TOOL_ENV) {
    $conf['ODP']['DAO']['DOCUMENT_PATH']            = '../'.$APP.'/models/dao/';
    $conf['ODP']['DAO']['DB_NAME']                  = 'fudao/zyb_fudao';
    $conf['ODP']['DATASERVICE']['DOCUMENT_PATH']    = '../'.$APP.'/models/';
    $conf['ODP']['PAGESERVICE']['DOCUMENT_PATH']    = '../'.$APP.'/models/';
    $conf['ODP']['APP']['DOCUMENT_PATH']            = '../';
    $conf['ODP']['CONTROLLER']['DOCUMENT_PATH']     = '../'.$APP.'/';
    $conf['ODP']['ACTION']['DOCUMENT_PATH']         = '../'.$APP.'/';
    $conf['ODP']['ALL']['DOCUMENT_PATH']            = [
                                                            'ACTION'      => '../' . $APP . '/',
                                                            'CONTROLLER'  => '../' . $APP . '/',
                                                            'DAO'         => '../' . $APP . '/models/dao/',
                                                            'DATASERVICE' => '../' . $APP . '/models/service/data/',
                                                            'PAGESERVICE' => '../' . $APP . '/models/service/page/',
                                                      ];
}

return $conf;

