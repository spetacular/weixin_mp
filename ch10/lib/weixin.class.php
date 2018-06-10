<?php
include 'weixin.config.php';
class weixin extends wxcommon
{
    const MSG_TYPE_TEXT = 'text';
    const MSG_TYPE_IMAGE='image';
    const MSG_TYPE_LINK='link';
    const MSG_TYPE_LOCATION = 'location';
    const MSG_TYPE_EVENT='event';//事件推送只支持微信4.5版本，即将开放，敬请期待。
    const MSG_TYPE_VOICE = 'voice';
    const MSG_TYPE_VIDEO = 'video';
    
    const REPLY_TYPE_TEXT = 'text';
    const REPLY_TYPE_IMAGE='image';
    const REPLY_TYPE_VOICE = 'voice';
    const REPLY_TYPE_VIDEO = 'video';
    const REPLY_TYPE_MUSIC='music';
    const REPLY_TYPE_NEWS = 'news';
    
    const EVENT_TYPE_SUBSCRIBE='subscribe';
    const EVENT_TYPE_UNSUBSCRIBE='unsubscribe';
    const EVENT_TYPE_SCAN='SCAN';
    const EVENT_TYPE_LOCATION='LOCATION';
    const EVENT_TYPE_CLICK='CLICK';
    const EVENT_TYPE_VIEW='VIEW';

    /**
     * 判断是否是订阅事件
     * @return boolean
     */
    public function isSubscribeEvent()
    {
        return $this->_postData->Event == self::EVENT_TYPE_SUBSCRIBE &&
            $this->_postData->MsgType == "event";
    }
    /**
     * 判断是否是退订事件
     * @return boolean
     */
    public function isUnsubscribeEvent()
    {
        return $this->_postData->Event == self::EVENT_TYPE_UNSUBSCRIBE;
    }

    /**
     * 判断是否是未关注用户扫描二维码事件
     * @return boolean
     */
    public function isSubscribeScanEvent()
    {
         return $this->_postData->Event == self::EVENT_TYPE_SUBSCRIBE &&
                $this->_postData->EventKey != "";
    }

    /**
     * 判断是否是扫描二维码事件
     * @return boolean
     */
    public function isScanEvent()
    {
        return $this->_postData->Event == self::EVENT_TYPE_SCAN;
    }

    /**
     * 判断是否是上传地理位置事件
     * @return boolean
     */
    public function isLocaitonEvent()
    {
        return $this->_postData->Event == self::EVENT_TYPE_LOCATION;
    }
   
    /**
     * 判断是否是点击菜单拉取消息事件
     * @return boolean
     */
    public function isClickEvent()
    {
        return $this->_postData->Event == self::EVENT_TYPE_CLICK;
    }

    /**
     * 判断是否是点击菜单跳转事件
     * @return boolean
     */
    public function isViewEvent()
    {
        return $this->_postData->Event == self::EVENT_TYPE_VIEW;
    }


    /**
     * 接收到的post数据
     * @var object
     */
    private $_postData;
    private $_token;

    
    public function __construct()
    {
        
      if (! defined('TOKEN')){
            throw new Exception('Token is required');
      }
        
        if (method_exists($this, 'errorHandler'))
            set_error_handler(array($this, 'errorHandler'));
        
        if (method_exists($this, 'exceptionHandler'))
            set_exception_handler(array($this, 'exceptionHandler'));
        
        $this->_token = TOKEN;

        $this->parsePostRequestData();
    }
    
    public function run()
    {
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            if ($this->_postData && $this->beforeProcess($this->_postData) === true) {
                $this->processRequest($this->_postData);
                $this->afterProcess();
            }
            else
                throw new Exception('POST 数据不正确或者beforeProcess方法没有返回true');
        }
        else
            $this->sourceCheck();
        
