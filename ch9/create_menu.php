<?php
require "lib/weixin.class.php";//引入微信类文件
$codeurl = weixin::createCodeUrl('snsapi_base', 'lottery','http://'.$_SERVER['SERVER_NAME'].'/choujiang.php');
//$menu变量为存放菜单项的json字符串
$menu = 
'{
  "button": [
    {
      "type": "view",
      "name": "抽奖",
      "url": "'.$codeurl.'"
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
