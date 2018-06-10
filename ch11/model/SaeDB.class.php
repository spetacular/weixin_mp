<?php
class SaeDB{
    private static $mysql;
    private function __construct() {
        echo "Not allowed";   
    }
    
    public static function getInstance(){
        if (!isset(self::$mysql)) {           
            self::$mysql=new SaeMysql(); ;
        }
        return self::$mysql;
    }
}
?>