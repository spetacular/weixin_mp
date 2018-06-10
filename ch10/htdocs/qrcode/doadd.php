<?php
if ( ! defined('IN_APP')) exit('No direct script access allowed');
$number = isset($_POST['number'])?intval($_POST['number']):1;
$expdays = isset($_POST['expdays'])?intval($_POST['expdays']):0;
$cost = isset($_POST['cost'])?intval($_POST['cost']):0;

require libfile("lib/weixin.class.php");
$options = array('scene_id'=>1234);
$ticket = weixin::getQrcodeTicket(1234,20);
var_dump($ticket);
$imgurl = weixin::getQrcodeImgUrlByTicket($ticket);
var_dump($imgurl);
//var_dump($ticket);
?>
