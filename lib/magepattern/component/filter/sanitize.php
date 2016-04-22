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
 * Time: 20:44
 *
 */
class filter_sanitize{

    /**
     * function pour ajouter des antislash
     *
     * @param string $string
     * @return string
     */
    public static function magicQuote($string){
        if (version_compare(phpversion(), '5.2.0', '<')) {
            return addslashes($string);
        }else{
            return filter_var($string, FILTER_SANITIZE_MAGIC_QUOTES);
        }
    }

    /**
     * removes all illegal e-mail characters from a string.
     * This filter allows all letters, digits and $-_.+!*'{}|^~[]`#%/?@&=
     *
     * @param string $mail
     * @return string
     */
    public static function mail($mail){
        return filter_var($mail, FILTER_SANITIZE_EMAIL);
    }

    /**
     * URL sanitize
     *
     * Encode every parts between / in url
     *
     * @param string	$str		String to sanitize
     * @return	string
     */
    public static function url($str){
        if (version_compare(phpversion(), '5.2.0', '<')) {
            return str_replace('%2F','/',rawurlencode($str));
        }else {
            return filter_var($str, FILTER_SANITIZE_URL);
        }
    }

    /**
     * URL  revert sanitize
     *
     * Decode every parts between / in url
     *
     * @param string	$str		String to sanitize
     * @return	string
     */
    public static function revertUrl($str)
    {
        return rawurldecode($str);
    }

    /**
     * filter removes all illegal characters from a number.
     * This filter allows digits and . + -
     *
     * @param string $str
     * @return string
     */
    public static function numeric($str)
    {
        return filter_var($str, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * filter removes all illegal characters from a float number.
     * This filter allows digits and + - by default
     *
     * @param string $str
     * @param $flag
     * @return string
     */
    public static function float($str,$flag)
    {
        switch ($flag) {
            case 'fraction':
                $flag = FILTER_FLAG_ALLOW_FRACTION;
                break;
            case 'thousand':
                $flag = FILTER_FLAG_ALLOW_THOUSAND;
                break;
            case 'scientific':
                $flag = FILTER_FLAG_ALLOW_SCIENTIFIC;
                break;
        }
        return filter_var($str, FILTER_SANITIZE_NUMBER_FLOAT,$flag);
    }
}
?>