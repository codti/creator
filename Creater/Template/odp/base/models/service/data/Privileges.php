<?php

/**
 * @name    Service_Data_Privileges
 * @desc    model data service, 按主题组织数据, 提供细粒度数据接口
 * @author  zhengzhiqing@zuoyebang.com
 */
class Service_Data_Privileges
{
    private $_objDaoPrivileges;
    private $_arrOutPut;

    //管理系统类型
    const SYS_TYPE_ADMIN = 1; //后台管理系统
    const SYS_TYPE_TEACHER = 2; //主讲工作台
    const SYS_TYPE_EDUAD = 3; //教务工作台

    //管理系统类别别名
    static $SYS_TYPE = array(
        self::SYS_TYPE_ADMIN    => '后台管理系统',
//        self::SYS_TYPE_TEACHER  => '主讲工作台',
//        self::SYS_TYPE_EDUAD    => '教务工作台',
    );

    //管理系统类别存放字段
    const SYS_TYPE_ADMIN_FLIED      = 'tutorManageSys';
    const SYS_TYPE_TEACHER_FLIED    = 'teacherSys';
    const SYS_TYPE_EDUAD_FLIED      = 'eduadSys';

    //管理系统类别所有存放字段
    static $SYS_TYPE_ALL_FLIED    =   array(
        self::SYS_TYPE_ADMIN  =>  self::SYS_TYPE_ADMIN_FLIED,
        self::SYS_TYPE_TEACHER  =>  self::SYS_TYPE_TEACHER_FLIED,
        self::SYS_TYPE_EDUAD  =>  self::SYS_TYPE_EDUAD_FLIED,
    );

    public function __construct() {
        $this->_objDaoPrivileges = new Dao_Privileges();
        $this->_arrOutPut        = array();
    }

    /*
     * @brief   get admin detail
     * @param   arrInput array
     * @return  array
     */
    public function getAdminDetail($uid) {
        $this->_arrOutPut = $this->_objDaoPrivileges->getAdminInfo($uid);

        return $this->_arrOutPut ? $this->_arrOutPut : array();
    }

    /*
     * @brief   get admin detail
     * @param   arrInput array
     * @return  array
     */
    public function getAdminByMail($mail) {
        $userInfo = $this->_objDaoPrivileges->getAdminByMail($mail);
        if ($userInfo) {
            $rbacId = json_decode($userInfo['rbacId'], true);
            if (!empty($rbacId['tutorManageSys'])) {
                $userInfo['rbacId'] = $rbacId['tutorManageSys'];
            }

            $ext = json_decode($userInfo['ext'], true);

            $privilegeInfo = array(
                "rPrivileges" => array(
                    "list"    => array(),
                    "model"   => array(),
                    "subject" => $ext && isset($ext['rPrivileges']['subject']) ? $ext['rPrivileges']['subject'] : array(),
                    "point"   => $ext && isset($ext['rPrivileges']['point']) ? $ext['rPrivileges']['point'] : array(),
                ),
            );
            // get group privileges
            $privileges = $this->_objDaoPrivileges->getPrivileges($userInfo['rbacId']);
            if ($privileges) {
                foreach ($privileges as $v) {
                    $jP = json_decode($v['rPrivileges'], true);
                    if ($jP) {
                        $privilegeInfo['rPrivileges']['list']  = array_merge($privilegeInfo['rPrivileges']['list'], $jP['list']);
                        $privilegeInfo['rPrivileges']['model'] = array_merge($privilegeInfo['rPrivileges']['model'], $jP['model']);
                    }
                }
            }
            if (in_array("all", $privilegeInfo['rPrivileges']['model'])) {
                $privilegeInfo['rPrivileges']['subject'] = array( array( "gradeId" => 0, "courseId" => 0 ) );
                $privilegeInfo['rPrivileges']['point']   = array(
                    array(
                        "gradeId"  => 0,
                        "courseId" => 0,
                        "pointId"  => 0,
                    ),
                );
            }
            if ($privilegeInfo) {
                $this->_arrOutPut = array_merge($userInfo, $privilegeInfo);
            }
        }
        return $this->_arrOutPut;
    }

