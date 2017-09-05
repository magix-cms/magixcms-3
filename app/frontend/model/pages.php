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

    protected $routingUrl,$imagesComponent;

    public function __construct($template)
    {
        $this->routingUrl = new component_routing_url();
        $this->imagesComponent = new component_files_images($template);
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
            $data['id']         = $row['id_pages'];
            $data['id_parent']  = !is_null($row['id_parent']) ? $row['id_parent'] : NULL;
            $data['title']       = $row['name_pages'];
            $data['iso']        = $row['iso_lang'];
            $data['url']  =
                $this->routingUrl->getBuildPages(array(
                    'iso_lang'  =>  $row['iso_lang'],
                    'id_pages'  =>  $row['id_pages'],
                    'url_pages' =>  $row['url_pages']
                ));
            $data['active'] = false;

            if ($row['id_pages'] == $current['controller']['id']) {
                $data['active'] = true;
            }

            if (isset($row['img_pages'])) {
                $imgPrefix = $this->imagesComponent->prefix();
                $fetchConfig = $this->imagesComponent->getConfigItems(array(
                    'module_img'=>'pages',
                    'attribute_img'=>'page'
                ));
                foreach ($fetchConfig as $key => $value) {
                    $data['imgSrc'][$value['type_img']]['img'] = '/upload/pages/'.$row['id_pages'].'/'.$imgPrefix[$value['type_img']] . $row['img_pages'];
                }
            }
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
            $arr[$item['id_lang']] = $this->routingUrl->getBuildPages(array(
                'iso_lang'  =>  $item['iso_lang'],
                'id_pages'  =>  $item['id_pages'],
                'url_pages' =>  $item['url_pages']
            ));
        }
        return $arr;
    }

    /**
     * Retourne les données sql sur base des paramètres passés en paramète
     * @param $custom
     * @param array $current
     * @param bool $class
     * @return array|null
     */
    public static function getData($custom,$current,$class = false)
    {
        if (!(is_array($custom))) {
            return null;
        }

        if (!(array_key_exists('controller',$current))) {
            return null;
        }

        $conf   =   array(
            'id'        =>  null,
            'type'      =>  'data',
            'limit'     =>  null,
            'lang'      =>  $current['lang']['iso'],
            'context'   =>  array(1 => 'parent')
        );

        $current    =   $current['controller'];

        // custom values: select or exclude
        if (isset($custom['select']) ) {
            if ($custom['select'] == 'current') {
                $conf['id'] = $current['id'];
            } elseif (is_array($custom['select']) ) {
                if (array_key_exists($conf['lang'],$custom['select'])) {
                    $conf['id'] = $custom['select'][$conf['lang']];
                }
            }
        } elseif (isset($custom['exclude'])) {
            if (is_array($custom['exclude'])) {
                if (array_key_exists($conf['lang'],$custom['exclude'])) {
                    $conf['id'] = $custom['exclude'][$conf['lang']];
                    //$conf['type'] = 'exclude';
                }
            }
        }

        // custom values: display
        if (isset($custom['context'])) {
            if (is_array($custom['context'])) {
                foreach ($custom['context'] as $k => $v)
                {
                    $conf['context'][1] = $k;
                    $conf['context'][2] = $v;
                }
            } else {
                $allowed = array(
                    '',
                    'all',
                    'parent',
                    'child'
                );

                if (in_array($custom['context'],$allowed)) {
                    $conf['context'][1] = $custom['context'];
                }
            }
        }

        if (isset($custom['limit'])) {
            $conf['limit']  =   $custom['limit'];
        }

        // Override with plugin
        if (isset($custom['plugins'])) {
            $conf['plugins']  =   $custom['plugins'];
        }

        // *** Load SQL data
        $conditions = '';
        if ($conf['context'][1] == 'parent' OR $conf['context'][1] == 'all') {

            $conditions .= ' WHERE lang.iso_lang = :iso AND c.published_pages = 1 AND p.id_parent IS NULL ';

            if(isset($custom['select'])){

                $conditions .= ' AND p.id_pages IN ('.$conf['id'].') ';
            }
            if(isset($custom['exclude'])){

                $conditions .= ' AND p.id_pages NOT IN ('.$conf['id'].') ';
            }

            if($conf['type'] == 'menu'){
                $conditions .= ' AND p.menu_pages = 1';
            }
            // ORDER
            $conditions .= ' ORDER BY p.order_pages ASC';

            if($conf['limit'] != null){
                $conditions .= ' LIMIT '.$conf['limit'];
            }

            if($conditions != ''){
                $data = parent::fetchData(
                    array('context'=>'all','type'=>'pages','conditions'=>$conditions),
                    array(
                        ':iso'=>$conf['lang']
                    )
                );
            }

            if($data != null AND ($conf['context'][2] == 'child' OR $conf['context'][1] == 'all'))
            {
                foreach ($data as $k1 => $v1)
                {

                    $conditions = '';
                    $conditions .= ' WHERE lang.iso_lang = :iso AND c.published_pages = 1
                    AND p.id_parent = :id';

                    if(isset($custom['select'])){

                        $conditions .= ' AND p.id_pages IN ('.$conf['id'].') ';
                    }
                    if(isset($custom['exclude'])){

                        $conditions .= ' AND p.id_pages NOT IN ('.$conf['id'].') ';
                    }

                    if($conf['type'] == 'menu'){
                        $conditions .= ' AND p.menu_pages = 1';
                    }

                    if($conf['limit'] != null){
                        $conditions .= ' LIMIT '.$conf['limit'];
                    }

                    $conditions .= ' GROUP BY p.id_pages ORDER BY p.order_pages ASC';

                    if($conditions != ''){
                        $data_2 = parent::fetchData(
                            array('context'=>'all','type'=>'child','conditions'=>$conditions),
                            array(
                                ':iso'  =>  $conf['lang'],
                                ':id'   =>  $v1['id_pages']
                            )
                        );
                    }

                    if ($data_2 != null) {
                        $data[$k1]['subdata'] = $data_2;
                    }
                }
                $data_2 = null;
            }
        } elseif ($conf['context'][1] == 'child') {
            $conditions = '';
            $conditions .= ' WHERE lang.iso_lang = :iso AND c.published_pages = 1 AND p.id_parent = :id';
            if($conf['type'] == 'menu'){
                $conditions .= ' AND p.menu_pages = 1';
            }

            $conditions .= ' GROUP BY p.id_pages';
            if($conf['sort'] != null){
                $conditions .= ' ORDER BY p.order_pages';
            }
            if($conf['limit'] != null){
                $conditions .= ' LIMIT '.$conf['limit'];
            }

            $data = parent::fetchData(
                array(
                    'context'=>'all',
                    'type'=>'child',
                    'conditions'=>$conditions
                ),
                array(
                    ':iso'=>$conf['lang'],
                    ':id'=>$conf['id'])
            );
        } /*elseif ($conf['context'][1] == 'mix') {
            $data = parent::s_page_all($conf['lang'],$conf['id'],$conf['type'],$conf['limit']);
            if($data != null AND ($conf['context'][2] == 'child' OR $conf['context'][1] == 'all'))
            {
                foreach ($data as $k1 => $v1)
                {
                    $data_2 =
                        parent::s_page_child(
                            $conf['lang'],
                            $v1['id_pages']
                        );
                    if ($data_2 != null)
                        $data[$k1]['subdata'] = $data_2;
                }
                $data_2 = null;
            }
        }*/

        return $data;
    }
}
?>