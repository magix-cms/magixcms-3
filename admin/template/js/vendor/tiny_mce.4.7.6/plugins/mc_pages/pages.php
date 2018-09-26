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
/**
 * Author: Gerits Aurelien <aurelien[at]magix-cms[point]com>
 * Copyright: MAGIX CMS
 * Date: 12/02/13
 * Update: 07/10/2013
 * Time: 19:13
 * License: Dual licensed under the MIT or GPL Version
 */
$baseadmin = '../../../../../../baseadmin.php';
if(file_exists($baseadmin)){
	require_once $baseadmin;
	if(!defined('PATHADMIN')){
		throw new Exception('PATHADMIN is not defined');
	}elseif(!defined('VERSION_EDITOR')){
		throw new Exception('VERSION_EDITOR is not defined');
	}
}

$realpathFilemanager = dirname(realpath( __FILE__ ));
$filemanagerArrayDir = array(PATHADMIN.DIRECTORY_SEPARATOR.'template'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'tiny_mce.'.VERSION_EDITOR.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'mc_pages');
$basePath = str_replace($filemanagerArrayDir, array('') , $realpathFilemanager);
$config_in = $basePath.'/app/init/common.inc.php';

if (file_exists($config_in)) {
	require $config_in;
}
else{
	throw new Exception('Error Ini Common Files');
	exit;
}
/**
 * Chargement du Bootsrap
 */
$bootstrap = $basePath.'/lib/bootstrap.php';
if (file_exists($bootstrap)){
	require $bootstrap;
}
else{
	throw new Exception('Boostrap is not exist');
	exit;
}

$loader = new autoloader();
$loader->addPrefixes(array(
	'component' => $basePath.'/app',
	'backend' => $basePath.'/app',
));
$loader->addPrefix('plugins',filter_path::basePath(array('lib','magepattern')));
$loader->register();

$template = new backend_model_template();
$language = new component_core_language($template,'strLanguage');
$language->run();
$members = new backend_controller_login($template);
$pages = new backend_controller_pages();
$pages->tinymce();