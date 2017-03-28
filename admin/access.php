<?php
require('lib/backend.inc.php');

$language = new component_core_language('strLanguage');
$language->run();
$members = new backend_controller_login();
$members->checkout();
if(http_request::isSession('keyuniqid_admin')) {
    /*$access = new backend_controller_access();
    $access->run();*/
}