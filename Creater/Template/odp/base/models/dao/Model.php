<?php

/**
 * @name    Dao_Models
 * @desc    model dao online db, 可以访问数据库，文件，其它系统等
 * @author  zhengzhiqing@zuoyebang.com
 */
class Dao_Model extends Hk_Common_BaseDao
{
    public function __construct() {
        $this->_dbName      = 'flipped/zyb_flipped';
        $this->_db          = Hk_Service_Db::getDB($this->_dbName);
        $this->_table       = "tblModelInfo";
        $this->arrFieldsMap = array(
            'modelId'        => 'modelId',
            'menuSort'       => 'menuSort',
            'menuTiTile'     => 'menuTitle',
            'menuUrl'        => 'menuUrl',
            'menuType'       => 'menuType',
            'menuPrivileges' => 'menuPrivileges',
        );

        $this->arrTypesMap = array(
            'modelId'        => Hk_Service_Db::TYPE_INT,
            'menuSort'       => Hk_Service_Db::TYPE_INT,
            'menuTiTile'     => Hk_Service_Db::TYPE_STR,
            'menuUrl'        => Hk_Service_Db::TYPE_STR,
            'menuType'       => Hk_Service_Db::TYPE_INT,
            'menuPrivileges' => Hk_Service_Db::TYPE_JSON,
        );
    }
}
