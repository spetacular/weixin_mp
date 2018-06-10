<?php
/**
 * 微信消息数据库操作类
 */
include_once 'SaeDB.class.php';
class SendMsgDB {
    private $db;
    public function __construct() {
        $this->db=  SaeDB::getInstance();   
    }
    
    /**
     * 保存用户最近的地理位置
     * @param type $data 微信消息体
     * @return boolean
     */
    public function saveUserLocation($data) {       
        $FromUserName=$this->db->escape($data->FromUserName);
        $CreateTime=  intval($data->CreateTime);
        $Latitude=doubleval(($data->Latitude));       
        $Longitude=doubleval($data->Longitude);
        $sql = "UPDATE  `dinner_userlocs` SET  `Latitude` =  '{$Latitude}',
            `Longitude` =  '{$Longitude}',
            `CreateTime` =  '{$CreateTime}' WHERE  `dinner_userlocs`.`FromUserName` ='{$FromUserName}';";
        $this->db->runSql( $sql );
        if($this->db->affectedRows() < 1){//if update fails,then insert one
            $sql="INSERT INTO `dinner_userlocs` (`id`, `Latitude`, `Longitude`, `FromUserName`, `createtime`) VALUES ".
                "(NULL, '{$Latitude}', '{$Longitude}', '{$FromUserName}','$CreateTime ');";        
            $this->db->runSql( $sql );
            if( $this->db->errno() != 0 ){
                sae_log("存入位置信息失败,错误原因为：".$this->db->errmsg()."出错sql为：".$sql);
                return FALSE;            
            }
        }        
        return TRUE;
    }
    
    /**
     * 获取用户最近位置
     * @param type $data 微信消息体
     * @return type
     */
    public function getUserLocation($data){
         $FromUserName=$this->db->escape($data->FromUserName);
         $sql = "SELECT * FROM  `dinner_userlocs` where FromUserName = '{$FromUserName}' order by `CreateTime` desc limit 1";
         return $this->db->getLine( $sql );
    }  
    
    /**
     * 获取用户最近预约情况
     * @param type $data 微信消息体
     * @return type
     */
    public function getRecentReserve($data) {
        $openid=$this->db->escape($data->FromUserName);
        $sql = "SELECT * 
        FROM  `diner_reserve` 
        WHERE  `openid` LIKE  '{$openid}' order by dinertime desc";
        return $this->db->getLine( $sql );
    }
}