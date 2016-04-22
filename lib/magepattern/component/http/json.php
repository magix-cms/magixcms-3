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
 * Date: 5/08/12
 * Time: 00:53
 *
 */
class http_json{
    /**
     * @throws Exception
     */
    private function stack_error(){
        if (version_compare(phpversion(),'5.3','>')) {
            switch (json_last_error()) {
                case JSON_ERROR_NONE:
                    //$error =  'No errors';
                    $error = '';
                    break;
                case JSON_ERROR_DEPTH:
                    $error =  'Maximum stack depth exceeded';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $error =  'Underflow or the modes mismatch';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $error =  'Unexpected control character found';
                    break;
                case JSON_ERROR_SYNTAX:
                    $error =  'Syntax error, malformed JSON';
                    break;
                case JSON_ERROR_UTF8:
                    $error =  'Malformed UTF-8 characters, possibly incorrectly encoded';
                    break;
                default:
                    $error = '';
                    break;
            }
            if (!empty($error)){
                throw new Exception('JSON Error: '.$error);
            }
        }
    }

    /**
     * @param $arr
     * @param null $new_arr
     * @return array
     * @example :
     *
     $result = $json->arrayJsonReplace(
        array('mykey'=>'ma clé','supertruc'=>'super truc'),
        array('mykey'=>'ma nouvelle clé','supertruc'=>'mon nouveau super truc')
     );
     */
    public function arrayJsonReplace($arr,$new_arr=NULL){
        $collection = new collections_ArrayTools();
        $arrayjson = $collection->replaceArray($arr,$new_arr);
        /*$func = function($key,$value)
        {
            // retourne lorsque l'entrée est paire
            //return('{'.http_json::json_encode($var).'}');
            return "$key: $value";
        };
        //array_map($func,$arrayjson,$arrayjson);*/
        $result[] = json_encode($arrayjson);
        return $result;
    }
    /**
     * @param $json_tabs
     * @param array $glue
     * @return string
     * @example :
        print $json->encode(
            $result,array('','')
        );
     */
    public function encode($json_tabs,$glue=array('[',']')){
        if(is_array($json_tabs)){
            self::stack_error();
            return $glue[0].implode(',',$json_tabs).$glue[1];
        }
    }
}
?>