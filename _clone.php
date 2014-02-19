<?php

require_once 'application/helpers/_FsHelper.php';

function getItem($data, $key, $defaultValue = "") {
    $value = $defaultValue;
    if (is_object($data)) {
        $data = get_object_vars($data);
    }
    if (is_array($data)) {
        if (isset($data[$key])) {
            $value = $data[$key];
        }
    }

    return $value;
}

function isArgAKey($arg){
    if(strpos($arg, '-')===0){
        $key = substr($arg, 1);
        return $key;
    }
    return false;
}

function parseArgv(){
    global $argc, $argv;
    $parsed = array();
    for($i = 0; $i < $argc; $i++){
        $arg = $argv[$i];
        $key = isArgAKey($arg);
        $next = $i+1 < $argc? $argv[$i+1]:'-!';
        if($key){
            $parsed[$key]=isArgAKey($next)?true:$next;
        }
    }
    return $parsed;
}

function printHelp(){
echo <<<END
    -project    : project name
    -base       : base folder, '../' by default
    -plugin     : defines that project is plugin

END;
}

function getExtension($filename) {
    $m = null;
    return preg_match('%\.([\w\d]+)$%', $filename, $m)?$m[1]:pathinfo($filename, PATHINFO_EXTENSION);
}

function searchReplaceCopy($src, $dst, $search, $replace, $dstAttribs=0777, $owner=null, $group=null) {
    if(strpos(basename($src), '_')===0){
    echo "[ommiting] $src\n";
        return 1;
    }
    $dst = str_replace($search, $replace, $dst);
    echo "[copy] $src => $dst\n";
    if (is_file($src)) {
        $ext = getExtension($src);
        $res = false;
        if(in_array($ext, array(
                'php', 'phtml', 
                'html', 'css', 'less', 'js', 
                'ini', 'properties', 'xml'
            ))){
            $data = str_replace($search, $replace, file_get_contents($src));
            file_put_contents($dst, $data);
            $res = file_exists($dst);
        }else{
            $res = copy($src, $dst);
        }
        if($res){
            if($owner){
                chown($dst, $owner);
            }
            if($group){
                chgrp($dst, $group);
            }
        }
        return $res;
    } elseif (is_dir($src)) {
        if (!is_dir($dst) && !mkdir($dst, $dstAttribs)){
            return 0;
        }
        if($owner){
            chown($dst, $owner);
        }
        if($group){
            chgrp($dst, $group);
        }
        $src = preg_replace("%/$%", '', $src);
        $dst = preg_replace("%/$%", '', $dst);
        $d = dir($src);
        while ($file = $d->read()) {
            if ($file == "." || $file == "..") {
                continue;
            }
            if (!searchReplaceCopy("$src/$file", "$dst/$file", $search, $replace, $dstAttribs, $owner, $group)) {
                $d->close();
                return 0;
            };
        }
        $d->close();
    } else {
        return 0;
    }
    return 1;
}

function main(){
    $params = parseArgv();
    $projectName = getItem($params, 'project');
    
    if(empty($params)||!$projectName){
        printHelp();
        return;
    }
    
    $projectVar = getItem($params, 'var', str_replace(array('-', '.', ' '), '_', $projectName));
    $projectConst = getItem($params, 'const', strtoupper($projectVar));
    $projectId = getItem($params, 'id', strtolower($projectName));
    $projectModule = getItem($params, 'module', str_replace(array('-', '_', ' '), '.', $projectName));
    
    $isPlugin = getItem($params, 'plugin');
    $isPluginStr = $isPlugin? 'yes':'no';
    
    $baseDir = getItem($data, 'base', '../');
    $destDir = realpath($baseDir).'/'.$projectName.($isPlugin?'.wpp':'.wpt');
    
    $owner = fileowner($_SERVER['PHP_SELF']);
    $group =  filegroup($_SERVER['PHP_SELF']);
echo <<<END
    
    project :   brx-Void    =>  $projectName
    var     :   brx_Void    =>  $projectVar
    const   :   BRX_VOID    =>  $projectConst
    id      :   brx-void    =>  $projectId
    module  :   brx.Void    =>  $projectModule
        
    dest    :   $destDir
        
    isPlugin:   $isPluginStr
        
    group   :   $group

is everything ok? (Y/n):

END;
    
    $fp = fopen('php://stdin', 'r');
    $answer = fgets($fp);
    fclose($fp);
    
    if($answer && 'y' != trim(strtolower($answer))){
        return;
    }
    
    echo "proceed\n";
    
    if (!is_dir($destDir) && !mkdir($destDir, 0777)){
        echo "cannot create dir\n";
        return 0;
    }
    
    chown($destDir, $owner);
    chgrp($destDir, $group);
    
    $tr = array(
        'brx-Void'    =>  $projectName,
        'brx_Void'    =>  $projectVar,
        'BRX_VOID'    =>  $projectConst,
        'brx-void'    =>  $projectId,
        'brx.Void'    =>  $projectModule
    );
    
    searchReplaceCopy('./application', $destDir.'/application', array_keys($tr), array_values($tr), 0755, $owner, $group);
    searchReplaceCopy('./library', $destDir.'/library', array_keys($tr), array_values($tr), 0755, $owner, $group);
    searchReplaceCopy('./nls', $destDir.'/nls', array_keys($tr), array_values($tr), 0755, $owner, $group);
    searchReplaceCopy('./res', $destDir.'/res', array_keys($tr), array_values($tr), 0777, $owner, $group);
    
    if(getItem($params, 'netbeans')){
        searchReplaceCopy('./nbproject', $destDir.'/nbproject', array_keys($tr), array_values($tr), 0777, $owner, $group);
    }
    
    if($isPlugin){
        searchReplaceCopy('./brx-Void.php', $destDir.'/brx-Void.wpp.php', array_keys($tr), array_values($tr), 0777, $owner, $group);
    }else{
        searchReplaceCopy('./brx-Void.php', $destDir.'/brx-Void.wpt.php', array_keys($tr), array_values($tr), 0777, $owner, $group);
        searchReplaceCopy('./index.php', $destDir.'/index.php', array_keys($tr), array_values($tr), 0777, $owner, $group);
        searchReplaceCopy('./loop.php', $destDir.'/loop.php', array_keys($tr), array_values($tr), 0777, $owner, $group);
        searchReplaceCopy('./header.php', $destDir.'/header.php', array_keys($tr), array_values($tr), 0777, $owner, $group);
        searchReplaceCopy('./footer.php', $destDir.'/footer.php', array_keys($tr), array_values($tr), 0777, $owner, $group);
        searchReplaceCopy('./functions.php', $destDir.'/functions.php', array_keys($tr), array_values($tr), 0777, $owner, $group);
        searchReplaceCopy('./style.css', $destDir.'/style.css', array_keys($tr), array_values($tr), 0777, $owner, $group);
        searchReplaceCopy('./screenshot.png', $destDir.'/screenshot.png', array_keys($tr), array_values($tr), 0777, $owner, $group);
    }
    
}

main();