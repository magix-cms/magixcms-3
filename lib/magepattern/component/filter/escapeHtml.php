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
 * Time: 19:31
 *
 */
class filter_escapeHtml{
    /**
     * function trim string function
     *
     * @param string $str
     * @return string
     */
    public static function trim($str){
        return trim($str);
    }

    /**
     *
     * Remove markup
     *
     * Removes every tags, comments, cdata from string
     *
     * @param string	$str		String to clean
     * @return	string
     */
    public static function clean($str)
    {
        $str = strip_tags($str);
        return $str;
    }

    /**
     * HTML escape
     *
     * Replaces HTML special characters by entities.
     *
     * @param string $str	String to escape
     * @return	string
     */
    public static function escapeHTML($str)
    {
        return htmlspecialchars($str,ENT_COMPAT,'UTF-8');
    }

    /**
     * HTML Extreme escape
     *
     * Replaces HTML characters by entities.
     *
     * @param string $str	String to escape
     * @return	string
     */
    public static function escapeExtremeHTML($str)
    {
        return htmlentities($str,ENT_COMPAT,'UTF-8');
    }

    /**
     * decode Extreme htmlentities
     *
     * @param string $str
     * @return string
     */
    public static function decodeExtremeHTML($str){
        return html_entity_decode($str,ENT_COMPAT,'UTF-8');
    }

    /**
     * function pour supprimer les antislash
     *
     * @param string $string
     * @return string
     */
    public static function cleanQuote($string){
        return stripcslashes($string);
    }

    /**
     * funtion intval —  Retourne la valeur numérique entière équivalente d'une variable
     * @param $int
     * @return Get the integer value of a variable
     */
    public static function intval($int){
        return intval($int);
    }
}
?>