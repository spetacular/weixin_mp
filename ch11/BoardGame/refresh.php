<?php


/**
 * 每隔1小时检查房间是否过期，如果房间超过2个小时未使用，即过期
 */
$mysql = new SaeMysql();

$sql = " update bg_rooms set Free = true where DATE_SUB(NOW(),INTERVAL 2 HOUR)>CreatedTime and Free = false ";
$mysql->runSql($sql);

if ($mysql->errno() != 0)
{
    die("Error:".$mysql->errmsg());
}
$mysql->closeDb();

?>