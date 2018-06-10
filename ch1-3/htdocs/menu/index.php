<?php
if ( ! defined('IN_APP')) exit('No direct script access allowed');
require libfile("lib/weixin.class.php");

$menu = '{"button":[{"type":"click","name":"历史文章","key":"V1001_TODAY_MUSIC"},{"type":"click","name":"兔子社区","key":"V1001_TODAY_SINGER"},{"name":"联系我","sub_button":[{"type":"view","name":"微博","url":"http://www.soso.com/"},{"type":"view","name":"邮箱","url":"http://v.qq.com/"},{"type":"click","name":"关于我","key":"V1001_GOOD"}]}]}';
$ret = weixin::createMenu($menu);
var_dump($ret);

$ret = weixin::getMenu();
var_dump($ret);
//var_dump($ticket);
if(isset($_GET['david'])){
    $ret = weixin::deleteMenu();
     var_dump($ret);
    $ret = weixin::getMenu();
    var_dump($ret);
}
?>
