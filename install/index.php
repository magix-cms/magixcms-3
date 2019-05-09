<?php
require('../lib/install.inc.php');
$template = new install_model_template();
$language = new component_core_language($template,'strLangue');
$language->run();
$template->configLoad();
$template->assign('url',http_url::getUrl());
$template->assign('install_folder','install');
$lang = $template->lang;
$template->assign('lang',$lang);
$installer = new install_controller_installer();
$installer->run();