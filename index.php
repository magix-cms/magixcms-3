<?php
require('lib/frontend.inc.php');
$language = new component_core_language('strLangue');
$language->run();
$controllerCollection = array('home','pages','news','catalog','webservice');
$formClean = new form_inputEscape();
$template = new frontend_model_template();
if(http_request::isGet('controller')){
    $controller_name = $formClean->simpleClean($_GET['controller']);
}else{
    $controller_name = 'home';
}
if(in_array($controller_name,$controllerCollection)){
    $routes = 'frontend';
    $plugins = null;
}else{
    $routes = 'plugins';
    $plugins = 'public';
}
$dispatcher = new component_routing_dispatcher($routes,$template,$plugins);
$dispatcher->dispatch();