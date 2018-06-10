<?php

require libfile('lib/weixin.class.php');
require libfile('model/SendMsgDB.php');
require libfile('lib/xiaodou.func.php');

class DefaultWeixin extends weixin {
    private $sendmsg;

    public function processRequest($data) {
        // $input即为用户输入的内容
        $input = $data->Content;
         sae_log(var_export($data,TRUE));
        $this->sendmsg = new SendMsgDB();
        //sae_log("用户输入".$input);
        // 如果用户发送的是文本数据
        if ($this->isTextMsg()) {
             $time_start = microtime_float();
            $this->sendmsg->saveText($data);
            switch ($input) {
                case 'subscribe'://新用户订阅
                    $this->welcome();
                    break;
                case 'Hello2BizUser':
                    $this->welcome();
                    break;
                case '福利'://福利
                    $this->fulinews();
                    break;
                case '一生所爱':
                    $this->yishengmusic();
                    break;
              case '抽奖':
                   $this->choujiang($input,$data);
                    break;
              //case '笑话':
              //$this->xiaohua();
              // break;
                default:
                //$this->xiaohua();
                //$text=precessxiaodou($input);
                //$this->text($text);
                   $this->text($input);
                break;
                   
            }
                //

                $time_end = microtime_float();
                $time = $time_end - $time_start;
                sae_log("消耗时间" . $time);          
        }
        // 如果用户发送的是地理位置数据
        elseif ($this->isLocationMsg()) {
            $this->sendmsg->saveLocation($data);
            $this->text(var_export($data,TRUE));
            //$this->fulinews();
        } elseif ($this->isImageMsg()) {
            $this->sendmsg->saveImage($data);
            $this->tuku();
        } elseif ($this->isLinkMsg()) {
            $this->sendmsg->saveLink($data);
            $this->fulinews();
        } elseif ($this->isEventMsg()) {
            //var_dump($data);
            //sae_log(var_export($data,TRUE));
             $this->text(var_export($data,TRUE));
        }elseif ($this->isVoiceMsg()) {
            //var_dump($data);
            //sae_log(var_export($data,TRUE));
            //$this->text(var_export($data,TRUE)); 
            $this->getMedia($data);
        } else {
            
        }
    }
private function getMedia($data) {
    $media_id = $data->MediaId;
    $format = $data->Format;
    $destFileName = $media_id.'.'.$format;
    $recognition = $data->Recognition;
    $content = weixin::download($media_id);
    $s = new SaeStorage();
    $musicurl = $s->write ('huoyaxiaotu', $destFileName,$content);
    sae_log(var_export($musicurl,TRUE));
    $hdmusicurl = $musicurl;
    $thumb_media_id = 'http://mp.weixin.qq.com/wiki/skins/common/images/weixin_wiki_logo.png';
    //$this->text($media_id.$recognition);
    $this->music($recognition, '来自微信语音识别的结果', $musicurl, $hdmusicurl, $thumb_media_id);
}
	
    

    /**
     * 返回福利图文回复
     */
    private function fulinews() {
        $text = 'QQ黄钻、蓝钻、红钻、绿钻或10Q币任选其一';
        $posts = array(
            array(
                'title' => '福利来了',
                'description' => $text,
                'picurl' => 'http://mmsns.qpic.cn/mmsns/XWia2Xj7RZ8mhQaESostBicFaX2HjVBbJYKKCBk9PkuicKrSZdfNL7XAw/0',
                'url' => 'http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5MDE4Njg2MQ==&appmsgid=10000009&itemidx=1#wechat_redirect',
            )
        );
        // outputNews用来返回图文信息
        $xml = $this->outputNews($posts);
        //sae_log($xml);
        header('Content-Type: application/xml');
        echo $xml;
    }
    
    /**
     * 返回图库
     */
    private function tuku() {
        $text = '投我以桃，报之以李';
        $posts = array(
            array(
                'title' => '福利来了',
                'description' => $text,
                'picurl' => 'http://mmsns.qpic.cn/mmsns/XWia2Xj7RZ8mhQaESostBicFaX2HjVBbJYKKCBk9PkuicKrSZdfNL7XAw/0',
                'url' => 'http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5MDE4Njg2MQ==&appmsgid=10000009&itemidx=1#wechat_redirect',
            )
        );
        // outputNews用来返回图文信息
        $xml = $this->outputNews($posts);
        //sae_log($xml);
        header('Content-Type: application/xml');
        echo $xml;
    }


    /**
     * 返回文本回复
     */
    private function text($text) {
        // outputText 用来返回文本信息
        $xml = $this->outputText($text);
        header('Content-Type: application/xml');
        echo $xml;
    }

    /**
     * 返回笑话
     */
    private function xiaohua() {
        $text = "你好，亲爱的朋友，我可能不在电脑旁。先看个笑话吧。有个小姑娘穿了一件白色大衣在等车，一个熊孩子把巧克力雪糕整个拍她身上了，孩子他妈说对不起孩子很皮，姑娘蹲下身和蔼的说：小朋友，我们拉钩，以后谁在大马路上瞎闹谁就死全家好不好？孩子他妈吓尿了~";
        // outputText 用来返回文本信息
        $xml = $this->outputText($text);
        header('Content-Type: application/xml');
        echo $xml;
    }

    /**
     * 返回订阅信息
     */
    private function welcome() {
        $text = "亲爱的朋友，欢迎关注兔子。回复“福利”看看兔子的10元Q币小礼吧。";
        // outputText 用来返回文本信息
        $xml = $this->outputText($text);
        header('Content-Type: application/xml');
        echo $xml;
    }

    private function music($title, $description, $musicurl, $hdmusicurl, $thumb_media_id) {
        $music = array(
            'title' => $title,
            'description' => $description,
            'musicurl' => $musicurl,
            'hdmusicurl' =>  $hdmusicurl,
            'thumb_media_id'=> $thumb_media_id,
        );
        $xml = $this->outputMusic($music);
        //sae_log($xml);
        header('Content-Type: application/xml');
        echo $xml;
    }
    
    private function music2() {
        $music = array(
            'title' => '在春天里',
            'description' => '在春天里-汪峰',
            'musicurl' => 'http://7xnmen.com1.z0.glb.clouddn.com/musicinspring.wma',
            'hdmusicurl' => 'http://7xnmen.com1.z0.glb.clouddn.com/musicinspring.mp3'
        );
        $xml = $this->outputMusic($music);
        //sae_log($xml);
        header('Content-Type: application/xml');
        echo $xml;
    }

    

    /**
     * 预处理，包括将请求保存入数据库
     * @return boolean
     */
    protected function beforeProcess($postData) {
        //sae_log("处理之前");
        //存入数据库

        return true;
    }

    protected function afterProcess() {
        //sae_log("处理之后");
    }

    public function errorHandler($errno, $error, $file = '', $line = 0) {
        $msg = sprintf('%s - %s - %s - %s', $errno, $error, $file, $line);
        sae_log($msg);
    }

    public function errorException(Exception $e) {
        $msg = sprintf('%s - %s - %s - %s', $e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
        sae_log($msg);
    }

    private function saveRequest($request) {
        
    }

}