        exit(0);
    }
    
    /**
     * 判断是否是文字信息
     * @return boolean
     */
    public function isTextMsg()
    {
        return $this->_postData->MsgType == self::MSG_TYPE_TEXT;
    }
    
    /**
     * 判断是否是位置信息
     * @return boolean
     */
    public function isLocationMsg()
    {
        return $this->_postData->MsgType == self::MSG_TYPE_LOCATION;
    }
    
    /**
     * 判断是否是图片
     * @return boolean
     */
    public function isImageMsg(){
        return $this->_postData->MsgType == self::MSG_TYPE_IMAGE;
    }

    /**
     * 判断是否是链接
     * @return boolean
     */
    public function isLinkMsg(){
        return $this->_postData->MsgType == self::MSG_TYPE_LINK;
    }
    
    /**
     * 判断是否是事件推送
     * @return boolean
     */
    public function isEventMsg(){
        return $this->_postData->MsgType == self::MSG_TYPE_EVENT;
    }
    
    /**
     * 判断是否是语音消息
     * @return boolean
     */
    public function isVoiceMsg(){
        return $this->_postData->MsgType == self::MSG_TYPE_VOICE;
    }
    
    /**
     * 判断是否是视频消息
     * @return boolean
     */
    public function isVideoMsg(){
        return $this->_postData->MsgType == self::MSG_TYPE_VIDEO;
    }

    /**
     * 生成向用户发送的文字信息
     * @param string $content
     * @return string xml字符串
     */
    public function outputText($content)
    {
        $textTpl = '<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[%s]]></MsgType>
                <Content><![CDATA[%s]]></Content>                
            </xml>';
    
        $text = sprintf($textTpl, $this->_postData->FromUserName, $this->_postData->ToUserName, time(), self::REPLY_TYPE_TEXT, $content);
        return $text;
    }
    
     /**
     * 生成向用户发送的图片信息
     * @param string $media_id
     * @return string xml字符串
     */
    public function outputImage($media_id)
    {
        $textTpl = '<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[%s]]></MsgType>
                <Image>
                    <MediaId><![CDATA[%s]]></MediaId>
                </Image>
            </xml>';
    
        $text = sprintf($textTpl, $this->_postData->FromUserName, $this->_postData->ToUserName, time(), self::REPLY_TYPE_VOICE, $media_id);
        return $text;
    }
    
    /**
     * 生成向用户发送的图文信息
     * @param arrry $posts 文章数组，每一个元素是一个文章数组，索引跟微信官方接口说明一致
     * @return string xml字符串
     */
    public function outputNews($posts = array())
    {
        $textTpl = '<xml>
             <ToUserName><![CDATA[%s]]></ToUserName>
             <FromUserName><![CDATA[%s]]></FromUserName>
             <CreateTime>%s</CreateTime>
             <MsgType><![CDATA[%s]]></MsgType>
             <ArticleCount>%d</ArticleCount>
             <Articles>%s</Articles>
         </xml>';
        
        $itemTpl = '<item>
             <Title><![CDATA[%s]]></Title>
             <Description>><![CDATA[%s]]></Description>>
             <PicUrl><![CDATA[%s]]></PicUrl>
             <Url><![CDATA[%s]]></Url>
         </item>';
        
        $items = '';
        foreach ((array)$posts as $p) {
            if (is_array($p))
                $items .= sprintf($itemTpl, $p['title'], $p['description'], $p['picurl'], $p['url']);
            else
                throw new Exception('$posts 数据结构错误');
        }
        
        $text = sprintf($textTpl, $this->_postData->FromUserName, $this->_postData->ToUserName, time(), self::REPLY_TYPE_NEWS,  count($posts), $items);
        return $text;
    }
    
    /**
     * 生成向用户发送的音乐信息
     * @param type $musicpost
     * @return type
     * @throws Exception
     */
    public function outputMusic($musicpost){
        $textTpl = '<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType> 
            <Music>%s</Music>
        </xml>';
        
        $musicTpl = '
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            <MusicUrl><![CDATA[%s]]></MusicUrl>
            <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
            ';
        $music = '';        
        if (is_array($musicpost)){
            $music .= sprintf($musicTpl, $musicpost['title'], $musicpost['description'], $musicpost['musicurl'], $musicpost['hdmusicurl']);
        }else{
            throw new Exception('$posts 数据结构错误');
        }
        
    
        $text = sprintf($textTpl, $this->_postData->FromUserName, $this->_postData->ToUserName, time(), self::REPLY_TYPE_MUSIC, $music);
        return $text;
         
    }

    /**
     * 生成向用户发送的语音信息
     * @param string $content
     * @return string xml字符串
     */
    public function outputVoice($media_id)
    {
        $textTpl = '<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[%s]]></MsgType>
                <Voice>
                    <MediaId><![CDATA[%s]]></MediaId>
                </Voice>
            </xml>';
    
        $text = sprintf($textTpl, $this->_postData->FromUserName, $this->_postData->ToUserName, time(), self::REPLY_TYPE_VOICE, $media_id);
        return $text;
    }
    
     /**
     * 生成向用户发送的视频信息
     * @param string $content
     * @return string xml字符串
     */
    public function outputVideo($videopost)
    {
        $textTpl = '<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[%s]]></MsgType>
                <Video>
                    <MediaId><![CDATA[%s]]></MediaId>
                    <Title><![CDATA[%s]]></Title>
                    <Description><![CDATA[%s]]></Description>
                </Video> 
            </xml>';
    
        $text = sprintf($textTpl, $this->_postData->FromUserName, $this->_postData->ToUserName, time(), self::REPLY_TYPE_VIDEO, $videopost['media_id'], $videopost['title'], $videopost['description']);
        return $text;
    }
    
    /**
     * 解析接收到的post数据
     * @return SimpleXMLElement
     */
    public function parsePostRequestData()
    {
        $rawData = $GLOBALS['HTTP_RAW_POST_DATA'];
        $data = simplexml_load_string($rawData, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($data !== false)
            $this->_postData = $data;
    
        return $data;
    }
    
    /**
     * 返回接收到的post数组
     * @return object
     */
    public function getPostData()
    {
        return $this->_postData;
    }
    
     /**
     * 创建菜单
     * @param {string|array} $menu 传入的菜单字符串或数组
     * @return {boolean} true|false
     */
    public static function createMenu($menu){
        $url=self::API_URL."/cgi-bin/menu/create?access_token=".self::getToken();
        $content=curl_post($url,$menu);
        $ret=json_decode($content,true);
        return wxcommon::getResult($ret);
  }
  
  
   /**
     * 查询菜单
     * @return {string} 菜单的json字符串
     */
  public static function getMenu(){  
        $url=self::API_URL."/cgi-bin/menu/get?access_token=".self::getToken();
        $content=  curl_get($url);
        $ret=json_decode($content,true);
        return wxcommon::getResult($ret);
  }
  

  /**
   * 删除菜单
   * @return {boolean} true|false
   */
  public static function deleteMenu(){
        $url=self::API_URL."/cgi-bin/menu/delete?access_token=".self::getToken();
        $content=curl_get($url);
        $ret=json_decode($content,true);
        return self::getResult($ret);  
  }
  /**
   * 获取ticket
   * @param {int} $scene_id
   * @param {int} $expire
   * @return {string} ticket
   */
  public static function getQrcodeTicket( $scene_id = 0, $expire = 0 ){
        $access_token = self::getToken();
        $scene_id   = intval($scene_id);
        $expire     = intval($expire);
        if( $expire ){ //临时二维码
            $data = array(
                'action_name' => 'QR_SCENE',
                'action_info' => array(
                    'scene' => array( 
                        'scene_id' => $scene_id
                    )
                ),
                'expire_seconds' => $expire,
           );           
        }else{ //永久二维码
            //永久二维码的scene_id只支持1--100000
            if( $scene_id < 1 || $scene_id > 100000 ){//
                $scene_id = 1;
            }
            $data = array(
                'action_name' => 'QR_LIMIT_SCENE',
                'action_info' => array(
                    'scene' => array( 
                        'scene_id' => $scene_id
                    )
                )
            );
        }
        $url = self::API_URL."/cgi-bin/qrcode/create?access_token=$access_token";
        
        
        $content = curl_post( $url, json_encode( $data ) );
        $ret = self::getResult(json_decode( $content, true ));        
        return  isset($ret['ticket'])?$ret['ticket']:false;
    }
   
    /**
     * 获取二维码图片url
     * @param {string} $ticket
     * @return {string} 图片url
     */
    public static function getQrcodeImgUrlByTicket( $ticket ){
        $ticket = urlencode( $ticket );
        return "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";
    }
    
    /**
     * 根据scene_id和expire获取二维码图片url
     * @param type $scene_id
     * @param type $expire
     * @return type
     */
    public static function getQrcodeImgUrl($scene_id, $expire) {
        $ticket = self::getQrcodeTicket($scene_id, $expire);
        return self::getQrcodeImgByTicket($ticket);
    }

    /**
     * 获取二维码图片内容
     * @param type $ticket
     * @return type
     */
    public static function getQrcodeImgByTicket( $ticket ){
        return curl_get( self::getQrcodeImgUrlByTicket( $ticket ) );
    }
    
    /**
     * 根据scene_id和expire获取二维码图片内容
     * @param type $scene_id
     * @param type $expire
     * @return type
     */
    public static function getQrcodeImg($scene_id, $expire) {
        $ticket = self::getQrcodeTicket($scene_id, $expire);
        return self::getQrcodeImgByTicket($ticket);
    }
    
    /**
     * 下载多媒体内容
     * @param {string} $media_id
     * @return type
     */
    public static function download( $media_id ){
        $access_token = self::getToken();
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$access_token}&media_id={$media_id}";        
        return curl_get( $url );
    }
    
    /**
     * 上传多媒体
     * @param {string} $type
     * @param {string} $file_path
     * @param {int} $mediaidOnly
     * @return null
     */
    public static function upload( $type, $file_path, $mediaidOnly = 1 ){
        $access_token = self::getToken();
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token={$access_token}&type={$type}";

        $ret = curl_post( $url, array( 'media' => "@$file_path" ) );
        $ret = json_decode( $ret, true );

        if( self::getResult( $ret) ){
            return $mediaidOnly ? $ret['media_id'] : $ret;
        }
        return null;
    }
    
    
    private static function _send( $toUser, $msgType, $data ){
        $access_token = self::getToken();
        $url = self::API_URL . "/cgi-bin/message/custom/send?access_token=$access_token";
        $json = json_encode(
            array(
                'touser'  => $toUser,
                'msgtype' => $msgType,
                $msgType     => $data
            )
        );

        $ret = curl_post($url, $json);
        return self::getResult( $ret );
    }
    
    /**
     * 发送文本消息
     * @param {string} $toUser
     * @param {string} $content
     * @return type
     */
    public static function sendText($toUser, $content) {
        return self::_send( $toUser, 'text', array( 'content' => $content ) );
    }
    
    /**
     * 发送图片消息
     * @param {string} $toUser
     * @param {string} $media_id
     * @return type
     */
    public static function sendImage( $toUser, $media_id ){
        return self::_send( $toUser, 'image', array( 'media_id' => $media_id ) );
    }
    
     /**
     * 发送语音消息
     * @param {string} $toUser
     * @param {string} $media_id
     * @return type
     */
    public static function sendVoice( $toUser, $media_id ){
        return self::_send( $toUser, 'voice', array( 'media_id' => $media_id ) );
    }
    

    /**
     * 发送视频消息
     * @param {string} $toUser
     * @param {string} $media_id
     * @param {string} $title
     * @param {string} $desc
     * @return type
     */
    public static function sendVideo( $toUser, $media_id, $title, $desc ){
        return self::_send( $toUser, 'video', array(
            'media_id'    => $media_id,
            'title'       => $title,
            'description' => $desc
        ) );
    }
    
    /**
     * 发送音乐消息
     * @param {string} $toUser
     * @param {string} $media_id
     * @return type
     */
    public static function sendMusic( $toUser, $url, $thumb_mid, $title, $desc = '', $hq_url = '' ){
        return self::_send( $toUser, 'music', array(
            'title'          => $title,
            'description'    => $desc || $title,
            'musicurl'       => $url,
            'thumb_media_id' => $thumb_mid,
            'hqmusicurl'     => $hq_url || $url
        ) );
    }
    
    /**
     * 发送图文消息
     * $articles = array(
     *      array(
     *          "title"=>"Happy Day",
                "description"=>"Is Really A Happy Day",
                "url"=>"URL",
                "picurl"=>"PIC_URL"
     *      ),
     * );
     * @param {string} $toUser
     * @param {string} $articles
     * @return type
     */
    public static function sendNews($toUser, $articles) {
        return self::_send( $toUser, 'news', array(
            'articles'          => $articles,            
        ) );
    }
    
    /**
     * 创建分组
     * @param {string} $name 分组名字（30个字符以内）
     * @return type
     */
    public static function createGroup( $name ){
        $access_token = self::getToken();
        $url = self::API_URL . "/cgi-bin/groups/create?access_token=$access_token";

        $ret = curl_post( $url, json_encode( array(
            'group' => array( 'name' => $name )
        ) ) );

        $ret = json_decode( $ret, true );
        return self::getResult( $ret ) ? $ret['group']['id'] : null;
    }
    
    /**
     * 修改分组名
     * @param type $gid 分组id
     * @param type $name 分组名字
     * @return type
     */
    public static function renameGroup( $gid, $name ){
        $access_token = self::getToken();
        $url = self::API_URL . "/cgi-bin/groups/update?access_token={$access_token}";

        $ret = curl_post( $url, json_encode( array(
            'group' => array(
                'id'   => $gid,
                'name' => $name
            )
        ) ) );

        $ret = json_decode( $ret, true );
        return self::getResult( $ret );
    }
    
    /**
     * 移动用户分组
     * @param type $openid 用户唯一标识符
     * @param type $gid 分组id
     * @return type
     */
    public static function moveUserById( $openid, $gid ){
        $access_token = self::getToken();
        $url = self::API_URL . "/cgi-bin/groups/members/update?access_token={$access_token}";
        
        $ret = curl_post(
            $url, 
            json_encode( 
                array(
                    'openid'     => $openid,
                    'to_groupid' => $gid
                )
            )
        );

        $ret = json_decode( $ret, true );
        return self::getResult( $ret );
    }

    /**
     * 查询所有分组
     * @return type
     */
    public static function getAllGroups(){
        $access_token = self::getToken();
        $url = self::API_URL . "/cgi-bin/groups/get?access_token={$access_token}";  
        $ret = json_decode( curl_get( $url ), true ); 
        return self::getResult( $ret ) ? $ret['groups'] : null;
    }

    /**
     * 查询用户所在分组
     * @param type $openid 用户唯一标识符
     * @return type
     */
    public static function getGroupidByOpenid( $openid ){
        $access_token = self::getToken();
        $url = self::API_URL. "/cgi-bin/groups/getid?access_token={$access_token}";

        $ret = curl_post( $url, json_encode( array(
            'openid' => $openid
        ) ) );

        $ret = json_decode( $ret, true );
        return self::getResult( $ret ) ? $ret['groupid'] : null;
    }
    
    /**
     * 获取关注者列表
     * @param type $next_id 第一个拉取的OPENID，不填默认从头开始拉取
     * @return type
     */
    public static function getUserList( $next_id = '' ){
        $access_token = self::getToken();
        $extend = '';
        if( !empty($next_id) ){
            $extend = "&next_openid=$next_id";
        }
        $url = self::API_URL . "/cgi-bin/user/get?access_token={$access_token}$extend";

        $ret = json_decode( 
                curl_get( $url ),
            true
        );

        return self::getResult( $ret ) 
            ? array(
                'total'   => $ret['total'],
                'list'    => $ret['data']['openid'],
                'next_id' => isset( $ret['next_openid'] ) ? $ret['next_openid'] : null
            ) 
            : null;
    }
    
    /**
     * 获取用户基本信息
     * @param type $openid 普通用户的标识，对当前公众号唯一
     * @param string $lang 返回国家地区语言版本，zh_CN 简体，zh_TW 繁体，en 英语
     * @return type
     */
    public static function getUserInfoById( $openid, $lang='zh_CN' ){
        if( !$lang ) $lang = 'zh_CN';
        $access_token = self::getToken();
        $url = self::API_URL . "/cgi-bin/user/info?access_token=$access_token&openid={$openid}&lang={$lang}";
        $content = curl_get( $url );
        $ret = json_decode($content, true );

        return self::getResult( $ret ) ? $content : null;
    }

    /**
     * 获取用户授权code url
     * @param type $scope 授权作用域:snsapi_base or snsapi_userinfo
     * @param type $state 重定向后会带上state参数，开发者可以填写a-zA-Z0-9的参数值
     * @return type
     */
    public static function createCodeUrl($scope,$state,$redirect_url){
        $open_url = 'https://open.weixin.qq.com';
        $redirect_url = urlencode($redirect_url);
        $url = $open_url.'/connect/oauth2/authorize?appid='.APPID.'&redirect_uri='.$redirect_url.'&response_type=code&scope='.$scope.'&state='.$state.'#wechat_redirect';
        return $url;
    }

    /**
     * 获取用户授权access_token
     * @param type $code 授权时获得code值
     * @return type
     */
    public static function getAuthToken($code){
        $url = self::API_URL.'/sns/oauth2/access_token?appid='.APPID.'&secret='.APPSECRET.'&code='.$code.'&grant_type=authorization_code';
        $content = curl_get( $url );
        $ret = json_decode($content, true );
        return self::getResult( $ret ) ? $ret : null;
    }

    /**
     * 刷新用户授权access_token
     * @param type $refresh_token 用户刷新access_token
     * @return type
     */
    public static function refershAuthToken($refresh_token){
        $url = self::API_URL.'/sns/oauth2/refresh_token?appid='.APPID.'&grant_type=refresh_token&refresh_token='.$refresh_token;
        $content = curl_get( $url );
        $ret = json_decode($content, true );

        return self::getResult( $ret ) ? $ret : null;
    }

    /**
     * 通过OAuth2.0获取用户信息
     * @param type $access_token 网页授权接口调用凭证
     * @param type $openid 用户的唯一标识
     * @param type $lang 返回国家地区语言版本，zh_CN 简体，zh_TW 繁体，en 英语
     * @return type
     */
    public static function getUserInfoByOAuth($access_token,$openid,$lang = 'zh_CN'){
        $url = self::API_URL.'/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang='.$lang;
        $content = curl_get( $url );
        $ret = json_decode($content, true );

        return self::getResult( $ret ) ? $ret : null;
    }
     
    
    protected function beforeProcess($postData)
    {
        return true;
    }
    
    protected function afterProcess()
    {
    }

    protected function processRequest($data)
    {
        throw new Exception('此方法必须被重写');
    }
    
    /**
     * 验证url来源是否证确
     * @return boolean
     */
    private function checkSignature()
    {
        $signature = $_GET['signature'];
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
    
        $params = array($this->_token, $timestamp, $nonce);
        sort($params, SORT_STRING);
        $sig = sha1(implode($params));
    
        return $sig == $signature;
    }
    
    private function sourceCheck()
    {
        if ($this->checkSignature()) {
            $echostr = $_GET['echostr'];
            echo $echostr;
        }else{
            throw new Exception('签名不正确');
        }    
        exit(0);
    }
}

