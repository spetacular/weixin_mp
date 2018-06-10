<?php
require 'lib/common.func.php';
require 'lib/weixin.class.php';

$token = weixin::getAuthToken($_GET['code']);
$userinfo = weixin::getUserInfoByOAuth($token['access_token'],$token['openid']);

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=screen-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="format-detection" content="telephone=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<title>OAuth2演示</title>
<style>
.content{
	border:1px solid #d9d9d9;
	border-radius: 15px;	
}
.content p{	
	width:100%;
}

.content p label{	
	margin-left:10px;
}
.content p input[type="text"] {	
	border:1px solid #d9d9d9;
	margin:0px 1px;
	width:98%;
	overflow:hidden;
}
</style>
</head>
<body>
<div class="content">
<p><label>OpenID</label></p>
<p><input type="text" readonly="readonly" value="<?php echo $userinfo['openid'];?>"/></p>

<p><label>昵称</label></p>
<p><input type="text" readonly="readonly" value="<?php echo $userinfo['nickname'];?>"/></p>

<p><label>性别</label></p>
<p><input type="text" readonly="readonly" value="<?php if($userinfo['sex'])echo '男';else echo '女';?>"/></p>

<p><label>省份</label></p>
<p><input type="text" readonly="readonly" value="<?php echo $userinfo['province'];?>"/></p>

<p><label>城市</label></p>
<p><input type="text" readonly="readonly" value="<?php echo $userinfo['city'];?>"/></p>

<p><label>头像</label></p>
<p><input type="text" readonly="readonly" value="<?php echo $userinfo['headimgurl'];?>"/></p>

</div>
</body>
</html>
