<?php
include_once 'model/SaeDB.class.php';
$mysql = SaeDB::getInstance();
$id = intval($_POST['id']);
$fname = $mysql->escape($_POST['fname']);
$loc = $mysql->escape($_POST['loc']);
$latLng = $mysql->escape($_POST['latLng']);
list($Latitude,$Longitude) = explode(',', $latLng);
if(!$fname || !$loc || !$latLng){
    header("Location:editloc.php?id={$id}&msg=emptyparams");
}
$sql = "UPDATE  `diner_locs` SET `fname` = '{$fname}' ,   `loc` =  '{$loc}' ,`Latitude` = '{$Latitude}' ,`Longitude` = '{$Longitude}' WHERE  `diner_locs`.`id` = {$id};";
$mysql->runSql($sql);
if ($mysql->errno() != 0)
{
    die("Error:" . $mysql->errmsg());
}
$mysql->closeDb();
header("Location:loclist.php");
?>
