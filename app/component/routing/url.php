<?php
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2013 magix-cms.com <support@magix-cms.com>
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
class component_routing_url{

    public function __construct(){}

    /**
     * @param $data
     * @return string
     */
	public function getBuildUrl($data){
        if(is_array($data)) {
            $iso = $data['iso'];
            $type = $data['type'];
            switch($type){
                case 'pages':
                    $url = '/'.$iso.'/'.$type.'/'.$data['id'].'-'.$data['url'].'/';
                    break;
                case 'about':
                    $url = '/'.$iso.'/'.$type.'/'.$data['id'].'-'.$data['url'].'/';
                    break;
                case 'category':
                    $url = '/'.$iso.'/catalog/'.$data['id'].'-'.$data['url'].'/';
                    break;
                case 'product':
                    if(isset($data['id_parent']) && isset($data['url_parent'])) {
                        $url = '/' . $iso . '/catalog/' . $data['id_parent'] . '-' . $data['url_parent'] . '/' . $data['id'] . '-' . $data['url'] . '/';
                    }
                    break;
            }
            return $url;
        }
    }
}