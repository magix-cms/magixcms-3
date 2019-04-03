<?php
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2013 magix-cms.com <support@magix-cms.com>
 #
 # OFFICIAL TEAM :
 #
 #   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
 #
 # Redistributions of files must retain the above copyright notice.
 # This program is free software: you can redistribute it and/or modify
 # it under the terms of the GNU General Public License as published by
 # the Free Software Foundation, either version 3 of the License, or
 # (at your option) any later version.
 #
 # This program is distributed in the hope that it will be useful,
 # but WITHOUT ANY WARRANTY; without even the implied warranty of
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 # GNU General Public License for more details.

 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------

 # DISCLAIMER

 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */

$config_in = '../app/init/common.inc.php';
if (file_exists($config_in)) {
	require $config_in;
}else{
	throw new Exception('Error Ini Common Files');
	exit;
}
/**
 * Chargement du Bootsrap
 */
$bootstrap = __DIR__.'/bootstrap.php';
if (file_exists($bootstrap)){
    require $bootstrap;
}else{
    throw new Exception('Boostrap is not exist');
}
$phpico = __DIR__. '/phpico/class-php-ico.php';
if (file_exists($phpico)) {
    require $phpico;
}else{
    throw new Exception('Error load library phpico');
    exit;
}
$loader = new autoloader();
$loader->addPrefixes(array(
	'component' => '../app',
	'backend' => '../app',
));
$loader->addPrefix('plugins',filter_path::basePath(array('lib','magepattern')));
$loader->register();
?>