    /*
     * @brief   get user info
     * @param   arrInput array
     * @return  array
     */
    public function getAdminInfo($uid) {
        $userInfo = $this->_objDaoPrivileges->getAdminInfo($uid);
        if ($userInfo) {
            $rbacId = json_decode($userInfo['rbacId'], true);
            if (!empty($rbacId['tutorManageSys']) && empty($rbacId['teacherRole'])) {
                $userInfo['rbacId'] = $rbacId['tutorManageSys'];
            }

            if (!empty($rbacId['teacherRole']) && empty($rbacId['tutorManageSys'])) {
                $userInfo['rbacId'] = $rbacId['teacherRole'];
            }

            if (!empty($rbacId['tutorManageSys']) && !empty($rbacId['teacherRole'])) {
                $userInfo['rbacId'] = array_merge($rbacId['tutorManageSys'], $rbacId['teacherRole']);
            }

            $ext = json_decode($userInfo['ext'], true);

            $privilegeInfo = array(
                "rPrivileges" => array(
                    "list"    => array(),
                    "model"   => array(),
                    "subject" => $ext && isset($ext['rPrivileges']['subject']) ? $ext['rPrivileges']['subject'] : array(),
                    "point"   => $ext && isset($ext['rPrivileges']['point']) ? $ext['rPrivileges']['point'] : array(),
                ),
            );

            // get group privileges
            $privileges = $this->_objDaoPrivileges->getPrivileges($userInfo['rbacId']);
            if ($privileges) {
                foreach ($privileges as $v) {
                    $jP = json_decode($v['rPrivileges'], true);
                    if ($jP) {
                        $privilegeInfo['rPrivileges']['list']  = array_merge($privilegeInfo['rPrivileges']['list'], $jP['list']);
                        $privilegeInfo['rPrivileges']['model'] = array_merge($privilegeInfo['rPrivileges']['model'], $jP['model']);
                    }
                }
            }

            if (in_array("all", $privilegeInfo['rPrivileges']['model'])) {
                $privilegeInfo['rPrivileges']['subject'] = array( array( "gradeId" => 0, "courseId" => 0 ) );
                $privilegeInfo['rPrivileges']['point']   = array(
                    array(
                        "gradeId"  => 0,
                        "courseId" => 0,
                        "pointId"  => 0,
                    ),
                );
            }

            if ($privilegeInfo) {
                $this->_arrOutPut = array_merge($userInfo, $privilegeInfo);
            }
        }

        return $this->_arrOutPut;
    }

    /*
     * @brief   get model list
     * @param   arrInput array
     * @return  array
     */
    public function getModelList($arrInput) {
        $this->_arrOutPut = $this->_objDaoPrivileges->getModelList($arrInput);

        return $this->_arrOutPut;
    }

    /**
     * @brief   get admin list
     * @param   int $type
     * @return  array
     */
    public function getAdminList($type = 0) {
        $this->_arrOutPut = $this->_objDaoPrivileges->getAdminList($type);

        return $this->_arrOutPut;
    }

    /**
     * @brief   get group list
     * @param   int $type
     * @return  array
     */
    public function getGroupList($type = 0) {
        $this->_arrOutPut = $this->_objDaoPrivileges->getGroupList($type);

        return $this->_arrOutPut;
    }

    /*
     * @brief   update admin info
     * @param   $arrFields array, $arrConds array
     * @return  array
     */
    public function editAdminInfo($arrFields, $arrConds) {
        if (empty($arrConds)) {
            Bd_Log::warning("Error[param error] Detail[arrConds empty]");

            return false;
        }

        $this->_arrOutPut = $this->_objDaoPrivileges->updateByConds($arrConds, $arrFields);
        if (is_int($this->_arrOutPut)) {
            $this->_arrOutPut = $arrConds['id'];
        } else {
            $this->_arrOutPut = 0;
        }

        return $this->_arrOutPut;
    }

