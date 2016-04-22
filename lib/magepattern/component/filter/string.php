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
 * Created by SC BOX.
 * User: aureliengerits
 * Date: 29/07/12
 * Time: 23:44
 *
 */
class filter_string{
    /**
     * Renvoi une chaine en majuscule en tenant compte de l'encodage
     *
     * @param string $str
     * @return string
     */
    public static function strtoupper($str){

        if (function_exists("mb_strtoupper")) {
            if (mb_detect_encoding($str,"utf-8") == "utf-8") {
                $str = mb_strtoupper($str,'utf-8');
            }
            elseif(mb_detect_encoding($str, "ISO-8859-1")){
                $str = mb_strtoupper($str, "ISO-8859-1");
            }
        }else{
            $str = strtoupper($str);
        }
        return $str;
    }

    /**
     * Renvoi une chaine en minuscule en tenant compte de l'encodage
     *
     * @param string $str
     * @return string
     */
    public static function strtolower($str){

        if (function_exists("mb_strtolower")) {
            if (mb_detect_encoding($str,"UTF-8") == "UTF-8") {
                $str = mb_strtolower($str,'UTF-8');
            }elseif(mb_detect_encoding($str, "ISO-8859-1")){
                $str = mb_strtolower($str,'ISO-8859-1');
            }
        }else{
            $str = strtolower($str);
        }
        return $str;
    }
    /**
     * Convert first letters string in Uppercase
     *
     * @param $str
     * @return string
     */
    public static function ucFirst($str){
        $str = self::strtoupper(substr($str,0,1)).substr($str,1);
        return $str;
    }
    /**
     * truncate string with clean delimiter
     * Tronque une chaîne de caractères sans couper au milieu d'un mot
     * @param $string
     * @param $lg_max (length max)
     * @param $delimiter (delimiter ...)
     * @return string
     */
    public static function truncate($string,$lg_max,$delimiter){
        if(form_inputFilter::isMaxString($string,$lg_max)){
            $string = substr($string, 0, $lg_max);
            $last_space = strrpos($string, " ");
            $string = substr($string, 0, $last_space).$delimiter;
        }
        return $string;
    }
}
?>