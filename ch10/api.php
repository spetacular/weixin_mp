<?php
/**
入口文件
 */
defined('BASEDIR') || define('BASEDIR', dirname (__FILE__));
define('BASE_URL','http://1.weixinbook.applinzi.com');
require 'lib/common.func.php';
require 'BookHotel/Bookhotel.php';

$weixin = new Bookhotel();
$weixin->run();
exit(0);