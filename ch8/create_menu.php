<?php
require "lib/weixin.class.php";//引入微信类文件
//$menu变量为存放菜单项的json字符串
$menu = 
'{
  "button": [
    {
      "type": "click",
      "name": "预约",
      "key": "CLICK_RESERVE"
    },
    {
      "type": "view",
      "name": "点菜",
      "url": "http://'.$_SERVER['SERVER_NAME'].'/menu.php"
    },
    {
      "name": "服务",
      "sub_button": [
        {
          "type": "view",
          "name": "优惠券",
          "url": "http://url.cn/OnDmNV"
        },
        {
          "type": "view",
          "name": "免费WIFI",
          "url": "http://url.cn/K0GNmj"
        },
        {
          "type": "click",
          "name": "路线导航",
          "key": "CLICK_ROUTE"
        },
        {
          "type": "view",
          "name": "兔子饭庄",
          "url": "http://url.cn/L7JNC2"
        }
      ]
    }
  ]
}';
$ret = weixin::createMenu($menu);//创建菜单
if($ret){//创建成功
    echo 'create menu ok';
}else{//创建失败
    echo 'create menu fail';
}
?>
