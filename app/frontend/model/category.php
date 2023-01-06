<?php
/*
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of MAGIX CMS.
# MAGIX CMS, The content management system optimized for users
# Copyright (C) 2008 - 2022 sc-box.com <support@magix-cms.com>
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
class frontend_model_category {
    /**
     * @var frontend_model_template $template
	 * @var frontend_model_plugins $modelPlugins
	 * @var frontend_model_seo $seo
	 * @var frontend_model_logo $logo
     * @var component_routing_url $routingUrl
	 * @var component_files_images $imagesComponent
     * @var component_format_math $math
     */
	protected frontend_model_template $template;
	protected frontend_model_plugins $modelPlugins;
	protected frontend_model_seo $seo;
	protected frontend_model_logo $logo;
	protected component_routing_url $routingUrl;
	protected component_files_images $imagesComponent;
	protected component_format_math $math;
	
    /**
     * @var array $imagePlaceHolder
     * @var array $defaultImage
     * @var array $imgPrefix
     * @var array $fetchConfig
     */
    protected array 
		$imagePlaceHolder,
		$defaultImage,
		$imgPrefix,
		$fetchConfig;

	/**
	 * @param null|frontend_model_template $t
	 */
    public function __construct(frontend_model_template $t = null) {
		$this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
		$this->routingUrl = new component_routing_url();
		$this->imagesComponent = new component_files_images($this->template);
		$this->modelPlugins = new frontend_model_plugins($this->template);
		$this->math = new component_format_math();
		$this->seo = new frontend_model_seo('catalog', 'parent', '',$this->template);
        $this->logo = new frontend_model_logo($this->template);
    }

	/**
	 * @return void
	 */
	private function initImageComponent() {
		if(!isset($this->imagePlaceHolder)) $this->imagePlaceHolder = $this->logo->getImagePlaceholder();
		if(!isset($this->imgPrefix)) $this->imgPrefix = $this->imagesComponent->prefix();
		if(!isset($this->fetchConfig)) $this->fetchConfig = $this->imagesComponent->getConfigItems([
			'module_img' => 'catalog',
			'attribute_img' => 'category'
		]);
		if(!isset($this->defaultImage)) $this->defaultImage = $this->imagesComponent->getConfigItems([
			'module_img' => 'logo',
			'attribute_img' => 'category'
		]);
	}

    /**
     * @param array $row
     * @param array $active
     * @param array $newRow
     * @return array
     */
    public function setItemData (array $row, array $active, array $newRow = []): array {
		$string_format = new component_format_string();
        $data = [];
        $extwebp = 'webp';
        $this->initImageComponent();

        if(!empty($row)) {
			$data['active'] = false;
			if ($row['id_cat'] == $active['controller']['id'] OR $row['id_cat'] == $active['controller']['id_parent'] ) {
				$data['active'] = true;
			}
			if (isset($row['img_cat'])) {
				// # return filename without extension
				$pathinfo = pathinfo($row['img_cat']);
				$filename = $pathinfo['filename'];
				foreach ($this->fetchConfig as $value) {
					$imginfo = $this->imagesComponent->getImageInfos(component_core_system::basePath().'/upload/catalog/c/'.$row['id_cat'].'/'.$this->imgPrefix[$value['type_img']] . $row['img_cat']);
					$data['img'][$value['type_img']]['src'] = '/upload/catalog/c/'.$row['id_cat'].'/'.$this->imgPrefix[$value['type_img']] . $row['img_cat'];
					if(file_exists(component_core_system::basePath().'/upload/catalog/c/'.$row['id_cat'].'/'.$this->imgPrefix[$value['type_img']] . $filename. '.' .$extwebp)) {
						$data['img'][$value['type_img']]['src_webp'] = '/upload/catalog/c/' . $row['id_cat'] . '/' . $this->imgPrefix[$value['type_img']] . $filename . '.' . $extwebp;
					}
					//$data['img'][$value['type_img']]['w'] = $value['width_img'];
					$data['img'][$value['type_img']]['w'] = $value['resize_img'] === 'basic' ? $imginfo['width'] : $value['width_img'];
					//$data['img'][$value['type_img']]['h'] = $value['height_img'];
					$data['img'][$value['type_img']]['h'] = $value['resize_img'] === 'basic' ? $imginfo['height'] : $value['height_img'];
					$data['img'][$value['type_img']]['crop'] = $value['resize_img'];
					$data['img'][$value['type_img']]['ext'] = mime_content_type(component_core_system::basePath().'/upload/catalog/c/'.$row['id_cat'].'/'.$this->imgPrefix[$value['type_img']] . $row['img_cat']);
				}
				$data['img']['name'] = $row['img_cat'];
			}
			$data['img']['alt'] = $row['alt_img'];
			$data['img']['title'] = $row['title_img'];
			$data['img']['caption'] = $row['caption_img'];
			$data['img']['default'] = [
				'src' => isset($this->imagePlaceHolder['category']) ? $this->imagePlaceHolder['category'] : '/skin/'.$this->template->theme.'/img/catalog/c/default.png',
				'w' => $this->defaultImage[0]['width_img'],
				'h' => $this->defaultImage[0]['height_img']
			];
			$data['url'] = $this->routingUrl->getBuildUrl([
				'type' => 'category',
				'iso'  => $row['iso_lang'],
				'id'   => $row['id_cat'],
				'url'  => $row['url_cat']
			]);
			// Base url for category
			$data['baseUrl']   = $row['url_cat'];
			$data['id']        = $row['id_cat'];
			$data['id_parent'] = !is_null($row['id_parent']) ? $row['id_parent'] : NULL;
			$data['id_lang']   = $row['id_lang'];
			$data['iso']       = $row['iso_lang'];
			$data['name']      = $row['name_cat'];
			$data['content']   = $row['content_cat'];
			$data['resume']    = $row['resume_cat'] ? $row['resume_cat'] : ($row['content_cat'] ? $string_format->truncate(strip_tags($row['content_cat'])) : '');
			$data['menu']      = $row['menu_cat'];
			$data['order']     = $row['order_cat'];
			$data['nb_product']= $row['nb_product'];
			// Plugin
			if(!empty($newRow)){
				foreach($newRow as $key => $value){
					$data[$key] = $row[$value];
				}
			}

			if (!isset($row['seo_title_cat']) || empty($row['seo_title_cat'])) {
				$seoTitle = $this->seo->replace_var_rewrite($data['name'],'','title');
				$data['seo']['title'] = $seoTitle ? $seoTitle : $data['name'];
			}
			else {
				$data['seo']['title'] = $row['seo_title_cat'];
			}
			if (!isset($row['seo_desc_cat']) || empty($row['seo_desc_cat'])) {
				$seoDesc = $this->seo->replace_var_rewrite($data['name'],'','description');
				$data['seo']['description'] = $seoDesc ? $seoDesc : ($data['resume'] ? $data['resume'] : $data['seo']['title']);
			}
			else {
				$data['seo']['description'] = $row['seo_desc_cat'];
			}
        }
		return $data;
    }

    /**
     * @param array $row
     * @return array
     */
    public function setItemShortData (array $row): array {
        $data = [];
        if (!empty($row)) {
			$data['id'] = $row['id_cat'];
			$data['url'] = $this->routingUrl->getBuildUrl([
				'type' => 'category',
				'iso'  => $row['iso_lang'],
				'id'   => $row['id_cat'],
				'url'  => $row['url_cat']
			]);
			// Base url for category
			$data['id_parent'] = !is_null($row['id_parent']) ? $row['id_parent'] : NULL;
			$data['name'] = $row['name_cat'];
        }
		return $data;
    }

    /**
     * @param array $row
     * @return array
     */
    public function setHrefLangCategoryData(array $row): array {
        $arr = [];
        foreach ($row as $item) {
            $arr[$item['id_lang']] = $this->routingUrl->getBuildUrl([
				'type'      =>  'category',
				'iso'       =>  $item['iso_lang'],
				'id'        =>  $item['id_cat'],
				'url'       =>  $item['url_cat']
			]);
        }
        return $arr;
    }
}