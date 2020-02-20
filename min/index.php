<?php
/**
 * Sets up MinApp controller and serves files
 * 
 * DO NOT EDIT! Configure this utility via config.php and groupsConfig.php
 * 
 * @package Minify
 */

$app = (require realpath('../lib/Minify/bootstrap.php'));
/* @var \Minify\App $app */

$app->configPath = __DIR__.DIRECTORY_SEPARATOR.'config.php';
$app->groupsConfigPath = __DIR__.DIRECTORY_SEPARATOR.'groupsConfig.php';
$app->runServer();