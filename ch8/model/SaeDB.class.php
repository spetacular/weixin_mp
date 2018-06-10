<?php
/**
 * 单例模式获取Mysql实例类
 */
class SaeDB{
    private static $mysql;
    private function __construct() {
        echo "Not allowed";   
    }
    
    public static function getInstance(){
        if (!isset(self::$mysql)) { 
            if(isset($_SERVER['HTTP_APPNAME'])){//sae平台
                self::$mysql=new SaeMysql(); ;
            }else{//其它平台
                self::$mysql= self::loadSAEMysql();
            }
        }
        return self::$mysql;
    }
    
    private static function loadSAEMysql() {
        require 'dbconfig.php';//数据库配置文件
        require 'saemysql.class.php';//sae mysql兼容类        
        return new SaeMysql($saeDBConfig['host'],$saeDBConfig['username'],$saeDBConfig['pass'],$saeDBConfig['db'],$saeDBConfig['port']);
    }
}