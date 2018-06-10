<?php
require_once '../lib/common.func.php';
require_once '../lib/weixin.class.php';
require_once '../model/SendMsgDB.php';

$token = weixin::getAuthToken($_GET['code']);
$openid = $token["openid"];
$mysql = new SaeMysql();
$sql = "select count(*) as count from bh_Order where OpenId ='$openid' and Finished=true";
$info = $mysql->getLine($sql);

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
    <title>我的抵用券</title>

</head>
<body>
<div class="d-left-module mt15">
    <div class="inner m-hotel-overview" id="jxDescTab">
        <h2 class="facility-title">抵用券</h2>

        <div class="hotel-introduce" id="descContent">
            <div class="base-info bordertop clrfix">
                <?php if(empty($info['count'])){?>
                <dl class="inform-list">
                    <dt>面 值：</dt>
                    <dd><cite>20元</cite></dd>
                </dl>
                <dl class="inform-list">
                    <dt>条件：</dt>
                    <dd><cite>首次通过微信预定</cite></dd>
                </dl>
                <?php }else{ ?>
                <dl class="inform-list">
                    <dt>抵用券：</dt>
                    <dd><cite>无</cite></dd>
                </dl>
                <dl class="inform-list">
                    <dt>原因：</dt>
                    <dd><cite>非首次通过微信预定</cite></dd>
                </dl>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>