<?php

require_once libfile('lib/weixin.class.php');
require_once libfile('model/SendMsgDB.php');

class UnderCover extends weixin
{
    private $sendmsg;

    public function processRequest($data) {
        sae_log(var_export($data,TRUE));
        $this->sendmsg = new SendMsgDB();
        $this->ProcessMessage($data);
    }

    /**
    *  判断用户消息及事件类型：
    */
    function ProcessMessage($data)
    {
    	$status = $this->getStatusByOpenid($data->FromUserName);
        $num = intval($data->Content);
        if ($this->isTextMsg()) {
            if($data->Content == '0')
            {
                $this->getPunish();
            }
            elseif ($data->Content == '1')
            {
                $this->createGame($data);
                $content = "正在创建谁是卧底,请输入游戏人数(4~13之间，不包括法官哦)";
                $this->outputText($content);
            }
            elseif($num >= 1000)
            {
                $this->joinRoom($num);
            }
            elseif($status=='creating' && $num <= 13 && $num >=4)
            {
                $this->createRoom($data);
            }
            elseif($status=='creating')
            {
                $content = "输入的数字必须是(4~13)之间的数字哦，请重新输入";
                $this->outputText($content);
            }
            elseif($status=='created' && $data->Content=='换')
            {
                $this->replaceWord($data->FromUserName);
            }
            elseif($status=='created' && $data->Content=='改')
            {
                $this->changeWord($data->FromUserName);
            }
            elseif($status == 'change')
            {   
                $this->processChangeWord($data);
            }
            else
            {
                $this->defaultMessage();
            }
        }
		else{
            $this->defaultMessage();
        }
    }

    function getPunish()
    {
        $content = "请输的同学摇骰子选择:\n\n";
        $mysql = new SaeMysql();

        $sql = "select max(Id) from bg_punish";
        $maxid = $mysql->getVar($sql);

        $a = array();
        $i = 1;
        while($i<=5) { 
            $id = fmod(rand(1,100000),$maxid) + 1;
            if(array_search($id, $a)==false)
            {
                $a[]=$id;
                $sql = "select item from bg_punish where Id = $id";
                $item = $mysql->getVar($sql);
                $content.=$i.". ".$item."\n\n";
                $i=$i+1;
            }
        }
        $this->outputText($content);
    }

    function defaultMessage()
    {
        $content = "-------开始提示----\n\n如果已经找好了人玩，请选一个人当法官，回复1创建游戏";
        $this->outputText($content);
    }

    function createGame($data)
    {
        $mysql = new SaeMysql();
        $sql = "select count(*) from bg_user where openid = '$data->FromUserName'";
        $isExist = $mysql->getVar($sql);
        if($isExist == 1)
        {
            $sql = " update bg_user set status = 'creating'"; 
        }
        else
        {
            $sql = " insert into bg_user(openid, status) values('$data->FromUserName', 'creating')";
        }
        $mysql->runSql($sql);

        if ($mysql->errno() != 0)
        {
            die("Error:".$mysql->errmsg());
        }
        $mysql->closeDb();
    }

    //加入房间，房间号至少是1000
    function joinRoom($roomid)
    {
        $status = $this->getStatusByRoomid($roomid);
        if($status == 'created')
        {
            $detail = $this->getWordInfo($roomid);
            $ids = explode(",", $detail['underCoverId']); 
            $allocate = $this->getAllocate($detail['Count']);

            if($detail['curUserId'] > $detail['Count'])
            {
                $content = "房间已满，请选择新的房间加入";
                $this->outputText($content);
            }

            $content = "房号：".$roomid;

            if(array_search($detail['curUserId'], $ids) == false)
            {
                $content .="\n\n词语:".$detail['word2'];
            }
            else
            {
                $content .="\n\n词语:".$detail['word1'];
            }
            $content.="\n\n你是:".$detail['curUserId']."号";
            $content.="\n\n配置：卧底".$allocate[0]."人，平民".$allocate[1]."人";
            $content.="\n\n输了要有惩罚哦，回复0查看大冒险惩罚";
            $this->outputText($content);
        }
        else
        {
            $content.="房间已过期，请法官重新建房";
            $this->outputText($content);
        }
    }

