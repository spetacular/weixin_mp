<?php
include_once 'model/SaeDB.class.php';
require 'lib/common.func.php';
$mysql = SaeDB::getInstance();
$name = $mysql->escape($_POST['name']);
$imgurl = $mysql->escape($_POST['imgurl']);
//save to sae
$f = new SaeFetchurl();
$imgbin = $f->fetch($imgurl);
$imgurl = savetosae($imgbin);
$price = floatval($_POST['price']);
$category = intval($_POST['category']);
$available = intval($_POST['available']);

if(!$name || !$imgurl || !$price){
    header("Location:addmenu.php?msg=emptyparams");
}
$sql = "INSERT INTO `diner_menu` (`id`, `name`, `imgurl`, `price`,`category`,`available`, `addtime`) VALUES (NULL, '{$name}', '{$imgurl}', {$price}, {$category},{$available},CURRENT_TIMESTAMP);";
$mysql->runSql($sql);
if ($mysql->errno() != 0)
{
    die("Error:" . $mysql->errmsg());
}
$mysql->closeDb();
header("Location:menulist.php");
?>
