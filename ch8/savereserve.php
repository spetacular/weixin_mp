<?php
include_once 'model/SaeDB.class.php';
$mysql = SaeDB::getInstance();
$name = $mysql->escape($_POST['name']);
$sex = intval($_POST['sex']);
$num = intval($_POST['num']);
$dinerdate = $mysql->escape($_POST['dinerdate']);
$dinertime = $mysql->escape($_POST['dinertime']);
$openid = $mysql->escape($_POST['openid']);
$phone = $mysql->escape($_POST['phone']);
$locid = intval($_POST['locid']);
$dinertimestamp = strtotime($dinerdate.' '.$dinertime);
$sql = "INSERT INTO `diner_reserve` (`id`, `name`, `sex`, `num`,`dinertime`,`openid`,`phone`,`locid`, `addtime`) VALUES (NULL, '{$name}', {$sex}, {$num}, {$dinertimestamp},'{$openid}','{$phone}',{$locid},CURRENT_TIMESTAMP);";
$mysql->runSql($sql);
if ($mysql->errno() != 0)
{
    die("Error:" . $mysql->errmsg());
}
$mysql->closeDb();
header("Location:myreserve.php?user={$openid}");