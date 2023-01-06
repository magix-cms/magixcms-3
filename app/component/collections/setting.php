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
 * Date: 14/12/13
 * Time: 00:29
 * License: Dual licensed under the MIT or GPL Version
 */
class component_collections_setting {
	/**
	 * @var debug_logger $logger
	 */
	protected debug_logger $logger;

	/**
	 * @return array
	 */
	public function getSetting(): array {
		$settingsData = $this->fetchData(['context'=>'all','type'=>'setting']);
		$settings = [];
		if(!empty($settingsData)) {
			$settings = array_column($settingsData,'value','name');
			/*foreach ($settingsData as $setting) {
				$settings[$setting['name']] = $setting['value'];
			}*/
		}
		return $settings;
	}

	/**
	 * @return array
	 */
	public function getSettingData(): array {
		$settingsData = $this->fetchData(['context'=>'all','type'=>'setting']);
		$settings = [];
		if(!empty($settingsData)) {
			foreach ($settingsData as $setting) {
				$settings[$setting['name']] = [
					'value' => $setting['value'],
					'category' => $setting['category']
				];
			}
		}
		return $settings;
	}

    /**
     * @param array $config
     * @param array $params
     * @return array|bool
     */
    public function fetchData(array $config,array $params = []) {
        if($config['context'] === 'all') {
			switch ($config['type']) {
			    case 'setting':
					$query = 'SELECT st.name,st.value,st.category FROM mc_setting AS st';
					break;
			    case 'cssInliner':
					$query = 'SELECT color.* FROM mc_css_inliner as color';
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetchAll($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		elseif($config['context'] === 'one') {
			switch ($config['type']) {
				case 'setting':
					$query = 'SELECT * FROM mc_setting WHERE name = :name';
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetch($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}

		return false;
    }
}