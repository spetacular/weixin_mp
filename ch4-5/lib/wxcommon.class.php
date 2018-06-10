<?php

require 'weixin.config.php';
class wxcommon{
  public static function getToken(){
  $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".APPID."&secret=".APPSECRET;
  $content=file_get_contents($url);
  $ret=json_decode($content,true);
    if(array_key_exists('errcode',$ret)){
  	var_dump($ret);
        return false;
    }else{
    	return $ret;
    }
  }

}

wxcommon::getToken();
?>