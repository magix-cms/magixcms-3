<?php
/*
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of MAGIX CMS.
# MAGIX CMS, The content management system optimized for users
# Copyright (C) 2008 - 2013 magix-cms.com <support@magix-cms.com>
#
# OFFICIAL TEAM :
#
#   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
#
# Redistributions of files must retain the above copyright notice.
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
# -- END LICENSE BLOCK -----------------------------------

# DISCLAIMER

# Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
# versions in the future. If you wish to customize MAGIX CMS for your
# needs please refer to http://www.magix-cms.com for more information.
*/
/**
 * Author: Gerits Aurelien <aurelien[at]magix-cms[point]com>
 * Copyright: MAGIX CMS
 * Date: 13/12/13
 * Time: 18:17
 * License: Dual licensed under the MIT or GPL Version
 */
class component_files_upload{
    /**
     * Vérifie si le type est bien une image
     * @param $filename
     * @param bool $debug
     * @return size
     */
    public static function imageValid($filename,$debug=false){
        try{
            $firebug = new debug_firephp();
            if (!function_exists('exif_imagetype')){
                $size = getimagesize($filename);
                switch ($size['mime']) {
                    case "image/gif":
                        break;
                    case "image/jpeg":
                        break;
                    case "image/png":
                        break;
                    case false:
                        break;
                }
                if($debug!=false){
                    $firebug->log('exif_imagetype not exist');
                }
            }else{
                $size = exif_imagetype($filename);
                switch ($size) {
                    case IMAGETYPE_GIF:
                        break;
                    case IMAGETYPE_JPEG:
                        break;
                    case IMAGETYPE_PNG:
                        break;
                    case false:
                        break;
                }
                if($debug!=false){
                    $firebug->log('exif_imagetype exist');
                }
            }
            return $size;
        }catch(Exception $e) {
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * Retourne l'extension du fichier image
     * @param $filename
     * @return size
     */
    public static function imageAnalyze($filename){
        try{
            $size = getimagesize($filename);
            switch ($size['mime']) {
                case "image/gif":
                    $imgtype = '.gif';
                    break;
                case "image/jpeg":
                    $imgtype = '.jpg';
                    break;
                case "image/png":
                    $imgtype = '.png';
                    break;
                case false:
                    break;
            }
            return $imgtype;
        }catch(Exception $e) {
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * function fixe maxsize
     * @param $maxh hauteur maximum
     * @param $maxw largeur maximum
     * @param string $source
     * @return bool
     */
    public static function imgSizeMax($source,$maxw,$maxh){
        list($width, $height) = getimagesize($source);
        if($width>$maxw || $height>$maxh){
            return false;
        }else{
            return true;
        }
    }

    /**
     * function fixe minsize
     * @param $maxh hauteur minimum
     * @param $maxw largeur minimum
     * @param string $source
     * @return bool
     */
    public static function imgSizeMin($source,$maxw,$maxh){
        list($width, $height) = getimagesize($source);
        if($width<$maxw || $height<$maxh){
            return false;
        }else{
            return true;
        }
    }

    /**
     * Envoi une image sur le serveur avec la méthode upload
     * @param files $img
     * @param dir $path
     * @param bool $setOption
     * @param bool|\debug $debug $debug
     * @return null|string|\true
     */
    public static function uploadImg($img,$path,$setOption = false,$debug = false){
        $error = null;
        $makefile = new filesystem_makefile();
        $firebug = new debug_firephp();
        if(is_array($setOption)){
            if(array_key_exists('maxwidth',$setOption)){
                $maxwidth = $setOption['maxwidth'];
            }else{
                $maxwidth = 2500;
            }
            if(array_key_exists('maxheight',$setOption)){
                $maxheight = $setOption['maxheight'];
            }else{
                $maxheight = 2500;
            }
            if(array_key_exists('minheight',$setOption)){
                $minheight = $setOption['minheight'];
            }else{
                $minheight = 5;
            }
            if(array_key_exists('minwidth',$setOption)){
                $minwidth = $setOption['minwidth'];
            }else{
                $minwidth = 5;
            }
        }else{
            $maxwidth = 2500;
            $maxheight = 2500;
            $minheight = 5;
            $minwidth = 5;
        }
        /**
         * Envoi de l'image
         */
        if (isset($_FILES[$img])) {
            if ($_FILES[$img]['error'] == UPLOAD_ERR_OK){
                if(self::imageValid($_FILES[$img]['tmp_name']) === false){
                    $error .= 'Invalid image format (gif, png, jpeg only)';
                }else{
                    if(!is_readable($_FILES[$img]["tmp_name"])){
                        //$tmp_img = chmod($_FILES[$img]["tmp_name"],0777);
                        $tmp_img = $makefile->chmod(array($_FILES[$img]["tmp_name"]),0777);
                    }else{
                        $tmp_img = $_FILES[$img]["tmp_name"];
                    }
                    //if(chmod($_FILES[$img]["tmp_name"],0777)){
                    if(is_uploaded_file($_FILES[$img]["tmp_name"])){
                        $source = $tmp_img;
                        $cible = component_core_system::basePath().$path.'/'.http_url::clean($_FILES[$img]["name"]);
                        if (self::imgSizeMax($source,$maxwidth,$maxheight) == false) {
                            $error .= 'Exceeds the maximum size '.$maxwidth.' x '.$maxheight;
                        }elseif (self::imgSizeMin($source,$minwidth,$minheight) == false) {
                            $error .= 'The file is too small: '.$minwidth.' x '.$minheight;
                        }else{
                            if (!move_uploaded_file($source, $cible)) {
                                $error .= 'Error in temporary file';
                            }else{
                                if($debug != false){
                                    $firebug->group('Upload image infos');
                                    $firebug->log('Success','Status');
                                    $firebug->log($source,'Source');
                                    $firebug->log($cible,'Cible');
                                    $firebug->groupEnd();
                                }
                            }
                        }
                    }else{
                        $error .= 'Disk write error';
                    }
                    //}
                }
            }elseif (UPLOAD_ERR_INI_SIZE == true){
                $error .=  'The file is too large';
            }elseif (UPLOAD_ERR_CANT_WRITE == true){
                $error .= 'Disk write error';
            }elseif (UPLOAD_ERR_FORM_SIZE == true){
                $error .= 'The file is too large: maximum size '.$maxwidth.' x '.$maxheight;
            }
        }elseif (UPLOAD_ERR_NO_FILE == true){
            $error .= 'No file';
        }else{
            $error .= 'Disk write error';
        }
        if($error != null){
            $n = $firebug->group('Upload image analyse');
            $n .= $firebug->log($error);
            $n .= $firebug->groupEnd();
        }else{
            $n = NULL;
        }
        return $n;
    }
}
?>