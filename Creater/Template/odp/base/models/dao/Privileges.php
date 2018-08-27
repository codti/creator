<?php

/**
 * @name    Dao_Privileges
 * @desc    model dao online db, 可以访问数据库，文件，其它系统等
 * @author  zhengzhiqing@zuoyebang.com
 */
class Dao_Privileges extends Hk_Common_BaseDao
{
    public function __construct() {
        $this->_dbName    = 'flipped/zyb_flipped';
        $this->_db        = Hk_Service_Db::getDB($this->_dbName);
        $this->_table     = "tblAdminInfo";
        $this->_tableName = "tblAdminInfo";
    }

    /*
     * @brief   get model list
     * @param   arrInput array
     * @return  array
     */
    public function getPrivileges($rbacIds) {
        if (empty ($this->_db)) {
            $this->_db = self::getDB($this->_dbName);
        }

        $ret = array();
        if (is_array($rbacIds)) {
            $sql = "select id, rbacId, title, rPrivileges from tblAdminGroup where rbacId in (" . implode(",", $rbacIds) . ") order by rbacId";
            $ret = $this->_db->query($sql);
        }

        return $ret;
    }

    public function editAdminGroup($arrConds, $arrFields) {
        $arrConds = self::getConds($arrConds);
        $res      = $this->_db->update('tblAdminGroup', $arrFields, $arrConds);

        return (is_int($res)) ? 1 : 0;
    }

    public function addAdminGroup($arrFields) {
        $res = $this->_db->insert('tblAdminGroup', $arrFields);

        return (is_int($res)) ? 1 : 0;
    }

    /*
     * @brief   get user info
     * @param   arrInput array
     * @return  array
     */
    public function getAdminInfo($uid) {
        if (empty ($this->_db)) {
            $this->_db = self::getDB($this->_dbName);
        }

        $sql = "select id, uid, uname, uRole, nickName, uMail, rbacId, ext, gradeId, courseId from tblAdminInfo where uid = " . $uid . " and deleted = 0";
        $ret = $this->_db->query($sql);

        return ($ret) ? $ret[0] : array();
    }

    /*
     * @brief   get user info
     * @param   arrInput array
     * @return  array
     */
    public function getAdminByMail($mail) {
        if (empty ($this->_db)) {
            $this->_db = self::getDB($this->_dbName);
        }

        $sql = "select id, uid, uname, uRole, phone, nickName, uMail, rbacId, ext, gradeId, courseId from tblAdminInfo where uMail = '" . $mail . "' and deleted = 0";
        $ret = $this->_db->query($sql);

        return ($ret) ? $ret[0] : array();
    }

    /*
     * @brief   get model list
     * @param   arrInput array
     * @return  array
     */
    public function getModelList($arrInput) {
        if (empty ($this->_db)) {
            $this->_db = self::getDB($this->_dbName);
        }
        $str = '';
        if ($arrInput['rbacId']) {
            $str = (implode(",", $arrInput['rbacId']) == "all" || in_array("all", $arrInput['rbacId'])) ? "" : " and modelId in (" . implode(",", $arrInput['rbacId']) . ") ";
        }

        if ($arrInput['sysType']) {
            $str .= ' and sys_type = ' . $arrInput['sysType'];
        }

        if ($arrInput['menuTitle'] != '') {
            $menuTitle = $arrInput['menuTitle'];
            $str .= " and menuTitle LIKE '%$menuTitle%' ";
        }
        $modelList  =   isset($arrInput['userInfo']['privileges']['list']) ? $arrInput['userInfo']['privileges']['list'] : array();
        if( $modelList && (reset($modelList)!='all') ) {
            $strModelId =   implode(',', $modelList);
            $str .= " and modelId in($strModelId) ";
        }
        $sql = "select modelId, menuSort, menuTitle, menuUrl, menuType, menuPrivileges,sys_type from tblModelInfo where deleted = 0 " . $str . " order by menuType, menuSort";
        $ret = $this->_db->query($sql);

        return ($ret) ? $ret : array();

    }

    /**
     * @brief   get admin list
     * @param   int $type
     * @return  array
     */
    public function getAdminList($type = 0) {
        if (empty ($this->_db)) {
            $this->_db = self::getDB($this->_dbName);
        }

        $sql = ($type) ? "select id, uid, uname, uRole, nickName, uMail, rbacId, gradeId, courseId,ext from tblAdminInfo where deleted = 0 and uRole = 5" : "select id, uid, uname, uRole, nickName, uMail, rbacId, gradeId, courseId,ext from tblAdminInfo where deleted = 0";
        $ret = $this->_db->query($sql);

        return ($ret) ? $ret : array();
    }

    /*
     * @brief   get model info by id
     * @param   arrInput array
     * @return  array
     */
    public function getModelById($nodeIds) {
        if (empty ($this->_db)) {
            $this->_db = self::getDB($this->_dbName);
        }

        $sql = "select menuPrivileges from tblModelInfo where modelId in (" . implode(",", $nodeIds) . ")";
        $ret = $this->_db->query($sql);

        return ($ret) ? $ret : array();
    }

    /**
     * @brief   get group list
     * @param   int $type
     * @return  array
     */
    public function getGroupList($type = 0) {
        if (empty ($this->_db)) {
            $this->_db = self::getDB($this->_dbName);
        }

        switch ($type) {
            case 0:
                $sql = "select rbacId, title,rPrivileges,sys_type from tblAdminGroup";
                break;
            case 10000:
                $sql = "select rbacId, title,rPrivileges,sys_type from tblAdminGroup where rbacId regexp '^2'";
                break;
            default:
                $sql = "select rbacId, title,rPrivileges,sys_type from tblAdminGroup where sys_type = " . $type;
                break;
        }
        $ret = $this->_db->query($sql);
        return ($ret) ? $ret : array();
    }

    /*
     * @brief   get Conds
     * @param   arrConds array
     * @return  arrCondsRes array
     */
    public function getConds($arrConds) {
        $arrCondsRes = array();
        foreach ($arrConds as $key => $value) {
            if (is_array($value)) {
                if (count($value) == 2) {
                    $arrCondsRes[$key . ' ' . $value[0]] = $value[1];
                } elseif (count($value) == 4) {
                    $arrCondsRes[$key . ' ' . $value[0]] = $value[1];
                    $arrCondsRes[$key . ' ' . $value[2]] = $value[3];
                }
            } else {
                $arrCondsRes[$key . ' ='] = $value;
            }
        }

        return $arrCondsRes;
    }

    /**
     * @brief   get getGroupListByModelId list
     * @param   int $modelId
     * @return  array
     */
    public function getGroupListByModelId($modelId) {
        if (empty ($this->_db)) {
            $this->_db = self::getDB($this->_dbName);
        }

        $sql = "select rbacId, title,rPrivileges from tblAdminGroup where rPrivileges LIKE '%$modelId%' ";
        $ret = $this->_db->query($sql);

        return ($ret) ? $ret : array();
    }
}