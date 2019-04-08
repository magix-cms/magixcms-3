<?php
/*
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of MAGIX CMS.
# MAGIX CMS, The content management system optimized for users
# Copyright (C) 2008 - 2019 magix-cms.com <support@magix-cms.com>
#
# OFFICIAL TEAM :
#
#   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com>
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
class frontend_model_logo extends frontend_db_logo {

    protected $template, $data, $imagesComponent,$finder;
    public $iso;

    /**
     * frontend_model_domain constructor.
     * @param stdClass $t
     */
    public function __construct($t = null)
    {
        $this->template = $t ? $t : new frontend_model_template();
        $this->data = new frontend_model_data($this,$this->template);
        $this->iso = $this->template->currentLanguage();
        $this->imagesComponent = new component_files_images($this->template);
        $this->finder = new file_finder();
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
     * Return the valid domains
     */
    /*public function getValidDomains()
    {
        return $this->getItems('domain',null,'all',false);
    }*/
    public function getLogoData(){
        $data = $this->getItems('page', array(':iso'=>$this->iso),'one',false);
        $newData = array();
        if($data != null && $data['img_logo'] != '') {

            $fetchConfig = $this->imagesComponent->getConfigItems(array(
                'module_img' => 'logo',
                'attribute_img' => 'logo'
            ));
            if(file_exists(component_core_system::basePath().'/img/logo/'.$data['img_logo'])) {
                $extwebp = 'webp';
                // # return filename without extension
                $pathinfo = pathinfo($data['img_logo']);
                $filename = $pathinfo['filename'];
                $extension = $pathinfo['extension'];
                $newData['img']['active'] = $data['active_logo'];
                $newData['img']['alt'] = $data['alt_logo'];
                $newData['img']['title'] = $data['title_logo'];

                foreach ($fetchConfig as $key => $value) {
                    $imginfo = $this->imagesComponent->getImageInfos(component_core_system::basePath() . '/img/logo/' . $filename . '@' . $value['width_img'] . '.' . $extension);
                    $newData['img'][$value['type_img']]['src'] = '/img/logo/' . $filename . '@' . $value['width_img'] . '.' . $extension;
                    $newData['img'][$value['type_img']]['src_webp'] = '/img/logo/' . $filename . '@' . $value['width_img'] . '.' . $extwebp;
                    $newData['img'][$value['type_img']]['w'] = $value['resize_img'] === 'basic' ? $imginfo['width'] : $value['width_img'];
                    $newData['img'][$value['type_img']]['h'] = $value['resize_img'] === 'basic' ? $imginfo['height'] : $value['height_img'];
                    $newData['img'][$value['type_img']]['crop'] = $value['resize_img'];
                    $newData['img'][$value['type_img']]['ext'] = mime_content_type(component_core_system::basePath() . '/img/logo/' . $filename . '@' . $value['width_img'] . '.' . $extension);
                }
            }

            return $newData;
        }

    }

    /**
     * @return array
     */
    public function getFaviconData(){
        $newData = array();
        /* ##### favicon ######*/
        $favCollection = $this->finder->scanDir(component_core_system::basePath().'/img/favicon/','.gitignore');
        $favicon = null;
        if(is_array($favCollection)) {
            $favicon = array();
            foreach ($favCollection as $key => $value) {
                if($value != 'fav.png' && $value != 'fav.jpg') {
                    $pathinfo = pathinfo($value);
                    $index = $pathinfo['extension'];
                    $size = $this->imagesComponent->getImageInfos(component_core_system::basePath() . '/img/favicon/' . $value);
                    $newData['img'][$index]['src'] = '/img/favicon/' . $value;
                    $newData['img'][$index]['w'] = $size['width'];
                    $newData['img'][$index]['h'] = $size['height'];
                    $newData['img'][$index]['ext'] = mime_content_type(component_core_system::basePath() . '/img/favicon/' . $value);
                }
            }
        }
        return $newData;
    }

    /**
     * @return array
     */
    public function getImagePlaceholder(){
        $newData = array();
        $module = array('category','product','news','pages');
        foreach($module as $key){
            $dirImg = component_core_system::basePath().'/img/default/'.$key.'/';
            if(file_exists($dirImg)){
                $scanDir = $this->finder->scanDir($dirImg,'.gitignore');
                if(is_array($scanDir)){
                    $newData[$key] = '/img/default/'.$key.'/'.$scanDir[0];
                }
            }
        }
        return $newData;
    }
}