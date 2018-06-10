<?php
function getxiaodou($msg)
{
  //$url = 'http://xiao.douqq.com/bot/chata.php?chat='.$msg;
$url='http://xiao.douqq.com/bot/chat.php';

$f = new SaeFetchurl();
$f->setMethod("post");
$f->setPostData(
    array(
      "chat"=> $msg
    )
);
$f->setHeader("User-Agent","Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17");
$f->setHeader("Referer","http://xiao.douqq.com/");
$data = $f->fetch($url);
//$data = file_get_contents($url);
return $data;
}
function precessxiaodou($msg){
$data=getxiaodou($msg);
if(strstr($data,"抱歉，小豆还不能理解")==true){
  $data="难倒兔子了~其实我的强项是讲笑话。";
  $data.=getxiaodou("笑话");
}
if(strstr($data,"Unauthorized access!")==true){
  $data="小兔抽风了！先看个笑话吧亲。";
  $data.=getxiaodou("笑话");
}

//去掉所有的小豆、豆豆等，换成兔子
$xiaodou=array("小豆","豆豆","傻样",'<br />',"<br>","QQ个性网","http://www.xiugexing.com","94113786","xiaodouqqcom","贱豆");
$xiaotu=array("小兔","兔兔","可爱","\n","\n","","","2292950616","huoyaxiaotu","贱兔");

$data = str_replace($xiaodou,$xiaotu,$data);

$zanghua=array("sb");
$haohua=array("好人");
$data = str_replace($zanghua,$haohua,$data);
return $data;
}
?>