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
}




