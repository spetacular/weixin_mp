<?php
if ( ! defined('IN_APP')) exit('No direct script access allowed');
require libfile("lib/weixin.class.php");


$ret = weixin::getUserList();
var_dump($ret);
$openid = $ret['list'][0];
$userinfo = weixin::getUserInfoById($openid);
var_dump($userinfo);
?>
