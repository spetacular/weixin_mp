<?php
$title = '删除成功';
include 'header.php';

$mysql = SaeDB::getInstance();
$id = intval($_GET['id']);
$sql = "DELETE FROM `diner_locs` WHERE `id` = {$id};";
$mysql->runSql($sql);
if ($mysql->errno() != 0)
{
    die("Error:" . $mysql->errmsg());
}
$mysql->closeDb();
?>

<body>
删除成功，<a href="loclist.php">返回列表</a>
</body>
</html>