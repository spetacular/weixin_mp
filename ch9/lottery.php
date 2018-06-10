<?php
if(!checkMicroMessenger()){
    $ret = array('result'=> -2,'msg'=>'不是微信浏览器','info' => '');
    echo json_encode($ret);
    exit();
}
require 'lib/common.func.php';
include_once 'model/SaeDB.class.php';
$mysql = SaeDB::getInstance();
$openid = $mysql->escape($_POST['id']);
$selectSql = "SELECT COUNT( * ) FROM  `shop_lottery` WHERE  `openid` LIKE  '{$openid}'";
$countData = $mysql->getLine( $selectSql );
$count = reset($countData);
if($count >= 5){
    $ret = array('result'=> -2,'msg'=>'抽奖机会已用完','info' => '');
    echo json_encode($ret);
    exit();
}
sae_log("openid:{$openid},count:".  var_export($count, true));//记录抽奖次数
$env = 'develop';//环境切换，develop为开发环境，product为线上环境
if($env == 'product'){
    require 'award.config.php';
}else if($env == 'develop'){
    require 'devaward.config.php';
}
//查询中奖次数
$selectSql = "SELECT COUNT( * ) FROM  `shop_lottery` WHERE  `openid` LIKE  '{$openid}' AND  `awardId` !=0";
$countData = $mysql->getLine( $selectSql );
$count = reset($countData);
if($count >= 1){//如果已经中一次奖，则不再中奖
    $awardId = 0;
    sae_log("openid:{$openid},已经中过奖");//用户中奖情况
}  else {
    $awardId = getLottery($awardConfig);
}

if($awardId == 0){
    $ret = array('result'=> 0,'msg'=>'谢谢参与','info' => array_rand($awardConfig,3));
}  else {
    $ret = array('result'=> $awardId,'msg'=>$awardConfig[$awardId]['name'],'info' => array($awardId,$awardId,$awardId));
}
sae_log("openid:{$openid},Lottery Result:".  var_export($ret, true));//记录下抽奖结果
$seq = md5($openid.  time());
$sql = "INSERT INTO `shop_lottery` (`id`, `openid`, `awardId`, `seq`, `addtime`) VALUES (NULL, '{$openid}', '{$awardId}', '{$seq}',CURRENT_TIMESTAMP);";
$mysql->runSql($sql);
$mysql->closeDb();
echo json_encode($ret);
//判断当前浏览器是否为微信浏览器
function checkMicroMessenger(){
    return preg_match("/MicroMessenger/i", $_SERVER['HTTP_USER_AGENT']);
}
//抽奖
function getLottery($awardConfig){	
	$randomNum = randomFloat();
	$luckId = 0;
	foreach($awardConfig as $item){
		$spice = $item['awardID'] /10.0 ;
                $sub = $randomNum - $spice;
		if($sub > 0 && $sub <= $item['probability']){
			$luckId = $item['awardID'];
			break;
		}
	}
	return $luckId;
}
//获取0到1之间的浮点数
function randomFloat($min = 0, $max = 1) {
    return $min + mt_rand() / mt_getrandmax() * ($max - $min);
}