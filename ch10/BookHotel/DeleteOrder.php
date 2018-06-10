<?php

	$mysql = new SaeMysql();
	
	$orderid=$mysql->escape($_POST['orderid']);

    $sql = "delete from bh_Order where Id = $orderid";
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
<title>删除订单</title>

</head>
<body>
<?php
	echo '<div class="d-left-module mt15"><div class="inner m-hotel-overview" id="jxDescTab">';
	echo '<h2 class="facility-title">退订成功，欢迎再次入住</h2><div class="hotel-introduce" id="descContent"><div class="base-info bordertop clrfix">';
	echo '</div></div></div></div>';
?>
</body>
</html>