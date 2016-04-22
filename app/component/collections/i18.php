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
 * Time: 18:12
 * License: Dual licensed under the MIT or GPL Version
 */
class component_collections_i18{
    /**
     * Remplace les valeurs d'un tableau par la traduction suivant sa clé
     * @param $template
     * @param array $arr
     * @throws Exception
     * @return array
     */
    private function replaceArrayFromConfig($template,$arr){
        if($arr!= NULL){
            if(is_array($arr)){
                $orig_arr = $arr;
                foreach($orig_arr as $key=>$value){
                    if(array_key_exists($key, $orig_arr)){
                        $new_tabs[$key] = $template->getConfigVars($key);
                    }
                }
                return $new_tabs;
            }else{
                throw new Exception('replaceArrayFromConfig is not array');
            }
        }else{
            return $arr;
        }
    }

    /**
     * Remplace une valeur d'un tableau par la traduction suivant sa clé
     * @param $template
     * @param array $arr
     * @param string $str
     * @throws Exception
     * @return array|null|string
     */
    private function replaceValFromConfig($template,$arr,$str){
        if($arr!= NULL){
            if(is_array($arr)){
                if(array_key_exists($str, $arr)){
                    return $template->getConfigVars($str);
                }else{
                    return null;
                }
            }else{
                throw new Exception('replaceValFromConfig : arr is not array');
            }
        }else{
            return $arr;
        }
    }

    /**
     * Retourne un nouveau tableau dans sa forme traduite
     * @param $template
     * @param array $orig_arr
     * @param null $str
     * @throws Exception
     * @return array|null|string
     */
    public static function replaceArrayToArray($template,array $orig_arr,$str=null){
        if($orig_arr!= NULL){
            if(is_array($orig_arr)){
                if(is_string($str) != null){
                    return self::replaceValFromConfig($template,$orig_arr,$str);
                }else{
                    return self::replaceArrayFromConfig($template,$orig_arr);
                }
            }else{
                throw new Exception('replaceArrayToArray params is bad formed');
            }
        }else{
            throw new Exception('replaceArrayToArray params is NULL');
        }
    }
}