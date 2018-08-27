<?php
/**
 * Created by PhpStorm.
 * User: edwinchan
 * Date: 2018/7/12
 * Time: 下午11:40
 */
namespace Creater\App;


abstract class CreateBase
{
    /**
     * 配置
     * @var
     */
    protected $_Config;

    /**
     * 模板内容
     * @var string
     */
    protected $content = '';

    /**
     * 参数
     * @var array
     */
    protected $params = [
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

    protected $note = [
        'FILE' => '',
        'AUTHOR' => '',
        'DATE'   => '',
    ];


    public function __construct($params)
    {
        $this->setParams($params);
        $this->setNote();
    }


    /**
     * 设置参数
     * @param array $params
     */
    public function setParams(array $params = [])
    {
        $this->params = $params;
    }


    /**
     * 设置文件头注释
     */
    public function setNote()
    {
        $this->note['FILE'] = $this->params['file_name'];
        $this->note['AUTHOR'] = $GLOBALS['config']['NOTE']['AUTHOR'];
        $this->note['DATE'] = date('Y/m/d',time());
    }

    /**
     * 创建
     */
    abstract public function create();

}