/**
 * POST方式提交数据并获取服务器响应
 * @param {string} $url POST请求的url
 * @param {string|array} $post_string 需POST提交的数据
 * @return {string|boolen} 成功时返回服务器响应内容，失败则返回false
 */
function curl_post($url, $post_string){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}


/**
 * GET方式获取服务器响应
 * @param {string} $url
 * @return {string|boolen} 成功时返回服务器响应内容，失败则返回false
 */
function curl_get( $url ){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);;
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    if(!curl_exec($ch)){
        error_log( curl_error ( $ch ));
        $data = ''; 
    } else {
        $data = curl_multi_getcontent($ch);
    }
    curl_close($ch);
    return $data;
}

/**
*微信通用接口
*/
class wxcommon{
    const API_URL  = 'https://api.weixin.qq.com'; 
    private static $access_token;
    private static $expries_time = 0;

  /**
  * 用于获取AccessToken。如成功返回AccessToken，失败返回false
  */
  public static function getToken(){
   if(isset(self::$access_token) && time() < self::$expries_time){
         return self::$access_token;
    }
    $url = self::API_URL."/cgi-bin/token?grant_type=client_credential&appid=".APPID."&secret=".APPSECRET;
    $content=curl_get($url);
    $ret=json_decode($content,true);//{"access_token":"ACCESS_TOKEN","expires_in":7200}
      if(array_key_exists('errcode',$ret) && $ret['errcode'] != 0){
          return false;
      }else{
          self::$access_token = $ret['access_token'];
          self::$expries_time = time() + intval($ret['expires_in']);
          return self::$access_token;
      }
    }
    public static function getResult($ret) {
        if(!is_array($ret) || !array_key_exists('errcode',$ret)){          
            return $ret;
        }
        $errcode = intval($ret['errcode']);
        if(in_array($errcode, self::$ERRCODE_MAP)){
            if($errcode == 0){
                return true;
            }
            return array('errcode' => $errcode, 'errinfo' => self::$ERRCODE_MAP[$errcode]);
        }
        return array('errcode'=>'-2','errinfo'=>'未知错误');
    }
    static $ERRCODE_MAP = array(
        '-1' => '系统繁忙',
        '0' => '请求成功',
        '40001' => '获取access_token时AppSecret错误，或者access_token无效',
        '40002' => '不合法的凭证类型',
        '40003' => '不合法的OpenID',
        '40004' => '不合法的媒体文件类型',
        '40005' => '不合法的文件类型',
        '40006' => '不合法的文件大小',
        '40007' => '不合法的媒体文件id',
        '40008' => '不合法的消息类型',
        '40009' => '不合法的图片文件大小',
        '40010' => '不合法的语音文件大小',
        '40011' => '不合法的视频文件大小',
        '40012' => '不合法的缩略图文件大小',
        '40013' => '不合法的APPID',
        '40014' => '不合法的access_token',
        '40015' => '不合法的菜单类型',
        '40016' => '不合法的按钮个数',
        '40017' => '不合法的按钮个数',
        '40018' => '不合法的按钮名字长度',
        '40019' => '不合法的按钮KEY长度',
        '40020' => '不合法的按钮URL长度',
        '40021' => '不合法的菜单版本号',
        '40022' => '不合法的子菜单级数',
        '40023' => '不合法的子菜单按钮个数',
        '40024' => '不合法的子菜单按钮类型',
        '40025' => '不合法的子菜单按钮名字长度',
        '40026' => '不合法的子菜单按钮KEY长度',
        '40027' => '不合法的子菜单按钮URL长度',
        '40028' => '不合法的自定义菜单使用用户',
        '40029' => '不合法的oauth_code',
        '40030' => '不合法的refresh_token',
        '40031' => '不合法的openid列表',
        '40032' => '不合法的openid列表长度',
        '40033' => '不合法的请求字符，不能包含\uxxxx格式的字符',
        '40035' => '不合法的参数',
        '40038' => '不合法的请求格式',
        '40039' => '不合法的URL长度',
        '40050' => '不合法的分组id',
        '40051' => '分组名字不合法',
        '41001' => '缺少access_token参数',
        '41002' => '缺少appid参数',
        '41003' => '缺少refresh_token参数',
        '41004' => '缺少secret参数',
        '41005' => '缺少多媒体文件数据',
        '41006' => '缺少media_id参数',
        '41007' => '缺少子菜单数据',
        '41008' => '缺少oauth code',
        '41009' => '缺少openid',
        '42001' => 'access_token超时',
        '42002' => 'refresh_token超时',
        '42003' => 'oauth_code超时',
        '43001' => '需要GET请求',
        '43002' => '需要POST请求',
        '43003' => '需要HTTPS请求',
        '43004' => '需要接收者关注',
        '43005' => '需要好友关系',
        '44001' => '多媒体文件为空',
        '44002' => 'POST的数据包为空',
        '44003' => '图文消息内容为空',
        '44004' => '文本消息内容为空',
        '45001' => '多媒体文件大小超过限制',
        '45002' => '消息内容超过限制',
        '45003' => '标题字段超过限制',
        '45004' => '描述字段超过限制',
        '45005' => '链接字段超过限制',
        '45006' => '图片链接字段超过限制',
        '45007' => '语音播放时间超过限制',
        '45008' => '图文消息超过限制',
        '45009' => '接口调用超过限制',
        '45010' => '创建菜单个数超过限制',
        '45015' => '回复时间超过限制',
        '45016' => '系统分组，不允许修改',
        '45017' => '分组名字过长',
        '45018' => '分组数量超过上限',
        '46001' => '不存在媒体数据',
        '46002' => '不存在的菜单版本',
        '46003' => '不存在的菜单数据',
        '46004' => '不存在的用户',
        '47001' => '解析JSON/XML内容错误',
        '48001' => 'api功能未授权',
        '50001' => '用户未授权该api',
    );

}