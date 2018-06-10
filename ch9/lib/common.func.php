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
    return $path;
}

/**
 * 保存文件到sae
 * @param type $imgbin
 * @param string $destFileName
 * @return type
 */
function savetosae($imgbin,$destFileName = ''){
    $storage = new SaeStorage();
    $domain = 'devweixin';
    if(!$destFileName){
        $destFileName = md5(time()).'.jpg';
    }
    $attr = array('encoding'=>'gzip');
    return $storage->write($domain,$destFileName, $imgbin, -1, $attr, true);
}