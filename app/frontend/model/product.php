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
class frontend_model_product {
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
		$this->seo = new frontend_model_seo('catalog', 'record', '',$this->template);
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
			'attribute_img' => 'product'
		]);
		if(!isset($this->defaultImage)) $this->defaultImage = $this->imagesComponent->getConfigItems([
			'module_img' => 'logo',
			'attribute_img' => 'product'
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
			//$subcat['id']   = (isset($row['idcls'])) ? $row['idcls'] : null;
			//$subcat['name'] = (isset($row['pathslibelle'])) ? $row['pathslibelle'] : null;
			$data['short_name']= $row['name_p'];
			$data['name']      = $row['name_p'];
			$data['long_name'] = $row['longname_p'];
			$data['url'] = $this->routingUrl->getBuildUrl(array(
				'type'       => 'product',
				'iso'        => $row['iso_lang'],
				'id'         => $row['id_product'],
				'url'        => $row['url_p'],
				'id_parent'  => $row['id_cat'],
				'url_parent' => $row['url_cat']
			));
			// Base url for product
			$data['baseUrl']       = $row['url_p'];
			$data['active'] = false;
			if ($row['id_product'] == $active['controller']['id']) {
				$data['active'] = true;
			}
			$data['id']        = $row['id_product'];
			$data['id_parent'] = $row['id_cat'];
			$data['url_parent'] = $this->routingUrl->getBuildUrl(array(
				'type' => 'category',
				'iso'  => $row['iso_lang'],
				'id'   => $row['id_cat'],
				'url'  => $row['url_cat']
			));
			$data['cat']       = $row['name_cat'];
			$data['id_lang']   = $row['id_lang'];
			$data['iso']       = $row['iso_lang'];
			$data['price']     = $row['price_p'];
			$data['reference'] = $row['reference_p'];
			$data['content']   = $row['content_p'];
			$data['resume']    = $row['resume_p'] ? $row['resume_p'] : ($row['content_p'] ? $string_format->truncate(strip_tags($row['content_p'])) : '');
			$data['order']     = isset($row['order_p']) ? $row['order_p'] : null;
			if (isset($row['img'])) {
				if(is_array($row['img'])) {
					foreach ($row['img'] as $item => $val) {
						// # return filename without extension
						$pathinfo = pathinfo($val['name_img']);
						$filename = $pathinfo['filename'];
						$data['imgs'][$item]['img']['alt'] = $val['alt_img'];
						$data['imgs'][$item]['img']['title'] = $val['title_img'];
						$data['imgs'][$item]['img']['caption'] = $val['caption_img'];
						$data['imgs'][$item]['img']['name'] = $val['name_img'];
						foreach ($this->fetchConfig as $key => $value) {
							$imginfo = $this->imagesComponent->getImageInfos(component_core_system::basePath().'/upload/catalog/p/' . $val['id_product'] . '/' . $this->imgPrefix[$value['type_img']] . $val['name_img']);
							$data['imgs'][$item]['img'][$value['type_img']]['src'] = '/upload/catalog/p/' . $val['id_product'] . '/' . $this->imgPrefix[$value['type_img']] . $val['name_img'];
							if(file_exists(component_core_system::basePath().'/upload/catalog/p/' . $val['id_product'] . '/' . $this->imgPrefix[$value['type_img']] . $filename. '.' .$extwebp)) {
								$data['imgs'][$item]['img'][$value['type_img']]['src_webp'] = '/upload/catalog/p/' . $val['id_product'] . '/' . $this->imgPrefix[$value['type_img']] . $filename . '.' . $extwebp;
							}
							$data['imgs'][$item]['img'][$value['type_img']]['crop'] = $value['resize_img'];
							//$data['imgs'][$item]['img'][$value['type_img']]['w'] = $value['width_img'];
							$data['imgs'][$item]['img'][$value['type_img']]['w'] = $value['resize_img'] === 'basic' ? $imginfo['width'] : $value['width_img'];
							//$data['imgs'][$item]['img'][$value['type_img']]['h'] = $value['height_img'];
							$data['imgs'][$item]['img'][$value['type_img']]['h'] = $value['resize_img'] === 'basic' ? $imginfo['height'] : $value['height_img'];
							$data['imgs'][$item]['img'][$value['type_img']]['ext'] = mime_content_type(component_core_system::basePath().'/upload/catalog/p/' . $val['id_product'] . '/' . $this->imgPrefix[$value['type_img']] . $val['name_img']);
						}
						$data['imgs'][$item]['default'] = $val['default_img'];
					}
				}
			}
			else {
				if(isset($row['name_img'])){
					// # return filename without extension
					$pathinfo = pathinfo($row['name_img']);
					$filename = $pathinfo['filename'];
					foreach ($this->fetchConfig as $key => $value) {
						$imginfo = $this->imagesComponent->getImageInfos(component_core_system::basePath().'/upload/catalog/p/'.$row['id_product'].'/'.$this->imgPrefix[$value['type_img']] . $row['name_img']);
						$data['img'][$value['type_img']]['src'] = '/upload/catalog/p/'.$row['id_product'].'/'.$this->imgPrefix[$value['type_img']] . $row['name_img'];
						if(file_exists(component_core_system::basePath().'/upload/catalog/p/'.$row['id_product'].'/'.$this->imgPrefix[$value['type_img']] . $filename. '.' .$extwebp)) {
							$data['img'][$value['type_img']]['src_webp'] = '/upload/catalog/p/' . $row['id_product'] . '/' . $this->imgPrefix[$value['type_img']] . $filename . '.' . $extwebp;
						}
						//$data['img'][$value['type_img']]['w'] = $value['width_img'];
						$data['img'][$value['type_img']]['w'] = $value['resize_img'] === 'basic' ? $imginfo['width'] : $value['width_img'];
						//$data['img'][$value['type_img']]['h'] = $value['height_img'];
						$data['img'][$value['type_img']]['h'] = $value['resize_img'] === 'basic' ? $imginfo['height'] : $value['height_img'];
						$data['img'][$value['type_img']]['crop'] = $value['resize_img'];
						$data['img'][$value['type_img']]['ext'] = mime_content_type(component_core_system::basePath().'/upload/catalog/p/'.$row['id_product'].'/'.$this->imgPrefix[$value['type_img']] . $row['name_img']);
					}
					$data['img']['alt'] = $row['alt_img'];
					$data['img']['title'] = $row['title_img'];
					$data['img']['caption'] = $row['caption_img'];
					$data['img']['name'] = $row['name_img'];
				}
				$data['img']['default'] = [
					'src' => isset($this->imagePlaceHolder['product']) ? $this->imagePlaceHolder['product'] : '/skin/'.$this->template->theme.'/img/catalog/p/default.png',
					'w' => $this->defaultImage[0]['width_img'],
					'h' => $this->defaultImage[0]['height_img']
				];
			}
			// Plugin
			if(!empty($newRow)){
				foreach($newRow as $key => $value){
					$data[$key] = $row[$value];
				}
			}
			
			if (!isset($row['seo_title_p']) || empty($row['seo_title_p'])) {
				$seoTitle = $this->seo->replace_var_rewrite($row['name_cat'],$data['name'],'title');
				$data['seo']['title'] = $seoTitle ? $seoTitle : $data['name'];
			}
			else {
				$data['seo']['title'] = $row['seo_title_p'];
			}
			if (!isset($row['seo_desc_p']) || empty($row['seo_desc_p'])) {
				$seoDesc = $this->seo->replace_var_rewrite($row['name_cat'],$data['name'],'description');
				$data['seo']['description'] = $seoDesc ? $seoDesc : ($data['resume'] ? $data['resume'] : $data['seo']['title']);
			}
			else {
				$data['seo']['description'] = $row['seo_desc_p'];
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
			$data['id'] = $row['id_product'];
			$data['name'] = $row['name_p'];
			$data['url'] = $this->routingUrl->getBuildUrl([
				'type'       => 'product',
				'iso'        => $row['iso_lang'],
				'id'         => $row['id_product'],
				'url'        => $row['url_p'],
				'id_parent'  => $row['id_cat'],
				'url_parent' => $row['url_cat']
			]);
			$data['id_parent'] = $row['id_cat'];
			$data['url_parent'] = $this->routingUrl->getBuildUrl([
				'type' => 'category',
				'iso'  => $row['iso_lang'],
				'id'   => $row['id_cat'],
				'url'  => $row['url_cat']
			]);
        }
		return $data;
    }

    /**
     * @param array $row
     * @return array
     */
    public function setHrefLangProductData(array $row): array {
		$arr = [];
		foreach ($row as $item) {
			$arr[$item['id_lang']] = $this->routingUrl->getBuildUrl([
				'type'       =>  'product',
				'iso'        =>  $item['iso_lang'],
				'id'         =>  $item['id_product'],
				'url'        =>  $item['url_p'],
				'id_parent'  =>  $item['id_cat'],
				'url_parent' =>  $item['url_cat']
			]);
		}
		return $arr;
    }
}