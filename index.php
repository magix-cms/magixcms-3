<?php
require('lib/frontend.inc.php');
$template = new frontend_model_template();
$language = new component_core_language($template,'strLangue');
$language->run();
$controllerCollection = array('home','about','pages','news','catalog','cookie','webservice','service');
$controller_name = http_request::isGet('controller') ? form_inputEscape::simpleClean($_GET['controller']) : 'home';
if(in_array($controller_name,$controllerCollection)){
    $routes = 'frontend';
    $plugins = null;
}
else {
    $routes = 'plugins';
    $plugins = 'public';
    $pluginsSetConfig = new frontend_model_plugins();
    $pluginsSetConfig->addConfigDir($routes, $template);
}
$dispatcher = new component_routing_dispatcher($routes,$template,$plugins);
$dispatcher->dispatch();