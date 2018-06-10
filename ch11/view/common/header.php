<?php
if ( ! defined('IN_APP')) exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="utf-8"/>
        <title>微信公共平台开发案例</title>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">    
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
        
    </head>
    
    <body>
        <!-- Static navbar -->
    <div class="navbar navbar-default navbar-static-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"></a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">首页</a></li>
            <!--<li><a href="#contact"></a></li>-->
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">案例<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="index.php?mod=qrcode&do=index">二维码</a></li>
                <!--
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
                -->
              </ul>
            </li>
          </ul>
          <!--
          <ul class="nav navbar-nav navbar-right">
            <li><a href="../navbar/"></a></li>
            <li class="active"><a href="./"></a></li>
            <li><a href="../navbar-fixed-top/"></a></li>
          </ul>
          -->
        </div><!--/.nav-collapse -->
      </div>
    </div>


    <div class="container">