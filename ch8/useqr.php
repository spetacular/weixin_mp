<?php
include_once 'model/SaeDB.class.php';
$sceneid = intval($_POST['sceneid']);
$mysql = SaeDB::getInstance();
$sql = "SELECT * FROM  `diner_qrcode` where sceneid = {$sceneid} and used = '0'";
$data = $mysql->getLine( $sql );
$mysql->closeDb();
if(empty($data)){  
    echo json_encode(array('ret'=>'used','msg'=>'优惠券已被使用或不存在'));
    exit();
}

$channelname = 'qrcheck';
$channel = new SaeChannel();
$channel->createChannel($channelname,600);
$message_content = json_encode(array('type'=>'requse','sceneid'=>$sceneid));
// Send message
$ret = $channel->sendMessage($channelname,$message_content);
if($ret){
    echo json_encode(array('ret'=>'ok','msg'=>'您的优惠券使用请求已发送'));
}else{
    echo json_encode(array('ret'=>'error','msg'=>'您的优惠券使用请求发送失败'));
}