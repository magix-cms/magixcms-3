<?php
$baseadmin = 'baseadmin.php';
if(file_exists($baseadmin)){
    require $baseadmin;
    if(!defined('PATHADMIN')){
        throw new Exception('PATHADMIN is not defined');
    }
}
require('../lib/backend.inc.php');

$language = new component_core_language('strLanguage');
$language->run();
/*$members = new backend_controller_login();
$members->checkout();
if(http_request::isSession('keyuniqid_admin')) {
    $home = new frontend_controller_home();
    $home->run();
}*/
/*$members = new backend_controller_login();
$members->checkout();*/
$template = new backend_model_template();
$controllerCollection = array('dashboard','login','employee','access','language','country','home','news','catalog','webservice','plugins');
$formClean = new form_inputEscape();
if(http_request::isGet('controller')){
    $controller_name = $formClean->simpleClean($_GET['controller']);
    if(in_array($controller_name,$controllerCollection)){
        $routes = 'backend';
        $plugins = null;
        if($_GET['controller'] != 'login'){
            $members = new backend_controller_login();
            $members->checkout();
            if (http_request::isSession('keyuniqid_admin')) {
                $dispatcher = new component_routing_dispatcher($routes, $template, $plugins);
                $dispatcher->dispatch();
            }
        }else{
            $dispatcher = new component_routing_dispatcher($routes, $template, $plugins);
            $dispatcher->dispatch();
        }
    }else{
        $routes = 'plugins';
        $plugins = 'admin';
        $members = new backend_controller_login();
        $members->checkout();
        if (http_request::isSession('keyuniqid_admin')) {
            $pluginsSetConfig = new backend_model_plugins();
            $pluginsSetConfig->templateDir($routes, $template, $plugins);
            $dispatcher = new component_routing_dispatcher($routes, $template, $plugins);
            $dispatcher->dispatch();
        }
    }
}else{
    $members = new backend_controller_login();
    $members->checkout();
}