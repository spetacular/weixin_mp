<?php
define('KEYFROM','huoyaxiaotu8');
define('APIKEY','232203214');

function translationHelper($msg)
{
  $youdaoUrl = 'http://fanyi.youdao.com/fanyiapi.do?keyfrom='.KEYFROM.'&key='.APIKEY.'&type=data&doctype=json&version=1.1&q='.$msg;
  $content=curl_get($youdaoUrl);
  $ret=json_decode($content,true);
  $result = '深呼吸，再试一次';
  if(array_key_exists('errorCode',$ret)){
       switch($ret['errorCode'])
       {
       		case 0:
       			$result = $ret['translation']['0'];
                break;
       		case 20:
       			$result = '亲,您的消息太长了哦';
       			break;
       		case 30:
                $result = '翻译助手也有不会翻译的时候哦';
                break;
            case 40:
                $result = '本助手是有原则的,不翻译鸟语,哼';
                break;
            case 50:
                $result = '不要胡言乱语，说人话';
                break;
       }
  }
  return $result;
}
?>