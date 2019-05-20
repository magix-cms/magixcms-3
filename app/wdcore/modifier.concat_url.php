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
 #
 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------
 #
 # DISCLAIMER
 #
 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */
/**
 * MAGIX CMS
 * @category   extends
 * @package    Smarty
 * @subpackage modifier
 * @name concat_url
 * @copyright  MAGIX CMS Copyright (c) 2010 Gerits Aurelien,
 * http://www.magix-cms.com, http://www.magix-cjquery.com
 * @license    Dual licensed under the MIT or GPL Version 3 licenses.
 * @version    plugin version
 * @author Salvatore Di Salvo <disalvo.infographiste@gmail.com>
 *
 * @param (string) $str url
 * @param (string) $type js|css
 *
 * @return string
 */
function smarty_modifier_concat_url($str,$type)
{
	$system = new component_core_system();
	$url = $str;
	$options = array(
		'url' => $str,
		'type' => $type,
		'filesgroups' => 'min/groupsConfig.php'
	);

	if(defined('PATHADMIN')){
		$options['caches'] = 'caching/caches';
		$options['minDir'] = '/'.PATHADMIN.'/min/';
		$options['callback'] = '/admin';
	}
	else{
		$options['caches'] = 'var/caches';
		$options['minDir'] = '/min/';
		$options['callback'] = '';
	}

	if(is_array($options) && !empty($options)) {
		try {
			$url = $system->getUrlConcat($options);
		} catch(Exception $e) {
			$logger = new debug_logger(MP_LOG_DIR);
			$logger->log('minify', 'concat', "Error : $e", debug_logger::LOG_MONTH);
		}
	}

	return $url;
}
