<?php
defined('IN_APP') || define('IN_APP', TRUE);
defined('BASEDIR') || define('BASEDIR', dirname (__FILE__));

$number = isset($_POST['number'])?intval($_POST['number']):1;
$expdays = isset($_POST['expdays'])?intval($_POST['expdays']):0;
$cost = isset($_POST['cost'])?intval($_POST['cost']):0;
require '../lib/common.func.php';
include  "../lib/weixin.class.php";
$a = curl_post('https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=2DAuR2KnxcnRmreuJ9LmBTuW3GLVtiNgEfFccC9N74K2Ft-4nkLN9x3Ch2H-6kPbfu3JJM7ZqlRMRyBMSmDU-_bwJ5WNodcFJmpu1uDYMD5nA4vX5I7rEhULbl1c5IGfxOWhxDr_DHuF9EGgSs1Ggw', $post_string);
var_dump('a',$a);


$options = array('scene_id'=>1234);
$ticket = qrcode::getQrcodeTicket($options);
var_dump($ticket);
$imgurl = qrcode::getQrcodeImgUrlByTicket($ticket);
var_dump($imgurl);
?>
