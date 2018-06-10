<?php
require "lib/weixin.class.php";//引入微信类文件
define('BASE_URL','http://'.$_SERVER['SERVER_NAME']);
$myOrderUrl = weixin::createCodeUrl('snsapi_base', 'hotel',BASE_URL.'/BookHotel/MyOrder.php');
$myMembershipUrl = weixin::createCodeUrl('snsapi_base', 'hotel',BASE_URL.'/BookHotel/MyMembership.php');
$myCouponsUrl = weixin::createCodeUrl('snsapi_base', 'hotel',BASE_URL.'/BookHotel/MyCoupons.php');
//$menu变量为存放菜单项的json字符串
$menu = '{ "button":[
             {
                  "name":"我",
                   "sub_button":[
                   {
                       "type":"view",
                       "name":"我的订单",
                       "url":"'.$myOrderUrl.'"
                    },
                    {
                       "type":"view",
                       "name":"我的会员卡",
                       "url":"'.$myMembershipUrl.'"
                       },
                    {
                       "type":"view",
                       "name":"抵用券",
                       "url":"'.$myCouponsUrl.'"
                    }]
              },
              {
                   "type":"click",
                   "name":"订酒店",
                   "key":"V2001"
              }]
       }';

$ret = weixin::createMenu($menu);//创建菜单
if($ret){//创建成功
    echo 'create menu ok';
}else{//创建失败
    echo 'create menu fail';
}
?>
