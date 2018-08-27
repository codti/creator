<?php
/**
 * Created by PhpStorm.
 * User: edwinchan
 * Date: 2018/7/15
 * Time: 下午4:09
 */
namespace Creater\App;

use Creater\Helper\PDOWrapper;

trait TableCreate
{
    /**
     * PDO
     * @var PDOWrapper
     */
    private $_DBHelper;

    /**
     * 表名
     * @var
     */
    private $_TableName;


    public function DBConstruct()
    {
        $this->_DBHelper  = new PDOWrapper();
    }

    /**
     * 设置表名
     * @param $dbName
     */
    public function setTableName($dbName)
    {
        $this->_TableName = $dbName;
    }

    /**
     * 获取数据
     * @return mixed
     */
    public function getColumnList()
    {
        //获取数据库
        $sql = "select * from COLUMNS where TABLE_NAME = '{$this->_TableName}' order by ORDINAL_POSITION";
        return $this->_DBHelper->fetchAll($sql);
    }
}