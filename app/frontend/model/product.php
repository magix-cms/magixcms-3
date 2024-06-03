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
		if(!isset($this->imagesComponent)) $this->imagesComponent = new component_files_images($this->template);
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

        if(!empty($row)) {
			$this->initImageComponent();
			$data['short_name']= $row['name_p'];
			$data['name']      = $row['name_p'];
			$data['long_name'] = $row['longname_p'] ?? null;
			$data['url'] = $this->routingUrl->getBuildUrl([
				'type' => 'product',
				'iso' => $row['iso_lang'],
				'id' => $row['id_product'],
				'url' => $row['url_p'],
				'id_parent' => $row['id_cat'],
				'url_parent' => $row['url_cat']
			]);
			$data['link'] = [
				'label' => $row['link_label_p'],
				'title' => $row['link_title_p']
			];
			// Base url for product
			$data['baseUrl']       = $row['url_p'];
			$data['active'] = false;
			if(!empty($active)) if ($row['id_product'] == $active['controller']['id']) $data['active'] = true;
			$data['id']        = $row['id_product'];
			$data['id_parent'] = $row['id_cat'];
			$data['url_parent'] = $this->routingUrl->getBuildUrl([
				'type' => 'category',
				'iso' => $row['iso_lang'],
				'id' => $row['id_cat'],
				'url' => $row['url_cat']
			]);
			$data['link_parent'] = [
				'label' => $row['link_label_cat'],
				'title' => $row['link_title_cat']
			];
			$data['cat']       = $row['name_cat'];
			$data['id_lang']   = $row['id_lang'];
			$data['iso']       = $row['iso_lang'];
			$data['price']     = $row['price_p'] ?? null;
            $data['promo_price']= $row['price_promo_p'] ?? null;
			$data['reference'] = $row['reference_p'] ?? null;
            $data['properties'] = [
                'width' => $row['width_p'] ?? null,
                'height' => $row['height_p'] ?? null,
                'depth' => $row['depth_p'] ?? null,
                'weight' => $row['weight_p'] ?? null,
                'availability' => $row['availability_p'] ?? null
            ];
			$data['content']   = $row['content_p'] ?? null;
			$data['resume']    = $row['resume_p'] ?? (($row['content_p']) ? $string_format->clearHTMLTemplate($row['content_p']) : '');
			$data['order']     = $row['order_p'] ?? null;
			if (isset($row['img'])) {
				if(is_array($row['img'])) {
					foreach ($row['img'] as $val) {
						$image = $this->imagesComponent->setModuleImage('catalog','product',$val['name_img'],$row['id_product'],$val['alt_img'] ?? $row['name_p'], $val['title_img'] ?? $row['name_p']);
						if($val['default_img']) {
							$data['img'] = $image;
							$image['default'] = 1;
						}
						$data['imgs'][] = $image;
					}
					$data['img']['default'] = $this->imagesComponent->setModuleImage('catalog','product');
				}
			}
			else {
				if(isset($row['name_img'])) {
					$data['img'] = $this->imagesComponent->setModuleImage('catalog','product',$row['name_img'],$row['id_product'],$row['alt_img'] ?? $row['name_p'], $row['title_img'] ?? $row['name_p']);
				}
				$data['img']['default'] = $this->imagesComponent->setModuleImage('catalog','product');
			}
			// Plugin
			if(!empty($newRow)){
				foreach($newRow as $key => $value){
					$data[$key] = $row[$value];
				}
			}
			
			if (!isset($row['seo_title_p']) || empty($row['seo_title_p'])) {
				$seoTitle = $this->seo->replace_var_rewrite($row['name_cat'] ?? '',$data['name'],'title');
				$data['seo']['title'] = $seoTitle ?: $data['name'];
			}
			else {
				$data['seo']['title'] = $row['seo_title_p'];
			}
			if (!isset($row['seo_desc_p']) || empty($row['seo_desc_p'])) {
				$seoDesc = $this->seo->replace_var_rewrite($row['name_cat'] ?? '',$data['name'],'description');
				$data['seo']['description'] = $seoDesc ?: ($data['resume'] ?: $data['seo']['title']);
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