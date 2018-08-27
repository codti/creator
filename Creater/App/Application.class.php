<?php
/**
 * Created by PhpStorm.
 * User: edwinChan
 * Date: 2017/9/9
 * Time: 11:05
 */
namespace Creater\App;

use Creater\Helper\CommonHelper;

/**
 * 框架初始类
 * Class Frame
 */
class Application
{
    /**
     * 执行动作
     * @var
     */
    static $_make;

    /**
     * 动作
     * @var
     */
    static $_action;

    /**
     * 名称
     * @var
     */
    static $_name;

    /**
     * 配置
     * @var
     */
    static $_config;

    /**
     * 参数
     * @var array
     */
    static $params = [
        //基础文件名
        'base_name' => '',
        //基础配置
        'base_config' => '',
        //生成目标文件的路径
        'path' => '',
        //生成目标文件的相对文件名
        'file_name' => '',
        //数据库名称
        'db_name' => '',
    ];

    /**
     * 参数验证
     * @var array
     */
    static $checkParams = [
        1 => [
            'create',
            'build',
        ],
        2 => [
            'app',
            'action',
            'all',
            'dao',
            'controller',
            'dataservice',
            'pageservice',
            'ds',
            'ps',
            'conf',
        ],
    ];

    /**
     * 参数转换
     * @var array
     */
    static $paramsMap = [
        'ds' => 'dataservice',
        'ps' => 'pageservice',
    ];

    /**
     * 使用table的action
     * @var array
     */
    static $useTableAction = [
        'dao',
        'ds',
        'dataservice',
    ];

    /**
     * 初始化方法
     */
    public static function run($argv)
    {
        self::initConfig();
        self::initConst();
        self::initAutoLoad();
        self::initParam($argv);
        self::initDispatch();
    }

    /**
     * 初始化配置
     */
    private static function initConfig()
    {
        $GLOBALS['config'] = require_once(CONF_PATH . "Conf.php");
    }

    /**
     * 初始化部分常量
     */
    private static function initConst()
    {
        define("TMPL_PATH",ROOT_PATH ."Creater".DS."Template".DS.strtolower($GLOBALS['config']['FRAME'] . DS));//模板路径
    }

    /**
     * 类的自动加载
     */
    private static function initAutoLoad()
    {
        spl_autoload_register(function ($className)
        {
            //将空间中的类名,转成真实的类文件路径
            //空间中的类名 Creater\App\Odp\CreateDao
            //真是的类文件 Creater\App\Odp\CreateDao.class.php
            $filename = ROOT_PATH.str_replace("\\",DS,$className).".class.php";
            //如果类文件存在,则包含
            if (file_exists($filename)) require_once($filename);
        });
    }

    /**
     * 初始化参数
     * @param $argv
     */
    private static function initParam($argv)
    {
        //校验参数
        if (!in_array($argv[1],self::$checkParams[1]) || !in_array($argv[2],self::$checkParams[2]) || empty($argv[3])) {
            echo 'PARAM ERROR!'.PHP_EOL;
            exit();
        }

        //参数转换 ds ps
        $argv[2] = array_key_exists($argv[2],self::$paramsMap) ? self::$paramsMap[$argv[2]] : $argv[2];

        self::$_make    = $argv[1]; //create
        self::$_action  = $argv[2];
        self::$_name    = $argv[3];
        $num = self::$_action == 'conf' ? 3 : 4;
        self::$_config  = array_slice($argv,$num);

        //配置名称
        $arr = array(
            'all',
            'conf',
        );
        if (!in_array(strtolower(self::$_action),$arr)) {
            $configName     = $GLOBALS['config'][strtoupper($GLOBALS['config']['FRAME'])][strtoupper(self::$_action)]['DOCUMENT_PATH'] . self::$_name;
        }else{
            $configName = '';
        }
        //初始化参数
        self::$params['base_name']   = CommonHelper::convertUnderline(self::$_name,true,true);
        self::$params['base_config'] = self::$_config;


        $DS = $GLOBALS['config']['ODP']['DS'];
        $name = !strrchr(self::$_name, $DS) ? self::$_name : trim(strrchr(self::$_name, $DS),$DS);

        self::$params['path'] = !strrpos($configName, $DS) ? dirname($configName) : strtolower(str_replace($DS, DS,substr($configName,0,strrpos($configName, $DS))));
        self::$params['file_name'] =  ucfirst($name) . '.php';

        //用到数据库的
        if (in_array(self::$_action,self::$useTableAction)) {
            self::$params['db_name'] = $GLOBALS['config'][strtoupper($GLOBALS['config']['FRAME'])]['DB']['PREFIX'] . ucfirst($name);
        }

    }

    /**
     * 请求分发
     * 创建哪个控制器类的对象?
     * 调用控制器对象的哪个方法
     */
    private static function initDispatch()
    {
        //分发规则
        //make dao Fz_Dao_Unit
        $className = "\\".__NAMESPACE__."\\".ucfirst(strtolower($GLOBALS['config']['FRAME']))."\\".ucfirst(self::$_make).ucfirst(self::$_action);
        $obj       = new $className(self::$params);
        $action    = self::$_make;
        $obj->$action();
    }

}