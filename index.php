<?php
require('lib/frontend.inc.php');
$config = dirname(__FILE__).DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'init'.DIRECTORY_SEPARATOR.'config.php';;
if (file_exists($config)) {
    $dispatcher = new component_routing_frontend();
    $dispatcher->setRoutes()->dispatch();
}