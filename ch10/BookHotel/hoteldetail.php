<?php

	$mysql = new SaeMysql();
	$hotelid=intval($_GET["hotelid"]);
	$openid=$mysql->escape($_GET['openid']);

	$sql = "select Name from `bh_User` where `OpenId`='$openid'";
    $Name=$mysql->getVar($sql);
	if(is_null($Name))
    {
    	if ($mysql->errno() != 0)
    	{
        	die("Error:".$mysql->errmsg());
    	}
    	$mysql->closeDb();

        header('Location:./BindUser.php?hotelid='.$hotelid.'&openid='.$openid);
        exit(0);
    }

    $sql = "select * from bh_Room where HotelId =$hotelid";
    $rooms=$mysql->getData($sql);

    $sql = "select * from bh_HotelInfo where Id =$hotelid";
    $hotel =$mysql->getLine($sql);

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
<title>纳吉酒店</title>
<link rel="stylesheet" href="../CSS/jquery.ui.all.css">
<link href="../CSS/styles.css" type="text/css" rel="stylesheet" />
	
<link href="../CSS/photoswipe.css" type="text/css" rel="stylesheet" />

<script type="text/javascript" src="../Js/simple-inheritance.min.js"></script>
<script type="text/javascript" src="../Js/code-photoswipe-1.0.11.min.js"></script>
	
<script src="../Js/jquery.ui.core.js"></script>
<script src="../Js/jquery.ui.widget.js"></script>
<script src="../Js/jquery.ui.datepicker.js"></script>
<link rel="stylesheet" href="../CSS/demos.css">
<link rel="stylesheet" href="../CSS/jquery-ui.css">
<script src="../Js/jquery-1.10.2.js"></script>
<script src="../Js/jquery-ui.js"></script>
<script>
$(function() {
	$( "#datepicker" ).datepicker();
	var date = (new Date().getMonth()+1)+"/"+new Date().getDate()+"/"+new Date().getFullYear();
    $( "#datepicker" ).val(date);
});

// Set up PhotoSwipe with all anchor tags in the Gallery container 
document.addEventListener('DOMContentLoaded', function(){
	
	Code.photoSwipe('a', '#Gallery');
	
}, false);
</script>
<style>
.content{
border:2px solid #d9d9d9;
border-radius: 15px;	
}
</style>
</head>
<body>
<div class="d-left-module mt15"><div class="inner m-hotel-overview" id="jxDescTab">
<h2 class="facility-title"><?php echo $hotel["Name"];?></h2><div class="hotel-introduce" id="descContent"><div class="base-info bordertop clrfix">

<dl class="inform-list"><dt>地址：</dt><dd><cite><?php echo $hotel["Address"];?></cite></dd></dl>
<dl class="inform-list"><dt>电话：</dt><dd><cite><a href="tel:<?php echo $hotel["Telephone"];?>"><?php echo $hotel["Telephone"];?></a></cite></dd></dl>
<br/>
<div id="Gallery">
	
	<div class="gallery-row">
		<div class="gallery-item"><a href="../images/full/1.jpg"><img src="../images/thumb/1.jpg" alt="Image 001" /></a></div>
		<div class="gallery-item"><a href="../images/full/2.jpg"><img src="../images/thumb/2.jpg" alt="Image 002" /></a></div>
		<div class="gallery-item"><a href="../images/full/3.jpg"><img src="../images/thumb/3.jpg" alt="Image 003" /></a></div>
	</div>
	<div class="gallery-row">
		<div class="gallery-item"><a href="../images/full/4.jpg"><img src="../images/thumb/4.jpg" alt="Image 004" /></a></div>
		<div class="gallery-item"><a href="../images/full/5.jpg"><img src="../images/thumb/5.jpg" alt="Image 005" /></a></div>
		<div class="gallery-item"><a href="../images/full/6.jpg"><img src="../images/thumb/6.jpg" alt="Image 006" /></a></div>
	</div>
</div>
<br/>
<form action='AddOrder.php' method='post' id='myform'>
<p>入住日期: <input type="text" name ='date' id="datepicker" value=''></p>
<p>入住天数:<input type="text" name='days' value='1'></p>
<br/>
<div class="room_select_box">
<div class="ht1_room_table">
<?php

$tab_str="<table>";
$tab_str.="<tr><th>房型</th><th>门市价</th><th>会员价</th><th></th></tr>";

foreach($rooms as $room){
    $tab_str.="<tr>";
	$tab_str.="<td>".$room["Type"]."</td>";
	$tab_str.="<td>".$room["Price"]."</td>";
	$tab_str.="<td>".$room["MemberPrice"]."</td>";

	$roomid = $room["Id"];
	$tab_str.="<td><a class='btn_buy' onClick=\"$('#roomid').val($roomid);$('#myform').submit();\">预定</a>";

    $tab_str.="</tr>";
}

$tab_str.="</table>";

print $tab_str;
?>
</div>
</div>
<input type='hidden' name='roomid' id='roomid'>
<input type='hidden' name='openid' id='openid' value="<?php echo $_GET['openid'];?>">
</form>
</div></div></div></div>
</body>
</html>