<?php
 $access_token = 'YourAccessToken';
 $touser = "OPENID";
 $template_id = "TEMPLATEID";
 $data = '{
           "touser":'.$touser.',
           "template_id":'.$template_id.',
           "url":"https://www.weixinbook.net/",
           "topcolor":"#FF0000",
           "data":{
                   "name": {
                       "value":"闫小坤",
                       "color":"#173177"
                   },
                   "time":{
                       "value":"2015年5月4日",
                       "color":"#173177"
                   }                   
           }
       }';

 $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token;
 $retjson = curl_post($url, $data);
 $ret = json_decode($retjson,true);
 if($ret['errcode'] == 0){
     echo "Push Template Message OK";
 }else{
     echo "Push Template Message Fail\n";
     var_dump($retjson);
 }
function curl_post($url, $post_string){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
?>
