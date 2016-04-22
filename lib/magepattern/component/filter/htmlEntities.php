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
 * Date: 19/07/12
 * Time: 22:43
 *
 */
class filter_htmlEntities{

    /**
     * replace baskslash separator
     * function unix_separator
     * @return string
     */
    public static function unix_separator(){
        if (DIRECTORY_SEPARATOR == '\\') {
            $str = str_replace('\\','/',DIRECTORY_SEPARATOR);
        }else{
            $str = DIRECTORY_SEPARATOR;
        }
        return $str;
    }

    /**
     * replace slash separator
     * win_separator
     * @return string
     */
    public static function win_separator(){
        if (DIRECTORY_SEPARATOR == '/') {
            $str = str_replace('/','\\',DIRECTORY_SEPARATOR);
        }else{
            $str = DIRECTORY_SEPARATOR;
        }
        return $str;
    }

    /**
     * convert text in ASCII
     *
     * @param string $str
     * @return string
     */
    public static function convertASCII($str){
        return ord($str);
    }

    /**
     * decode text in ASCII
     *
     * @param string $str
     * @return string
     */
    public static function decodeASCII($str){
        return chr($str);
    }

    /**
     * Decode HTML entities
     *
     * Returns a string with all entities decoded.
     *
     * @param string    $str  String to protect
     * @param bool|string $keep_special Keep special characters: &gt; &lt; &amp;
     * @return    string
     */
    public static function decodeEntities($str,$keep_special=false)
    {
        if ($keep_special) {
            $str = str_replace(
                array('&amp;','&gt;','&lt;'),
                array('&amp;amp;','&amp;gt;','&amp;lt;'),
                $str);
        }

        # Some extra replacements
        $extra = array(
            '&apos;' => "'"
        );

        $str = str_replace(array_keys($extra),array_values($extra),$str);

        return html_entity_decode($str,ENT_QUOTES,'UTF-8');
    }

    /**
     * function encode entities HTML
     *
     * @param string $str
     * @param bool|void $keep_special
     * @return string
     */
    public static function encodeEntities($str,$keep_special=false){
        if ($keep_special) {
            $str = str_replace(
                array('&','<','</','>'),
                array('&amp;', '&lt;','&lt;/','&gt;'),
                $str);
        }

        # Some extra replacements
        $extra = array(
            "'" => '&apos;'
        );

        $str = str_replace(array_keys($extra),array_values($extra),$str);

        return $str;
        //return filter_var($str, FILTER_SANITIZE_SPECIAL_CHARS,FILTER_FLAG_ENCODE_HIGH);
        //return htmlspecialchars($str,ENT_QUOTES, 'UTF-8');
    }

    /**
     * URL escape
     *
     * Returns an escaped URL string for HTML content
     *
     * @param string	$str		String to escape
     * @return	string
     */
    public static function escapeURL($str){
        return str_replace('&','&amp;',$str);
    }

    /**
     * Javascript escape
     *
     * Returns a protected JavaScript string
     *
     * @param string	$str		String to protect
     * @return	string
     */
    public static function escapeJS($str){
        $str = htmlspecialchars($str,ENT_NOQUOTES,'UTF-8');
        $str = str_replace("'","\"",$str);
        $str = str_replace('"','\"',$str);
        return $str;
    }

    /**
     *
     * @Utf8_decode
     *
     * @Replace accented chars with latin
     *
     * @param string $string The string to convert
     *
     * @return string The corrected string
     *
     */
    public static function decode_utf8($string){

        $accented = array(
            'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ă', 'Ą',
            'Ç', 'Ć', 'Č', 'Œ',
            'Ď', 'Đ',
            'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ă', 'ą',
            'ç', 'ć', 'č', 'œ',
            'ď', 'đ',
            'È', 'É', 'Ê', 'Ë', 'Ę', 'Ě',
            'Ğ',
            'Ì', 'Í', 'Î', 'Ï', 'İ',
            'Ĺ', 'Ľ', 'Ł',
            'è', 'é', 'ê', 'ë', 'ę', 'ě',
            'ğ',
            'ì', 'í', 'î', 'ï', 'ı',
            'ĺ', 'ľ', 'ł',
            'Ñ', 'Ń', 'Ň',
            'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ő',
            'Ŕ', 'Ř',
            'Ś', 'Ş', 'Š',
            'ñ', 'ń', 'ň',
            'ò', 'ó', 'ô', 'ö', 'ø', 'ő',
            'ŕ', 'ř',
            'ś', 'ş', 'š',
            'Ţ', 'Ť',
            'Ù', 'Ú', 'Û', 'Ų', 'Ü', 'Ů', 'Ű',
            'Ý', 'ß',
            'Ź', 'Ż', 'Ž',
            'ţ', 'ť',
            'ù', 'ú', 'û', 'ų', 'ü', 'ů', 'ű',
            'ý', 'ÿ',
            'ź', 'ż', 'ž',
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р',
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'р',
            'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я',
            'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я'
        );

        $replace = array(
            'A', 'A', 'A', 'A', 'A', 'A', 'AE', 'A', 'A',
            'C', 'C', 'C', 'CE',
            'D', 'D',
            'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'a', 'a',
            'c', 'c', 'c', 'ce',
            'd', 'd',
            'E', 'E', 'E', 'E', 'E', 'E',
            'G',
            'I', 'I', 'I', 'I', 'I',
            'L', 'L', 'L',
            'e', 'e', 'e', 'e', 'e', 'e',
            'g',
            'i', 'i', 'i', 'i', 'i',
            'l', 'l', 'l',
            'N', 'N', 'N',
            'O', 'O', 'O', 'O', 'O', 'O', 'O',
            'R', 'R',
            'S', 'S', 'S',
            'n', 'n', 'n',
            'o', 'o', 'o', 'o', 'o', 'o',
            'r', 'r',
            's', 's', 's',
            'T', 'T',
            'U', 'U', 'U', 'U', 'U', 'U', 'U',
            'Y', 'Y',
            'Z', 'Z', 'Z',
            't', 't',
            'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y',
            'z', 'z', 'z',
            'A', 'B', 'B', 'r', 'A', 'E', 'E', 'X', '3', 'N', 'N', 'K', 'N', 'M', 'H', 'O', 'N', 'P',
            'a', 'b', 'b', 'r', 'a', 'e', 'e', 'x', '3', 'n', 'n', 'k', 'n', 'm', 'h', 'o', 'p',
            'C', 'T', 'Y', 'O', 'X', 'U', 'u', 'W', 'W', 'b', 'b', 'b', 'E', 'O', 'R',
            'c', 't', 'y', 'o', 'x', 'u', 'u', 'w', 'w', 'b', 'b', 'b', 'e', 'o', 'r'
        );

        return str_replace($accented, $replace, $string);
    }
}
?>