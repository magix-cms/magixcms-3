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
class backend_db_setting {
	/**
	 * @var debug_logger $logger
	 */
	protected debug_logger $logger;

    /**
     * @param array $config
     * @param array $params
     * @return array|bool
     */
    public function fetchData(array $config, array $params = []) {
		if($config['context'] === 'all') {
			switch ($config['type']) {
				case 'settings':
					$query = 'SELECT * FROM mc_setting';
					break;
				case 'cssinliner':
					$query = 'SELECT * FROM mc_css_inliner';
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
				case 'skin':
					$query = "SELECT * FROM mc_setting WHERE name = 'theme'";
					break;
				case 'setting':
					$query = 'SELECT * FROM mc_setting WHERE name = :setting';
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

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	public function update(array $config, array $params = []) {
		switch ($config['type']) {
			case 'advanced':
				$query = "UPDATE `mc_setting`
						SET `value` = CASE `name`
							WHEN 'content_css' THEN :content_css
							WHEN 'concat' THEN :concat
							WHEN 'ssl' THEN :ssl
							WHEN 'http2' THEN :http2
							WHEN 'service_worker' THEN :service_worker
							WHEN 'cache' THEN :cache
							WHEN 'mode' THEN :mode
						    WHEN 'amp' THEN :amp
						    WHEN 'maintenance' THEN :maintenance
						    WHEN 'geminiai' THEN :geminiai
						END
						WHERE `name` IN ('content_css','concat','ssl','http2','service_worker','cache','mode','amp','geminiai')";
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
					$query = "UPDATE mc_setting SET value = :css_inliner WHERE name = 'css_inliner'";
				}
				break;
			case 'theme':
				$query = "UPDATE mc_setting SET value = :theme WHERE name = 'theme'";
				break;
			case 'google':
				$query = "UPDATE `mc_setting`
						SET `value` = CASE `name`
							WHEN 'analytics' THEN :analytics
							WHEN 'robots' THEN :robots
						END
						WHERE `name` IN ('analytics','robots')";
				break;
            case 'catalog':
                $query = "UPDATE `mc_setting`
						SET `value` = CASE `name`
							WHEN 'product_per_page' THEN :product_per_page
							WHEN 'vat_rate' THEN :vat_rate
							WHEN 'price_display' THEN :price_display
						END
						WHERE `name` IN ('product_per_page','vat_rate','price_display')";
                break;
            case 'news':
                $query = "UPDATE `mc_setting`
						SET `value` = CASE `name`
							WHEN 'news_per_page' THEN :news_per_page
						END
						WHERE `name` IN ('news_per_page')";
                break;
            case 'mail':
                $query = "UPDATE `mc_setting`
						SET `value` = CASE `name`
							WHEN 'mail_sender' THEN :mail_sender
							WHEN 'smtp_enabled' THEN :smtp_enabled
						    WHEN 'set_host' THEN :set_host
						    WHEN 'set_port' THEN :set_port
						    WHEN 'set_encryption' THEN :set_encryption
						    WHEN 'set_username' THEN :set_username
						    WHEN 'set_password' THEN :set_password
						END
						WHERE `name` IN ('mail_sender','smtp_enabled','set_host','set_port','set_encryption','set_username','set_password')";
                break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->update($query,$params);
			return true;
		}
        catch (Exception $e) {
            if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
            $this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
        }
        return false;
    }
}