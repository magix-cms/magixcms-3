<?php
/**
 * Ini name data Mysql
 */
$config = dirname(__FILE__).DIRECTORY_SEPARATOR.'config.php';
if (file_exists($config)) {
	require $config;
}
else {
    header('Location: /install/');
}
//setlocale(LC_TIME, 'fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8');
if(defined('MP_LOG')){
    if(MP_LOG == 'debug'){
        $dis_errors = 1;
    }elseif(MP_LOG == 'log'){
        $dis_errors = 1;
    }else{
        $dis_errors = 0;
    }
    ini_set('display_errors', $dis_errors);
}
//error_reporting(E_ALL ^ E_NOTICE);
?>