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
 * Date: 15/12/13
 * Time: 18:48
 * License: Dual licensed under the MIT or GPL Version
 */
class component_collections_language{
	/**
	 * @param $config
	 * @param bool $params
	 * @return mixed|null
	 * @throws Exception
	 */
	public function fetchData($config, $params = false)
    {
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		if ($config['context'] === 'all') {
			switch ($config['type']) {
			    case 'active':
					$sql = 'SELECT l.id_lang, l.iso_lang, l.name_lang
					   FROM mc_lang AS l
					   WHERE l.active_lang = 1
					   ORDER BY l.id_lang';
			    	break;
			    case 'langs':
					$sql = 'SELECT l.id_lang, l.iso_lang, l.name_lang, l.default_lang
					   FROM mc_lang AS l
					   WHERE l.active_lang = 1
					   ORDER BY l.default_lang DESC,l.id_lang ASC';
			    	break;
                case 'adminLangs':
                    $sql = 'SELECT l.id_lang, l.iso_lang, l.name_lang, l.default_lang
					   FROM mc_lang AS l
					   ORDER BY l.default_lang DESC,l.id_lang ASC';
                    break;
			    case 'domain':
					$sql = 'SELECT dl.*,lang.iso_lang, lang.name_lang
						FROM mc_domain_language AS dl
						JOIN mc_lang AS lang ON ( dl.id_lang = lang.id_lang )
						WHERE dl.id_domain = :id';
			    	break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
		}
		elseif($config['context'] === 'one') {
			switch ($config['type']) {
			    case 'default':
			    	$sql = 'SELECT id_lang,iso_lang FROM mc_lang as lang WHERE lang.default_lang = 1';
			    	break;
			    case 'currentDomain':
			    	$sql = 'SELECT d.* FROM mc_domain AS d WHERE d.url_domain = :url';
			    	break;
			    case 'isoFromId':
			    	$sql = 'SELECT *  FROM mc_lang as lang WHERE id_lang = :id';
			    	break;
			}

			return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
		}
    }
}