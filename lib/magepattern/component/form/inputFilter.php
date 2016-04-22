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
 * Date: 21/07/12
 * Time: 14:37
 *
 */
class form_inputFilter{
    /**
     * Constante for URL format
     * @var void
     */
    const REGEX_URL_FORMAT = '~^(https?|ftps?)://   # protocol
      (([a-z0-9-]+\.)+[a-z]{2,6}              		# a domain name
          |                                   		#  or
        \d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}    		# a IP address
      )
      (:[0-9]+)?                              		# a port (optional)
      (/?|/\S+)                               		# a /, nothing or a / with something
    $~ix';

    /**
     * function isEmpty
     *
     * @param string $val
     * @param bool $zero
     * @return false
     */
    public static function isEmpty($val,$zero = true){
        $val = trim($val);
        if($zero){
            $value = empty($val) && $val !== 0;
        }else{
            $value =  empty($val);
        }
    }

    /**
     * function isURL
     * is Valide URL
     *
     * @param bool $url
     * @throws Exception
     * @return bool
     */
    public static function isURL($url){
        /*filter_var($url, FILTER_VALIDATE_URL);//FILTER_FLAG_SCHEME_REQUIRED
          return $url;*/
        //String
        $clean = (string) $url;
        // Invalid URL
        if (!preg_match(self::REGEX_URL_FORMAT, $clean)){
            return false;
        }else{
            return $clean;
        }
    }
    /**
     * function isMail
     *
     * @param bool $mail
     * @return bool
     */
    public static function isMail($mail){
        return filter_var($mail, FILTER_VALIDATE_EMAIL) ? $mail : false;
    }
    /**
     * Checks if variable of Numeric
     *
     * @param bool $str
     * @return bool
     */
    public static function isNumeric($str){
        return (integer) ctype_digit($str) ? $str : false;
    }
    /**
     * Checks if variable of Float
     *
     * @param bool $str
     * @return bool
     */
    public static function isFloat($str){
        return filter_var($str, FILTER_VALIDATE_FLOAT) ? $str : false;
    }
    /**
     * Checks if variable of Integer
     *
     * @param bool $str
     * @return bool
     */
    public static function isInt($str){
        return filter_var($str,FILTER_VALIDATE_INT) ? $str : false;
    }
    /**
     * Checks if variable of String
     *
     * @param bool $str
     * @return bool
     */
    public static function isAlpha($str){
        return (string) ctype_alpha($str) ? $str : false;
    }
    /**
     * Checks if variable of alphanumeric
     *
     * @param bool $str
     * @return bool
     */
    public static function isAlphaNumeric($str){
        return (string) ctype_alnum($str) ? $str : false;
    }

    /**
     * Function pour vérifier la longueur minimal d'un texte
     *
     * @param $str
     * @param integer $size
     * @internal param string $getPost
     * @return vars
     */
    public static function isMinString($str, $size){
        $small = strlen($str) < $size;
        return $small;
    }

    /**
     * Function pour vérifier la longueur maximal d'un texte
     *
     * @param $str
     * @param integer $size
     * @internal param string $getPost
     * @return vars
     */
    public static function isMaxString($str, $size){
        $largest = strlen($str) > $size;
        return $largest;
    }
    /**
     *
     * Join function for get Alpha string
     *
     * @see filter_escapeHtml::trim
     * @see filter_escapeHtml::isAlpha
     * @see filter_escapeHtml::isMaxString
     *
     * @param string $str
     * @param intéger $lg_max
     * @return bool|string
     */
    public static function isAlphaMax($str,$lg_max){
        $string = self::isAlpha(filter_escapeHtml::trim($str));
        $string .= self::isMaxString($str,$lg_max);
        return $string;
    }

    /**
     * Join function for get Alpha Numéric string
     *
     * @see filter_escapeHtml::trim
     * @see filter_escapeHtml::isAlphaNumeric
     * @see filter_escapeHtml::isMaxString
     *
     * @param string $str
     * @param intéger $lg_max
     * @return bool|string
     */
    public static function isAlphaNumericMax($str,$lg_max){
        $string = self::isAlphaNumeric(filter_escapeHtml::trim($str));
        $string .= self::isMaxString($str,$lg_max);
        return $string;
    }

    /**
     * Join function for get Intéger
     *
     *
     * @see filter_escapeHtml::trim
     * @see filter_escapeHtml::isNumeric
     * @see filter_escapeHtml::isMaxString
     *
     * @param string $str
     * @param intéger $lg_max
     * @return bool|string
     */
    public static function isNumericClean($str,$lg_max){
        $string = self::isNumeric(filter_escapeHtml::trim($str));
        $string .= self::isMaxString($str,$lg_max);
        return $string;
    }
}
?>