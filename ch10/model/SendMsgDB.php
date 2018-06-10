<?php

include_once 'SaeDB.class.php';

/**
 * Description of SendMsgDB
 *
 * @author davidyan
 */
class SendMsgDB {
    private $db;
    public function __construct() {
        $this->db=  SaeDB::getInstance();   
    }
    
    /**
     * 保存文本信息
     * @return boolean
     */
    public function saveText($data){
        $tousername=$this->db->escape($data->ToUserName);
        $fromusername=$this->db->escape($data->FromUserName);
        $createtime=  intval($data->CreateTime);
        $content=$this->db->escape($data->Content);
        $msgid=$this->db->escape($data->MsgId);
        $sql="INSERT INTO `sendtext` (`id`, `tousername`, `fromusername`, `createtime`, `msgtype`, `content`, `msgid`) VALUES ".
                "(NULL, '".$tousername."', '".$fromusername."', '".$createtime."', 'text', '".$content." ', '".$msgid."');";        
        $this->db->runSql( $sql );
        if( $this->db->errno() != 0 ){
            sae_log("存入文本信息失败,错误原因为：".$this->db->errmsg()."出错sql为：".$sql);
            return FALSE;            
        }
        return TRUE;
    }
    
    /**
     * 保存图片信息
     * @param type $data
     * @return boolean
     */
    public function saveImage($data){
        $tousername=$this->db->escape($data->ToUserName);
        $fromusername=$this->db->escape($data->FromUserName);
        $createtime=  intval($data->CreateTime);
        $picurl=$this->db->escape($data->PicUrl);
        $msgid=$this->db->escape($data->MsgId);
        $sql="INSERT INTO `sendimage` (`id`, `tousername`, `fromusername`, `createtime`, `msgtype`, `picurl`, `msgid`) VALUES ".
                "(NULL, '".$tousername."', '".$fromusername."', '".$createtime."', 'image', '".$picurl." ', '".$msgid."');";        
        $this->db->runSql( $sql );
        if( $this->db->errno() != 0 ){
            sae_log("存入图片信息失败,错误原因为：".$this->db->errmsg()."出错sql为：".$sql);
            return FALSE;            
        }
        return TRUE;
    }
    
    /**
     * 保存链接信息
     * @param type $data
     * @return boolean
     */
    public function saveLink($data){
        $tousername=$this->db->escape($data->ToUserName);
        $fromusername=$this->db->escape($data->FromUserName);
        $createtime=  intval($data->CreateTime);
        $title=$this->db->escape($data->Title);       
        $description=$this->db->escape($data->Description);
        $url=$this->db->escape($data->Url);
        $msgid=$this->db->escape($data->MsgId);
        $sql="INSERT INTO `sendlink` (`id`, `tousername`, `fromusername`, `createtime`, `msgtype`, `title`, `description`, `title`, `msgid`) VALUES ".
                "(NULL, '".$tousername."', '".$fromusername."', '".$createtime."', 'link', '".$title." ', '".$description." ', '".$url." ', '".$msgid."');";        
        $this->db->runSql( $sql );
        if( $this->db->errno() != 0 ){
            sae_log("存入链接信息失败,错误原因为：".$this->db->errmsg()."出错sql为：".$sql);
            return FALSE;            
        }
        return TRUE;
    }
    
     /**
     * 保存链接信息
     * @param type $data
     * @return boolean
     */
    public function saveLocation($data){
        $tousername=$this->db->escape($data->ToUserName);
        $fromusername=$this->db->escape($data->FromUserName);
        $createtime=  intval($data->CreateTime);
        $location_x=doubleval(($data->Location_X));       
        $location_y=doubleval($data->Location_Y);
        $scale=doubleval($data->Scale);
        $label=$this->db->escape($data->Label);
        $msgid=$this->db->escape($data->MsgId);
        $sql="INSERT INTO `sendlocation` (`id`, `tousername`, `fromusername`, `createtime`, `msgtype`, `location_x`, `location_y`, `scale`, `label`, `msgid`) VALUES ".
                "(NULL, '".$tousername."', '".$fromusername."', '".$createtime."', 'location', '".$location_x." ', '".$location_y." ', '".$scale." ', '".$label." ', '".$msgid."');";        
        $this->db->runSql( $sql );
        if( $this->db->errno() != 0 ){
            sae_log("存入位置信息失败,错误原因为：".$this->db->errmsg()."出错sql为：".$sql);
            return FALSE;            
        }
        return TRUE;
    }

	public function saveDialog($input,$output){
		$input=$this->db->escape($input);
		$output=$this->db->escape($output);
		$createtime=time();
		$sql="INSERT INTO `xiaotu` (`id`, `input`, `output`, `createtime`) VALUES ".
                "(NULL, '".$input."', '".$output."', '".$createtime."');";        
        $this->db->runSql( $sql );
        if( $this->db->errno() != 0 ){
            sae_log("存入对话信息失败,错误原因为：".$this->db->errmsg()."出错sql为：".$sql);
            return FALSE;            
        }
        return TRUE;
	}
}

?>
