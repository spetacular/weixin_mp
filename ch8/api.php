<?php
/**
 * 微信消息接口URL
 */
defined('BASEDIR') || define('BASEDIR', dirname (__FILE__));
define('BASE_URL','http://8.weixinbook.applinzi.com');
require 'lib/common.func.php';
require 'lib/defaultweixin.php';
$weixin = new DefaultWeixin();
$weixin->run();
exit(0);
