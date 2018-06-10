<?php
	require_once '../lib/common.func.php';
	require_once '../lib/weixin.class.php';
	require_once '../model/SendMsgDB.php';

	$token = weixin::getAuthToken($_GET['code']);
	$openid = $token["openid"];
        $mysql = new SaeMysql();
        $sql = "select * from bh_User where OpenId ='$openid'";
        $info=$mysql->getLine($sql);

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
<title>我的会员卡</title>

</head>
<body>
<?php
	echo '<div class="d-left-module mt15"><div class="inner m-hotel-overview" id="jxDescTab">';
	echo '<h2 class="facility-title">会员卡</h2><div class="hotel-introduce" id="descContent"><div class="base-info bordertop clrfix">';
    echo '<dl class="inform-list"><dt>姓 名：</dt><dd><cite>'.$info["Name"].'</cite></dd></dl>';
    echo '<dl class="inform-list"><dt>电 话：</dt><dd><cite>'.$info["Telephone"].'</cite></dd></dl>';
	echo '<dl class="inform-list"><dt>会员卡：</dt><dd><cite>'.$info["Type"].'</cite></dd></dl>';
	echo '<dl class="inform-list"><dt>积 分：</dt><dd><cite>'.$info["Credits"].'</cite></dd></dl>';
	echo '</div></div></div></div>';
?>
</body>
</html>