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
 * @category   DB CLass 
 * @package    Magix CMS
 * @copyright  MAGIX CMS Copyright (c) 2010 Gerits Aurelien, 
 * http://www.magix-cms.com, http://www.magix-cjquery.com
 * @license    Dual licensed under the MIT or GPL Version 3 licenses.
 * @version    1.2.0
 * @author Gérits Aurélien <aurelien@magix-cms.com> <aurelien@magix-dev.be>
 *
 */
class frontend_db_setting {
	/**
	 * @param $config
	 * @param bool $data
	 * @return mixed|null
	 */
	public function fetchData($config, $data = false)
	{
		$sql = '';
		$params = false;

		if (is_array($config)) {
			if ($config['context'] === 'all') {
				if ($config['type'] === 'color') {
					$sql = 'SELECT color.* FROM mc_css_inliner as color';
				}

				return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
			}
			elseif ($config['context'] === 'one') {
				if ($config['type'] === 'setting') {
					//Return current skin
					$sql = 'SELECT value FROM mc_setting WHERE name = :setting';
					$params = $data;
				}

				return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
			}
		}
	}
}