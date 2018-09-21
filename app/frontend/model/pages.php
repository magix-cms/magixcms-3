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

    protected $routingUrl,$imagesComponent,$modelPlugins,$template,$data;

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
    }

    /**
     * Formate les valeurs principales d'un élément suivant la ligne passées en paramètre
     * @param $row
     * @param $current
     * @param bool $newRow
     * @return array|null
     */
    public function setItemData($row,$current,$newRow = false)
    {
        $data = null;

        if ($row != null) {
			if (isset($row['name'])) {
				$data['name']       = $row['name'];
				$data['content']    = $row['content'];
			}
			elseif (isset($row['name_pages'])) {
				$data['id']         = $row['id_pages'];
				$data['id_parent']  = !is_null($row['id_parent']) ? $row['id_parent'] : NULL;
				$data['title']      = $row['name_pages'];
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
					foreach ($fetchConfig as $key => $value) {
						$data['img'][$value['type_img']]['src'] = '/upload/pages/' . $row['id_pages'] . '/' . $imgPrefix[$value['type_img']] . $row['img_pages'];
						$data['img'][$value['type_img']]['w'] = $value['width_img'];
						$data['img'][$value['type_img']]['h'] = $value['height_img'];
						$data['img'][$value['type_img']]['crop'] = $value['resize_img'];
					}
				}
				$data['img']['default'] = '/skin/'.$this->template->theme.'/img/pages/default.png';

				$data['content'] = $row['content_pages'];
				$data['menu'] = $row['menu_pages'];
				$data['date']['update'] = $row['last_update'];
				$data['date']['register'] = $row['date_register'];
				$data['seo']['title'] = $row['seo_title_pages'];
				$data['seo']['description'] = $row['seo_desc_pages'];
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
					'parent',
					'child'
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
			elseif (is_array($custom['select'])) {
				if (array_key_exists($conf['lang'],$custom['select'])) {
					$conf['id'] = $custom['select'][$conf['lang']];
					$conf['type'] = 'collection';
				}
			}
		}

		// Define exclude
		if (isset($custom['exclude'])) {
			if (is_array($custom['exclude'])) {
				if (array_key_exists($conf['lang'],$custom['exclude'])) {
					$conf['exclude'] = $custom['exclude'][$conf['lang']];
					$conf['type'] = 'collection';
				}
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

				if (isset($custom['exclude'])) {
					$conditions .= ' AND p.id_pages NOT IN (' . implode(',',$conf['id']) . ') ';
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
        elseif ($conf['context'][1] == 'parent') {
            if ($override) {
                $getCallClass = $this->modelPlugins->getCallClass($override);
                if(method_exists($getCallClass,'override')){
                    $conf['data'] = 'parent';
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
                $conditions .= ' WHERE lang.iso_lang = :iso AND c.published_pages = 1 AND p.id_parent IS NULL ';

                if (isset($custom['select'])) {
                    $conditions .= ' AND p.id_pages IN (' . $conf['id'] . ') ';
                }

                if (isset($custom['exclude'])) {
                    $conditions .= ' AND p.id_pages NOT IN (' . $conf['id'] . ') ';
                }

                if ($custom['type'] == 'menu') {
                    $conditions .= ' AND p.menu_pages = 1';
                }

				// Set order
				switch ($conf['sort']['type']) {
					case 'order':
						$conditions .= ' ORDER BY p.id_pages, p.order_pages '.$conf['sort']['order'];
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
            if($data != null AND ($conf['context'][2] == 'child'))
            {
                if ($override) {
                    $getCallClass = $this->modelPlugins->getCallClass($override);
                    if(method_exists($getCallClass,'override')){
                        foreach ($data as $k1 => $v1) {
                            $conf['data'] = 'child';
                            $conf['controller'] = $current;
                            $conf['id_pages'] = $v1['id_pages'];
                            $data_2 = call_user_func_array(
                                array(
                                    $getCallClass,
                                    'override'
                                ),
                                array(
                                    $conf,
                                    $custom
                                )
                            );
                            if ($data_2 != null) {
                                $data[$k1]['subdata'] = $data_2;
                            }
                        }
                        $data_2 = null;
                    }
                } else {

                    foreach ($data as $k1 => $v1) {

                        $conditions = '';
                        $conditions .= ' WHERE lang.iso_lang = :iso AND c.published_pages = 1
                    AND p.id_parent = :id';

                        /*if (isset($custom['select'])) {

                            $conditions .= ' AND p.id_pages IN (' . $conf['id'] . ') ';
                        }*/
                        if (isset($custom['exclude'])) {

                            $conditions .= ' AND p.id_pages NOT IN (' . $conf['id'] . ') ';
                        }

                        if ($conf['type'] == 'menu') {
                            $conditions .= ' AND p.menu_pages = 1';
                        }

                        $conditions .= ' GROUP BY p.id_pages ORDER BY p.order_pages ASC';

                        if ($conf['limit'] != null) {
                            $conditions .= ' LIMIT ' . $conf['limit'];
                        }


                        if ($conditions != '') {
                            $data_2 = parent::fetchData(
                                array('context' => 'all', 'type' => 'child', 'conditions' => $conditions),
                                array(
                                    ':iso' => $conf['lang'],
                                    ':id' => $v1['id_pages']
                                )
                            );
                        }

                        if ($data_2 != null) {
                            $data[$k1]['subdata'] = $data_2;
                        }
                    }
                    $data_2 = null;
                }
            }
        }
        elseif ($conf['context'][1] == 'child') {
            if ($override) {
                $getCallClass = $this->modelPlugins->getCallClass($override);
                if(method_exists($getCallClass,'override')){
                    $conf['data'] = 'child';
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
            } else {

                $conditions = '';
                $conditions .= ' WHERE lang.iso_lang = :iso AND c.published_pages = 1 AND p.id_parent = :id';

                if ($custom['type'] == 'menu') {
                    $conditions .= ' AND p.menu_pages = 1';
                }

                $conditions .= ' GROUP BY p.id_pages';
                if ($conf['sort'] != null) {
                    $conditions .= ' ORDER BY p.order_pages';
                }
                if ($conf['limit'] != null) {
                    $conditions .= ' LIMIT ' . $conf['limit'];
                }

                $data = parent::fetchData(
                    array(
                        'context' => 'all',
                        'type' => 'child',
                        'conditions' => $conditions
                    ),
                    array(
                        ':iso' => $conf['lang'],
                        ':id' => $conf['id'])
                );
            }
        }

        return $data;
    }
}