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
 * Author: Sire Sam (www.sire-sam.be)
 * Copyright: MAGIX CMS
 * Date: 29/12/12
 * License: Dual licensed under the MIT or GPL Version
 */
class frontend_model_pages extends frontend_db_pages{

    protected $routingUrl,$imagesComponent,$modelPlugins,$template,$data,$logo;

	/**
	 * frontend_model_pages constructor.
	 * @param stdClass $t
	 */
    public function __construct($t = null)
    {
		$this->template = $t ? $t : new frontend_model_template();
		$this->routingUrl = new component_routing_url();
		$this->imagesComponent = new component_files_images($t);
		$this->modelPlugins = new frontend_model_plugins();
        $this->data = new frontend_model_data($this,$this->template);
        $this->logo = new frontend_model_logo();
    }

    /**
     * Formate les valeurs principales d'un élément suivant la ligne passées en paramètre
     * @param $row
     * @param $current
     * @param bool $newRow
     * @return array|null
     * @throws Exception
     */
    public function setItemData($row,$current,$newRow = false)
    {
    	$string_format = new component_format_string();
        $data = null;
        $extwebp = 'webp';
        $imagePlaceHolder = $this->logo->getImagePlaceholder();

        if ($row != null) {
			if (isset($row['name'])) {
				$data['name']       = $row['name'];
				$data['content']    = $row['content'];
			}
			elseif (isset($row['name_pages'])) {
				$data['id']         = $row['id_pages'];
				$data['id_parent']  = !is_null($row['id_parent']) ? $row['id_parent'] : NULL;
				$data['name']       = $row['name_pages'];
				$data['iso']        = $row['iso_lang'];
				$data['url']  =
					$this->routingUrl->getBuildUrl(array(
						'type'      =>  'pages',
						'iso'       =>  $row['iso_lang'],
						'id'        =>  $row['id_pages'],
						'url'       =>  $row['url_pages']
					)
				);

				$data['active'] = false;

				if ($row['id_pages'] == $current['controller']['id']) {
					$data['active'] = true;
				}

				if (isset($row['img_pages'])) {
					$imgPrefix = $this->imagesComponent->prefix();
					$fetchConfig = $this->imagesComponent->getConfigItems(array(
						'module_img' => 'pages',
						'attribute_img' => 'page'
					));
					// # return filename without extension
                    $pathinfo = pathinfo($row['img_pages']);
                    $filename = $pathinfo['filename'];

					foreach ($fetchConfig as $key => $value) {
						$imginfo = $this->imagesComponent->getImageInfos(component_core_system::basePath().'/upload/pages/' . $row['id_pages'] . '/' . $imgPrefix[$value['type_img']] . $row['img_pages']);
						$data['img'][$value['type_img']]['src'] = '/upload/pages/' . $row['id_pages'] . '/' . $imgPrefix[$value['type_img']] . $row['img_pages'];
                        $data['img'][$value['type_img']]['src_webp'] = '/upload/pages/' . $row['id_pages'] . '/' . $imgPrefix[$value['type_img']] . $filename. '.' .$extwebp;
						//$data['img'][$value['type_img']]['w'] = $value['width_img'];
						$data['img'][$value['type_img']]['w'] = $value['resize_img'] === 'basic' ? $imginfo['width'] : $value['width_img'];
						//$data['img'][$value['type_img']]['h'] = $value['height_img'];
						$data['img'][$value['type_img']]['h'] = $value['resize_img'] === 'basic' ? $imginfo['height'] : $value['height_img'];
						$data['img'][$value['type_img']]['crop'] = $value['resize_img'];
                        $data['img'][$value['type_img']]['ext'] = mime_content_type(component_core_system::basePath().'/upload/pages/' . $row['id_pages'] . '/' . $imgPrefix[$value['type_img']] . $row['img_pages']);
					}
					$data['img']['name'] = $row['img_pages'];
				}
				$data['img']['default'] = isset($imagePlaceHolder['pages']) ? $imagePlaceHolder['pages'] : '/skin/'.$this->template->theme.'/img/pages/default.png' ;
				$data['img']['alt'] = $row['alt_img'];
				$data['img']['title'] = $row['title_img'];
				$data['img']['caption'] = $row['caption_img'];


				$data['content'] = $row['content_pages'];
				$data['resume'] = $row['resume_pages'] ? $row['resume_pages'] : ($row['content_pages'] ? $string_format->truncate(strip_tags($row['content_pages'])) : '');
				$data['menu'] = $row['menu_pages'];
				$data['date']['update'] = $row['last_update'];
				$data['date']['register'] = $row['date_register'];
				$data['seo']['title'] = $row['seo_title_pages'];
				$data['seo']['description'] = $row['seo_desc_pages'] ? $row['seo_desc_pages'] : ($data['resume'] ? $data['resume'] : $data['seo']['title']);
				// Plugin
				if($newRow != false){
					if(is_array($newRow)){
						foreach($newRow as $key => $value){
							$data[$key] = $row[$value];
						}
					}
				}
			}
			return $data;
        }
    }

