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
class backend_model_logo extends backend_db_logo {
    protected $template, $data, $imagesComponent, $settings;
    public $iso;

    /**
     * frontend_model_domain constructor.
     * @param stdClass $t
     */
    public function __construct($t = null)
    {
        $this->template = $t ? $t : new backend_model_template();
        $this->data = new backend_model_data($this,$this->template);
        $this->iso = $this->template->lang;
        $this->imagesComponent = new component_files_images($this->template);
		$this->settings = new backend_model_setting();
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
	 * @return array
	 * @throws Exception
	 */
    public function getLogoData(){
        $data = $this->getItems('page', array('iso'=>$this->iso),'one',false);
        $newData = array();
        if($data != null && $data['img_logo'] != '') {
            $fetchConfig = $this->imagesComponent->getConfigItems(array(
                'module_img' => 'logo',
                'attribute_img' => 'logo'
            ));
            $path = 'img/logo/';
            if(file_exists(component_core_system::basePath().$path.$data['img_logo'])) {
                $extwebp = 'webp';
                // # return filename without extension
                $pathinfo = pathinfo($data['img_logo']);
                $filename = $pathinfo['filename'];
                $extension = $pathinfo['extension'];
                $newData['img']['active'] = $data['active_logo'];
                $newData['img']['alt'] = $data['alt_logo'];
                $newData['img']['title'] = $data['title_logo'];

                foreach ($fetchConfig as $key => $value) {
                    $imginfo = $this->imagesComponent->getImageInfos(component_core_system::basePath().$path.$filename.'@'.$value['width_img'].'.'.$extension);
                    $newData['img'][$value['type_img']]['src'] = '/'.$path.$filename.'@'.$value['width_img'].'.'.$extension;
                    $newData['img'][$value['type_img']]['src_webp'] = '/'.$path.$filename.'@'.$value['width_img'].'.'.$extwebp;
                    $newData['img'][$value['type_img']]['w'] = $value['resize_img'] === 'basic' ? $imginfo['width'] : $value['width_img'];
                    $newData['img'][$value['type_img']]['h'] = $value['resize_img'] === 'basic' ? $imginfo['height'] : $value['height_img'];
                    $newData['img'][$value['type_img']]['crop'] = $value['resize_img'];
                    $newData['img'][$value['type_img']]['ext'] = mime_content_type(component_core_system::basePath().$path.$filename.'@'.$value['width_img'].'.'.$extension);
                }
            }
        }
		return $newData;
    }
}