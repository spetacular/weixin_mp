<?php
function curl_post($remote_server, $post_string){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $remote_server);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($ch);
curl_close($ch);
return $data;


}
require 'weixin.config.php';
class wxmenu{
  public static function createMenu($menu){
  $url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".ACCESS_TOKEN;
  $content=curl_post($url,$menu);
  var_dump($content);
    /*
  $content=file_get_contents($url);
  $ret=json_decode($content,true);
    if(array_key_exists('errcode',$ret)){
  	var_dump($ret);
        return false;
    }else{
    	return $ret;
    }
    
	*/
  }
  
  public static function getMenu(){
  
  $url="https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".ACCESS_TOKEN;
  $content=file_get_contents($url);
  $ret=json_decode($content,true);
    if(array_key_exists('errcode',$ret)){
    var_dump($ret);
        return false;
    }else{
    	return $ret;
    }
  }
  
  public static function deleteMenu(){
  $url="https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".ACCESS_TOKEN;
  $content=file_get_contents($url);
  $ret=json_decode($content,true);
  if($ret['errcode']==0){

        return true;
    }else{
      var_dump($ret);
    	return false;
    }
  
  }

}

$menu=array(
"button"=>array(
	"type"=>"click",
          "name"=>"今日歌曲",
          "key"=>"V1001_TODAY_MUSIC"
	)

);

//wxmenu::createMenu($menu);
//wxmenu::getMenu();
wxmenu::deleteMenu();
?>