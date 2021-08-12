<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of Mage Pattern.
# The toolkit PHP for developer
# Copyright (C) 2012 - 2013 Gerits Aurelien contact[at]aurelien-gerits[dot]be
#
# OFFICIAL TEAM MAGE PATTERN:
#
#   * Gerits Aurelien (Author - Developer) contact[at]aurelien-gerits[dot]be
#
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
# Redistributions of source code must retain the above copyright notice,
# this list of conditions and the following disclaimer.
#
# Redistributions in binary form must reproduce the above copyright notice,
# this list of conditions and the following disclaimer in the documentation
# and/or other materials provided with the distribution.
#
# DISCLAIMER

# Do not edit or add to this file if you wish to upgrade Mage Pattern to newer
# versions in the future. If you wish to customize Mage Pattern for your
# needs please refer to http://www.magepattern.com for more information.
#
# -- END LICENSE BLOCK -----------------------------------

/**
 * Created by Magix Dev.
 * User: aureliengerits
 * Date: 17/06/12
 * Time: 22:30
 *
 */
class http_request{
    /**
     * Checks if variable of POST type exists
     *
     * @param bool $str
     * @return bool
     */
    public static function isPost($str){
        if(function_exists('filter_has_var')){
            return filter_has_var(INPUT_POST, $str) ? true : false;
        }else{
            return isset($_POST[$str]) ? true : false;
        }
    }
    /**
     * Checks if variable of GET type exists
     *
     * @param bool $str
     * @return bool
     */
    public static function isGet($str){
        if(function_exists('filter_has_var')){
            return filter_has_var(INPUT_GET, $str) ? true : false;
        }else{
            return isset($_GET[$str]) ? true : false;
        }
    }
    /**
     * Checks if variable of REQUEST type exists
     *
     * @param bool $str
     * @return bool
     */
    public static function isRequest($str){
        //@ToDo INPUT_REQUEST is not yet implemented for filter_has_var
        //
        //if(function_exists('filter_has_var')){
        //    return filter_has_var(INPUT_REQUEST, $str) ? true : false;
        //}else{
        //    return isset($_REQUEST[$str]) ? true : false;
        //}
        return isset($_REQUEST[$str]) ? true : false;
    }
    /**
     * Checks if variable of SESSION type exists
     *
     * @param bool $str
     * @return bool
     */
    public static function isSession($str){
        return isset($_SESSION[$str]) ? true : false;
    }
    /**
     * Checks if variable of SERVER type exists
     *
     * @param bool $str
     * @return bool
     */
    public static function isServer($str){
        if(function_exists('filter_has_var')){
            return filter_has_var(INPUT_SERVER, $str) ? true : false;
        }else{
            return isset($_SERVER[$str]) ? true : false;
        }
    }
}