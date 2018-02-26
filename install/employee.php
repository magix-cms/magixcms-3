<?php
$config_in = '../app/init/common.inc.php';
if (file_exists($config_in)) {
    require $config_in;
}else{
    throw new Exception('Error Ini Common Files');
    exit;
}
require('../lib/install.inc.php');
$run = new install_controller_employee();
$run->run();
?>