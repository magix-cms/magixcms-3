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
class backend_db_theme {
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
				case 'links':
					/*$query = "SELECT
							m.id_link as id_link,
							m.id_page,
							m.type_link as type_link,
							m.mode_link as mode_link,
							mc.id_lang,
							COALESCE(mc.name_link, pc.name_pages, apc.name_pages, cc.name_cat, pl.name) as name_link,
							mc.title_link as title_link,
							COALESCE(mc.url_link, pc.url_pages, apc.url_pages, cc.url_cat) as url_link,
							COALESCE(pc.published_pages, apc.published_pages, cc.published_cat, 1) as active_link
						FROM mc_menu as m
						LEFT JOIN mc_menu_content as mc ON m.id_link = mc.id_link
						LEFT JOIN mc_lang as l ON mc.id_lang = l.id_lang
						LEFT JOIN mc_cms_page as p ON m.id_page = p.id_pages AND m.type_link = 'pages'
						LEFT JOIN mc_cms_page_content as pc ON p.id_pages = pc.id_pages AND pc.id_lang = l.id_lang
						LEFT JOIN mc_about_page as ap ON m.id_page = ap.id_pages AND m.type_link = 'about_page'
						LEFT JOIN mc_about_page_content as apc ON ap.id_pages = apc.id_pages AND apc.id_lang = l.id_lang
						LEFT JOIN mc_catalog_cat as c ON m.id_page = c.id_cat AND m.type_link = 'category'
						LEFT JOIN mc_catalog_cat_content as cc ON c.id_cat = cc.id_cat AND cc.id_lang = l.id_lang
						LEFT JOIN mc_plugins as pl ON m.id_page = pl.id_plugins
						ORDER BY m.order_link ASC";*/
					$query = "SELECT 
m.id_link, 
m.id_page, 
m.type_link as type_link, 
m.mode_link as mode_link, 
lg.id_lang, 
COALESCE(mc.name_link, pc.name_pages, apc.name_pages, cc.name_cat, pl.name) as name_link, 
mc.title_link as title_link,
COALESCE(mc.url_link, pc.url_pages, apc.url_pages, cc.url_cat) as url_link,
COALESCE(pc.published_pages, apc.published_pages, cc.published_cat, 1) as active_link
FROM mc_menu as m
LEFT JOIN mc_menu_content as mc ON m.id_link = mc.id_link
LEFT JOIN mc_cms_page as p ON (m.id_page = p.id_pages AND m.type_link = 'pages')
LEFT JOIN mc_cms_page_content as pc ON (p.id_pages = pc.id_pages)
LEFT JOIN mc_about_page as ap ON (m.id_page = ap.id_pages AND m.type_link = 'about_page')
LEFT JOIN mc_about_page_content as apc ON (ap.id_pages = apc.id_pages)
LEFT JOIN mc_catalog_cat as c ON (m.id_page = c.id_cat AND m.type_link = 'category')
LEFT JOIN mc_catalog_cat_content as cc ON (c.id_cat = cc.id_cat)
LEFT JOIN mc_lang as lg ON (
	mc.id_lang = lg.id_lang OR
	pc.id_lang = lg.id_lang OR
	apc.id_lang = lg.id_lang OR
	cc.id_lang = lg.id_lang
)
LEFT JOIN mc_plugins as pl ON m.id_page = pl.id_plugins
WHERE lg.active_lang = 1
ORDER BY m.order_link, lg.id_lang";
					break;
				case 'link':
					$query = "SELECT 
							m.id_link as id_link, 
							m.id_page, 
							m.type_link as type_link, 
							m.mode_link as mode_link, 
							mc.id_lang, 
							COALESCE(mc.name_link, pc.name_pages, apc.name_pages, cc.name_cat, pl.name) as name_link, 
							mc.title_link as title_link,
							COALESCE(mc.url_link, pc.url_pages, apc.url_pages, cc.url_cat) as url_link,
							COALESCE(pc.published_pages, apc.published_pages, cc.published_cat, 1) as active_link
						FROM mc_menu as m
						LEFT JOIN mc_menu_content as mc ON m.id_link = mc.id_link
						LEFT JOIN mc_lang as l ON mc.id_lang = l.id_lang
						LEFT JOIN mc_cms_page as p ON m.id_page = p.id_pages AND m.type_link = 'pages'
						LEFT JOIN mc_cms_page_content as pc ON p.id_pages = pc.id_pages AND pc.id_lang = l.id_lang
						LEFT JOIN mc_about_page as ap ON m.id_page = ap.id_pages AND m.type_link = 'about_page'
						LEFT JOIN mc_about_page_content as apc ON ap.id_pages = apc.id_pages AND apc.id_lang = l.id_lang
						LEFT JOIN mc_catalog_cat as c ON m.id_page = c.id_cat AND m.type_link = 'category'
						LEFT JOIN mc_catalog_cat_content as cc ON c.id_cat = cc.id_cat AND cc.id_lang = l.id_lang
						LEFT JOIN mc_plugins as pl ON m.id_page = pl.id_plugins
						WHERE m.id_link = :id";
					break;
				case 'pages':
					$query = 'SELECT * FROM (
						SELECT p.id_pages AS id, p.id_parent AS parent, pc.name_pages AS name
						FROM mc_cms_page AS p
						LEFT JOIN mc_cms_page_content AS pc
						USING ( id_pages ) 
						LEFT JOIN mc_lang AS l ON pc.id_lang = l.id_lang
						WHERE p.menu_pages =1
						AND pc.published_pages =1
						ORDER BY p.id_pages, l.default_lang DESC
						) as pt
						GROUP BY pt.id';
					break;
				case 'about_page':
					$query = 'SELECT * FROM (
						SELECT p.id_pages as id, p.id_parent as parent, pc.name_pages as name
						FROM mc_about_page as p
						LEFT JOIN mc_about_page_content as pc
						USING(id_pages)
						LEFT JOIN mc_lang AS l ON pc.id_lang = l.id_lang
						WHERE p.menu_pages =1
						AND pc.published_pages =1
						ORDER BY p.id_pages, l.default_lang DESC
						) as pt
						GROUP BY pt.id';
					break;
				case 'category':
					$query = 'SELECT * FROM (
						SELECT p.id_cat as id, p.id_parent as parent, pc.name_cat as name
						FROM mc_catalog_cat as p
						LEFT JOIN mc_catalog_cat_content as pc
						USING(id_cat)
						LEFT JOIN mc_lang AS l ON pc.id_lang = l.id_lang
						WHERE pc.published_cat =1
						ORDER BY p.id_cat, l.default_lang DESC
						) as pt
						GROUP BY pt.id';
					break;
				case 'plugin':
					$query = 'SELECT id_plugins as id, name FROM mc_plugins';
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
				case 'newLink':
					$query = 'SELECT id_link FROM mc_menu ORDER BY id_link DESC LIMIT 0,1';
					break;
				case 'link':
					$query = 'SELECT * FROM mc_menu as m WHERE id_link = :id';
					break;
				case 'link_content':
					$query = 'SELECT * FROM mc_menu as m LEFT JOIN mc_menu_content as mc USING(id_link) WHERE id_link = :id AND id_lang = :lang';
					break;
				case 'pages':
					$query = 'SELECT p.id_pages as id, pc.name_pages as name, pc.url_pages as url
						FROM mc_cms_page as p
						LEFT JOIN mc_cms_page_content as pc
						USING(id_pages)
						WHERE id_lang = :id_lang
						AND id_pages = :id
						AND pc.published_pages = 1';
					break;
				case 'about_page':
					$query = 'SELECT p.id_pages as id, pc.name_pages as name, pc.url_pages as url
						FROM mc_about_page as p
						LEFT JOIN mc_about_page_content as pc
						USING(id_pages)
						WHERE id_lang = :id_lang
						AND id_pages = :id
						AND pc.published_pages = 1';
					break;
				case 'category':
					$query = 'SELECT p.id_cat as id, pc.name_cat as name, pc.url_cat as url
						FROM mc_catalog_cat as p
						LEFT JOIN mc_catalog_cat_content as pc
						USING(id_cat)
						WHERE id_lang = :id_lang
						AND id_cat = :id
						AND pc.published_cat = 1';
					break;
				case 'plugin':
					$query = 'SELECT id_plugins as id, name FROM mc_plugins WHERE id_plugins = :id';
					break;
				case 'shareConfig':
					$query = 'SELECT 
							facebook,
							twitter,
							viadeo,
							google,
							linkedin,
							pinterest,
							twitter_id
						FROM mc_share_config 
						LIMIT 0,1';
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
	public function insert(array $config, array $params = []) {
		switch ($config['type']) {
			case 'link':
				$query = "INSERT INTO `mc_menu`(type_link, id_page, order_link)  
					SELECT :type, :id_page, (IFNULL(MAX(order_link),0) + 1) FROM mc_menu";
				break;
			case 'link_content':
				$query = 'INSERT INTO `mc_menu_content`(id_link,id_lang,name_link,title_link,url_link) 
					VALUES (:id,:id_lang,:name_link,:title_link,:url_link)';
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->insert($query,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	public function update(array $config, array $params = []) {
		switch ($config['type']) {
			case 'theme':
				$query = "UPDATE mc_setting SET value = :theme WHERE name = 'theme'";
				break;
			case 'share':
				$query = "UPDATE mc_share_config 
					SET 
						facebook = :facebook,
						twitter = :twitter,
						viadeo = :viadeo,
						google = :google,
						linkedin = :linkedin,
						pinterest = :pinterest,
						twitter_id = :twitter_id
					WHERE id_share = 1";
				break;
			case 'link':
				$query = 'UPDATE mc_menu 
					SET mode_link = :mode_link
					WHERE id_link = :id';
				break;
			case 'link_content':
				$query = 'UPDATE mc_menu_content 
					SET 
						name_link = :name_link,
						title_link = :title_link
					WHERE id_link = :id
					AND id_lang = :id_lang';
				break;
            case 'link_content_url':
                $query = 'UPDATE mc_menu_content 
					SET 
						name_link = :name_link,
						title_link = :title_link,
					    url_link = :url_link
					WHERE id_link = :id
					AND id_lang = :id_lang';
                break;
			case 'order':
				$query = 'UPDATE mc_menu 
					SET order_link = :order_link
					WHERE id_link = :id';
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->update($query,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	public function delete(array $config, array $params = []) {
		switch ($config['type']) {
			case 'link':
				$query = 'DELETE FROM `mc_menu` WHERE `id_link` IN ('.$params['id'].')';
				$params = array();
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->delete($query,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}
}