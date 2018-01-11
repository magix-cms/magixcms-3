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
 * Date: 20/07/12
 * Time: 21:04
 *
 */
class file_finder{
    /**
     * scans the directory and returns all files
     * @param string $directory
     * @param string exclude
     * @return array|null
     */
    public function scanDir($directory,$exclude=''){
        try{
            $file = null;
            $it = new DirectoryIterator($directory);
            for($it->rewind(); $it->valid(); $it->next()) {
                if(!$it->isDir() && !$it->isDot() && $it->isFile()){
                    /*if($it->getFilename() == $exclude) continue;
                    $file[] = $it->getFilename();*/
                    if(is_array($exclude)){
                        if(!in_array($it->getFilename(), $exclude)){
                            $file[] = $it->getFilename();
                        }
                    }else{
                        if($it->getFilename() == $exclude) continue;
                        $file[] = $it->getFilename();
                    }
                }
            }
            return $file;
        }catch (Exception $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('error', 'php', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_VOID);
        }
    }
    /**
     * scan folders recursive and returns all folders
     * @param string $directory
     * @param string exclude
     * @return array|string
     */
    public function scanRecursiveDir($directory,$exclude=''){
        try{
            $file = array();
            $it = new DirectoryIterator($directory);
            for($it->rewind(); $it->valid(); $it->next()) {
                if($it->isDir() && !$it->isDot()){
                    if($it->getFilename() == $exclude) continue;
                    $file[] = $it->getFilename();
                }
            }
            return $file;
        }catch (Exception $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('error', 'php', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_VOID);
        }
    }
    /**
     * scans the folder and returns all folders and files
     * @param string $directory
     * @return string
     */
    public function scanRecursiveDirectoryFile($directory){
        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory), RecursiveIteratorIterator::SELF_FIRST);
        $dir = '';
        foreach($objects as $name => $object){
            $dir[] .= $object->getFilename();
        }
        return $dir;
    }

    /**
     * @param $directory
     * @return mixed
     */
    public function dirFilterIterator($directory){
        $directories = new AppendIterator () ;
        $directories->append (new RecursiveIteratorIterator (new RecursiveDirectoryIterator ($directory)));
        //$directories->append (new RecursiveIteratorIterator (new RecursiveDirectoryIterator ('/autre_repertoire/')));
        $itFiles = new ExtensionFilterIteratorDecorator($directories);
        $itFiles->setExtension ('.phtml');
        $t = '';
        foreach ( $itFiles as $Item )  {
            //applique le traitement à $Item
            return $t[] = $Item;
        }
    }

    /**
     * return size directory in bytes
     * @param string $directory
     * @return string
     */
    public function sizeDirectory($directory){
        try{
            $foldersize = 0;
            $dir = new sizeDirectory($directory);
            foreach($dir as $size) $foldersize += $size;
            return $foldersize.' bytes';
        }catch (Exception $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('error', 'php', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_VOID);
        }
    }
    /**
     *
     * @recursively check if a value is in array
     *
     * @param string $string (needle)
     *
     * @param array $array (haystack)
     *
     * @param bool $type (optional)
     *
     * @return bool
     *
     */
    function in_array_recursive($string, $array, $type=false)
    {
        /*** an recursive iterator object ***/
        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));

        /*** traverse the $iterator object ***/
        while($it->valid())
        {
            /*** check for a match ***/
            if( $type === false )
            {
                if( $it->current() == $string )
                {
                    return true;
                }
            }
            else
            {
                if( $it->current() === $string )
                {
                    return true;
                }
            }
            $it->next();
        }
        /*** if no match is found ***/
        return false;
    }

    /**
     * filterFiles => filter files with extension
     * $t = new file_finder();
     * var_dump($t->filterFiles('mydir',array('gif','png','jpe?g')));
     * or
     * var_dump($t->filterFiles('mydir','php'));
     * @param $directory
     * @param $extension
     * @internal param $dir
     * @return string
     */
    public function filterFiles($directory,$extension){
        try {
            $filterfiles = new filterFiles($directory,$extension);
            $filter = '';
            foreach($filterfiles as $file) {
                if(($file->isDot()) || ($file->isDir())) continue;
                $filter[] .= $file;
            }
            return $filter;
        }catch (Exception $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('error', 'php', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_VOID);
        }
    }
}
?>