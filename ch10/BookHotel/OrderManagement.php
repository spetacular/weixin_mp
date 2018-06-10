<?php
	require_once '../lib/common.func.php';
	require_once '../model/SendMsgDB.php';

	$data = array();
        $mysql = new SaeMysql();
        $sql = "select * from bh_Order where Finished =false";
        $orderlist=$mysql->getData($sql);
        foreach ($orderlist as $order){
            $item = array();
            $roomid = $order["RoomId"];
            $sql = "select * from bh_Room where Id =$roomid";
            $room = $mysql->getLine($sql);

            $hotelid= $room["HotelId"];
            $sql = "select * from bh_HotelInfo where Id =$hotelid";
            $hotelinfo=$mysql->getLine($sql);

            $item = array(
                "id"=>$order["Id"],
                "hotelname"=>$hotelinfo["Name"],
                "date"=>$order["Time"],
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
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=screen-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="format-detection" content="telephone=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<link href="../CSS/styles.css" type="text/css" rel="stylesheet" />
<script src="../Js/jquery.ui.core.js"></script>
<script src="../Js/jquery.ui.widget.js"></script>
<script src="../Js/jquery-1.10.2.js"></script>
<script src="../Js/jquery-ui.js"></script>
<link rel="stylesheet" href="../CSS/jquery-ui.css">

<title>我的订单</title>

</head>
<body>
    <h2 class="detail_title">订单列表</h2>
    <table class="gridtable" width="98%">
        <tr>
            <th>订单号</th>
            <th>房型</th>
            <th>入住日期</th>
            <th>天数</th>
            <th>价格</th>
            <th>总价</th>
        </tr>
        
        <?php 
        foreach($data as $item){
            echo '<tr>';
            echo "<td>{$item['id']}</td>";
            echo "<td>{$item['type']}</td>";
            echo "<td>{$item['date']}</td>";
            echo "<td>{$item['count']}</td>";
            echo "<td>{$item['price']}</td>";
            echo "<td>{$item['total']}</td>";
            echo "<td><a href='FinishOrder.php?orderid={$item['id']}'>入住</a> <a href='OrderOverTime.php?orderid={$item['id']}'>过期</a></td>";
            echo '<tr>';
        }
        ?>
    </table>
</body>
</html>