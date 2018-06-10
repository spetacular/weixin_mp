<?php
include_once 'model/SaeDB.class.php';
$mysql = SaeDB::getInstance();
$id = intval($_POST['id']);
$name = $mysql->escape($_POST['name']);
$imgurl = $mysql->escape($_POST['imgurl']);
$price = floatval($_POST['price']);
$category = intval($_POST['category']);
$available = intval($_POST['available']);

if(!$name || !$imgurl || !$price){
    header("Location:editmenu.php?id={$id}&msg=emptyparams");
}
$sql = "UPDATE  `diner_menu` SET `name` = '{$name}' ,   `imgurl` =  '{$imgurl}' ,`price` = {$price} ,`category` = {$category},`available` = {$available} WHERE `id` = {$id};";
$mysql->runSql($sql);
if ($mysql->errno() != 0)
{
    die("Error:" . $mysql->errmsg());
}
$mysql->closeDb();
header("Location:menulist.php");
?>
