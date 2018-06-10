<?php
require_once '../lib/common.func.php';
require_once '../lib/weixin.class.php';
require_once '../model/SendMsgDB.php';

$token = weixin::getAuthToken($_GET['code']);
$openid = $token["openid"];
$data = array();
$mysql = new SaeMysql();
$sql = "select * from bh_Order where OpenId ='$openid' and Finished=false";
$orderlist = $mysql->getData($sql);
foreach ($orderlist as $order) {
    $item = array();
    $roomid = $order["RoomId"];
    $sql = "select * from bh_Room where Id =$roomid";
    $room = $mysql->getLine($sql);

    $hotelid = $room["HotelId"];
    $sql = "select * from bh_HotelInfo where Id =$hotelid";
    $hotelinfo = $mysql->getLine($sql);

    $item = array(
        "id" => $order["Id"],
        "hotelname" => $hotelinfo["Name"],
        "date" => $order["Time"],
        "count" => $order["Count"],
        "price" => $order["Price"],
        "total" => $order["Total"],
        "address" => $hotelinfo["Address"],
        "telephone" => $hotelinfo["Telephone"],
        "type" => $room["Type"]
    );
    $data[] = $item;
}

if ($mysql->errno() != 0) {
    die("Error:" . $mysql->errmsg());
}
$mysql->closeDb();
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport"
          content="width=screen-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <link href="../CSS/styles.css" type="text/css" rel="stylesheet"/>
    <script src="../Js/jquery.ui.core.js"></script>
    <script src="../Js/jquery.ui.widget.js"></script>
    <script src="../Js/jquery-1.10.2.js"></script>
    <script src="../Js/jquery-ui.js"></script>
    <link rel="stylesheet" href="../CSS/jquery-ui.css">

    <title>我的订单</title>

</head>
<body>
<form action='DeleteOrder.php' method='post' id='myform'>
    <h2 class="detail_title">订单列表</h2>
    <?php
    foreach ($data as $item) {
        echo '<div class="d-left-module mt15"><div class="inner m-hotel-overview" id="jxDescTab">';
        echo '<h2 class="facility-title"><span class="fr inform-error">';
        echo "<a class='btn_buy' onClick=\"$('#orderid').val(" . $item["id"] . ");$('#myform').submit();\">退订</a></span>";
        echo '订单号：' . $item["id"] . '</h2>';
        echo '<div class="hotel-introduce" id="descContent"><div class="base-info bordertop clrfix">';
        echo '<dl class="inform-list"><dt>酒   店：</dt><dd><cite>' . $item["hotelname"] . '</cite></dd></dl>';
        echo '<dl class="inform-list"><dt>房   型：</dt><dd><cite>' . $item["type"] . '</cite></dd></dl>';
        echo '<dl class="inform-list"><dt>入住日期：</dt><dd><cite>' . $item["date"] . '</cite></dd></dl>';
        echo '<dl class="inform-list"><dt>价   格：</dt><dd><cite>' . $item["price"] . '</cite></dd></dl>';
        echo '<dl class="inform-list"><dt>数   量：</dt><dd><cite>' . $item["count"] . '</cite></dd></dl>';
        echo '<dl class="inform-list"><dt>总   额：</dt><dd><cite>' . $item["total"] . '</cite></dd></dl>';
        echo '<dl class="inform-list"><dt>电   话：</dt><dd><cite><a href="tel:' . $item["telephone"] . '">' . $item["telephone"] . '</a></cite></dd></dl>';
        echo '<dl class="inform-list"><dt>地   址：</dt><dd><cite>' . $item["address"] . '</cite></dd></dl>';
        echo '</div></div></div></div>';
    }
    ?>
    <input type='hidden' name='orderid' id='orderid'>
</form>
</body>
</html>