<?php
/**
 * 微信消息响应
 * 针对用户发送的不同类型的数据，返回不同的响应信息
 */
require libfile('lib/weixin.class.php');
require libfile('model/SendMsgDB.php');

class DefaultWeixin extends weixin {

    private $sendmsg;//消息处理模型
    private $process_time;

    public function processRequest($data) {
        $time_start = microtime_float();//开始计时
        $this->sendmsg = new SendMsgDB();//消息处理模型实例化
        //判断并处理文本消息
        if ($this->isTextMsg()) {
            //$input为用户输入的内容
            $input = $data->Content;            
            switch ($input) {
                default:
                    $this->text($input);
                    break;
            }
        }
        //判断并处理地理位置消息
        else if ($this->isLocationMsg()) {
            $userLocData = $data;
            $userLocData->addChild('Latitude', $data->Location_X);
            $userLocData->addChild('Longitude', $data->Location_Y);
            $this->sendmsg->saveUserLocation($data);
            $this->goroute($data);
        } 
        //判断并处理图片消息
        else if ($this->isImageMsg()) {

        } 
        //判断并处理链接消息
        else if ($this->isLinkMsg()) {

        } 
        //判断并处理事件推送
        else if ($this->isEventMsg()) {
            switch ($data->Event) {
                case 'subscribe':
                    if (!empty($data->EventKey)) {
                        $this->showqr($data, 0);
                    } else {
                        $this->text('您好，欢迎关注兔子饭庄。');
                    }
                    break;
                case 'unsubscribe':
                    $this->text('用户取消订阅');
                    break;
                case 'VIEW':
                    break;
                case 'CLICK':
                    $this->click($data);
                    break;
                case 'SCAN':
                    $this->showqr($data, 1);
                    break;
                case 'LOCATION':
                    $this->sendmsg->saveUserLocation($data);
                    break;
                default :
                    
            }
        } else if ($this->isVoiceMsg()) {
            //处理语音信息
        } else {
            //处理其它消息
        }
         $time_end = microtime_float();//计时结束
         $this->process_time = $time_end - $time_start;//记录处理时间
    }

    /**
     * 分类处理点击事件
     * @param type $data 微信消息体
     */
    private function click($data) {
        $eventKey = $data->EventKey;
        switch ($eventKey) {
            case 'CLICK_RESERVE':
                $this->goreserve($data);
                break;
            case 'CLICK_ORDER':
                break;
            case 'CLICK_ROUTE':
                $userLoc = $this->sendmsg->getUserLocation($data);
                sae_log(var_export($userLoc, TRUE));
                if (empty($userLoc)) {
                    $this->text('【兔子饭庄路线导航】
试试发送你的位置，即可为您指引到各个分店线路:
1. 点击左下方“小键盘”
2. 点击打字窗口旁边的“+号键”
3. 选择“位置”图标
4. 完成定位后点击右上角“发送”');
                } else {
                    $this->goroute($data);
                }
                break;
            default :                
                break;
        }
    }

    /**
     * 显示路线导航页面
     * @param type $data 微信消息体
     */
    private function goroute($data) {
        $text = '最近的兔子饭庄路线';
        $posts = array(
            array(
                'title' => '路线导航',
                'description' => $text,
                'picurl' => 'http://mmbiz.qpic.cn/mmbiz/XWia2Xj7RZ8nxGcFl47qJQjsm1iaqf3SquP9ucVPEoCCBFAibdicKtaCmbEZCLJcE5ib6gEKSZicjHSlySJclicrgicPHQ/0',
                'url' => 'http://devweixin.sinaapp.com/diner/route.php?user=' . $data->FromUserName,
            )
        );
        $this->outputNews($posts);
    }

    /**
     * 显示预约页面
     * @param type $data 微信消息体
     */
    private function goreserve($data) {
        $ret = $this->sendmsg->getRecentReserve($data);
        if (!empty($ret) && $ret['dinertime'] > time() - 1800) {
            $posts = array(
                array(
                    'title' => '您最近的预约',
                    'description' => '时间：' . date('Y-m-d H:i', $ret['dinertime']) . '，人数：' . $ret['num'],
                    'picurl' => 'http://mmbiz.qpic.cn/mmbiz/XWia2Xj7RZ8nxGcFl47qJQjsm1iaqf3SquWeL0BzAficTdkxo2oeV9PvVqvcUUj14pE4oq5fAZx2s4TGsZIalZFCg/0',
                    'url' => 'http://devweixin.sinaapp.com/diner/myreserve.php?user=' . $data->FromUserName,
                )
            );
        } else {
            $posts = array(
                array(
                    'title' => '兔子饭庄精美美食等着你哟',
                    'description' => '现在预约吧',
                    'picurl' => 'http://mmbiz.qpic.cn/mmbiz/XWia2Xj7RZ8nxGcFl47qJQjsm1iaqf3SquWeL0BzAficTdkxo2oeV9PvVqvcUUj14pE4oq5fAZx2s4TGsZIalZFCg/0',
                    'url' => 'http://devweixin.sinaapp.com/diner/reserve.php?user=' . $data->FromUserName,
                )
            );
        }

        $this->outputNews($posts);
    }

    /**
     * 显示优惠券页面
     * @param type $data $data->EventKey的值与type有关。当type=0时为qrscene_123123，type=1时为123123
     * @param type $type 0,未关注 1 关注
     */
    private function showqr($data, $type = 0) {
        if ($type == 0) {
            $sceneid = substr($data->EventKey, 8);
        } else if ($type == 1) {
            $sceneid = $data->EventKey;
        }
        $text = '使用优惠券';
        $posts = array(
            array(
                'title' => '使用优惠券',
                'description' => $text,
                'picurl' => 'http://mmsns.qpic.cn/mmsns/XWia2Xj7RZ8mhQaESostBicFaX2HjVBbJYKKCBk9PkuicKrSZdfNL7XAw/0',
                'url' => 'http://devweixin.sinaapp.com/diner/showqr.php?sceneid=' . $sceneid . '&user=' . $data->FromUserName,
            )
        );
        $this->outputNews($posts);
    }

    /**
     * 图文消息封装
     * @param type $posts
     */
    function outputNews($posts = array()) {
        $xml = parent::outputNews($posts);
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
     * 预处理，一般会包括postData的备份等
     * @return boolean
     */
    protected function beforeProcess($postData) {
        //sae_log("处理之前");
        sae_log(var_export($postData,true));
        return true;
    }

    /**
     * 后处理，一般会包括数据上报
     * @return boolean
     */
    protected function afterProcess() {
        //sae_log("处理之后");
        sae_log("消耗时间" . $this->process_time);        
        return true;
    }

    public function errorHandler($errno, $error, $file = '', $line = 0) {
        $msg = sprintf('%s - %s - %s - %s', $errno, $error, $file, $line);
        sae_log($msg);
    }

    public function errorException(Exception $e) {
        $msg = sprintf('%s - %s - %s - %s', $e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
        sae_log($msg);
    }

}