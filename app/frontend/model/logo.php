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
    /**
     * @var frontend_model_template $template
     * @var frontend_model_data $data
     * @var component_files_images $imagesComponent
     * @var file_finder $finder
     */
    protected frontend_model_template $template;
    protected frontend_model_data $data;
    protected component_files_images $imagesComponent;
    protected file_finder $finder;

    /**
     * @var string $iso
     */
    public string $iso;

    /**
     * frontend_model_domain constructor.
     * @param null|frontend_model_template $t
     */
    public function __construct(frontend_model_template $t = null) {
        $this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
        $this->data = new frontend_model_data($this,$this->template);
        $this->iso = $this->template->lang;
        $this->imagesComponent = new component_files_images($this->template);
        $this->finder = new file_finder();
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param array|int|null $id
     * @param string|null $context
     * @param bool|string $assign
     * @return mixed
     */
    private function getItems(string $type, $id = null, string $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }

	/**
	 * @return array
	 */
    public function getLogoData(): array {
        $data = $this->getItems('page', ['iso'=>$this->iso],'one',false);
        $newData = [];
        if(!empty($data) && $data['img_logo'] != '') {
            $fetchConfig = $this->imagesComponent->getConfigItems('logo','logo');
            if(file_exists(component_core_system::basePath().'/img/logo/'.$data['img_logo'])) {
                $extwebp = 'webp';
                // # return filename without extension
                $pathinfo = pathinfo($data['img_logo']);
                $filename = $pathinfo['filename'];
                $extension = $pathinfo['extension'];
                $newData['img']['active'] = $data['active_logo'];
                $newData['img']['alt'] = $data['alt_logo'];
                $newData['img']['title'] = $data['title_logo'];

                foreach ($fetchConfig as $value) {
                    $imginfo = $this->imagesComponent->getImageInfos(component_core_system::basePath().'/img/logo/'.$value['prefix'].'_'.$filename.'@'.$value['width'].'.'.$extension);
                    $newData['img'][$value['type']]['src'] = '/img/logo/'.$value['prefix'].'_'.$filename.'@'.$value['width'].'.'.$extension;
                    if(file_exists(component_core_system::basePath() .'/img/logo/'.$value['prefix'].'_'.$filename.'@'.$value['width'].'.'.$extwebp)){
                        $newData['img'][$value['type']]['src_webp'] = '/img/logo/'.$value['prefix'].'_'.$filename.'@'.$value['width'].'.'.$extwebp;
                    }
                    $newData['img'][$value['type']]['w'] = $value['resize'] === 'basic' ? $imginfo['width'] : $value['width'];
                    $newData['img'][$value['type']]['h'] = $value['resize'] === 'basic' ? $imginfo['height'] : $value['height'];
                    $newData['img'][$value['type']]['crop'] = $value['resize'];
                    $newData['img'][$value['type']]['ext'] = mime_content_type(component_core_system::basePath().'/img/logo/'.$value['prefix'].'_'.$filename.'@'.$value['width'].'.'.$extension);
                }
            }
        }
		return $newData;
    }

    /**
     * @return array
     */
    public function getFaviconData(): array {
        $newData = [];
        $favCollection = $this->finder->scanDir(component_core_system::basePath().'/img/favicon/','.gitignore');
        if(is_array($favCollection)) {
            foreach ($favCollection as $value) {
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
    public function getHomescreen(): array {
        $newData = [];
        $collection = $this->finder->scanDir(component_core_system::basePath().'/img/touch/','.gitignore');
        if(is_array($collection)) {
            foreach ($collection as $value) {
                $size = $this->imagesComponent->getImageInfos(component_core_system::basePath().'/img/touch/'.$value);
                $newData['img'][$size['width']]['src'] = '/img/touch/'.$value;
                $newData['img'][$size['width']]['w'] = $size['width'];
                $newData['img'][$size['width']]['h'] = $size['height'];
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

    /**
     * @return array
     */
    public function getImageSocial(): array {
        $newData = [];
        if(file_exists(component_core_system::basePath() . '/img/social/' . 'social.jpg')) {
            $size = $this->imagesComponent->getImageInfos(component_core_system::basePath().'/img/social/'.'social.jpg');
            $newData['img']['src'] = '/img/social/'.'social.jpg';
            $newData['img']['w'] = $size['width'];
            $newData['img']['h'] = $size['height'];
        }
        return $newData;
    }
}