<?php
/**
 * Created by PhpStorm.
 * User: edwinChan
 * Date: 2017/9/9
 * Time: 16:08
 */
namespace Creater\Helper;
//声明命名空间
//namespace Frame\Vendor;
//引入根空间下的PDO
use \PDO;
use \PDOException;
/**
 * 封装PDO
 * Class PDOWrapper
 * @package Frame\Vendor
 */
class PDOWrapper
{
    //数据库配置属性
    private $db_type;
    private $db_host;
    private $db_port;
    private $db_user;
    private $db_pwd;
    private $db_name;
    private $charset;

    //保存PDO对象的属性
    private $pdo = NULL;


    /**
     * 构造方法
     * PDOWrapper constructor.
     */
    public function __construct()
    {
        $this->db_type = $GLOBALS['config']['PDO']['DB_TYPE'];
        $this->db_host = $GLOBALS['config']['PDO']['DB_HOST'];
        $this->db_port = $GLOBALS['config']['PDO']['DB_PORT'];
        $this->db_user = $GLOBALS['config']['PDO']['DB_USER'];
        $this->db_pwd  = $GLOBALS['config']['PDO']['DB_PWD'];
        $this->db_name = $GLOBALS['config']['PDO']['DB_NAME'];
        $this->charset = $GLOBALS['config']['PDO']['DB_CHARSET'];

        //创建PDO对象,连接数据库,选择数据库
        $this->connectDb();

        //设置字符集
        $this->setCharset();

        //设置PDO错误模式
        $this->setErrMode();
    }

    /**
     * 创建PDO对象
     */
    private function connectDb()
    {
        try
        {
            $dsn = "$this->db_type:host=$this->db_host;port=$this->db_port;dbname=$this->db_name;charset=$this->charset";
            $this->pdo = new PDO($dsn,$this->db_user,$this->db_pwd);
        }
        catch(PDOException $e)
        {
            echo "<h2></h2>";
            echo "错误状态码:".$e->getCode();
            echo "<br>错误行号".$e->getLine();
            echo "<br>错误文件".$e->getFile();
            echo "<br>错误信息".$e->getMessage();
            exit();
        }
    }

    /**
     * 设置PDO错误模式为异常模式
     */
    private function setErrMode()
    {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }

    private function setCharset()
    {
        $sql = 'set names utf8';
        $this->pdo->query($sql);
    }


    /**
     * 公共的执行SQL语句的方法:insert .delete update set
     * @param $sql
     * @return mixed
     */
    public function exec($sql)
    {
        try{
            return$this->pdo->exec($sql);
        }
        catch(PDOException $e)
        {
            $this->showError($e);
        }

    }

    /**
     * 获取一条数据
     * @param $sql
     * @return mixed
     */
    public function fetchOne($sql)
    {
        try{
            //执行SQL语句,并返回结果集对象
            $PDOStatement =  $this->pdo->query($sql);
            //从结果集对象取出一条数据并返回
            return $PDOStatement->fetch(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e)
        {
            $this->showError($e);
        }
    }

    /**
     * 获取多行数据(二维数组)
     * @param $sql
     * @return mixed
     */
    public function fetchAll($sql)
    {
        try{
            //执行SQL语句,并返回结果集对象
            $PDOStatement =  $this->pdo->query($sql);
            //从结果集对象取出一条数据并返回
            return $PDOStatement->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e)
        {
            $this->showError($e);
        }
    }

    /**
     * 返回条数
     * @param $sql
     * @return mixed
     */
    public function rowCount($sql)
    {
        try{
            //执行SQL语句,并返回结果集对象
            $PDOStatement =  $this->pdo->query($sql);
            //从结果集对象取出一条数据并返回
            return $PDOStatement->rowCount();
        }
        catch(PDOException $e)
        {
            $this->showError($e);
        }
    }

    /**
     * 显示sql语句的错误信息
     * @param $e
     */
    private function showError($e)
    {
        $str = "<h2>SQL语句错误</h2>";
        $str .= "错误状态码:".$e->getCode();
        $str .= "<br>错误行号".$e->getLine();
        $str .= "<br>错误文件".$e->getFile();
        $str .= "<br>错误信息".$e->getMessage();
        echo $str;
    }
}


