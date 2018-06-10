<?php

require_once libfile('lib/weixin.class.php');
require_once libfile('model/SendMsgDB.php');

class Bookhotel extends weixin
{
    private $sendmsg;

    public function processRequest($data) {
        sae_log(var_export($data,TRUE));
        $this->sendmsg = new SendMsgDB();
        //$this->CreateNewMenu();
        $this->ProcessMessage($data);
    }

    /**
    *  判断用户消息及事件类型：
    */
    function ProcessMessage($data)
    {
	   // 如果用户发送的是文本数据
        if ($this->isTextMsg()) {
            $this->outputText("请点击菜单，完成您的需求哦");
        }
        // 如果用户发送的是地理位置数据
        elseif ($this->isLocationMsg()) {
            $this->saveLocation($data->Location_X,$data->Location_Y,$data->FromUserName,$data->Label);
            $list = $this->queryHotelByCoordinate($data->FromUserName);
            $this->sendHotelListNews($data,$list);
        }elseif ($this->isEventMsg()) {
             $this->checkEvent($data);
        }else{
            $this->outputText("请点击菜单，完成您的需求哦");
        }
    }

    private function checkEvent($data)
    {
        if($this->isSubscribeEvent())
        {
            $this->outputText("吖，欢迎来到纳吉酒店，注册用户第一次使用微信预定，在原有折扣基础之上再减20元,积分还可以抵扣房费，还等什么，来吧!!!么么哒~"); 
        }
        elseif ($this->isLocaitonEvent())
        {
            $this->saveLocation($data->Latitude,$data->Longitude,$data->FromUserName);
        }
        elseif ($this->isClickEvent())
        {
            if($data->EventKey == 'V2001')
            {
                $list = $this->queryHotelByCoordinate($data->FromUserName);
                $this->sendHotelListNews($data,$list);
            }
        }
    }

    private function saveUser($openid)
    {
        $mysql = new SaeMysql();
        $sql = "select count(*) from bh_User where OpenId='$openid'";
        $Exist=$mysql->getVar($sql);
        if($Exist == 0)
        {
            $sql = "insert into bh_User(OpenId) values('$openid')";
            $mysql->runSql($sql);
        }
        if ($mysql->errno() != 0)
        {
            die("Error:".$mysql->errmsg());
        }
        $mysql->closeDb();
    }
    /**
     * 保存地理位置
     */
    private function saveLocation($Latitude,$Longitude,$openid,$Label=null)
    {
        $this->saveUser($openid);

        $mysql = new SaeMysql();
        $sql = "update bh_User set Latitude = $Latitude, Longitude =$Longitude,";
        if(!is_null($Label))
        {
            $city = $this->extractCityFromLabel($Label);
            $sql.= "City='$city',";
        }
        $sql.= "LocTime =NOW() where `OpenId`='$openid'";
        $mysql->runSql($sql);
        if ($mysql->errno() != 0)
        {
            die("Error:".$mysql->errmsg());
        }
        $mysql->closeDb();
    }

    /**
     * 发送酒店列表图文消息
     */
    private function sendHotelListNews($data,$list)
    {
        $newslist = array();

        foreach ($list as $hotel) {
            $news = array(
                'title' => $hotel["Name"]."\n距离".number_format($hotel["Distance"]/1000,2)."公里",
                'description' => "",
                'picurl' => $hotel["PicUrl"],
                'url' => BASE_URL.'/BookHotel/hoteldetail.php?hotelid='.$hotel["Id"].'&openid='.$data->FromUserName
                );
            $newslist[] = $news;
        }
        // outputNews用来返回图文信息
        $this->outputNews($newslist);
    }


