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
 * Date: 23/09/2016
 * Time: 12:17
 * License: Dual licensed under the MIT or GPL Version
 */
class component_files_upload{

    protected $config;
    public $img,$file;

    public function __construct()
    {
        $formClean = new form_inputEscape();
        $this->config = new component_collections_config();
        if(isset($_FILES['img']["name"])){
            $this->img = http_url::clean($_FILES['img']["name"]);
        }
        if(isset($_FILES['file']["name"])){
            $this->file = http_url::clean($_FILES['file']["name"]);
        }
    }
    /**
     * si fileInfo n'est pas disponible c'est mime_content_type qui
     * prend le relay pour analyser le type de fichier
     * @param $filename
     * @return mixed
     */
    private function mimeContent($filename) {
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );
        $parts = explode('.',$filename);
        $extension = array_pop($parts);
        if (array_key_exists($extension, $mime_types)) {
            $mime = $mime_types[$extension];
        }else {
            $mime = 'application/pdf';
        }
        return $mime;
    }
    /**
     * Retourne le chemin depuis la racine
     * @param $pathUpload
     * @return string
     */
    public function imgBasePath($pathUpload){
        return component_core_system::basePath().$pathUpload;
    }

    /**
     * Return path string for upload
     * @param $data
     * @return string
     */
    public function dirImgUpload($data){
        if(is_array($data)){
            if(array_key_exists('type',$data)){
                switch($data['type']){
                    case 'defunct':
                        $type = 'defunct';
                        break;
                }
                if(array_key_exists('imgBasePath',$data)){
                    if($data['imgBasePath']){
                        $url = $this->imgBasePath("upload".DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR);
                    }else{
                        $url = "upload".DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR;
                    }
                }
                return $url;
            }
        }
    }

    /**
     * Return path collection for upload
     * @param $data
     * @return array
     */
    public function dirImgUploadCollection($data){
        $makeFiles = new filesystem_makefile();
        if(is_array($data)){
            if(array_key_exists('type',$data)){
                switch($data['type']){
                    case 'defunct':
                        $type = 'defunct';
                        break;
                }
                if(array_key_exists('upload_dir',$data)){
                    if(is_array('upload_dir')){
                        foreach($data['upload_dir'] as $key){
                            if(!file_exists("upload".DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$key)){
                                $makeFiles->mkdir("upload".DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$key);
                            }
                            $url[] = $this->imgBasePath("upload".DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$key.DIRECTORY_SEPARATOR);
                        }
                    }else{
                        if(!file_exists("upload".DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$data['upload_dir'])){
                            $makeFiles->mkdir("upload".DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$data['upload_dir']);
                        }
                        $url = $this->imgBasePath("upload".DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$data['upload_dir'].DIRECTORY_SEPARATOR);
                    }
                }
                return $url;
            }
        }
    }
    /**
     * Vérifie si le type est bien une image
     * @param $filename
     * @param bool $debug
     * @return size
     */
    public function imageValid($filename,$debug=false){
        try{
            if (!function_exists('exif_imagetype')){
                $size = @getimagesize($filename);
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
    public function imageAnalyze($filename){
        try{
            $size = @getimagesize($filename);
            if($size){
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
                }
                return $imgtype;
            }
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
    public function imgSizeMax($source,$maxw,$maxh){
        list($width, $height) = @getimagesize($source);
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
    public function imgSizeMin($source,$maxw,$maxh){
        list($width, $height) = @getimagesize($source);
        if($width<$maxw || $height<$maxh){
            return false;
        }else{
            return true;
        }
    }
    /**
     * Upload une image
     * @param files $img
     * @param dir $path
     * @param bool $debug
     * @return null|string
     */
    public function uploadImg($img,$path,$debug=false){
        $msg = null;
        $response = null;
        if (isset($_FILES[$img])) {
            //print_r($_FILES[$img]);
            if ($_FILES[$img]['error'] == UPLOAD_ERR_OK){
                if($this->imageValid($_FILES[$img]['tmp_name']) === false){
                    $msg .= 'Bad file format (only gif,png,jpeg)';
                }else{
                    $tmp_img = $_FILES[$img]["tmp_name"];
                    if(is_uploaded_file($_FILES[$img]["tmp_name"])){
                        $source = $tmp_img;
                        $target = component_core_system::basePath().$path.http_url::clean($_FILES[$img]["name"]);
                        if ($this->imgSizeMax($source,2500,2500) === false) {
                            $msg .= 'the maximum size is 2500';
                        }elseif ($this->imgSizeMin($source,5,5) === false) {
                            $msg .= 'the minimum size is 5';
                        }else{
                            if (!move_uploaded_file($source, $target)) {
                                $msg .= 'Temporary File Error';
                            }else{
                                if($debug != false){
                                    $response = array('source'=>$source,'target'=>$target);
                                }
                            }
                        }
                    }else{
                        $msg .= 'Disk write error';
                    }
                }
            }elseif (UPLOAD_ERR_INI_SIZE == true){
                $msg .=  'The file is too large';
            }elseif (UPLOAD_ERR_CANT_WRITE == true){
                $msg .= 'Disk write error';
            }elseif (UPLOAD_ERR_FORM_SIZE == true){
                $msg .= 'the maximum size is 2500 x 2500';
            }
        }elseif (UPLOAD_ERR_NO_FILE == true){
            $msg .= 'No file';
        }else{
            $msg .= 'Disk write error';
        }
        if($msg != null){
            $result = array('title'=>'Upload result','statut'=>false,'notify'=>'upload_error','msg'=>$msg);
        }else{
            if($debug){
                $result = array('title'=>'Upload result','statut'=>true,'notify'=>'upload','msg'=>'Source: '.$response['source'].' Target: '.$response['target']);
            }else{
                $result = array('title'=>'Upload result','statut'=>true,'notify'=>'upload','msg'=>'Upload success');
            }
        }
        return $result;
    }


    /**
     * $this->id = 1;
     * $resultUpload = $this->upload->setUploadImage(
        'img',
            array(
                'name'      => filter_rsa::randMicroUI(),
                'edit'      => $data['img'],
                'prefix'=> array('l_','m_','s_'),
                'attr_name' => 'defunct'
            ),
            array(
                'type'      => 'defunct',
                'upload_dir'=> array($this->id)
            ),
        $debug
    );
     * Set Upload files
     * @param $img
     * @param $data
     * @param $imgCollection
     * @param bool $debug
     * @return array
     */
    public function setUploadImage($img,$data,$imgCollection,$debug=false){
        if(isset($this->$img)){
            try{
                // Charge la classe pour le traitement du fichier
                $makeFiles = new filesystem_makefile();
                $resultUpload = null;
                $dirImg = $this->dirImgUpload(array_merge(array('type'=>$imgCollection['type']),array('imgBasePath'=>true)));
                $fetchConfig = $this->config->fetchImg(array('context'=>'imgSize','attr_name'=>$data['attr_name']));
                if(!empty($this->$img)){
                    /**
                     * Envoi une image dans le dossier "racine" catalogimg
                     */
                    $resultUpload = $this->uploadImg(
                        $img,
                        $this->dirImgUpload(array_merge(array('type'=>$imgCollection['type']),array('imgBasePath'=>false))),
                        $debug
                    );

                    if($resultUpload['statut'] != null) {
                        /**
                         * Analyze l'extension du fichier en traitement
                         * @var $fileextends
                         */

                        $fileExtends = $this->imageAnalyze($dirImg . $this->$img);
                        if ($this->imgSizeMin($dirImg . $this->$img, 25, 25)) {
                            /*if(file_exists($dirImg.$data['name'].$fileExtends)){
                                foreach($data['prefix'] as $key => $value){
                                    $makeFiles->remove(array($dirImg.$data['prefix'][$key].$data['edit']));
                                }
                            }*/
                            // Renomme le fichier
                            $makeFiles->rename(
                                array(
                                    'origin' => $dirImg . $this->$img,
                                    'target' => $dirImg . $data['name'] . $fileExtends
                                )
                            );
                            //print $dirImg.$data['name'].$fileExtends;
                            /**
                             *
                             * Charge la taille des images des sous catégories du catalogue
                             */
                            if ($debug) {
                                print '<pre>';
                                print_r($fetchConfig);
                                print '</pre>';
                            }
                            if ($fetchConfig != null) {
                                if (is_array($imgCollection)) {
                                    $dirImgArray = $this->dirImgUploadCollection($imgCollection);
                                    foreach ($fetchConfig as $key => $value) {
                                        if (is_array($dirImgArray)) {
                                            $filesPath = $dirImgArray[$key];
                                        } else {
                                            $filesPath = $dirImgArray;
                                        }
                                        /**
                                         * Initialisation de la classe phpthumb
                                         * @var void
                                         */
                                        try {
                                            $thumb = PhpThumbFactory::create($dirImg . $data['name'] . $fileExtends,array('jpegQuality'=>70));
                                        } catch (Exception $e) {
                                            $logger = new debug_logger(MP_LOG_DIR);
                                            $logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
                                        }
                                        if (array_key_exists('prefix', $data)) {
                                            if (is_array($data['prefix'])) {
                                                $prefix = $data['prefix'][$key];
                                            } else {
                                                $prefix = '';
                                            }
                                        } else {
                                            $prefix = '';
                                        }

                                        switch ($value['img_resizing']) {
                                            case 'basic':
                                                $thumb->resize($value['width'], $value['height']);
                                                $thumb->save($filesPath . $prefix . $data['name'] . $fileExtends);
                                                break;
                                            case 'adaptive':
                                                $thumb->adaptiveResize($value['width'], $value['height']);
                                                $thumb->save($filesPath . $prefix . $data['name'] . $fileExtends);
                                                break;
                                        }
                                        $filesPathDebug[] = $filesPath . $prefix . $data['name'] . $fileExtends;
                                    }
                                    if ($debug) {
                                        print '<pre>';
                                        print_r($filesPathDebug);
                                        print '</pre>';
                                    }
                                }
                            }

                            // Supprime l'ancienne image
                            if (!empty($data['edit'])) {
                                if (is_array($imgCollection)) {
                                    $dirImgArray = $this->dirImgUploadCollection($imgCollection);
                                    foreach ($fetchConfig as $key => $value) {
                                        if (is_array($dirImgArray)) {
                                            $filesPath = $dirImgArray[$key];
                                        } else {
                                            $filesPath = $dirImgArray;
                                        }
                                        if (array_key_exists('prefix', $data)) {
                                            if (is_array($data['prefix'])) {
                                                $prefix = $data['prefix'][$key];
                                            } else {
                                                $prefix = '';
                                            }
                                        } else {
                                            $prefix = '';
                                        }
                                        if (file_exists($filesPath . $prefix . $data['edit'])) {
                                            $makeFiles->remove(array($filesPath . $prefix . $data['edit']));
                                        }
                                    }
                                }
                            }
                            //Supprime le fichier local
                            if (file_exists($dirImg . $data['name'] . $fileExtends)) {
                                $makeFiles->remove(array($dirImg . $data['name'] . $fileExtends));
                            } else {
                                throw new Exception('file: ' . $this->$img . ' is not found');
                            }

                            return array('file' => $data['name'] . $fileExtends, 'statut' => $resultUpload['statut'], 'notify' => $resultUpload['notify'], 'msg' => $resultUpload['msg']);
                        } else {
                            //Supprime le fichier local
                            if (file_exists($dirImg . $this->$img)) {
                                $makeFiles->remove(array($dirImg . $this->$img));
                            } else {
                                throw new Exception('file: ' . $this->$img . ' is not found');
                            }
                        }
                    }else{
                        return array('file'=>null,'statut'=>$resultUpload['statut'],'notify'=>$resultUpload['notify'],'msg'=>$resultUpload['msg']);
                    }
                }else{
                    if(!empty($data['edit'])){
                        if(is_array($imgCollection)) {
                            $dirImgArray = $this->dirImgUploadCollection($imgCollection);
                            foreach($fetchConfig as $key => $value) {
                                if (file_exists($dirImgArray[$key] . $data['edit'])) {
                                    $makeFiles->remove(array($dirImgArray[$key] . $data['edit']));
                                }
                            }
                        }
                    }
                    return array('file'=>null,'statut'=>$resultUpload['statut'],'notify'=>$resultUpload['notify'],'msg'=>$resultUpload['msg']);
                }
            }catch (Exception $e){
                $logger = new debug_logger(MP_LOG_DIR);
                $logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
            }
        }
    }
    ################# Upload Files ##############
    /**
     * Return path string for upload
     * @param $data
     * @return string
     */
    public function dirFileUpload($data){
        if(is_array($data)){
            if(array_key_exists('type',$data)){
                switch($data['type']){
                    case 'defunct':
                        $type = 'pdf';
                        break;
                }
                $makeFiles = new filesystem_makefile();
                if(array_key_exists('fileBasePath',$data)) {
                    if ($data['fileBasePath']) {
                        if(array_key_exists('upload_dir',$data)){
                            if(is_array('upload_dir')){
                                foreach($data['upload_dir'] as $key){
                                    if(!file_exists("upload".DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$key)){
                                        $makeFiles->mkdir("upload".DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$key);
                                    }
                                    $url[] = $this->imgBasePath("upload".DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$key.DIRECTORY_SEPARATOR);
                                }
                            }else{
                                if(!file_exists("upload".DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$data['upload_dir'])){
                                    $makeFiles->mkdir("upload".DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$data['upload_dir']);
                                }
                                $url = $this->imgBasePath("upload".DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$data['upload_dir'].DIRECTORY_SEPARATOR);
                            }
                        }
                    }else{
                        if(is_array('upload_dir')){
                            foreach($data['upload_dir'] as $key){
                                if(!file_exists("upload".DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$key)){
                                    $makeFiles->mkdir("upload".DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$key);
                                }
                                $url[] = "upload".DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$key.DIRECTORY_SEPARATOR;
                            }
                        }else{
                            if(!file_exists("upload".DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$data['upload_dir'])){
                                $makeFiles->mkdir("upload".DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$data['upload_dir']);
                            }
                            $url = "upload".DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$data['upload_dir'].DIRECTORY_SEPARATOR;
                        }
                    }
                }
                return $url;
            }
        }
    }
    /**
     * Upload un fichier
     * @param files $file
     * @param dir $path
     * @param bool $debug
     * @return null|string
     */
    public function uploadFiles($file,$path,$debug=false){
        $msg = null;
        $response = null;
        if (isset($_FILES[$file])) {
            if ($_FILES[$file]['error'] == UPLOAD_ERR_OK){
                $tmp_img = $_FILES[$file]["tmp_name"];
                if(is_uploaded_file($_FILES[$file]["tmp_name"])){
                    $source = $tmp_img;
                    $target = component_core_system::basePath().$path.http_url::clean($_FILES[$file]["name"]);
                    if (!move_uploaded_file($source, $target)) {
                        $msg .= 'Temporary File Error';
                    }else{
                        if($debug != false){
                            $response = array('source'=>$source,'target'=>$target);
                        }
                    }
                }else{
                    $msg .= 'Disk write error';
                }
            }elseif (UPLOAD_ERR_INI_SIZE == true){
                $msg .=  'The file is too large';
            }elseif (UPLOAD_ERR_CANT_WRITE == true){
                $msg .= 'Disk write error';
            }elseif (UPLOAD_ERR_FORM_SIZE == true){
                $msg .= 'the maximum size';
            }
        }elseif (UPLOAD_ERR_NO_FILE == true){
            $msg .= 'No file';
        }else{
            $msg .= 'Disk write error';
        }
        if($msg != null){
            $result = array('title'=>'Upload result','statut'=>false,'notify'=>'upload_error','msg'=>$msg);
        }else{
            if($debug){
                $result = array('title'=>'Upload result','statut'=>true,'notify'=>'upload','msg'=>'Source: '.$response['source'].' Target: '.$response['target']);
            }else{
                $result = array('title'=>'Upload result','statut'=>true,'notify'=>'upload','msg'=>'Upload success');
            }
        }
        return $result;
    }

    /**
     * @param $file
     * @param $data
     * @param $filesCollection
     * @param bool $debug
     * @return array
     */
    public function setUploadFile($file,$data,$filesCollection,$debug=false)
    {
        if (isset($this->$file)) {
            try {
                // Charge la classe pour le traitement du fichier
                $makeFiles = new filesystem_makefile();
                $resultUpload = null;
                $dirFiles = $this->dirFileUpload(array_merge(array('type'=>$filesCollection['type'],'upload_dir'=>$filesCollection['upload_dir']),array('fileBasePath'=>true)));

                if(!empty($this->$file)){
                    $resultUpload = $this->uploadFiles(
                        $file,
                        $this->dirFileUpload(array_merge(array('type'=>$filesCollection['type'],'upload_dir'=>$filesCollection['upload_dir']),array('fileBasePath'=>false))),
                        $debug
                    );
                    if($resultUpload['statut'] != null) {
                        // Renomme le fichier
                        $makeFiles->rename(
                            array(
                                'origin' => $dirFiles . $this->$file,
                                'target' => $dirFiles . $data['name']
                            )
                        );
                        // Supprime l'ancien fichier
                        if (!empty($data['edit'])) {
                            if (is_array($filesCollection)) {
                                if (file_exists($dirFiles . $data['edit'])) {
                                    $makeFiles->remove(array($dirFiles . $data['edit']));
                                }
                            }
                        }
                        return array('file' => $data['name'], 'statut' => $resultUpload['statut'], 'notify' => $resultUpload['notify'], 'msg' => $resultUpload['msg']);
                    }else{
                        return array('file'=>null,'statut'=>$resultUpload['statut'],'notify'=>$resultUpload['notify'],'msg'=>$resultUpload['msg']);
                    }
                }

            } catch (Exception $e) {
                $logger = new debug_logger(MP_LOG_DIR);
                $logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
            }
        }
    }
}
?>