<?php

include_once 'SaeDB.class.php';

/**
 * Description of SendMsgDB
 *
 * @author davidyan
 */
class ArticleDB {
    private $db;
    public function __construct() {
        $this->db=  SaeDB::getInstance();   
    }
    
    /**
     * 保存笑话信息
     * @return boolean
     */
    public function saveJokes($data){
        $title=$this->db->escape($data['title']);
        $content=$this->db->escape($data['content']);
        $pic=$this->db->escape($data['pic']);
        $poster=$this->db->escape($data['poster']);
        $createtime=  intval($data['createtime']);
 
        $sql="INSERT INTO `jokes` (`id`, `title`, `content`, `createtime`, `poster`, `pic`) VALUES ".
                "(NULL, '".$title."', '".$content."', '".$createtime."', '".$poster."','".$pic."');";        
        $this->db->runSql( $sql );
        if( $this->db->errno() != 0 ){
            sae_log("存入笑话失败,错误原因为：".$this->db->errmsg()."出错sql为：".$sql);
            return FALSE;            
        }
        return TRUE;
    }
    
  public function getJokes(){
  	$sql="SELECT  `content`  
FROM  `jokes` 
ORDER BY  `createtime` DESC 
LIMIT 0 , 20";
        $data = $this->db->getData( $sql );
        return $data;
  }
}

?>
