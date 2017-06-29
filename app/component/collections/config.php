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
/**
 * Author: Gerits Aurelien <aurelien[at]magix-cms[point]com>
 * Copyright: MAGIX CMS
 * Date: 13/12/13
 * Time: 21:09
 * License: Dual licensed under the MIT or GPL Version
 */
class component_collections_config{
    /**
     * @param $data
     * @return array
     * @deprecated
     */
    public function fetchImg($data){
        if(is_array($data)) {
            if (array_key_exists('context', $data)) {
                $context = $data['context'];
            }
            if($context === 'imgSize'){
                $sql = 'SELECT csi.*
                        FROM ap_config_size_img AS csi
                        WHERE csi.attr_name = :attr_name
                        ORDER BY csi.width DESC';
                return component_routing_db::layer()->fetchAll($sql,array(
                    ':attr_name' => $data['attr_name']
                ));
            }
        }
    }

    /**
     * @param $config
     * @param bool $data
     * @return mixed|null
     */
    public function fetchData($config,$data = false)
    {
        $sql = '';
        $params = false;
        if (is_array($config)) {
            if ($config['context'] === 'all' || $config['context'] === 'return') {
                if ($config['type'] === 'imgSize') {
                    $sql = 'SELECT * FROM mc_config_img 
                    WHERE module_img = :module_img AND attribute_img = :attribute_img
                    ORDER BY width_img DESC';
                    $params = $data;
                }
                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
            }elseif($config['context'] === 'unique' || $config['context'] === 'last') {
                if ($config['type'] === 'imgSize') {

                }
                return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
            }
        }
    }
}