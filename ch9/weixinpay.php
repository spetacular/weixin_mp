<?php
define('APPID', 'wxf8b4f85f3a794e77');
define('APPSECRET', '4333d426b8d01a3fe64d53f36892df');
define('PAYSIGNKEY', '2Wozy2aksie1puXUBpWD8oZxiD1DfQuEaiC7KcRATv1Ino3mdopKaPGQQ7TtkNySuAmCaDCrw4xhPY5qKTBl7Fzm0RgR3c0WaVYIXZARsxzHV2x7iwPPzOz94dnwPWSn');
define('PARTNERID', '1900000109');
define('PARTNERKEY', '8934e7d15453e97507ef794cf7b0519d');

define('APPID', '公众号id');
define('APPSECRET', '公众号密钥Key');
define('PAYSIGNKEY', '支付签名');
define('PARTNERID', '财付通商户id');
define('PARTNERKEY', '财付通商户密钥Key');
//echo getAppId();
//echo getTimeStamp();
//for($i=0;$i<100;$i++){
//$l = strlen(getNonceStr());
//if($l == 31){
//	echo "出错了\n";
//}else{
//	//echo "没有错\n";
//}
//}
//echo getPackage("棉花糖",5);
//echo getSign('abc', '123', '345');
/**
 * 获取公众号名称
 */
function getAppId(){
    return APPID;
}

/**
 * 获取时间戳
 */
function getTimeStamp(){
    return strval(time());
}

/**
 * 获取随机字符串
 */
function getNonceStr(){
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $len = strlen($chars);
    $noceStr = "";
    for ($i = 0; $i < 32; $i++) {
        $noceStr .= substr($chars, rand(0, $len-1), 1); // 
    }
    return $noceStr;
}
/**
 * 获取随机数
 * @return type
 */
function getANumber(){
    $timeStamp = time();
    return $timeStamp*(date('dHis',$timeStamp)+  rand());
}
/**
 * 获取扩展包
 * @param type $goodsDesc 商品描述
 * @param type $totalFee 总费用
 * @return type
 */
function getPackage($goodsDesc, $totalFee){
    $banktype = "WX";
    $fee_type = "1";//费用类型，这里1为默认的人民币
    $input_charset = "GBK";//字符集，这里将统一使用GBK
    $notify_url = "http://www.qq.com";//支付成功后将通知该地址
    //$out_trade_no = getANumber();//订单号，商户需要保证该字段对于本商户的唯一性
    $out_trade_no = 111;
    $partner = PARTNERID;//测试商户号
    $spbill_create_ip = $_SERVER['REMOTE_ADDR'];//用户浏览器的ip，这个需要在前端获取。
    $partnerKey = PARTNERKEY;//这个值和以上其他值不一样是：签名需要它，而最后组成的传输字符串不能含有它。这个key是需要商户好好保存的。

    //首先第一步：对原串进行签名，注意这里不要对任何字段进行编码。这里是将参数按照key=value进行字典排序后组成下面的字符串,在这个字符串最后拼接上key=XXXX。由于这里的字段固定，因此只需要按照这个顺序进行排序即可。
    $signString = "bank_type=".$banktype."&body=".$goodsDesc."&fee_type=".$fee_type."&input_charset=".$input_charset."&notify_url=".$notify_url."&out_trade_no=".$out_trade_no."&partner=".$partner."&spbill_create_ip=".$spbill_create_ip."&total_fee=".$totalFee."&key=".$partnerKey;

    $md5SignValue = strtoupper(md5($signString));
    //然后第二步，对每个参数进行url编码。
    $banktype = urlencode($banktype);
    $goodsDesc=urlencode($goodsDesc);
    $fee_type=urlencode($fee_type);
    $input_charset = urlencode($input_charset);
    $notify_url = urlencode($notify_url);
    $out_trade_no = urlencode($out_trade_no);
    $partner = urlencode($partner);
    $spbill_create_ip = urlencode($spbill_create_ip);
    $totalFee = urlencode($totalFee);


    //然后进行最后一步，这里按照key＝value除了sign外进行字典序排序后组成下列的字符串,最后再串接sign=value
    $completeString = "bank_type=".$banktype."&body=".$goodsDesc."&fee_type=".$fee_type."&input_charset=".$input_charset."&notify_url=".$notify_url."&out_trade_no=".$out_trade_no."&partner=".$partner."&spbill_create_ip=".$spbill_create_ip."&total_fee=".$totalFee;
    $completeString = $completeString . "&sign=".$md5SignValue;

    return $completeString;

}

/**
 * 获取微信签名方式 sha1
 */
function getSignType(){
    return "SHA1";
}

/**
 * 获取微信签名
 * @param type $oldNonceStr 随机字符串
 * @param type $oldPackageString 扩展包
 * @param type $oldTimeStamp 时间戳
 * @return type
 */
function getSign($oldNonceStr, $oldPackageString, $oldTimeStamp){
    $keyvaluestring = "appid=".APPID."&appkey=".PAYSIGNKEY."&noncestr=".$oldNonceStr."&package=".$oldPackageString."&timestamp=".$oldTimeStamp;
    return sha1($keyvaluestring);
}
/**
 * 生成用于查询订单的json字符串
 * @param String $appid 公众号id
 * @param String $out_trade_no 第三方唯一订单号
 * @param String $appkey 支付签名
 * @param String $partner 财付通商户身份标识
 * @param String $partnerkey 财付通商户权限密钥Key
 * @param String $timestamp 时间戳
 * @return String 
 */
function genOrderQuery($appid,$out_trade_no,$appkey,$partner,$partnerkey,$timestamp) {
    $sign = md5("out_trade_no={$out_trade_no}&partner={$partner}&key={$partnerkey}");
    $app_signature = md5("appid={appid}&appkey={$appkey}&package={package}&timestamp={timestamp}");
    $postArray = array(
        'appid' => $appid,
        'package' => "out_trade_no={$out_trade_no}&partner={$partner}&sign={$sign}",
        'timestamp' => $timestamp,
        'app_signature' => $app_signature,
        'sign_method' => 'sha1',
    );
    return json_encode($postArray);
}
$appid = 'wwwwb4f85f3a797777';
$out_trade_no = 11122;
$partner = 1900090055;
$timestamp=1369745073;
$appkey = '';
$partnerkey = '';
echo genOrderQuery($appid,$out_trade_no,$appkey,$partner,$partnerkey,$timestamp);
?>