    /*
     * @brief   update group info
     * @param   $arrFields array, $arrConds array
     * @return  array
     */
    public function editAdminGroup($arrFields, $arrConds) {
        $this->_arrOutPut = $this->_objDaoPrivileges->editAdminGroup($arrConds, $arrFields);
        if (is_int($this->_arrOutPut)) {
            $this->_arrOutPut = $arrConds['rbacId'];
        } else {
            $this->_arrOutPut = 0;
        }

        return $this->_arrOutPut;
    }

    /*
     * @brief   update group info
     * @param   $arrFields array, $arrConds array
     * @return  array
     */
    public function addAdminGroup($arrFields) {
        $this->_arrOutPut = $this->_objDaoPrivileges->addAdminGroup($arrFields);

        return $this->_arrOutPut;
    }


    /*
     * @brief   get model info by id
     * @param   arrInput array
     * @return  array
     */
    public function getModelById($nodeIds) {
        $ret = $this->_objDaoPrivileges->getModelById($nodeIds);
        if ($ret) {
            foreach ($ret as $v) {
                $jv = json_decode($v['menuPrivileges'], true);
                if ($jv) {
                    $this->_arrOutPut = array_merge($this->_arrOutPut, $jv);
                }
            }
        }

        return array_unique($this->_arrOutPut);
    }

    /*
     * @brief   add admin info
     * @param   arrInput array
     * @return  array
     */
    public function addAdminInfo($arrInput) {
        if ($this->_objDaoPrivileges->insertRecords($arrInput)) {
            $this->_arrOutPut = $this->_objDaoPrivileges->getInsertID();
        } else {
            $this->_arrOutPut = 0;
        }

        return $this->_arrOutPut;
    }

    /*
     * @brief   get group model
     * @param   arrInput array
     * @return  array
     */
    public function getGroupModel($rbacId) {
        $this->_arrOutPut = array();
        $group_model      = $this->_objDaoPrivileges->getPrivileges(array( $rbacId ));
        if ($group_model) {
            $nodeIds                        = json_decode($group_model[0]['rPrivileges'], true);
            $this->_arrOutPut['gid']        = $group_model[0]['id'];
            $this->_arrOutPut['rbacId']     = $group_model[0]['rbacId'];
            $this->_arrOutPut['title']      = $group_model[0]['title'];
            $this->_arrOutPut['createTime'] = '';
            $this->_arrOutPut['nodeIds']    = $nodeIds['list'];
            //$this->_arrOutPut['operationStandardCommon'] = Service_Page_Admin_ModelEdit::$OPERATION_STANDARD_COMMON;
        }

        return $this->_arrOutPut;
    }


    /*
     * @brief   get group model
     * @param   arrInput array
     * @return  array
     */
    public function getGroupListByModelId($modelId) {
        $this->_arrOutPut = array();
        $group_list       = $this->_objDaoPrivileges->getGroupListByModelId($modelId);
        if (empty($group_list)) {
            return $this->_arrOutPut;
        }
        $tmp = array();
        foreach ($group_list as $val) {
            $rPrivileges = json_decode($val['rPrivileges'], true);
            if (empty($rPrivileges['list'])) {
                return $this->_arrOutPut;
            }
            $list = implode(',', $rPrivileges['list']);
            foreach ($rPrivileges['list'] as $mid) {
                if ($mid == $modelId) {
                    $val['nodeIds'] = $list;
                    unset($val['rPrivileges']);
                    $tmp[] = $val;
                }
            }
        }
        if ($tmp) {
            $this->_arrOutPut = $tmp;
        }

        return $this->_arrOutPut;
    }

}