    /**
     * @param $row
     * @return array
     */
    public function setHrefLangData($row)
    {
        $arr = array();

        foreach ($row as $item) {
            $arr[$item['id_lang']] = $this->routingUrl->getBuildUrl(array(
                'type'      =>  'pages',
                'iso'       =>  $item['iso_lang'],
                'id'        =>  $item['id_pages'],
                'url'       =>  $item['url_pages']
            ));
        }

        return $arr;
    }

	/**
	 * @param $d
	 * @param $c
	 * @return mixed|null
	 */
	public function parseData($d,$c,$nr = false)
	{
		return $this->data->parseData($d,$this,$c,$nr);
	}

    /**
     * Retourne les données sql sur base des paramètres passés en paramète
     * @param $custom
     * @param array $current
     * @param bool $override
     * @return array|null
     */
    public function getData($custom,$current,$override = false)
    {
		if (!(is_array($custom))) return null;

		if (!(array_key_exists('controller', $current))) return null;

		$lang = $current['lang']['iso'];
		$current = $current['controller'];
		$current['name'] = !empty($current['name']) ? $current['name'] : 'pages';

        $conf = array(
            'id' => null,
			'id_parent' => ($current['id_parent'] ? $current['id_parent'] : null),
            'type' => 'data',
			'lang' =>  $lang,
			'context' => array(
				1 => 'parent'
			),
			'sort' => array(
				'type' => 'order',
				'order' => 'ASC'
			),
			'exclude' => null,
            'limit' => null,
			'deepness' => 0
        );

		// Define context
		if (isset($custom['context'])) {
			if (is_array($custom['context'])) {
				foreach ($custom['context'] as $k => $v) {
					$conf['context'][1] = $k;
					$conf['context'][2] = $v;
				}
			}
			else {
				$allowed = array(
					'all',
					'one'
				);

				if (in_array($custom['context'],$allowed)) $conf['context'][1] = $custom['context'];
			}
		}

		// Define select
		if (isset($custom['select'])) {
			if ($custom['select'] === 'current') {
				$conf['type'] = 'collection';
			}
			elseif ($custom['select'] === 'all') {
				$conf['id'] = null;
				$conf['type'] = null;
			}
			else {
				$conf['id'] = $custom['select'];
				$conf['type'] = 'collection';
			}
		}

		// Define exclude
		if (isset($custom['exclude'])) {
			if (is_array($custom['exclude'])) {
				$conf['exclude'] = $custom['exclude'];
				$conf['type'] = 'collection';
			}
		}

		// Define limit
		if (isset($custom['limit'])) $conf['limit'] = $custom['limit'];

		// Define sort
		if (isset($custom['sort'])) {
			if (is_array($custom['sort'])) {
				if(array_key_exists('type', $custom['sort'])) $conf['sort']['type'] =  $custom['sort']['type'];
				if(array_key_exists('order', $custom['sort'])) $conf['sort']['order'] =  $custom['sort']['order'];
			}
		}

		// deepness for element
		if(isset($custom['deepness'])) {
			$deepness_allowed = array('all','none');
			if (in_array($custom['deepness'],$deepness_allowed)) {
				if($custom['deepness'] == 'all'){
					$conf['deepness'] = null;
				}
				elseif($custom['deepness'] == 'none') {
					$conf['deepness'] = 0;
				}
			}
			else {
				$conf['deepness'] = 0;
			}
		}

		// Override with plugin
		if (isset($custom['plugins'])) $conf['plugins'] = $custom['plugins'];

		// *** Load SQL data
		$conditions = '';
		$data = null;

        if ($conf['context'][1] == 'all') {
			if ($override) {
				$getCallClass = $this->modelPlugins->getCallClass($override);
				if(method_exists($getCallClass,'override')){
					$conf['data'] = 'all';
					$conf['controller'] = $current;
					$data = call_user_func_array(
						array(
							$getCallClass,
							'override'
						),
						array(
							$conf,
							$custom
						)
					);
				}
			}
			else {
				$conditions .= ' WHERE lang.iso_lang = :iso AND c.published_pages = 1 ';

				/*if (isset($custom['select'])) {
					$conditions .= ' AND (p.id_pages IN (' . (is_array($conf['id']) ? implode(',',$conf['id']) : $conf['id']) . ') OR p.id_parent IN (' . (is_array($conf['id']) ? implode(',',$conf['id']) : $conf['id']) . '))';
				}*/

				if (isset($custom['exclude'])) {
					$conditions .= ' AND p.id_pages NOT IN (' . (is_array($conf['id']) ? implode(',',$conf['id']) : $conf['id']) . ') AND p.id_parent NOT IN (' . (is_array($conf['id']) ? implode(',',$conf['id']) : $conf['id']) . ')';
				}

				if ($custom['type'] == 'menu') {
					$conditions .= ' AND p.menu_pages = 1';
				}

				// Set order
				switch ($conf['sort']['type']) {
					case 'order':
						$conditions .= ' ORDER BY p.id_parent, p.order_pages '.$conf['sort']['order'];
						break;
				}

				if ($conf['limit'] !== null) $conditions .= ' LIMIT ' . $conf['limit'];

				if ($conditions != '') {
					$data = parent::fetchData(
						array('context' => 'all', 'type' => 'pages', 'conditions' => $conditions),
						array('iso' => $conf['lang'])
					);

					if(is_array($data) && !empty($data)) {
						$branch = ($conf['id'] !== null) ? $conf['id'] : 'root';
						$data = $this->data->setPagesTree($data,'pages',$branch);
					}
				}
			}
		}
        elseif ($conf['context'][1] == 'one') {
            if ($override) {
                $getCallClass = $this->modelPlugins->getCallClass($override);
                if(method_exists($getCallClass,'override')){
                    $conf['data'] = 'one';
                    $conf['controller'] = $current;
                    $data = call_user_func_array(
                        array(
                            $getCallClass,
                            'override'
                        ),
                        array(
                            $conf,
                            $custom
                        )
                    );
                }
            }
            else {
				$conditions .= ' WHERE lang.iso_lang = :iso AND c.published_pages = 1 ';

				if (isset($custom['select'])) {
					$conditions .= ' AND p.id_pages IN (' . (is_array($conf['id']) ? implode(',',$conf['id']) : $conf['id']) . ')';
				}

				if (isset($custom['exclude'])) {
					$conditions .= ' AND p.id_pages NOT IN (' . (is_array($conf['id']) ? implode(',',$conf['id']) : $conf['id']) . ') AND p.id_parent NOT IN (' . (is_array($conf['id']) ? implode(',',$conf['id']) : $conf['id']) . ')';
				}

				if ($custom['type'] == 'menu') {
					$conditions .= ' AND p.menu_pages = 1';
				}

				// Set order
				switch ($conf['sort']['type']) {
					case 'order':
						$conditions .= ' ORDER BY p.id_parent, p.order_pages '.$conf['sort']['order'];
						break;
				}

				if ($conf['limit'] !== null) $conditions .= ' LIMIT ' . $conf['limit'];

				if ($conditions != '') {
					$data = parent::fetchData(
						array('context' => 'all', 'type' => 'pages', 'conditions' => $conditions),
						array('iso' => $conf['lang'])
					);
				}
            }
        }

        return $data;
    }

	/**
	 * @param $id
	 * @return array
	 * @throws Exception
	 */
	public function getParents($id)
	{
		return $this->data->getParents($id);
    }
}