<?php

require_once '../lib/weixin.class.php';

$mysql = new SaeMysql();
$openid=$mysql->escape($_POST['openid']);
$sql = "select count(*) from `bh_Order` where `OpenId`={$openid}";
$count=$mysql->getVar($sql);
$isFirstOrder = $count == 0?true:false;
$RoomId = intval($_POST['roomid']);
$sql = "select * from bh_Room where Id =$RoomId";
$roominfo=$mysql->getLine($sql);

$price = $roominfo["MemberPrice"];
$Total = $price*intval($_POST['days']);
if($isFirstOrder)
{
	$Total=$Total-20;
	$discount = 20;
}

$date=date('Y-m-d',strtotime($mysql->escape($_POST['date'])));
$Count=intval($_POST['days']);

$sql = "insert into bh_Order(RoomId, Time, OpenId, Price, Count, Total,Finished,FirstOrder) 
                values($RoomId, '$date', '$openid',$price, $Count, $Total,false,$isFirstOrder)";
$mysql->runSql($sql);

$sql = "select * from bh_HotelInfo where Id ='".$roominfo["HotelId"]."'";
$HotelInfo=$mysql->getLine($sql);

if ($mysql->errno() != 0)
{
    die("Error:".$mysql->errmsg());
}
$mysql->closeDb();

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=screen-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="format-detection" content="telephone=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<link href="../CSS/styles.css" type="text/css" rel="stylesheet" />
<title>预定酒店</title>

</head>
<body>
<?php
	echo '<div class="d-left-module mt15"><div class="inner m-hotel-overview" id="jxDescTab">';
	echo '<h2 class="facility-title">预定成功，欢迎入住</h2><div class="hotel-introduce" id="descContent"><div class="base-info bordertop clrfix">';
    echo '<dl class="inform-list"><dt>酒   店：</dt><dd><cite>'.$HotelInfo["Name"].'</cite></dd></dl>';
    echo '<dl class="inform-list"><dt>数   量：</dt><dd><cite>'.$Count.'天</cite></dd></dl>';
	echo '<dl class="inform-list"><dt>房   型：</dt><dd><cite>'.$roominfo["Type"].'</cite></dd></dl>';
	echo '<dl class="inform-list"><dt>入住日期：</dt><dd><cite>'.$date.'</cite></dd></dl>';
	echo '<dl class="inform-list"><dt>会 员 价：</dt><dd><cite>'.$roominfo["MemberPrice"].'元</cite></dd></dl>';
	if($isFirstOrder)
	{
		echo '<dl class="inform-list"><dt>抵   扣：</dt><dd><cite>'.$discount.'元</cite></dd></dl>';
	}
	echo '<dl class="inform-list"><dt>总   价：</dt><dd><cite>'.$Total.'元</cite></dd></dl>';
	echo '<dl class="inform-list"><dt>电   话：</dt><dd><cite>'.$HotelInfo["Telephone"].'</cite></dd></dl>';
	echo '<dl class="inform-list"><dt>地   址：</dt><dd><cite>'.$HotelInfo["Address"].'</cite></dd></dl>';
	echo '</div></div></div></div>';
?>
</body>
</html>

