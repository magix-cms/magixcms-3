<?php
require('lib/frontend.inc.php');

$language = new component_core_language('strLangue');
$language->run();
$dispatcher = new component_routing_dispatcher('frontend');
$dispatcher->dispatch();