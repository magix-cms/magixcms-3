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
 * Author: Gerits Aurelien <aurelien[at]magix-cms[point]com>
 * Copyright: MAGIX CMS
 * Date: 29/12/12
 * Time: 15:03
 * License: Dual licensed under the MIT or GPL Version
 */
class frontend_model_catalog extends frontend_db_catalog {
    /**
     * @var component_routing_url
     */
    protected $routingUrl,$imagesComponent,$modelPlugins,$template,$data;

	/**
	 * frontend_model_catalog constructor.
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
     * @param $newRow
     * @return array|null
     *
     * @todo revoir le nommage de 'current', lui préférant 'active'
     */

    public function setItemData ($row,$current,$newRow = false)
    {
        /*$ModelImagepath     =   new magixglobal_model_imagepath();
        $ModelTemplate      =   new frontend_model_template();
        $ModelRewrite       =   new magixglobal_model_rewrite();*/

        $data = null;

        if ($row != null) {
            if (isset($row['name'])) {
                $data['name']       = $row['name'];
                $data['content']    = $row['content'];
            }
            // *** Product
            elseif (isset($row['name_p'])) {
                //$subcat['id']   = (isset($row['idcls'])) ? $row['idcls'] : null;
                //$subcat['name'] = (isset($row['pathslibelle'])) ? $row['pathslibelle'] : null;
                $data['name']      = $row['name_p'];

                $data['url']  =
                    $this->routingUrl->getBuildUrl(array(
                            'type'       => 'product',
                            'iso'        => $row['iso_lang'],
                            'id'         => $row['id_product'],
                            'url'        => $row['url_p'],
                            'id_parent'  => $row['id_cat'],
                            'url_parent' => $row['url_cat']
                        )
                    );
                // Base url for product
                $data['baseUrl']       = $row['url_p'];

                $data['active'] = false;
                if ($row['id_product'] == $current['controller']['id']) {
                    $data['active'] = true;
                }

                $data['id']        = $row['id_product'];
                $data['id_parent'] = $row['id_cat'];
                $data['url_parent'] = $this->routingUrl->getBuildUrl(array(
                        'type' => 'category',
                        'iso'  => $row['iso_lang'],
                        'id'   => $row['id_cat'],
                        'url'  => $row['url_cat']
                    )
                );

                $data['cat']       = $row['name_cat'];
                $data['id_lang']   = $row['id_lang'];
                $data['iso']       = $row['iso_lang'];
                $data['price']     = $row['price_p'];
                $data['reference'] = $row['reference_p'];
                $data['content']   = $row['content_p'];
				$data['resume']    = ($row['resume_p'] != '') ? $row['resume_p'] : NULL;
                $data['order']     = $row['order_p'];
                if (isset($row['img'])) {
                    if($row['img'] != NULL) {
                        $imgPrefix = $this->imagesComponent->prefix();
                        $fetchConfig = $this->imagesComponent->getConfigItems(array(
                            'module_img' => 'catalog',
                            'attribute_img' => 'product'
                        ));
                        if(is_array($row['img'])) {
                            foreach ($row['img'] as $item => $val) {
                                $data['imgs'][$item]['alt'] = $val['alt_img'];
                                $data['imgs'][$item]['title'] = $val['title_img'];
                                foreach ($fetchConfig as $key => $value) {
                                    $data['imgs'][$item]['img'][$value['type_img']]['src'] = '/upload/catalog/p/' . $val['id_product'] . '/' . $imgPrefix[$value['type_img']] . $val['name_img'];
									$data['imgs'][$item]['img'][$value['type_img']]['w'] = $value['width_img'];
									$data['imgs'][$item]['img'][$value['type_img']]['h'] = $value['height_img'];
									$data['imgs'][$item]['img'][$value['type_img']]['crop'] = $value['resize_img'];
                                }
                                $data['imgs'][$item]['default'] = $val['default_img'];
                            }
                        }
                    }
                    $data['img_default'] = '/skin/'.$this->template->theme.'/img/catalog/p/default.png';
                }
                else {
                    if(isset($row['name_img'])){
                        $imgPrefix = $this->imagesComponent->prefix();
                        $fetchConfig = $this->imagesComponent->getConfigItems(array(
                            'module_img'=>'catalog',
                            'attribute_img'=>'category'
                        ));
                        foreach ($fetchConfig as $key => $value) {
                            $data['img'][$value['type_img']]['src'] = '/upload/catalog/p/'.$row['id_product'].'/'.$imgPrefix[$value['type_img']] . $row['name_img'];
							$data['img'][$value['type_img']]['w'] = $value['width_img'];
							$data['img'][$value['type_img']]['h'] = $value['height_img'];
							$data['img'][$value['type_img']]['crop'] = $value['resize_img'];
                        }
                    }
                    $data['img']['default'] = '/skin/'.$this->template->theme.'/img/catalog/p/default.png';

                }
                // -- Similar / Associated product
                if(isset($row['associated'])){

                    foreach($row['associated'] as $key => $value){
                        $data['associated'][$key]['name'] = $value['name_p'];
                        $data['associated'][$key]['url']  =
                            $this->routingUrl->getBuildUrl(array(
                                    'type'       => 'product',
                                    'iso'        => $value['iso_lang'],
                                    'id'         => $value['id_product'],
                                    'url'        => $value['url_p'],
                                    'id_parent'  => $value['id_cat'],
                                    'url_parent' => $value['url_cat']
                                )
                            );
                        // Base url for product
                        $data['associated'][$key]['baseUrl']       = $value['url_p'];

                        $data['associated'][$key]['active'] = false;
                        if ($value['id_product'] == $current['controller']['id']) {
                            $data['associated'][$key]['active'] = true;
                        }
                        $data['associated'][$key]['id']        = $value['id_product'];
                        $data['associated'][$key]['id_parent'] = $value['id_cat'];
                        $data['associated'][$key]['url_parent'] = $this->routingUrl->getBuildUrl(array(
                                'type' => 'category',
                                'iso'  => $value['iso_lang'],
                                'id'   => $value['id_cat'],
                                'url'  => $value['url_cat']
                            )
                        );

                        $data['associated'][$key]['id_lang']    = $value['id_lang'];
                        $data['associated'][$key]['iso']        = $value['iso_lang'];
                        $data['associated'][$key]['price']      = $value['price_p'];
                        $data['associated'][$key]['content']    = $value['content_p'];
                        $data['associated'][$key]['resume']     = ($value['resume_p'] != '') ? $value['resume_p'] : NULL;
                        $data['associated'][$key]['order']      = $value['order_p'];
                        if(isset($value['name_img'])){
                            $imgPrefix = $this->imagesComponent->prefix();
                            $fetchConfig = $this->imagesComponent->getConfigItems(array(
                                'module_img'=>'catalog',
                                'attribute_img'=>'product'
                            ));
                            foreach ($fetchConfig as $keyConfig => $valueConfig) {
                                $data['associated'][$key]['img'][$valueConfig['type_img']]['src'] = '/upload/catalog/p/'.$value['id_product'].'/'.$imgPrefix[$valueConfig['type_img']] . $value['name_img'];
								$data['associated'][$key]['img'][$valueConfig['type_img']]['w'] = $value['width_img'];
								$data['associated'][$key]['img'][$valueConfig['type_img']]['h'] = $value['height_img'];
								$data['associated'][$key]['img'][$valueConfig['type_img']]['crop'] = $value['resize_img'];
                            }
                        }
                        $data['associated'][$key]['img']['default'] = '/skin/'.$this->template->theme.'/img/catalog/p/default.png';
                    }
                }
                // Plugin
                if($newRow != false){
                    if(is_array($newRow)){
                        foreach($newRow as $key => $value){
                            $data[$key] = $row[$value];
                        }
                    }
                }

            // *** Category
            }
            elseif(isset($row['name_cat'])) {

                /*$data['active']   =    false;
                if (is_array($current) AND isset($current['category']['id'])) {
                    $data['active']   = ($current['category']['id'] == $row['idclc']) ? true : false;
                }*/
                $data['active'] = false;
                if ($row['id_cat'] == $current['controller']['id'] OR $row['id_cat'] == $current['controller']['id_parent'] ) {
                    $data['active'] = true;
                }
                if (isset($row['img_cat'])) {
                    $imgPrefix = $this->imagesComponent->prefix();
                    $fetchConfig = $this->imagesComponent->getConfigItems(array(
                        'module_img'=>'catalog',
                        'attribute_img'=>'category'
                    ));
                    foreach ($fetchConfig as $key => $value) {
                        $data['img'][$value['type_img']]['src'] = '/upload/catalog/c/'.$row['id_cat'].'/'.$imgPrefix[$value['type_img']] . $row['img_cat'];
                        $data['img'][$value['type_img']]['w'] = $value['width_img'];
                        $data['img'][$value['type_img']]['h'] = $value['height_img'];
                        $data['img'][$value['type_img']]['crop'] = $value['resize_img'];
                    }
                }
                $data['img']['default'] = '/skin/'.$this->template->theme.'/img/catalog/c/default.png';

                $data['url']  =
                    $this->routingUrl->getBuildUrl(array(
                            'type'      =>  'category',
                            'iso'       =>  $row['iso_lang'],
                            'id'        =>  $row['id_cat'],
                            'url'       =>  $row['url_cat']
                        )
                    );
                // Base url for category
                $data['baseUrl']       = $row['url_cat'];

                $data['id']         =    $row['id_cat'];
                $data['id_parent']  =    !is_null($row['id_parent']) ? $row['id_parent'] : NULL;
                $data['id_lang']    =    $row['id_lang'];
                $data['iso']        =    $row['iso_lang'];
                $data['name']       =    $row['name_cat'];
                $data['content']    =    ($row['content_cat'] != '') ? $row['content_cat'] : NULL;
                $data['resume']     =    ($row['resume_cat'] != '') ? $row['resume_cat'] : NULL;
                $data['menu']       =    $row['menu_cat'];
                $data['order']      =    $row['order_cat'];
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
    public function setHrefLangCategoryData($row)
    {
        $arr = array();

        foreach ($row as $item) {
            $arr[$item['id_lang']] = $this->routingUrl->getBuildUrl(array(
                    'type'      =>  'category',
                    'iso'       =>  $item['iso_lang'],
                    'id'        =>  $item['id_cat'],
                    'url'       =>  $item['url_cat']
                )
            );
        }

        return $arr;
    }

    /**
     * @param $row
     * @return array
     */
    public function setHrefLangProductData($row)
    {
        $arr = array();

        foreach ($row as $item) {
            $arr[$item['id_lang']] = $this->routingUrl->getBuildUrl(array(
                    'type'              =>  'product',
                    'iso'               =>  $item['iso_lang'],
                    'id'                =>  $item['id_product'],
                    'url'               =>  $item['url_p'],
                    'id_parent'         =>  $item['id_cat'],
                    'url_parent'        =>  $item['url_cat']
                )
            );
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
     * @param array $custom
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
		$current['name'] = !empty($current['name']) ? $current['name'] : 'catalog';

        $conf = array(
            'id' => ($current['id'] ? $current['id'] : null),
            'id_parent' => ($current['id_parent'] ? $current['id_parent'] : null),
            'type' => 'data',
            'lang' =>  $lang,
            'context' => array(
            	1 => ($current['id_parent'] ? 'product' : 'category')
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
				foreach ($custom['context'] as $k => $v)
				{
					$conf['context'][1] = $k;
					if (is_array($v)) {
						foreach($v as $k2 => $v2){
							$conf['context'][2] = $k2;
							$conf['context'][3] = $v2;
						}
					}
					else {
						$conf['context'][2] = $v;
					}
				}
			}
			else {
				$allowed = array(
					'category',
					'product',
					'lastProduct'
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

        if ($conf['context'][1] == 'category') {
            if ($override) {
                $getCallClass = $this->modelPlugins->getCallClass($override);
                if(method_exists($getCallClass,'override')){
                    $conf['data'] = 'category';
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
                $conditions .= ' WHERE lang.iso_lang = :iso AND c.published_cat = 1';

                if( (isset($custom['select']) && $custom['select'] !== 'all') || !isset($custom['select']) ){
					if (isset($custom['select'])) {
						$conditions .= ' AND (p.id_cat IN (' . implode(',',$conf['id']) . ') OR p.id_parent IN (' . implode(',',$conf['id']) . '))';
					}

					if (isset($custom['exclude'])) {
						$conditions .= ' AND p.id_cat NOT IN (' . implode(',',$conf['id']) . ') AND p.id_parent NOT IN (' . implode(',',$conf['id']) . ')';
					}
				}

				if ($custom['type'] == 'menu') {
					$conditions .= ' AND p.menu_cat = 1';
				}

                // Set order
				switch ($conf['sort']['type']) {
					case 'order':
						$conditions .= ' ORDER BY p.id_parent, p.order_cat '.$conf['sort']['order'];
						break;
				}

                if ($conf['limit'] !== null) $conditions .= ' LIMIT ' . $conf['limit'];

                if ($conditions !== '') {
                    $data = parent::fetchData(
                        array('context' => 'all', 'type' => 'category', 'conditions' => $conditions),
                        array('iso' => $conf['lang'])
                    );

					if(is_array($data) && !empty($data)) {
						$branch = ($conf['id'] !== null) ? $conf['id'] : 'root';
						$data = $this->data->setPagesTree($data,'cat',$branch);
					}
                }
            }
        }
        elseif ($conf['context'][1] == 'product') {
            if ($override) {
                $getCallClass = $this->modelPlugins->getCallClass($override);
                if(method_exists($getCallClass,'override')){
                    $conf['data'] = 'product';
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

                //$conditions .= ' WHERE lang.iso_lang = :iso AND cat.published_cat =1 AND pc.published_p =1 AND catalog.default_c = 1 AND img.default_img = 1';

				$conditions .= ' WHERE lang.iso_lang = :iso 
                				AND cat.published_cat = 1 
                				AND pc.published_p = 1 
                				AND catalog.default_c = 1 
                				AND (img.default_img = 1 
                				OR img.default_img IS NULL)';

                if(isset($current['id_parent'])){
                    $conditions .= ' AND catalog.id_cat = '.$conf['id_parent'];
                }

                if (isset($custom['exclude'])) {
                    $conditions .= ' AND catalog.id_product NOT IN (' . implode(',',$conf['id']) . ')';
                }

                if (isset($custom['select'])) {
                    $conditions .= ' AND catalog.id_product IN (' . implode(',',$conf['id']) . ')';
                }

                $conditions .= ' GROUP BY catalog.id_product';

                // ORDER
				// Set order
				switch ($conf['sort']['type']) {
					case 'order':
						$conditions .= ' ORDER BY catalog.order_p '.$conf['sort']['order'];
						break;
				}

                if ($conf['limit'] != null) $conditions .= ' LIMIT ' . $conf['limit'];

                if ($conditions != '') {
                    $data = parent::fetchData(
                        array('context' => 'all', 'type' => 'product', 'conditions' => $conditions),
                        array('iso' => $conf['lang'])
                    );

                    /*if($data != null) {
                        $branch = isset($custom['select']) ? $conf['id'] : 'root';
                        $data = $this->setPagesTree($data,$branch);
                    }*/
                }
            }
        }
        elseif ($conf['context'][1] == 'lastProduct') {
            // Product
            if ($override) {
                $getCallClass = $this->modelPlugins->getCallClass($override);
                if(method_exists($getCallClass,'override')) {
                    $conf['data'] = 'product';
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
                /*$conditions .= ' WHERE lang.iso_lang = :iso
                				AND cat.published_cat =1 
                				AND pc.published_p =1 
                				AND catalog.default_c = 1 
                				AND img.default_img = 1 
                				AND catalog.id_cat = '.$current['id'];*/

                $conditions .= ' WHERE lang.iso_lang = :iso 
                				AND cat.published_cat = 1 
                				AND pc.published_p = 1 
                				AND catalog.default_c = 1 
                				AND (img.default_img = 1 
                				OR img.default_img IS NULL)
								GROUP BY catalog.id_product';
                /*if(isset($current['id'])){
                    $conditions .= ' AND p.id_parent = '.$current['id'];
                }*/

                if (isset($custom['exclude'])) {
                    $conditions .= ' AND catalog.id_product NOT IN (' . $conf['id'] . ') ';
                }

                if (isset($custom['select'])) {
                    $conditions .= ' AND catalog.id_product IN (' . $conf['id'] . ') ';
                }

                // ORDER
                $conditions .= ' ORDER BY catalog.id_product DESC';

                if ($conf['limit'] != null) {
                    $conditions .= ' LIMIT ' . $conf['limit'];
                }

                if ($conditions != '') {

                    $data = parent::fetchData(
                        array('context' => 'all', 'type' => 'product', 'conditions' => $conditions),
                        array(
                            ':iso' => $conf['lang']
                        )
                    );

                    /*if($data != null) {
                        $branch = isset($custom['select']) ? $conf['id'] : 'root';
                        $data = $this->setPagesTree($data,$branch);
                    }*/
                }
            }
        }

        return $data;
    }
}