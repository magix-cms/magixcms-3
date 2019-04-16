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
 * Date: 14/12/13
 * Time: 00:29
 * License: Dual licensed under the MIT or GPL Version
 */
class component_collections_setting{
	/**
	 * @return mixed|null
	 */
	public function getSetting(){
		$data = $this->fetchData(array('context'=>'all','type'=>'setting'));
		$arr = array();
		if($data != null) {
			foreach ($data as $item) {
				$arr[$item['name']] = array();
				$arr[$item['name']]['value'] = $item['value'];
				$arr[$item['name']]['category'] = $item['category'];
			}
		}
		return $arr;
	}

    /**
     * @param $name
     * @return mixed
     * @throws Exception
     * @deprecated
     */
    public function fetch($name){
        $sql = 'SELECT *
    	FROM mc_setting WHERE name = :name';
        return component_routing_db::layer()->fetch($sql,
            array(
                ':name'	=>	$name
            )
        );
    }

    /**
     * @return mixed
     * @throws Exception
     * @deprecated
     */
    public function fetchAll(){
        $sql = 'SELECT st.id_setting,st.value 
        FROM mc_setting AS st';
        return component_routing_db::layer()->fetchAll($sql);
    }

    /**
     * @param $config
     * @param bool $data
     * @return mixed|null
     * @throws Exception
     */
    public function fetchData($config,$data = false){
        $sql = '';
        $params = false;

        if(is_array($config)) {
            if($config['context'] === 'all') {
                if ($config['type'] === 'setting') {
                    $sql = 'SELECT st.name,st.value,st.category
                    FROM mc_setting AS st';
                    //$params = $data;
                }
                elseif ($config['type'] === 'cssInliner') {
                    $sql = 'SELECT color.*
    	            FROM mc_css_inliner as color';
                    //$params = $data;
                }

                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;

            }
            elseif($config['context'] === 'one') {
                if ($config['type'] === 'setting') {
                    //Return current skin
                    $sql = 'SELECT *
    	            FROM mc_setting WHERE name = :name';
                    $params = $data;
                }

                return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
            }
        }
    }
}