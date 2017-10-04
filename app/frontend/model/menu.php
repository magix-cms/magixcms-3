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

	protected $data, $routingUrl, $modelPlugins, $language, $languages, $collectionLanguage;

	public function __construct($template)
	{
		$this->routingUrl = new component_routing_url();
		$this->modelPlugins = new frontend_model_plugins();
		$this->data = new frontend_model_data($this);
		$this->collectionLanguage = new component_collections_language();
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
	 * @param $iso
	 * @return array
	 */
	public function setLinksData($iso) {
		$links = $this->getItems('links',array('iso' => $iso),'all',false);

		foreach ($links as &$link) {
			switch ($link['type_link']) {
				case 'pages':
					$link['url_link']  =
						$this->routingUrl->getBuildUrl(array(
								'type'      =>  'pages',
								'iso'       =>  $link['iso_lang'],
								'id'        =>  $link['id_page'],
								'url'       =>  $link['url_link']
							)
						);
					break;
			}
		}

		return $links;
	}
}
?>