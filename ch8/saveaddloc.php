<?php
include_once 'model/SaeDB.class.php';
$mysql = SaeDB::getInstance();
$fname = $mysql->escape($_POST['fname']);
$loc = $mysql->escape($_POST['loc']);
$latLng = $mysql->escape($_POST['latLng']);
list($Latitude,$Longitude) = explode(',', $latLng);
if(!$fname || !$loc || !$latLng){
    header("Location:addloc.php?msg=emptyparams");
}
$sql = "INSERT INTO `diner_locs` (`id`, `fname`, `loc`, `Latitude`,`Longitude`, `addtime`) VALUES (NULL, '{$fname}', '{$loc}', '{$Latitude}', '{$Longitude}',CURRENT_TIMESTAMP);";
$mysql->runSql($sql);
if ($mysql->errno() != 0)
{
    die("Error:" . $mysql->errmsg());
}
$mysql->closeDb();
header("Location:loclist.php");