    function getWordInfo($roomid)
    {
        $mysql = new SaeMysql();
        $sql = "select * from bg_user where roomid ='$roomid'";
        $detail=$mysql->getLine($sql);

        $sql = "update bg_user set curUserId = curUserId +1 where roomid ='$roomid'";
        $mysql->runSql($sql);

        if ($mysql->errno() != 0)
        {
            die("Error:".$mysql->errmsg());
        }
        $mysql->closeDb();
        return $detail;
    }

    function getStatusByOpenid($openid)
    {
    	$mysql = new SaeMysql();
	    $sql = "select status from bg_user where openid ='$openid'";
	    $status=$mysql->getVar($sql);

	    if ($mysql->errno() != 0)
	    {
	        die("Error:".$mysql->errmsg());
	    }
	    $mysql->closeDb();
	    return $status;
    }

    function getStatusByRoomid($roomid)
    {
        $mysql = new SaeMysql();
        $sql = "select status from bg_user where roomid ='$roomid'";
        $status=$mysql->getVar($sql);

        if ($mysql->errno() != 0)
        {
            die("Error:".$mysql->errmsg());
        }
        $mysql->closeDb();
        return $status;
    }

    function createRoom($data)
    {
        $allocate = $this->getAllocate($data->Content);
        $UnderCover= $this->selectUnderCover($allocate[0], $data->Content);
        
        $words = $this->getWords();
        
        $this->saveWordInfo($words['word1'], $words['word2'], $UnderCover, $data->FromUserName, $data->Content);

	    $roomid = $this->getRoomId($data->FromUserName);

        $content="建房成功!".$this->detailInfo($roomid, $allocate[0], $allocate[1], $words['word1'], $words['word2'], $UnderCover);
    	$this->outputText($content);
    }

    function replaceWord($openid)
    {
        $info = $this->getRoomInfoByOpenid($openid);
        $allocate = $this->getAllocate($info['Count']);

        $UnderCover= $this->selectUnderCover($allocate[0], $info['Count']);
        $words = $this->getWords();
        $this->saveWordInfo($words['word1'], $words['word2'], $UnderCover, $data->FromUserName, $info['Count']);

        $this->refreshRoom($info['roomid']);
        $content="换词成功!".$this->detailInfo($info['roomid'], $allocate[0], $allocate[1], $words['word1'], $words['word2'], $UnderCover);
        $this->outputText($content);
    }

    function changeWord($openid)
    {
        $mysql = new SaeMysql();
        $sql = "update bg_user set status= 'change' where openid = '$openid'";
        $count = $mysql->runSql($sql);

        if ($mysql->errno() != 0)
        {
            die("Error:".$mysql->errmsg());
        }
        $mysql->closeDb();

        $content = "请输入卧底词和平民词,如：状元，冠军";
        $this->outputText($content);
    }

    function processChangeWord($data)
    {
        $words = explode(",", $data->Content);
        if(count($words) != 2)
        {
            $words = explode("，", $data->Content);
        }
        if(count($words) != 2)
        {
            $content = "请按照正确格式输入卧底词和平民词,如：状元，冠军";
            $this->outputText($content);
        }
        $info = $this->getRoomInfoByOpenid($data->FromUserName);
        $allocate = $this->getAllocate($info['Count']);

        $UnderCover= $this->selectUnderCover($allocate[0], $info['Count']);
        $this->saveWordInfo($words[0], $words[1], $UnderCover, $data->FromUserName, $info['Count']);

        $this->refreshRoom($info['roomid']);
        $content="改词成功!".$this->detailInfo($info['roomid'], $allocate[0], $allocate[1], $words[0], $words[1], $UnderCover);
        $this->outputText($content);
    }

    function getRoomInfoByOpenid($openid)
    {
        $mysql = new SaeMysql();
        $sql = "select * from bg_user where openid = '$openid'";
        $info = $mysql->getLine($sql);

        if ($mysql->errno() != 0)
        {
            die("Error:".$mysql->errmsg());
        }
        $mysql->closeDb();
        return $info;
    }

