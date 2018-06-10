<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=screen-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="format-detection" content="telephone=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<title>用户注册</title>
<style>
.content{
	border:2px solid #d9d9d9;
	border-radius: 15px;	
}
.content p{	
	width:100%;
}

.content p label{	
	margin-left:10px;
	font-size:18px;
	font-family:"微软雅黑","Arial","Helvetica",sans-serif,verdana;
	color:#383838;
}
.content p input[type="text"] {	
	height:32px;
	border:1px solid #d9d9d9;
	margin:0px 1px;
	width:90%;
	font-size:18px;
	overflow:hidden;
}
</style>
<link rel="stylesheet" href="../CSS/demos.css">
<script src="../Js/jquery-1.10.2.js"></script>
<script src="../Js/jquery.ui.core.js"></script>
<script src="../Js/jquery.ui.widget.js"></script>
<script src="../Js/jquery.ui.datepicker.js"></script>
</head>
<body>
<div class="content">
<form action='AddUser.php' method='post' id='myform'>
<p>
<label >欢迎来到纳吉酒店，注册用户第一次使用微信预定，在原有折扣基础之上再减20元,积分还可以抵扣房费，还等什么，来吧!!!</label>
</p>
<br/>
<p><label>姓名：</label>
<input type="text" id='name' name='name' /></p>

<p><label>电话：</label>
<input type="text" id='telephone' name='telephone'/></p>

<p><label>身份证号码:</label>
<input type="text" id='Identity' name='Identity' /></p>
<p>
<a class='btn_buy' onClick="$('#myform').submit();">注册</a>
</p>
<input type='hidden' name='openid' id='openid' value="<?php echo $_GET['openid'];?>">
<input type='hidden' name='hotelid' id='hotelid' value="<?php echo $_GET['hotelid'];?>">
</form>
</div>
</body>
</html>
