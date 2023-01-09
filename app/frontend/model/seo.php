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
	 * @var frontend_model_template $template
	 * @var frontend_model_data $data
	 */
	protected frontend_model_template $template;
	protected frontend_model_data $data;

	/**
	 * @var array $seoVar String memory
	 */
	protected array $seoVar = [];

	/**
	 * @var string $attribute Définition de l'attribut
	 * @var string $level Définition du niveau
	 * @var string $type Définition du style de métas
	 * @var string $iso Définition de la langue
	 */
	public string
		$attribute,
		$level,
		$type,
		$iso;

	/**
	 * frontend_model_seo constructor.
	 * @param string $attribute
	 * @param string $level
	 * @param string $type
	 * @param null|frontend_model_template $t
	 */
	public function __construct(string $attribute, string $level, string $type, frontend_model_template $t = null){
		$this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
		$this->data = new frontend_model_data($this,$this->template);
		$this->attribute = $attribute;
		$this->level = $level;
		$this->type = $type;
		$this->iso = $this->template->lang;
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
	public function replace_var_rewrite(string $parent = '', string $record = '', string $type  = '', $extend = false){
		$content = null;
		if($type === '') $type = $this->type;

		if(!isset($this->seoVar[$this->iso])
            || !isset($this->seoVar[$this->iso][$this->attribute])
            || !isset($this->seoVar[$this->iso][$this->attribute][$this->level])
            || !is_array($this->seoVar[$this->iso][$this->attribute][$this->level])
            || !key_exists($type, $this->seoVar[$this->iso][$this->attribute][$this->level])) {

			//$db = $this->getItems('replace',['attribute' => $this->attribute, 'lvl' => $this->level, 'type' => $type, 'iso' => $this->iso],'one',false);
			$db = $this->getItems('replace',['attribute' => $this->attribute, 'lvl' => $this->level, 'iso' => $this->iso],'all',false);
			$seoConfig = [];
			foreach ($db as $config) {
				$seoConfig[$config['type_seo']] = $config;
			}

			if(!key_exists($this->iso, $this->seoVar)) $this->seoVar[$this->iso] = [];
			if(!key_exists($this->attribute, $this->seoVar[$this->iso])) $this->seoVar[$this->iso][$this->attribute] = [];
			if(!key_exists($this->level, $this->seoVar[$this->iso][$this->attribute])) $this->seoVar[$this->iso][$this->attribute][$this->level] = [];

			$this->seoVar[$this->iso][$this->attribute][$this->level]['title'] = (isset($seoConfig['title']['content_seo']) && !empty($seoConfig['title']['content_seo'])) ? $seoConfig['title']['content_seo'] : null;
			$this->seoVar[$this->iso][$this->attribute][$this->level]['description'] = (isset($seoConfig['description']['content_seo']) && !empty($seoConfig['description']['content_seo'])) ? $seoConfig['description']['content_seo'] : null;
		}

		$string = $this->seoVar[$this->iso][$this->attribute][$this->level][$type];

		if($string !== null){
			//Tableau des variables à rechercher
			$search = array('[[PARENT]]','[[RECORD]]');
			//Tableau des variables à remplacer
			$replace = array($parent, $record);
            //$extend = array('[[DATE]]'=>'06-01-2021','[[NAME]]'=>'my page');
            if(isset($extend) AND is_array($extend)){
                foreach($extend as $key => $value){
                    $search_extend[] = $key;
                    $replace_extend[] = $value;
                }
                $search = array_merge($search,$search_extend);
                $replace = array_merge($replace,$replace_extend);
            }
			//texte générique à remplacer
			$content = str_replace($search ,$replace, $string);
		}

		return $content;
	}
}