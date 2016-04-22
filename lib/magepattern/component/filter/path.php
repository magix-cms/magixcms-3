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
 * Date: 18/07/12
 * Time: 23:19
 *
 */
class filter_path{
    /**
     * @static
     * @param array|bool $tabsearch
     * @throws Exception
     * @internal param array $tabreplace
     * @return mixed|string
     * @example :
        filesystem_path::basePath(
            array('component','filesystem')
        );
     */
    public static function basePath($tabsearch=false){
        try{
            $default_path = array('component','filter');
            if($tabsearch != false){
                if(is_array($tabsearch)){
                    $search = array_merge($tabsearch,$default_path,array(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR));
                }else{
                    throw new Exception(__METHOD__.' params "tabsearch" is not array');
                }
            }else{
                //$search = array_merge(explode(DIRECTORY_SEPARATOR,__DIR__),array(DIRECTORY_SEPARATOR));
                $search = array_merge($default_path,array(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR));
            }
            /*if($tabreplace != false){
                if(is_array($tabreplace)){
                    $replace = array_merge($tabreplace,array('',''));

                }else{
                    throw new Exception(__METHOD__.' params "tabreplace" is not array');
                }
            }else{
                $replace = array_fill(0,count($search),'');
            }*/
            if(count($search) > 0){
                $replace = array_fill(0,count($search),'');
            }else{
                throw new Exception('Error replace : internal params is null');
            }

            $pathreplace = str_replace($search, $replace, __DIR__);

            if(strrpos($pathreplace,DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR)){
                $pathclean = substr($pathreplace, -1);
            }else{
                $pathclean = $pathreplace;
            }

            if(strrpos($pathclean,DIRECTORY_SEPARATOR, strlen($pathclean) -1)){
                $path = $pathclean;//$path = substr($pathclean,0, -1);
            }else{
                $path = $pathclean.DIRECTORY_SEPARATOR;
            }

            return $path;
        }catch(Exception $e) {
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }
}
?>