    function detailInfo($roomid, $udcount, $cilivincount, $word1, $word2, $undercover)
    {
        $content = "您是法官，请让参与游戏的玩家对我回复[$roomid]进入房间。\n";
        $content.="房  号：$roomid\n";
        $content.="配  置：卧底".$udcount."人，平民".$cilivincount."人\n";
        $content.="卧底词：$word1\n";
        $content.="平民词：$word2\n";
        $content.="卧  底：$undercover 号\n";
        $content.="回复[换]，换一组词，\n回复[改]，自己出题，\n回复[0],查看大冒险惩罚!(一局结束后，不必重建房，回复[换]直接换词)";
        return $content;
    }

    function saveWordInfo($word1, $word2, $UnderCover, $openid, $count)
    {
        $mysql = new SaeMysql();

        $sql = "update bg_user set status = 'created',Count = $count ,word1 = '$word1',word2 = '$word2',underCoverId = '$UnderCover',curUserId=1 where openid = '$openid'";
        
        $mysql->runSql($sql);

        if ($mysql->errno() != 0)
        {
            die("Error:".$mysql->errmsg());
        }
        $mysql->closeDb();
    }

    function refreshRoom($roomid)
    {
        $mysql = new SaeMysql();
        $sql = "update bg_rooms set CreatedTime = NOW()  where Id =$roomid";
        $mysql->runSql($sql);
        if ($mysql->errno() != 0)
        {
            die("Error:".$mysql->errmsg());
        }
        $mysql->closeDb();
    }

    function getWords()
    {
        $id = rand(1,100000);
        $mysql = new SaeMysql();
        $sql = "select max(Id) from bg_words";
        $maxid = $mysql->getVar($sql);

        $sql = "select * from bg_words where Id = mod($id,$maxid) + 1";
        $words=$mysql->getLine($sql);
        if ($mysql->errno() != 0)
        {
            die("Error:".$mysql->errmsg());
        }
        $mysql->closeDb();

        return $words;
    }

    function selectUnderCover($udcount, $count)
    {
        $a = array();
        for($i = 1; $i <= $count ; $i++)
        {
            array_push($a, $i);
        }

        shuffle($a);
        $str = "".$a[0];
        for($i = 1; $i < $udcount; $i++)
        {
            $str.=",".$a[$i];
        }
        return $str;
    }

    function getAllocate($num)
    {
    	$allocate = array();
    	switch($num)
    	{
    		case '4':
                $allocate[]=1;
    			$allocate[]=3;break;
    		case '5':
    			$allocate[]=1;
    			$allocate[]=4;break;
        	case '6':
        		$allocate[]=2;
    			$allocate[]=4;break;
        	case '7':
        		$allocate[]=2;
    			$allocate[]=5;break;
        	case '8':
        		$allocate[]=2;
    			$allocate[]=6;break;
        	case '9':
        		$allocate[]=2;
    			$allocate[]=7;break;
        	case '10':
        		$allocate[]=3;
    			$allocate[]=7;break;
        	case '11':
        		$allocate[]=3;
    			$allocate[]=8;break;
        	case '12':
        		$allocate[]=3;
    			$allocate[]=9;break;
        	case '13':
        		$allocate[]=3;
    			$allocate[]=10;break;
    	}
        return $allocate;
    }

    function getRoomId($openid)
    {
        $mysql = new SaeMysql();
    	$sql = "select Id from bg_rooms where Free = true";
	    $Id=$mysql->getVar($sql);

	    $sql = "update bg_rooms set CreatedTime = NOW() and Free = false where Id =$Id";
	    $mysql->runSql($sql);

	    $sql = "update bg_user set roomid = $Id where openid = '$openid' ";
		$mysql->runSql($sql);
	    if ($mysql->errno() != 0)
	    {
	        die("Error:".$mysql->errmsg());
	    }
	    $mysql->closeDb();

	    return $Id;
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
    function outputText($text) {
        // outputText 用来返回文本信息
        $xml = parent::outputText($text);
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