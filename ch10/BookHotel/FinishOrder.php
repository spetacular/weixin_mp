<?php

	$mysql = new SaeMysql();
	$orderid=$mysql->escape($_GET['orderid']);

    $sql = "select * from  bh_Order where Id = $orderid";
    $order = $mysql->getLine($sql);

    $sql = "update bh_User set  Credits = {$order['Total']} where OpenId = '{$order['OpenId']}'";
    $mysql->runSql($sql);

    $sql = "update bh_Order set  Finished = true where Id = $orderid";
    $mysql->runSql($sql);

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
<title>订单完成</title>

</head>
<body>
<?php
	echo '<div class="d-left-module mt15"><div class="inner m-hotel-overview" id="jxDescTab">';
	echo '<h2 class="facility-title">欢迎入住</h2><div class="hotel-introduce" id="descContent"><div class="base-info bordertop clrfix">';
	echo '</div></div></div></div>';
?>
</body>
</html>