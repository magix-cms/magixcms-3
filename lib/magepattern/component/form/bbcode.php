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

class form_bbcode{
    /**
     * @static
     * @var $default_color
     */
    private static $default_color = array(
        'red','green','blue','yellow','purple','olive'
    );
    /**
     * @static
     * @var $defaut_option
     */
    private static $defaut_option = array(
        'linebreaks'=>true,
        'clear'=>false,
        'new_color'=>null
    );
    /**
     * Retourne les couleurs à remplacer
     * @param array $new_color
     * @throws Exception
     * @return string
     */
    private function colorString(array $new_color=null){
        if($new_color != null){
            if(is_array($new_color)){
                $tabs = array_merge((array) self::$default_color,(array) $new_color);
            }else{
                throw new Exception('new_color is not array');
            }
        }else{
            $tabs = self::$default_color;
        }
        return implode('|', $tabs);
    }
    /**
     * Les regex pour le remplacement des bbcode
     * @param $new_color
     */
    private function regPattern($new_color=null){
        return array(
            '/\[b](.+?)\[\/b\]/i','/\[i](.+?)\[\/i\]/i',
            '/\[u](.+?)\[\/u\]/i',
            '/\[color=('.self::colorString($new_color).'|#[[:xdigit:]]{6})\](.+?)\[\/color\]/i',
            '/\[quote\](.+?)\[\/quote\]/i',
            '/\[url=(.+?)\](.+?)\[\/url\]/i'
        );
    }
    /**
     * Les balises html qui remplace le bbcode pour l'affichage
     * @param bool $clear
     */
    private function strHtml($clear=false){
        if($clear == true){
            $pattern = array(
                '$1','$1','$1','$2','$1','$2'
            );
        }else{
            $pattern = array(
                '<strong>$1</strong>',
                '<span style="font-style:italic;">$1</span>',
                '<span style="text-decoration:underline;">$1</span>',
                '<span style="color:$1;">$2</span>',
                '$1',
                '$2'
            );
        }
        return $pattern;
    }
    /**
     * Fonction qui effectue la conversion du bbcode=>html
     * @param string $string
     * @param array $str_option
     * @example:
     *  magetools_model_bbcode::html_convert(
    $string,
    array(
    'linebreaks'=>true,
    'clear'=>false,
    'new_color'=>array('darkgreen')
    )
    );
     */
    public static function html_convert($string,$option=null){
        if($string != null OR ($string != '')){
            if(!is_null($option)){
                $str_option = $option;
            }else{
                $str_option = self::$defaut_option;
            }
            if(array_key_exists('linebreaks', $str_option)){
                if($str_option['linebreaks']!=false){
                    $lb = nl2br($string);
                }else{
                    $lb = $string;
                }
            }else{
                $lb = $string;
            }
            if(array_key_exists('clear', $str_option)){
                $clear = $str_option['clear'];
            }else{
                $clear = null;
            }
            if(array_key_exists('new_color', $str_option)){
                $new_color = $str_option['new_color'];
            }else{
                $new_color = null;
            }
            return preg_replace(self::regPattern($new_color), self::strHtml($clear), $lb);
        }else{
            return $string;
        }
    }
}
?>