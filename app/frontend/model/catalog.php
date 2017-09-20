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
    protected $routingUrl,$imagesComponent,$modelPlugins,$coreTemplate;

    public function __construct($template)
    {
        $this->routingUrl = new component_routing_url();
        $this->imagesComponent = new component_files_images($template);
        $this->modelPlugins = new frontend_model_plugins();
        $this->coreTemplate = new frontend_model_template();
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
                $data['title']      = $row['name_p'];

                $data['url']  =
                    $this->routingUrl->getBuildUrl(array(
                            'type'              =>  'product',
                            'iso'               =>  $row['iso_lang'],
                            'id'                =>  $row['id_product'],
                            'url'               =>  $row['url_p'],
                            'id_parent'         =>  $row['id_cat'],
                            'url_parent'        =>  $row['url_cat']
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
                        'type'      =>  'category',
                        'iso'       =>  $row['iso_lang'],
                        'id'        =>  $row['id_cat'],
                        'url'       =>  $row['url_cat']
                    )
                );

                $data['id_lang']    = $row['id_lang'];
                $data['iso']        = $row['iso_lang'];
                $data['price']      = $row['price_p'];
                $data['content']    = $row['content_p'];
                $data['order']      = $row['order_p'];
                if (isset($row['img'])) {
                    if($row['img'] != NULL) {
                        $imgPrefix = $this->imagesComponent->prefix();
                        $fetchConfig = $this->imagesComponent->getConfigItems(array(
                            'module_img' => 'catalog',
                            'attribute_img' => 'product'
                        ));

                        foreach ($row['img'] as $item => $val) {
                            $data['img'][$item]['alt'] = $val['alt_img'];
                            $data['img'][$item]['title'] = $val['title_img'];
                            foreach ($fetchConfig as $key => $value) {
                                $data['img'][$item]['imgSrc'][$value['type_img']] = '/upload/catalog/p/' . $val['id_product'] . '/' . $imgPrefix[$value['type_img']] . $val['name_img'];
                            }
                            $data['img'][$item]['default'] = $val['default_img'];
                        }
                    }else{
                        $data['img']['imgSrc']['default']   =
                            '/skin/'.$this->coreTemplate->themeSelected().'/img/catalog/p/default.png';
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
            } elseif(isset($row['name_cat'])) {
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
                        $data['imgSrc'][$value['type_img']] = '/upload/catalog/c/'.$row['id_cat'].'/'.$imgPrefix[$value['type_img']] . $row['img_cat'];
                    }
                }else{
                    $data['imgSrc']['default']  =
                        '/skin/'.$this->coreTemplate->themeSelected().'/img/catalog/c/default.png';
                }

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
                $data['order']      =    $row['order_cat'];
                // Plugin
                if($newRow != false){
                    if(is_array($newRow)){
                        foreach($newRow as $key => $value){
                            $data[$key] = $row[$value];
                        }
                    }
                }

                // *** Micro-gallery (product page)
            }/* elseif(isset($row['idmicro'])) {
                $data['id']        = $row['idmicro'];
                $data['imgSrc']['small']   =
                    $ModelImagepath->filterPathImg(
                        array(
                            'filtermod'=>'catalog',
                            'img'=>'mini/'.
                                $row['imgcatalog'],
                            'levelmod'=>'galery'
                        )
                    );
                $data['imgSrc']['medium']   =
                    $ModelImagepath->filterPathImg(
                        array(
                            'filtermod'=>'catalog',
                            'img'=>'maxi/'.
                                $row['imgcatalog'],
                            'levelmod'=>'galery'
                        )
                    );
            }*/
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
     * Retourne les données sql sur base des paramètres passés en paramète
     * @param array $custom
     * @param array $current
     * @param bool $class
     * @return array|null
     */
    public static function getData($custom,$current,$class = false)
    {
        if (!(is_array($custom))) {
            return null;
        }

        if (!(array_key_exists('catalog',$current))) {
            return null;
        }

        $conf           =   array(
            'id'        =>  null,
            'type'      =>  null,
            'sort'      =>  null,
            'limit'     =>  null,
            'lang'      =>  $current['lang']['iso'],
            'context'   =>  array(1 => 'all')
        );
        $current = $current['catalog'];

        if (!(isset($custom['context']))) {
            if (isset($current['product']['id'])) {
                $conf['context'][1]   =   'product';

            } elseif (isset($current['subcategory']['id'])) {
                $conf['id']         =   $current['subcategory']['id'];
                $conf['context'][1]   =   'product';

            } elseif (isset($current['category']['id'])) {
                $conf['id']         =   $current['category']['id'];
                $conf['context'][1] = 'subcategory';

            } else {
                $conf['context'][1] = 'category';
            }
        }

        // custom values: data_sort
        if (isset($custom['select'])) {
            if ($custom['select'] == 'current') {
                if (isset($current['subcategory']['id'])) {
                    $conf['id']     = $current['subcategory']['id'];
                    $conf['type']   = 'collection';

                } elseif($current['category']['id'] != null) {
                    $conf['id'] = $current['category']['id'];
                    $conf['type']   = 'collection';

                }

            } elseif(is_array($custom['select'])) {
                if (array_key_exists($conf['lang'],$custom['select'])) {
                    $conf['id']     = $custom['select'][$conf['lang']];
                    $conf['type']   = 'collection';

                }
            } elseif($custom['select'] = 'all') {
                $conf['id']     = null;
                $conf['type']   = null;

            }
        } elseif(isset($custom['exclude'])) {
            if ( is_array($custom['exclude'])) {
                if (array_key_exists($conf['lang'],$custom['exclude'])) {
                    $conf['id'] = $custom['exclude'][$conf['lang']];
                    $conf['type'] = 'exclude';
                }
            }
        }



        if (isset($custom['limit'])) {
            $conf['limit']  =   $custom['limit'];
        }
        // Sort
        if (isset($custom['sort'])) {
            if ( is_array($custom['sort'])) {
                if (array_key_exists(key($custom['sort']), $custom['sort'])) {
                    $conf['sort_type'] = key($custom['sort']);
                    $conf['sort_order'] = $custom['sort'][key($custom['sort'])];
                }
            }
        }else{
            $conf['sort_type'] = 'id';
            $conf['sort_order'] = 'DESC';
        }

        // deepness for element
        if(isset($custom['deepness'])){
            $deepness_allowed = array('all','none');
            if (in_array($custom['deepness'],$deepness_allowed)) {
                if($custom['deepness'] == 'all'){
                    $conf['deepness'] = null;
                }elseif($custom['deepness'] == 'none'){
                    $conf['deepness'] = 0;
                }
            }else{
                $conf['deepness'] = 0;
            }
        }else{
            $conf['deepness'] = 0;
        }

        // Override with plugin
        if (isset($custom['plugins'])) {
            $conf['plugins']  =   $custom['plugins'];
        }
        // custom values: display
        if (isset($custom['context'])) {
            if (is_array($custom['context'])) {
                foreach ($custom['context'] as $k => $v)
                {
                    $conf['context'][1]   =   $k;
                    if (is_array($v)) {
                        foreach($v as $k2 => $v2){
                            $conf['context'][2]   =   $k2;
                            $conf['context'][3]   =   $v2;
                        }

                    } else {
                        $conf['context'][2]   =   $v;
                    }
                }
            } else {
                $allowed = array(
                    'category',
                    //'subcategory',
                    'product',
                    'last-product',
                    'last-product-cat',
                    //'last-product-subcat',
                    'all'/*,
                    'product-gallery'*/
                );

                if (in_array($custom['context'],$allowed)) {
                    $conf['context'][1] = $custom['context'];
                }
            }
        }

        // *** Load SQL data
        $data = null;
        if ($conf['context'][1] == 'category' OR $conf['context'][1] == 'all') {
            // Category
            if($class && class_exists($class)){
                $data = $class::fetchCategory(
                    array(
                        'fetch'         =>  'all',
                        'iso'           =>  $conf['lang'],
                        'selectmodeid'  =>  $conf['id'],
                        'selectmode'    =>  $conf['type'],
                        'sort_type'     =>  $conf['sort_type'],
                        'sort_order'    =>  $conf['sort_order'],
                        'limit'         =>  $conf['limit']
                    )
                );
            }else{
                $data = parent::fetchCategory(
                    array(
                        'fetch'         =>  'all',
                        'iso'           =>  $conf['lang'],
                        'selectmodeid'  =>  $conf['id'],
                        'selectmode'    =>  $conf['type'],
                        'sort_type'     =>  $conf['sort_type'],
                        'sort_order'    =>  $conf['sort_order'],
                        'limit'         =>  $conf['limit']
                    )
                );
            }
            if (($conf['context'][2] == 'subcategory' OR $conf['context'][1] == 'all') AND $data != null) {
                foreach ($data as $k1 => $v_1)
                {
                    // Category > subcategory
                    if($class && class_exists($class)){
                        $data_2 = $class::fetchSubCategory(
                            array(
                                'fetch'         =>  'in_cat',
                                'idclc'         =>  $v_1['idclc'],
                                'sort_type'     =>  $conf['sort_type'],
                                'sort_order'    =>  $conf['sort_order'],
                                'limit'         =>  $conf['limit']
                            )
                        );
                    }else{
                        $data_2 = parent::fetchSubCategory(
                            array(
                                'fetch'         =>  'in_cat',
                                'idclc'         =>  $v_1['idclc'],
                                'sort_type'     =>  $conf['sort_type'],
                                'sort_order'    =>  $conf['sort_order'],
                                'limit'         =>  $conf['limit']
                            )
                        );
                    }
                    if ($data_2 != null) {
                        $data[$k1]['subdata']   =   $data_2;
                        if (($conf['context'][3] == 'product' OR $conf['context'][1] == 'all') AND $data_2 != null) {
                            $data_3     =   null;
                            foreach ($data_2 as $k2 => $v_2)
                            {
                                // Category > subcategory > Product
                                if($class && class_exists($class)){
                                    $data_3 =  parent::fetchProduct(
                                        array(
                                            'fetch'         =>  'all_in',
                                            'idclc'         =>  $v_2['idclc'],
                                            'idcls'         =>  $v_2['idcls'],
                                            'sort_type'     =>  $conf['sort_type'],
                                            'sort_order'    =>  $conf['sort_order'],
                                            'limit'         =>  $conf['limit']
                                        )
                                    );

                                }else{
                                    $data_3 =  parent::fetchProduct(
                                        array(
                                            'fetch'         =>  'all_in',
                                            'idclc'         =>  $v_2['idclc'],
                                            'idcls'         =>  $v_2['idcls'],
                                            'sort_type'     =>  $conf['sort_type'],
                                            'sort_order'    =>  $conf['sort_order'],
                                            'limit'         =>  $conf['limit']
                                        )
                                    );
                                }
                                if ($data_3 != null) {
                                    $data[$k1]['subdata'][$k2]['subdata']   =   $data_3;
                                }
                            }
                        }
                    }
                }
            } elseif ($conf['context'][2] == 'product' AND $data != null) {
                foreach($data as $k1 => $v_1)
                {
                    // Category > Product
                    $data_2 =  parent::fetchProduct(
                        array(
                            'fetch'         =>  'all_in',
                            'idclc'         =>  $v_1['idclc'],
                            'idcls'         =>  $conf['deepness'],
                            'sort_type'     =>  $conf['sort_type'],
                            'sort_order'    =>  $conf['sort_order'],
                            'limit'         =>  $conf['limit']
                        )
                    );
                    if ($data_2 != null) {
                        $data[$k1]['subdata']   =   $data_2;
                    }
                }
            }
        } elseif($conf['context'][1] == 'subcategory') {
            if ($custom['select'] == 'current' AND isset($current['subcategory']['id'])) {
                // Subcategory[current]
                if($class && class_exists($class)){
                    $data = $class::fetchSubCategory(
                        array(
                            'fetch'         =>  'all',
                            'iso'           =>  $conf['lang'],
                            'selectmodeid'  =>  $current['subcategory']['id'],
                            'selectmode'    =>  $conf['type'],
                            'sort_type'     =>  $conf['sort_type'],
                            'sort_order'    =>  $conf['sort_order'],
                            'limit'         =>  $conf['limit']
                        )
                    );
                }else{
                    $data = parent::fetchSubCategory(
                        array(
                            'fetch'         =>  'all',
                            'iso'           =>  $conf['lang'],
                            'selectmodeid'  =>  $current['subcategory']['id'],
                            'selectmode'    =>  $conf['type'],
                            'sort_type'     =>  $conf['sort_type'],
                            'sort_order'    =>  $conf['sort_order'],
                            'limit'         =>  $conf['limit']
                        )
                    );
                }


            } elseif (isset($current['category']['id']) AND empty($custom['select'])) {
                // Subcategory[in_cat]
                if($class && class_exists($class)){
                    $data = $class::fetchSubCategory(
                        array(
                            'fetch'         =>  'in_cat',
                            'idclc'         =>  $current['category']['id'],
                            'sort_type'     =>  $conf['sort_type'],
                            'sort_order'    =>  $conf['sort_order'],
                            'limit'         =>  $conf['limit']
                        )
                    );
                }else{
                    $data = parent::fetchSubCategory(
                        array(
                            'fetch'         =>  'in_cat',
                            'idclc'         =>  $current['category']['id'],
                            'sort_type'     =>  $conf['sort_type'],
                            'sort_order'    =>  $conf['sort_order'],
                            'limit'         =>  $conf['limit']
                        )
                    );
                }

            } else {
                // Subcategory
                if($class && class_exists($class)){
                    $data = $class::fetchSubCategory(
                        array(
                            'fetch'         =>  'all',
                            'iso'           =>  $conf['lang'],
                            'selectmodeid'  =>  $conf['id'],
                            'selectmode'    =>  $conf['type'],
                            'sort_type'     =>  $conf['sort_type'],
                            'sort_order'    =>  $conf['sort_order'],
                            'limit'         =>  $conf['limit']
                        )
                    );
                }else{
                    $data = parent::fetchSubCategory(
                        array(
                            'fetch'         =>  'all',
                            'iso'           =>  $conf['lang'],
                            'selectmodeid'  =>  $conf['id'],
                            'selectmode'    =>  $conf['type'],
                            'sort_type'     =>  $conf['sort_type'],
                            'sort_order'    =>  $conf['sort_order'],
                            'limit'         =>  $conf['limit']
                        )
                    );
                }

            }

            if ($conf['context'][2] == 'product' AND $data != null) {

                foreach ($data as $k1 => $v_1)
                {
                    // Subcategory > product
                    if($class && class_exists($class)){
                        $data_2 =  $class::fetchProduct(
                            array(
                                'fetch'         =>  'all_in',
                                'idclc'         =>  $v_1['idclc'],
                                'idcls'         =>  $v_1['idcls'],
                                'sort_type'     =>  $conf['sort_type'],
                                'sort_order'    =>  $conf['sort_order'],
                                'limit'         =>  $conf['limit']
                            )
                        );

                    }else{
                        $data_2 =  parent::fetchProduct(
                            array(
                                'fetch'         =>  'all_in',
                                'idclc'         =>  $v_1['idclc'],
                                'idcls'         =>  $v_1['idcls'],
                                'sort_type'     =>  $conf['sort_type'],
                                'sort_order'    =>  $conf['sort_order'],
                                'limit'         =>  $conf['limit']
                            )
                        );
                    }
                    if ($data_2 != null) {
                        $data[$k1]['subdata']   =   $data_2;
                    }
                }
            }
        } elseif ( $conf['context'][1] == 'product') {

            if (isset($current['product']['id']) AND empty($custom['select'])) {
                // Product[in_product]
                if($class && class_exists($class)){
                    $data   =   $class::fetchProduct(
                        array(
                            'fetch'         => 'related',
                            'idproduct'     => $current['product']['id'],
                            'selectmode'    => $conf['type'],
                            'selectmodeid'  => $conf['id'],
                            'sort_type'     => $conf['sort_type'],
                            'sort_order'    => $conf['sort_order'],
                            'limit'         => $conf['limit']
                        )
                    );
                }else{
                    $data = parent::fetchProduct(
                        array(
                            'fetch'         => 'related',
                            'idproduct'     => $current['product']['id'],
                            'selectmode'    => $conf['type'],
                            'selectmodeid'  => $conf['id'],
                            'sort_type'     => $conf['sort_type'],
                            'sort_order'    => $conf['sort_order'],
                            'limit'         => $conf['limit']
                        )
                    );
                }

            } elseif ( (isset($current['category']['id']) OR isset($current['subcategory']['id'])) AND empty($custom['select'])) {
                // Product[in_category OR in_subcategory]
                if (isset($current['category']['id'])) {
                    $catId  =   $current['category']['id'];
                    $subcatId  =   (isset($current['subcategory']['id'])) ? $current['subcategory']['id'] : 0;
                } else {
                    $catId  =   null;
                    $subcatId  =   (isset($current['subcategory']['id'])) ? $current['subcategory']['id'] : null;
                }
                if(isset($custom['select']) OR $custom['exclude']){
                    if($class && class_exists($class)) {
                        $data = $class::fetchProduct(
                            array(
                                'fetch'         =>  'all',
                                'context'       =>  $conf['context'][1],
                                'idclc'         =>  $catId,
                                'idcls'         =>  $subcatId,
                                'limit'         =>  $conf['limit'],
                                'selectmode'    =>  $conf['type'],
                                'selectmodeid'  =>  $conf['id'],
                                'sort_type'     =>  $conf['sort_type'],
                                'sort_order'    =>  $conf['sort_order']
                            )
                        );
                    }else{
                        $data = parent::fetchProduct(
                            array(
                                'fetch'         =>  'all',
                                'context'       =>  $conf['context'][1],
                                'idclc'         =>  $catId,
                                'idcls'         =>  $subcatId,
                                'limit'         =>  $conf['limit'],
                                'selectmode'    =>  $conf['type'],
                                'selectmodeid'  =>  $conf['id'],
                                'sort_type'     =>  $conf['sort_type'],
                                'sort_order'    =>  $conf['sort_order']
                            )
                        );
                    }
                }else{
                    if($class && class_exists($class)){
                        $data =  $class::fetchProduct(
                            array(
                                'fetch'         =>  'all_in',
                                'idclc'         =>  $catId,
                                'idcls'         =>  $subcatId,
                                'sort_type'     =>  $conf['sort_type'],
                                'sort_order'    =>  $conf['sort_order'],
                                'limit'         =>  $conf['limit']
                            )
                        );
                    }else{
                        $data =  parent::fetchProduct(
                            array(
                                'fetch'         =>  'all_in',
                                'idclc'         =>  $catId,
                                'idcls'         =>  $subcatId,
                                'sort_type'     =>  $conf['sort_type'],
                                'sort_order'    =>  $conf['sort_order'],
                                'limit'         =>  $conf['limit']
                            )
                        );
                    }
                }

            } else {
                if(isset($custom['select']) OR $custom['exclude']){
                    if($class && class_exists($class)) {
                        $data = $class::fetchProduct(
                            array(
                                'fetch'         =>  'all',
                                'context'       =>  $conf['context'][1],
                                'iso'           =>  $conf['lang'],
                                'limit'         =>  $conf['limit'],
                                'selectmode'    =>  $conf['type'],
                                'selectmodeid'  =>  $conf['id'],
                                'sort_type'     =>  $conf['sort_type'],
                                'sort_order'    =>  $conf['sort_order']
                            )
                        );
                    }else{
                        $data = parent::fetchProduct(
                            array(
                                'fetch'         =>  'all',
                                'context'       =>  $conf['context'][1],
                                'iso'           =>  $conf['lang'],
                                'limit'         =>  $conf['limit'],
                                'selectmode'    =>  $conf['type'],
                                'selectmodeid'  =>  $conf['id'],
                                'sort_type'     =>  $conf['sort_type'],
                                'sort_order'    =>  $conf['sort_order']
                            )
                        );
                    }
                }else{
                    // All products in lang
                    if($class && class_exists($class)) {
                        $data =  $class::fetchProduct(
                            array(
                                'fetch'         =>  'all_in',
                                'sort_type'     =>  $conf['sort_type'],
                                'sort_order'    =>  $conf['sort_order'],
                                'limit'         =>  $conf['limit'],
                            )
                        );
                    }else{
                        $data =  parent::fetchProduct(
                            array(
                                'fetch'         =>  'all_in',
                                'sort_type'     =>  $conf['sort_type'],
                                'sort_order'    =>  $conf['sort_order'],
                                'limit'         =>  $conf['limit'],
                            )
                        );
                    }
                }
            }
        } elseif ($conf['context'][1] == 'last-product') {
            // Product[last]
            // @TODO: mise en place des paramètre 'exclude'
            if($class && class_exists($class)){
                $data =  $class::fetchProduct(
                    array(
                        'fetch'         =>  'all_in',
                        'sort_type'     =>  $conf['sort_type'],
                        'sort_order'    =>  $conf['sort_order'],
                        'limit'         =>  $conf['limit']
                    )
                );
            }else{
                $data =  parent::fetchProduct(
                    array(
                        'fetch'         =>  'all_in',
                        //'idclc'         =>  $conf['id'],
                        'sort_type'     =>  $conf['sort_type'],
                        'sort_order'    =>  $conf['sort_order'],
                        'limit'         =>  $conf['limit']
                    )
                );
            }
        }else if($conf['context'][1] == 'last-product-cat'){
            if(isset($custom['select']) OR $custom['exclude']){
                if($class && class_exists($class)) {
                    $data = $class::fetchProduct(
                        array(
                            'fetch'         =>  'all',
                            'context'       =>  $conf['context'][1],
                            'iso'           =>  $conf['lang'],
                            'limit'         =>  $conf['limit'],
                            'selectmode'    =>  $conf['type'],
                            'selectmodeid'  =>  $conf['id'],
                            'sort_type'     =>  $conf['sort_type'],
                            'sort_order'    =>  $conf['sort_order'],
                        )
                    );
                }else{
                    $data = parent::fetchProduct(
                        array(
                            'fetch'         =>  'all',
                            'context'       =>  $conf['context'][1],
                            'iso'           =>  $conf['lang'],
                            'limit'         =>  $conf['limit'],
                            'selectmode'    =>  $conf['type'],
                            'selectmodeid'  =>  $conf['id'],
                            'sort_type'     =>  $conf['sort_type'],
                            'sort_order'    =>  $conf['sort_order'],
                        )
                    );
                }
            }else{
                if($class && class_exists($class)) {
                    $data =  $class::fetchProduct(
                        array(
                            'fetch'         =>  'all_in',
                            'sort_type'     =>  $conf['sort_type'],
                            'sort_order'    =>  $conf['sort_order'],
                            'limit'         =>  $conf['limit'],
                        )
                    );
                }else{
                    $data =  parent::fetchProduct(
                        array(
                            'fetch'         =>  'all_in',
                            'sort_type'     =>  $conf['sort_type'],
                            'sort_order'    =>  $conf['sort_order'],
                            'limit'         =>  $conf['limit'],
                        )
                    );
                }
            }

        } elseif ($conf['context'][1] == 'last-product-subcat') {
            if(isset($custom['select']) OR $custom['exclude']){
                if($class && class_exists($class)) {
                    $data = $class::fetchProduct(
                        array(
                            'fetch'         =>  'all',
                            'context'       =>  $conf['context'][1],
                            'iso'           =>  $conf['lang'],
                            'limit'         =>  $conf['limit'],
                            'selectmode'    =>  $conf['type'],
                            'selectmodeid'  =>  $conf['id'],
                            'sort_type'     =>  $conf['sort_type'],
                            'sort_order'    =>  $conf['sort_order'],
                        )
                    );
                }else{
                    $data = parent::fetchProduct(
                        array(
                            'fetch'         =>  'all',
                            'context'       =>  $conf['context'][1],
                            'iso'           =>  $conf['lang'],
                            'limit'         =>  $conf['limit'],
                            'selectmode'    =>  $conf['type'],
                            'selectmodeid'  =>  $conf['id'],
                            'sort_type'     =>  $conf['sort_type'],
                            'sort_order'    =>  $conf['sort_order'],
                        )
                    );
                }
            }else{
                if($class && class_exists($class)) {
                    $data =  $class::fetchProduct(
                        array(
                            'fetch'         =>  'all_in',
                            'sort_type'     =>  $conf['sort_type'],
                            'sort_order'    =>  $conf['sort_order'],
                            'limit'         =>  $conf['limit'],
                        )
                    );
                }else{
                    $data =  parent::fetchProduct(
                        array(
                            'fetch'         =>  'all_in',
                            'sort_type'     =>  $conf['sort_type'],
                            'sort_order'    =>  $conf['sort_order'],
                            'limit'         =>  $conf['limit'],
                        )
                    );
                }
            }

        } elseif($conf['context'][1] == 'product-gallery') {
            // Product Gallery
            if($class && class_exists($class)) {
                $data = $class::fetchProduct(
                    array(
                        'fetch'         => 'galery',
                        'idproduct'     => $current['product']['id'],
                        'sort_type'     => $conf['sort_type'],
                        'sort_order'    => $conf['sort_order'],
                        'limit'         => $conf['limit']
                    )
                );
            }else{
                $data = parent::fetchProduct(
                    array(
                        'fetch'         => 'galery',
                        'idproduct'     => $current['product']['id'],
                        'sort_type'     => $conf['sort_type'],
                        'sort_order'    => $conf['sort_order'],
                        'limit'         => $conf['limit']
                    )
                );
            }
        }
        return $data;
    }
}
?>