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
 * Time: 23:26
 *
 */
class form_inputEscape{
    /**
     * Combine function trim and escapeHTML for input
     *
     * @param string $str
     * @return string
     */
    public static function simpleClean($str){
        return filter_escapeHtml::trim(filter_escapeHtml::escapeHTML($str));
    }
    /**
     * Combine function trim and Extreme escapeHTML for input
     *
     * @param string $str
     * @return string
     */
    public static function extremeClean($str){
        return filter_escapeHtml::trim(filter_escapeHtml::escapeExtremeHTML($str));
    }
    /**
     * Combine function trim and strip_tag for input
     *
     * @param string $str
     * @return string
     */
    public static function tagClean($str){
        return filter_escapeHtml::trim(filter_escapeHtml::clean($str));
    }
    /**
     * Conbine function trim and rplMagixString
     *
     * @param string $str
     * @return string
     */
    public static function rewriteUrl($str){
        return filter_escapeHtml::trim(http_url::clean($str));
    }
    /**
     * Conbine function trim and Clean Quote
     *
     * @param string $str
     * @return string
     */
    public static function cleanQuote($str){
        return filter_escapeHtml::trim(filter_escapeHtml::cleanQuote($str));
    }
    /**
     * Combine function trim and escapeHTML and downTextCase for input
     *
     * @param string $str
     * @return string
     */
    public static function cleanStrtolower($str){
        return filter_escapeHtml::trim(filter_escapeHtml::escapeHTML(filter_string::strtolower($str)));
    }
    /**
     * Combine function trimText and cleanTruncate for input
     * @param string $str
     * @param intégrer $lg_max
     * @param string $delimiter
     * @return string
     */
    public static function truncateClean($str,$lg_max,$delimiter){
        return filter_escapeHtml::trim(filter_string::truncate($str,$lg_max,$delimiter));
    }
    /**
     * Combine function trimText and isPostAlphaNumeric for input
     * @param string $str
     *
     * @return string
     */
    public static function alphaNumeric($str){
        return filter_escapeHtml::trim(form_inputFilter::isAlphaNumeric($str));
    }
    /**
     * Combine function trimText and isPostNumeric for input
     * @param string $str
     *
     * @return string
     */
    public static function numeric($str){
        return filter_escapeHtml::trim(form_inputFilter::isNumeric($str));
    }
    /**
     * Special function for clean array
     *
     * @param string $array
     * @return string
     */
    public static function arrayClean($array){
        if(is_array($array)){
            foreach($array as $key => $val) {
                if (!is_array($array[$key])) {
                    $array[$key] = self::simpleClean($val);
                }
                else{
                    $array[$key] = self::arrayClean($array[$key]);
                }
            }
            return $array;
        }
    }
    /**
     * Special function for extreme clean array
     *
     * @param string $array
     * @return string
     */
    public static function arrayExtremeClean($array){
        foreach($array as $key => $val) {
            if (!is_array($array[$key])) {
                $array[$key] = self::inputExtremeClean($val);
            }
            else{
                $array[$key] = self::arrayClean($array[$key]);
            }
        }
        return $array;
    }
}
?>