<?php

	$mysql = new SaeMysql();
	$OpenId=$mysql->escape($_POST['openid']);
	$Telephone=$mysql->escape($_POST['telephone']);
	$Name=$mysql->escape($_POST['name']);
	$Identity=$mysql->escape($_POST['Identity']);

    $sql = "update `bh_User` set Telephone='$Telephone',Name='$Name',Identity='$Identity',Type='虚拟忆卡',Credits=0 where OpenId='$OpenId'";
    $mysql->runSql($sql);
    if ($mysql->errno() != 0)
    {
        die("Error:".$mysql->errmsg());
    }
    $mysql->closeDb();

	$url = './hoteldetail.php?hotelid='.$_POST['hotelid'].'&openid='.$OpenId;
	header('Location:'.$url);

?>