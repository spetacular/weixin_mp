<?php
$title = '删除成功';
include 'header.php';
?>
<?php
$mysql = SaeDB::getInstance();
$id = intval($_GET['id']);
$sql = "DELETE FROM `diner_qrcode` WHERE `id` = {$id};";
$mysql->runSql($sql);
if ($mysql->errno() != 0)
{
    die("Error:" . $mysql->errmsg());
}
$mysql->closeDb();
?>

<body>
删除成功，<a href="qrlist.php">返回列表</a>
</body>
</html>