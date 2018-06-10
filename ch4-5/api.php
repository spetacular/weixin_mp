<?php
/**
 入口文件
 */
defined('BASEDIR') || define('BASEDIR', dirname (__FILE__));
require 'lib/common.func.php';
require 'lib/defaultweixin.php';

$weixin = new DefaultWeixin();
$weixin->run();
exit(0);
