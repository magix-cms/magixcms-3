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
class backend_db_setting{
    /**
     * @param $config
     * @param bool $params
     * @return mixed
     * @throws Exception
     */
    public function fetchData($config, $params = false)
	{
		if (!is_array($config)) return '$config must be an array';

        $sql = '';

		if($config['context'] === 'all') {
			switch ($config['type']) {
				case 'settings':
					$sql = 'SELECT * FROM mc_setting';
					break;
				case 'cssinliner':
					$sql = 'SELECT * FROM mc_cssinliner';
					break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
		}
		elseif($config['context'] === 'one') {
			switch ($config['type']) {
				case 'skin':
					$sql = 'SELECT * FROM mc_setting WHERE name = "theme"';
					break;
				case 'setting':
					$sql = 'SELECT * FROM mc_setting WHERE name = :setting';
					break;
			}

			return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
		}
    }

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
	public function update($config, $params = array())
    {
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
			case 'general':
				$sql = "UPDATE `mc_setting`
						SET `value` = CASE `name`
							WHEN 'content_css' THEN :content_css
							WHEN 'concat' THEN :concat
							WHEN 'ssl' THEN :ssl
							WHEN 'service_worker' THEN :service_worker
							WHEN 'cache' THEN :cache
							WHEN 'mode' THEN :mode
						END
						WHERE `name` IN ('content_css','concat','ssl','service_worker','cache','mode')";
				break;
			case 'css_inliner':
				if($params['css_inliner'] != '0') {
					$queries = array(
						array(
							'request'=>"UPDATE mc_setting SET value = :css_inliner WHERE name = 'css_inliner'",
							'params'=>array(':css_inliner' => $params['css_inliner'])
						),
						array(
							'request'=>"UPDATE mc_css_inliner SET color_cssi = :header_bg WHERE property_cssi = 'header_bg'",
							'params'=>array(':header_bg' => $params['header_bg'])
						),
						array(
							'request'=>"UPDATE mc_css_inliner SET color_cssi = :header_c WHERE property_cssi = 'header_c'",
							'params'=>array(':header_c' => $params['header_c'])
						),
						array(
							'request'=>"UPDATE mc_css_inliner SET color_cssi = :footer_bg WHERE property_cssi = 'footer_bg'",
							'params'=>array(':footer_bg' => $params['footer_bg'])
						),
						array(
							'request'=>"UPDATE mc_css_inliner SET color_cssi = :footer_c WHERE property_cssi = 'footer_c'",
							'params'=>array(':footer_c' => $params['footer_c'])
						)
					);

					try {
						component_routing_db::layer()->transaction($queries);
						return true;
					}
					catch (Exception $e) {
						return 'Exception reçue : '.$e->getMessage();
					}
				}
				else {
					$sql = "UPDATE mc_setting SET value = :css_inliner WHERE name = 'css_inliner'";
				}
				break;
			case 'theme':
				$sql = "UPDATE mc_setting SET value = :theme WHERE name = 'theme'";
				break;
			case 'google':
				$sql = "UPDATE `mc_setting`
						SET `value` = CASE `name`
							WHEN 'analytics' THEN :analytics
							WHEN 'robots' THEN :robots
						END
						WHERE `name` IN ('analytics','robots')";
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->update($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }

	/**
	 * singleton dbnews
	 * @access public
	 * @var void
	 */
	/*static public $publicdbsetting;
	/**
	 * instance backend_db_home with singleton
	 */
	/*public static function publicDbSetting(){
        if (!isset(self::$publicdbsetting)){
         	self::$publicdbsetting = new backend_db_setting();
        }
    	return self::$publicdbsetting;
    }

	/**
	 * Retourne le setting selectionner
	 * @param $setting_id (string) identifiant du setting
	 * @return mixed
	 */
    /*public function s_uniq_setting_value($setting_id){
    	$sql = 'SELECT setting_value FROM ap_setting WHERE setting_id = :setting_id';
		return component_routing_db::layer()->fetch($sql,array(':setting_id'	=>	$setting_id));
    }

    /**
	 * Return all settings
	 * @return mixed
	 */
    /*public function s_all_settings(){
    	$sql = 'SELECT setting_id,setting_value FROM ap_setting';
		return component_routing_db::layer()->fetchAll($sql);
    }

    /**
     * @return array
     */
    /*public function fetchCSSIColor(){
        $sql = 'SELECT color.*
    	FROM ap_css_inliner_color as color';
        return component_routing_db::layer()->fetchAll($sql);
    }*/
}