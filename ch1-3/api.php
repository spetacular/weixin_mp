<?php
/**
 * 使用说法
 * CDWeixin 是主要的封闭类，你需要做的就是继承这个类（例如：TestWeixin)，然后重写其中的processRequest方法
 * 在这个方法中主要完成的工作就是针对用户发送的不同类型的数据结合自己的业务系统，返回不同的信息
 * 返回信息有两个方法，outputText和outputNews，分别用以返回文本内容和图文内容
 *
 * CDWeixin 这个类也可以直接实例化进行使用，单独使用的时候就不需要运行run方法了，实例化后直接使用其中的方法进行编程即可。
 */
defined('BASEDIR') || define('BASEDIR', dirname (__FILE__));
require 'lib/common.func.php';
require 'lib/defaultweixin.php';

$weixin = new DefaultWeixin();
$weixin->run();
exit(0);
