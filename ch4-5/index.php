<?php

defined('IN_APP') || define('IN_APP', TRUE);
defined('BASEDIR') || define('BASEDIR', dirname (__FILE__));
$mod = !empty($_GET['mod']) ? $_GET['mod']:'home';
$action = isset($_GET['do'])?$_GET['do']:'index';

$allowedMods = array('home', 'qrcode','menu','user');
if(!in_array($mod, $allowedMods)) {
	$mod = 'home';
	$action = 'index';
}

require 'lib/common.func.php';
$htdocsFile = "htdocs/{$mod}/{$action}.php";
$viewFile = "view/{$mod}/{$action}.php";
if( !file_exists($htdocsFile) && !file_exists($viewFile)){
    header("Location:404.php");
}
if(file_exists($htdocsFile))  require $htdocsFile;
if(file_exists($viewFile))  require $viewFile;


?>