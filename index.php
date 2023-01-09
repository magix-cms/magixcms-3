<?php
require('lib/frontend.inc.php');
$dispatcher = new component_routing_frontend();
$dispatcher->setRoutes()->dispatch();