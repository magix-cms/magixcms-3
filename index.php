<?php
require('lib/frontend.inc.php');

$language = new component_core_language('strLangue');
$language->run();
if(http_request::isGet('plugins')){
    $routes = 'plugins';
}else{
    $routes = 'frontend';
}
$dispatcher = new component_routing_dispatcher($routes);
$dispatcher->dispatch();