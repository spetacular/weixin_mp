<?php
include_once 'model/SaeDB.class.php';
$type = intval($_POST['type']);
$sceneid = intval($_POST['sceneid']);
$mysql = SaeDB::getInstance();
if($type == 0){//不允许使用
    $ret = json_encode(array('ret'=>'ok','msg'=>'优惠券不允许被使用'));
    $sql = "UPDATE `diner_qrcode` SET  `used` =  '2' WHERE  `sceneid` ={$sceneid};";
    $mysql->runSql( $sql );
}else if($type == 1){//允许使用    
    $sql = "UPDATE `diner_qrcode` SET  `used` =  '1' WHERE  `sceneid` ={$sceneid} and `used` = 0;";
    $mysql->runSql( $sql );
    if($mysql->affectedRows() < 1){//if update fails,then insert one
        $ret = json_encode(array('ret'=>'error','msg'=>'优惠券已被使用或不存在'));
    }else{
        $ret = json_encode(array('ret'=>'ok','msg'=>'优惠券使用成功'));
    }
    
}else if($type == 2){//查询是否被使用    
   $sql = "SELECT used FROM  `diner_qrcode` where sceneid = {$sceneid}";
    $data = $mysql->getLine( $sql );
    if(!empty($data)){
        if($data['used'] == 0){
            $ret = json_encode(array('ret'=>'error','msg'=>'优惠券还未使用')); 
        }else if($data['used'] == 1){
            $ret = json_encode(array('ret'=>'ok','msg'=>'优惠券使用成功'));
        }else if($data['used'] == 2){
           $ret = json_encode(array('ret'=>'notallowed','msg'=>'优惠券不允许被使用')); 
        }
        
    }else{
        $ret = json_encode(array('ret'=>'error','msg'=>'优惠券不存在')); 
    }   
}
$mysql->closeDb();
echo $ret;