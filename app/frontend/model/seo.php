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
class frontend_model_seo extends frontend_db_seo {
	/**
	 * @var backend_model_data
	 */
	protected $data, $template;

	/**
	 * Définition de l'attribut
	 * @var $attribute
	 */
	public $attribute,
		/**
		 * Définition du niveau
		 */
		$level,
		/**
		 * Définition du style de métas
		 */
		$type,
		/**
		 * Définition de la langue
		 */
		$iso;

	/**
	 * frontend_model_seo constructor.
	 * @param $attribute
	 * @param $level
	 * @param $type
	 */
	public function __construct($attribute, $level, $type){
		$this->data = new frontend_model_data($this);
		$this->template = new frontend_model_template();

		$this->attribute = $attribute;
		$this->level = $level;
		$this->type = $type;
		$this->iso = $this->template->currentLanguage();
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
	 * @access public
	 * Remplace les données en tre crochet pour construire la réécriture
	 * @param string $parent
	 * @param string $record
	 * @param string $type
	 * @return mixed
	 */
	public function replace_var_rewrite($parent='', $record='', $type =''){
		if($type === '') $type = $this->type;
		$db = $this->getItems('replace',array('attribute' => $this->attribute, 'lvl' => $this->level, 'type' => $type, 'iso' => $this->iso),'one',false);

		if($db != null){
			//Tableau des variables à rechercher
			$search = array('[[PARENT]]','[[RECORD]]');
			//Tableau des variables à remplacer
			$replace = array($parent, $record);
			//texte générique à remplacer
			$content = str_replace($search ,$replace, $db['content_seo']);
			return $content;
		}
	}
}