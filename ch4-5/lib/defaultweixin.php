<?php

require libfile('lib/weixin.class.php');
require libfile('model/SendMsgDB.php');
require libfile('lib/xiaodou.func.php');
require libfile('lib/translation.func.php');

class DefaultWeixin extends weixin {
    private $sendmsg;

    public function processRequest($data) {
        sae_log(var_export($data,TRUE));
        $this->sendmsg = new SendMsgDB();
        
        if ($this->isTextMsg()) {
            $this->processText($data->Content);
        }
        elseif($this->isVoiceMsg()) {
            $this->processText($data->Recognition);
        }
    }
private function processText($data)
{
    $firstTwoWord = mb_substr($data,0,2,"UTF-8");
    $len = mb_strlen($data,'UTF-8');
    if($firstTwoWord == '翻译' && $len > 2)
    {
        $result = translationHelper(mb_substr($data,2,$len,'UTF-8'));
        $this->outputText($result);
    }
    else
    {
        $this->outputText($data);
    }
}
private function checkInputMsg($data)
{
    // 如果用户发送的是文本数据
        if ($this->isTextMsg()) {
            //$this->text("您发送的是文本消息，消息内容是:");
            $this->outputText("您发送的是文本消息，消息内容是:".$data->Content); 
        }
        // 如果用户发送的是地理位置数据
        elseif ($this->isLocationMsg()) {
            $this->sendmsg->saveLocation($data);
            $this->outputText("您发送的是位置消息,维度为: ".$data->Location_X."\n经度为: ".$data->Location_Y."\n缩放级别为: ".$data->Scale."\n位置为: ".$data->Label);
            //$this->fulinews();
        } elseif ($this->isImageMsg()) {
            $this->sendmsg->saveImage($data);
            $this->outputText("您发送的是图片消息,图片链接是: ".$data->PicUrl."\n媒体ID是: ".$data->MediaId);
        } elseif ($this->isLinkMsg()) {
            $this->sendmsg->saveLink($data);
           $this->outputText("您发送的是链接消息,标题是: ".$data->Title."\n摘要是: ".$data->Description."\n链接是: ".$data->Url);
        } elseif ($this->isEventMsg()) {
            //var_dump($data);
            //sae_log(var_export($data,TRUE));
             $this->checkEvent($data);
        }elseif ($this->isVoiceMsg()) {
            //var_dump($data);
            //sae_log(var_export($data,TRUE));
            //$this->text(var_export($data,TRUE)); 
            $this->outputText("您发送的是语音消息,媒体ID是: ".$data->MediaId."\n语音格式是: ".$data->Format);
        } elseif ($this->isVideoMsg()) {
            $this->outputText("您发送的是视频消息,媒体ID是: ".$data->MediaId."\n缩略图ID是: ".$data->ThumbMediaId);
        }
}

private function checkEvent($data)
{
    if($this->isSubscribeEvent())
    {
        $this->outputText("订阅事件，订阅用户是:".$data->FromUserName); 
    }
    elseif ($this->isSubscribeScanEvent())
    {
        $this->outputText("未订阅用户扫描二维码事件，Key值是:".$data->EventKey."\nTicket值是:".$data->Ticket); 
    }
    elseif ($this->isScanEvent())
    {
        $this->outputText("已订阅用户扫描二维码事件，Key值是:".$data->EventKey."\nTicket值是:".$data->Ticket."\nCreateTime是:".$data->CreateTime);
    }
    elseif ($this->isLocaitonEvent())
    {
        $this->outputText("上传地理位置事件，纬度是:".$data->Latitude."\n经度是:".$data->Longitude."\n精度是:".$data->Precision); 
    }
    elseif ($this->isClickEvent())
    {
        $this->outputText("点击菜单拉取消息事件,Key值是:".$data->EventKey); 
    }
    elseif ($this->isViewEvent())
    {
        $this->outputText("点击菜单跳转事件,Key值是:".$data->EventKey); 
    }
    else
    {
        $this->outputText("未知事件"); 
    }
}


private function testMusic()
{
    $music = array(
            'title' => '在春天里',
            'description' => '在春天里-汪峰',
            'musicurl' => 'http://7xnmen.com1.z0.glb.clouddn.com/musicinspring.wma',
            'hdmusicurl' => 'http://7xnmen.com1.z0.glb.clouddn.com/musicinspring.mp3'
        );
     $this->outputMusic($music);
}

private function testImage()
{
    $this->outputImage('yj-cwuy7NMwdjRJKKBYVlxEPefnC-QnN93zVTT-0d_DdLXVvdhguzhyDRjzeV8lm');
}

private function testVoice()
{
    $this->outputVoice('vbSe8LsLWyxDI-NbRZAO8Ura36jo-b_p4Na-aiz-U0o21vLqgBiIWOqrUO5joqN1');
}

private function testVideo()
{   

    $video = array(
            'media_id' => 'Dx09rTLDC3nZKTSmfWMHXKxNxb01oTbgqn3yHtLPgTUmDJxSSamGW7uqNGRZp3of',
            'title' => '独墅湖',
            'description' => '生活在独墅湖'
        );
     $this->outputVideo($video);
}

private function testNews()
{
        $posts = array(
            array(
                'title' => '世界因你而不同',
                'description' => '最大化你的影响力，这就是你一生的意义。',
                'picurl' => 'http://www.hers.cn/uploadfile/2011/1006/20111006022157183.jpg',
                'url' => 'http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5MDE4Njg2MQ==&appmsgid=10000072&itemidx=1&sign=bea6deb75836dbe1249dcf394e8f3c21#wechat_redirect',
            ),
            array(
                'title' => '平横',
                'description' => '心要多静才能保持如此的平衡',
                'picurl' => 'http://news.shangdu.com/304/2009/08/20/images/501_20090820_911.jpg',
                'url' => 'http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5MDE4Njg2MQ==&appmsgid=10000066&itemidx=1#wechat_redirect',
            )
        );
        // outputNews用来返回图文信息
        $xml = $this->outputNews($posts);
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




