<?php
/*
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of MAGIX CMS.
# MAGIX CMS, The content management system optimized for users
# Copyright (C) 2008 - 2013 sc-box.com <support@magix-cms.com>
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
class frontend_model_menu extends frontend_db_menu {

	protected $template, $data, $routingUrl, $modelPlugins, $language, $languages, $collectionLanguage, $modelSystem;

	public $about, $pages, $catalog, $category, $plugin, $current, $controller, $id, $id_parent;

	/**
	 * frontend_model_menu constructor.
	 * @param null|frontend_model_template $t
	 */
	public function __construct($t = null)
	{
		$this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
		$this->routingUrl = new component_routing_url();
		$this->modelPlugins = new frontend_model_plugins($this->template);
		$this->data = new frontend_model_data($this,$this->template);
		$this->collectionLanguage = new component_collections_language();
		$this->modelSystem = new frontend_model_core($this->template);
		$this->current = $this->modelSystem->setCurrentId();
		$this->controller = $this->current['controller']['name'];
		$this->id = $this->current['controller']['id'];
		$this->id_parent = $this->current['controller']['id_parent'];
	}

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param string|int|null $id
	 * @param string $context
	 * @param boolean $assign
	 * @return mixed
	 */
	private function getItems($type, $id = null, $context = null, $assign = true) {
		return $this->data->getItems($type, $id, $context, $assign);
	}

	/**
	 * @param $params
	 * @return array
	 */
	public function getPluginPages($params)
	{
		$plugin = $this->getItems('plugin',array('id' => $params['id']),'one',false);
		$plugin_class = 'plugins_'.$plugin['name'].'_public';
		$plugin = new $plugin_class($this->template);
		return $plugin->submenu($params['lang']);
	}

	/**
	 * @param $params
	 * @return array
	 */
	public function getControllerId($params)
	{
		$plugin = $this->getItems('plugin_id',array('name' => $params['name']),'one',false);
		return $plugin['id'];
	}

	/**
	 * @param $params
	 * @return bool
	 */
	public function getPluginMenuConf($name)
	{
		$plugin_class = 'plugins_'.$name.'_public';
		$plugin = new $plugin_class($this->template);
		return (method_exists($plugin,'is_amp')) ? $plugin->is_amp() : false;
	}

	/**
	 * @param $iso
	 * @return array
	 */
	public function setLinksData($iso) {
		$links = $this->getItems('links',array('iso' => $iso),'all',false);
		$active = array('controller' => $this->controller, 'ids' => array());
		$catalog = null;
		$pages = null;

		foreach ($links as &$link) {
			switch ($link['type_link']) {
				case 'home':
					$link['controller'] = null;
					break;
				case 'pages':
					$link['controller'] = 'pages';
					$link['url_link'] =
						$this->routingUrl->getBuildUrl(array(
								'type' => 'pages',
								'iso'  => $link['iso_lang'],
								'id'   => $link['id_page'],
								'url'  => $link['url_link']
							)
						);
					break;
				case 'about_page':
					$link['controller'] = 'about';
					$link['url_link'] =
						$this->routingUrl->getBuildUrl(array(
								'type' => 'about',
								'iso'  => $link['iso_lang'],
								'id'   => $link['id_page'],
								'url'  => $link['url_link']
							)
						);
					break;
				case 'category':
					$link['controller'] = 'catalog';
					$link['url_link'] =
						$this->routingUrl->getBuildUrl(array(
								'type' => 'category',
								'iso'  => $link['iso_lang'],
								'id'   => $link['id_page'],
								'url'  => $link['url_link']
							)
						);
					break;
				case 'plugin': $link['controller'] = $link['plugin_name']; break;
				default: $link['controller'] = $link['type_link'];
			}

			if(in_array($link['type_link'],array('home','pages','about','about_page','catalog','category','news'))) {
				$link['amp_available'] = true;
			}
			elseif($link['type_link'] === 'plugin') {
				$link['amp_available'] = $this->getPluginMenuConf($link['plugin_name']);
			}

			if($link['mode_link'] !== 'simple') {
				$data = null;
				$model = null;

				switch ($link['type_link']) {
					case 'home':
					case 'pages':
						if($pages === null) {
							if(!$this->pages) $this->pages = new frontend_model_pages($this->template);
							$conf = array(
								'context' => 'all',
								'type' => 'menu',
								'select' => 'tree'
							);
							$pages = $this->pages->getShortData(
								$conf,
								$this->current
							);
						}
						$model = $this->pages;
						if($link['type_link'] === 'pages') {
							$data = $pages[$link['id_page']]['subdata'];
						}
						else {
							$data = $pages['root'];
						}
						break;
					case 'about':
					case 'about_page':
						if(!$this->about) $this->about = new frontend_model_about($this->template);
						$model = $this->about;
						$conf = array(
							'context' => 'all',
							'type' => 'menu'
						);
						if($link['type_link'] === 'about_page') $conf['select'] = $link['id_page'];
						$data = $this->about->getShortData(
							$conf,
							$this->current
						);
						if($link['type_link'] === 'about_page') $data = $data[0]['subdata'];
						break;
					case 'catalog':
					case 'category':
						if($catalog === null) {
							if(!$this->catalog) $this->catalog = new frontend_model_catalog($this->template);
							$conf = array(
								'context' => 'category',
								'type' => 'menu',
								'select' => 'tree',
								'deepness' => $link['mode_link'] === 'dropdown' ? 1 : 2
							);
							$catalog = $this->catalog->getShortData(
								$conf,
								$this->current
							);
						}
						$model = $this->catalog;
						if($link['type_link'] === 'category') {
							$data = $catalog[$link['id_page']]['subdata'];
						}
						else {
							$data = $catalog['root'];
						}
						break;
					case 'plugin':
						$link['subdata'] = $this->getPluginPages(
							array(
								'type' => 'plugin',
								'id' => $link['id_page'],
								'lang' => $iso
							)
						);
						break;
				}

				if($data) $link['subdata'] = $this->data->parseData($data,$model,$this->current,[],true);
			}
		}

		switch ($this->controller) {
			case 'home':
			case 'news':
				break;
			case 'about':
				if(!$this->about) $this->about = new frontend_model_about($this->template);
				if($this->id || $this->id_parent) $active['ids'] = $this->about->getParents($this->id_parent ? $this->id_parent : $this->id);
				break;
			case 'pages':
				if(!$this->pages) $this->pages = new frontend_model_pages($this->template);
				if($this->id || $this->id_parent) $active['ids'] = $this->pages->getParents($this->id_parent ? $this->id_parent : $this->id);
				break;
			case 'catalog':
				if(!$this->catalog) $this->catalog = new frontend_model_catalog($this->template);
				if($this->id || $this->id_parent) $active['ids'] = $this->catalog->getParents($this->id_parent ? $this->id_parent : $this->id);
				break;
			default:
				if(!empty($this->controller)) $active['ids'] = array($this->getControllerId(array('name' => $this->controller)));
		}

		$this->template->assign('active_link',$active);
		$this->template->assign('links',$links);
	}
}