    /**
     * 根据地理位置查找附近酒店
     */
    private function queryHotelByCoordinate($openid)
    {
        $mysql = new SaeMysql();
        $sql = "select * from bh_User where OpenId ='$openid'";
        $userinfo = $mysql->getLine($sql);

        $diffseconds = strtotime(date("Y-m-d H:i:s"))-strtotime(($userinfo["LocTime"]));

        if($diffseconds > 300)
        {
            $this->outputText("无法获取您的位置，请点击右上角的小人图表，并允许\"提供位置信息\"。\n-----------\n您也可以手动发送位置来查询");
        }

        $mysql = new SaeMysql();
        $sql = "select * from bh_HotelInfo";
        if(!is_null($userinfo["City"]))
        {
            $city =$userinfo["City"];
            $sql.=" where City ='$city'";
        }

        $hotellist=$mysql->getData($sql);
        if ($mysql->errno() != 0)
        {
            die("Error:".$mysql->errmsg());
        }
        $mysql->closeDb();
        $list = array();
        foreach($hotellist as $hotel)
        {
            $dis = $this->getDistance($hotel["Latitude"],$hotel["Longitude"],$userinfo["Latitude"],$userinfo["Longitude"]);
            $_hotel = array(
                "Id"=>$hotel["Id"],
                "Name"=>$hotel["Name"],
                "Distance"=>$dis,
                "PicUrl"=>$hotel["PicUrl"]
                );

            $list[] = $_hotel;
        }

        //按照距离远近排序
        $distances = array();
        foreach ($list as $key => $row)
        {
            $distances[$key]  = $row['Distance'];
        }

        array_multisort($distances, SORT_ASC, $list);
        return $list;
    }

    /**
     * 删除订单
     */
    function deleteOrder($data)
    {
        $id= $data->Content;
        $mysql = new SaeMysql();

        $sql = "select RoomId from Order where Id =$id";
        $RoomId=$mysql->getVar($sql);

        $sql = "update Room set Free = Free+1 where Id =$RoomId";
        $mysql->runSql($sql);

        $sql = "delete from Order where Id =$id";
        $mysql->runSql($sql);

        if ($mysql->errno() != 0)
        {
            die("Error:".$mysql->errmsg());
        }
        $mysql->closeDb();

        $this->outputText('订单已删除');
    }

    /**
     * 查询订单
     */
    static function qrOrder($openid)
    {
        $data = array();
        $mysql = new SaeMysql();
        $sql = "select * from Order where Id ='$openid'";
        $orderlist=$mysql->getData($sql);
        foreach ($orderlist as $order){
            $item = array();
            $roomid = $order["RoomId"];
            $sql = "select * from Room where Id =$roomid";
            $room = $mysql->getLine($sql);

            $hotelid= $room["HotelId"];
            $sql = "select * from HotelInfo where Id =$hotelid";
            $hotelinfo=$mysql->getLine($sql);

            $item = array(
                "id"=>$order["Id"],
                "hotelname"=>$hotelinfo["Name"],
                "count"=>$order["Count"],
                "price"=>$order["Price"],
                "total"=>$order["Total"],
                "address"=>$hotelinfo["Address"],
                "telephone"=>$hotelinfo["Telephone"],
                "type"=>$room["Type"]
            );
            $data[]=$item;
        }

        if ($mysql->errno() != 0)
        {
            die("Error:".$mysql->errmsg());
        }
        $mysql->closeDb();
    }

    /**
     *  @desc 根据两点间的经纬度计算距离
     *  @param float $lat 纬度值
     *  @param float $lng 经度值
     */
    function getDistance($lat1, $lng1, $lat2, $lng2)
    {
         //地球半径近似值
         $earthRadius = 6367000; 

         $lat1 = ($lat1 * pi() ) / 180;
         $lng1 = ($lng1 * pi() ) / 180;

         $lat2 = ($lat2 * pi() ) / 180;
         $lng2 = ($lng2 * pi() ) / 180;

         $calcLongitude = $lng2 - $lng1;
         $calcLatitude = $lat2 - $lat1;
         $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);  $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
         $calculatedDistance = $earthRadius * $stepTwo;

         return round($calculatedDistance);
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
     * 从地理位置中提取出城市名
     */
    private function extractCityFromLabel($label)
    {
        $pos1 = strpos($label, "省");
        $pos2 = strpos($label, "市");
        return mb_substr($label, $pos1, $pos2-$pos1);
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
?>