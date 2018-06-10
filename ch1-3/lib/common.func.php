<?php

/**
 * sae的日志封装
 * @param type $msg
 */
function sae_log($msg){
    sae_set_display_errors(false);//关闭信息输出
    sae_debug($msg);//记录日志
    sae_set_display_errors(true);//记录日志后再打开信息输出，否则会阻止正常的错误信息的显示
}

/**
 * 获取当前时间，精确到微秒
 * @return type
 */
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}


/**
 * 获取文件扩展名
 * @param type $filepath
 * @return type
 */
function get_file_ext($filepath){
	return substr($filepath, strrpos($filepath, "."));
}



/**
 * 加载文件
 * @param string $path
 */
function libfile($path) {
    list($folder , $file) = explode("/", $path, 2);
    if(substr($file, -4) == '.php'){
        $path = BASEDIR . "/{$folder}/{$file}";
    }else{
        $path = BASEDIR . "/{$folder}/{$file}.php";
    }
    //var_dump($folder,$file,$path);
    return $path;
}
/**
 * 加载模板文件
 * @param string $path
 */
function load_view($path) {
    //list($mod , $action) = explode("/", $path,2);
    //$path = BASEDIR . "/view/{$mod}/{$action}.php";
    $viewPath = libfile("view/{$path}");
    //var_dump("viwepath=",$viewPath);
    require $viewPath;
}

/**
 * 获取访问url
 * @param type $path
 * @return type
 */
function get_url($path) {
    list($mod , $action) = explode("/", $path,2);
    $url = "index.php?mod={$mod}&do={$action}";
    return $url;
}
?>
