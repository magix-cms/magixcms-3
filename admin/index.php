<?php
$baseadmin = 'baseadmin.php';
if(file_exists($baseadmin)){
    require $baseadmin;
    if(!defined('PATHADMIN')){
        throw new Exception('PATHADMIN is not defined');
    }
}
require('../lib/backend.inc.php');
//$dispatcher = new component_routing_dispatcher('backend');
$dispatcher = new component_routing_backend();
$dispatcher->setRoutes()->dispatch();