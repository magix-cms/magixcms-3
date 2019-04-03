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
// import the Intervention Image Manager Class
use Intervention\Image\ImageManager;

class component_files_upload{

    protected $config,$imageManager;
    public $img,$file,$img_multiple;

    public function __construct()
    {
        $formClean = new form_inputEscape();
        $this->config = new component_collections_config();
        $this->imageManager = new ImageManager(array('driver' => 'gd'));
        if(isset($_FILES['img_multiple']["name"])){
            $this->img_multiple = $_FILES['img_multiple']["name"];
        }
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
     * @param $data
     * @return array
     */
    public function mimeContentType($data){
        $mimeTypes = array(
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
            /*'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',*/
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
        if(is_array($data)){
            if(isset($data['filename'])){
                $mimeContent = mime_content_type($data['filename']);
                foreach($mimeTypes as $key=>$value){
                    if($value === $mimeContent){
                        return array('type'=>$key,'mime'=>$value);
                    }
                }
            }
        }
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
     * @throws Exception
     */
    public function dirImgUpload($data){
        $makeFiles = new filesystem_makefile();
        if(is_array($data)){
            if(array_key_exists('upload_root_dir',$data)){

                if(!file_exists($this->imgBasePath($data['upload_root_dir'].DIRECTORY_SEPARATOR))){
                    $makeFiles->mkdir($this->imgBasePath($data['upload_root_dir'].DIRECTORY_SEPARATOR));
                }
                if(array_key_exists('imgBasePath',$data)){
                    if($data['imgBasePath']){
                        $url = $this->imgBasePath($data['upload_root_dir'].DIRECTORY_SEPARATOR);
                    }else{
                        $url = $data['upload_root_dir'].DIRECTORY_SEPARATOR;
                    }
                }
                return $url;
            }
        }
    }

    /**
     * Return path collection for upload
     * @param $data
     * @param bool $debug
     * @return array
     * @throws Exception
     */
    public function dirImgUploadCollection($data,$debug = false){
        $makeFiles = new filesystem_makefile();
        if($debug){
            print_r($data);
        }
        if(is_array($data)){
            if(array_key_exists('upload_root_dir',$data)){
                if(array_key_exists('upload_dir',$data)){
                    if(is_array($data['upload_dir'])){
                        foreach($data['upload_dir'] as $key => $value){
                            if(!file_exists($this->imgBasePath($data['upload_root_dir'].DIRECTORY_SEPARATOR.$value))){
                                $makeFiles->mkdir($this->imgBasePath($data['upload_root_dir'].DIRECTORY_SEPARATOR.$value));
                            }
                            $url[] = $this->imgBasePath($data['upload_root_dir'].DIRECTORY_SEPARATOR.$value.DIRECTORY_SEPARATOR);
                        }
                    }else{
                        if(!file_exists($this->imgBasePath($data['upload_root_dir'].DIRECTORY_SEPARATOR.$data['upload_dir']))){
                            $makeFiles->mkdir($this->imgBasePath($data['upload_root_dir'].DIRECTORY_SEPARATOR.$data['upload_dir']));
                        }
                        $url = $this->imgBasePath($data['upload_root_dir'].DIRECTORY_SEPARATOR.$data['upload_dir'].DIRECTORY_SEPARATOR);
                    }
                }
                return $url;
            }
        }
    }

    /**
     * Renomme une image
     * @param $data
     * @param $imgCollection
     * @return bool|string
     */
	public function renameImages($data, $imgCollection)
	{
		try {
			$makeFiles = new filesystem_makefile();
			$fetchConfig = $this->config->fetchData(
				array('context'=>'all','type'=>'imgSize'),
				array('module_img'=>$data['module_img'],'attribute_img'=>$data['attribute_img'])
			);
			$img = pathinfo($data['edit']);
			$ext = $img['extension'];
			$filename = $img['filename'];
            $extwebp = 'webp';

			// Rename the image
			if (!empty($data['edit'] && !empty($data['name']))) {
				if (is_array($imgCollection)) {
					$dirImgArray = $this->dirImgUploadCollection($imgCollection);
                    // Check if the image to rename exists and if there is not already an image with this name and extension
                    $filesPath = (string) $dirImgArray;
                    if(!file_exists($filesPath.$data['name'].'.'.$ext)
                        && file_exists($filesPath.$filename.'.'.$ext)) {
                        $makeFiles->rename(
                            array(
                                'origin' => $filesPath.$filename.'.'.$ext,
                                'target' => $filesPath.$data['name'].'.'.$ext
                            )
                        );
                    }
                    else {
                        return false;
                    }

					foreach ($fetchConfig as $key => $value) {
						$filesPath = is_array($dirImgArray) ? $dirImgArray[$key] : $dirImgArray;
						$prefix = (array_key_exists('prefix', $data) && is_array($data['prefix'])) ? $data['prefix'][$key] : '';
                        $suffix = (array_key_exists('suffix', $data) && is_array($data['suffix'])) ? $data['suffix'][$key] : '';

						// Check if the original image still exists:
						// - if it is, the renaming has failed, the process should not continue
						// - if not, the image has been correctly renamed or the image has never exist, the process can continue
						if(!file_exists($filesPath.$data['edit'])) {
							// Check if the image to rename exists and if there is not already an image with this name and extension
							if (file_exists($filesPath.$prefix.$filename.$suffix.'.'.$ext)
								&& !file_exists($filesPath.$prefix.$data['name'].$suffix.'.'.$ext)) {

							    $makeFiles->rename(
									array(
										'origin' => $filesPath.$prefix.$filename.$suffix.'.'.$ext,
										'target' => $filesPath.$prefix.$data['name'].$suffix.'.'.$ext
									)
								);

                                // Check if webp is defined
                                if(!isset($data['webp']) || $data['webp'] != false){
                                    // Check if the image to rename exists and if there is not already an image with this name and webp extension
                                    if(file_exists($filesPath.$prefix.$filename.$suffix.'.'.$extwebp)
                                        && !file_exists($filesPath.$prefix.$data['name'].$suffix.'.'.$extwebp)){
                                        $makeFiles->rename(
                                            array(
                                                'origin' => $filesPath.$prefix.$filename.$suffix.'.'.$extwebp,
                                                'target' => $filesPath.$prefix.$data['name'].$suffix.'.'.$extwebp
                                            )
                                        );
                                    }
                                }
							}
						}
						else {
							return false;
						}
					}
					return $data['name'].'.'.$ext;
				}
			}
		}
		catch (Exception $e){
			$logger = new debug_logger(MP_LOG_DIR);
			$logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
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
     * @deprecated
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
     * convert the $_FILES array to the cleaner (IMHO) array
     * @param $file_post
     * @return array
     */
    private function reArrayFiles(&$file_post) {

        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }

        return $file_ary;
    }

    /**
     * Upload multiple image
     * @param $imgCollection
     * @param $path
     * @param bool $data
     * @param bool $debug
     * @return array
     * @throws Exception
     */
    public function multiUploadImg($imgCollection,$path,$data=false,$debug=false){
        $msg = null;
        $response = null;
        //print_r($imgCollection);
        $setUpload = $this->reArrayFiles($_FILES[$imgCollection]);
        if (isset($setUpload)) {
            if(is_array($setUpload)) {
                foreach ($setUpload as $item) {
                    if ($setUpload['error'][$item] == UPLOAD_ERR_OK) {
                        //print_r($item);
                        if($this->imageValid($item['tmp_name']) === false){
                            $msg .= 'Bad file format (only gif,png,jpeg)';
                        }else{
                            //print 'File Name: ' . $item['name'];
                            $tmpImg = $item["tmp_name"];
                            //Détecte le type mime du fichier
                            $mimeContent = $this->mimeContentType(array('filename'=>$tmpImg));
                            if(is_uploaded_file($item["tmp_name"])){
                                $source = $tmpImg;
                                $target = component_core_system::basePath().$path.http_url::clean($item["name"]);
                                if ($this->imgSizeMax($source,3000,3000) === false) {
                                    $msg .= 'the maximum size is 2500';
                                }elseif ($this->imgSizeMin($source,5,5) === false) {
                                    $msg .= 'the minimum size is 5';
                                }else{
                                    if (!move_uploaded_file($source, $target)) {
                                        $msg .= 'Temporary File Error';
                                    }
                                }

                                $prefix = '';
                                $name = filter_rsa::randMicroUI();
                                if(is_array($data)){
                                	if(isset($data['prefix_name'])) {
                                		if(isset($data['prefix_increment']) && $data['prefix_increment']) {
                                			$data['prefix_name']++;
											$prefix = $data['prefix_name'].'_';
										}
                                		else {
											$prefix = $data['prefix_name'];
										}
									}

                                	if(isset($data['name'])) {
										$name = $data['name'];
									}
								}
								$name = $prefix.$name;

                                if($debug){
                                    $result[] = array(
                                        'statut'=> true,
                                        'notify'=> 'upload',
                                        'name'=> $item["name"],
                                        'tmp_name'=> $item["tmp_name"],
                                        'new_name'=> $name,
                                        'mimecontent'=>
                                            array(
                                                'type'=> $mimeContent['type'],
                                                'mime'=> $mimeContent['mime']
                                            )
                                    );
                                }else{
                                    $result[] = array(
                                        'statut'=> true,
                                        'notify'=> 'upload',
                                        'name'=> $item["name"],
                                        'tmp_name'=> $item["tmp_name"],
                                        'new_name'=> $name,
                                        'mimecontent'=>
                                            array(
                                                'type'=> $mimeContent['type'],
                                                'mime'=> $mimeContent['mime']
                                            )
                                    );
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
                        $msg .= 'the maximum size is 3000 x 3000';
                    }
                }
               return $result;
            }
        }elseif (UPLOAD_ERR_NO_FILE == true){
            $msg .= 'No file';
        }else{
            $msg .= 'Disk write error';
        }
    }

    /**
     * Upload unique image
     * @param files $img
     * @param dir $path
     * @param bool $debug
     * @return array
     * @throws Exception
     */
    public function uploadImg($img,$path,$debug=false){
        $msg = null;
        $response = null;
        if (isset($_FILES[$img])) {
            if ($_FILES[$img]['error'] == UPLOAD_ERR_OK){
                if($this->imageValid($_FILES[$img]['tmp_name']) === false){
                    $msg .= 'Bad file format (only gif,png,jpeg)';
                }else{
                    $tmpImg = $_FILES[$img]["tmp_name"];
                    //Détecte le type mime du fichier
                    $mimeContent = $this->mimeContentType(array('filename'=>$tmpImg));
                    if(is_uploaded_file($_FILES[$img]["tmp_name"])){
                        $source = $tmpImg;
                        $target = component_core_system::basePath().$path.http_url::clean($_FILES[$img]["name"]);
                        if ($this->imgSizeMax($source,3000,3000) === false) {
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
                        if($debug){
                            $result = array('title'=>'Upload result','statut'=>true,'notify'=>'upload','msg'=>'Upload success','debug'=>'Source: '.$response['source'].' Target: '.$response['target'],'mimecontent'=>array('type'=>$mimeContent['type'],'mime'=>$mimeContent['mime']));
                        }else{
                            $result = array('title'=>'Upload result','statut'=>true,'notify'=>'upload','msg'=>'Upload success','mimecontent'=>array('type'=>$mimeContent['type'],'mime'=>$mimeContent['mime']));
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
                $msg .= 'the maximum size is 3000 x 3000';
            }
        }elseif (UPLOAD_ERR_NO_FILE == true){
            $msg .= 'No file';
        }else{
            $msg .= 'Disk write error';
        }
        if($msg != null){
            $result = array('title'=>'Upload result','statut'=>false,'notify'=>'upload_error','msg'=>$msg);
        }
        return $result;
    }


    /**
     * $this->id = 1;
     * $resultUpload = $this->upload->setUploadImage(
        'img',
            array(
                'name'              => filter_rsa::randMicroUI(),
                'edit'              => $data['img'],
                'prefix'            => array('l_','m_','s_'),
                'suffix'            => array('@500','@480','@300'),
                'module_img'        => 'pages',
                'attribute_img'     => 'page',
                'original_remove'   => false,
                'webp'              => true
            ),
            array(
                'upload_root_dir'   => 'upload/test', //string
                'upload_dir'        => array($this->id) //string ou array
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
    public function setImageUpload($img,$data,$imgCollection,$debug=false){
        if(isset($this->$img)){
            try{
                // Charge la classe pour le traitement du fichier
                $makeFiles = new filesystem_makefile();
                $resultUpload = null;
                $debugResult = null;
                $extwebp = 'webp';
                $dirImg = $this->dirImgUpload(
                    array_merge(
                        array('upload_root_dir'=>$imgCollection['upload_root_dir']),
                        array('imgBasePath'=>true)
                    )
                );
				$fetchConfig = $this->config->fetchData(
				    array(
				        'context'=>'all',
                        'type'=>'imgSize'
                    ),
                    array(
                        'module_img'=>$data['module_img'],
                        'attribute_img'=>$data['attribute_img']
                    )
                );
                //print_r($fetchConfig);
                if(!empty($this->$img)){
                    /**
                     * Envoi une image dans le dossier "racine" du module
                     */
                    $resultUpload = $this->uploadImg(
                        $img,
                        $this->dirImgUpload(
                            array_merge(
                                array('upload_root_dir'=>$imgCollection['upload_root_dir']),
                                array('imgBasePath'=>false)
                            )
                        ),
                        $debug
                    );

                    if($resultUpload['statut'] != null) {
                        /**
                         * Analyze l'extension du fichier en traitement
                         * @var $fileextends
                         */

                        //$fileExtends = $this->imageAnalyze($dirImg . $this->$img);
                        if ($this->imgSizeMin($dirImg . $this->$img, 25, 25)) {
                            /*if(file_exists($dirImg.$data['name'].$fileExtends)){
                                foreach($data['prefix'] as $key => $value){
                                    $makeFiles->remove(array($dirImg.$data['prefix'][$key].$data['edit']));
                                }
                            }*/


							// Supprime l'ancienne image
							if (!empty($data['edit'])) {
								if (is_array($imgCollection)) {
									$dirImgArray = $this->dirImgUploadCollection($imgCollection);
									//print_r($dirImgArray);
                                    $imgData = pathinfo($dirImgArray.$data['edit']);
                                    $filename = $imgData['filename'];
                                    $mimeContent = $this->mimeContentType(array('filename'=>$dirImgArray.$data['edit']));
                                    //print $filename.'.'.$mimeContent['type'];
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

                                        if (array_key_exists('suffix', $data)) {
                                            if (is_array($data['suffix'])) {
                                                $suffix = $data['suffix'][$key];
                                            } else {
                                                $suffix = '';
                                            }
                                        } else {
                                            $suffix = '';
                                        }

										/*if (file_exists($filesPath . $prefix . $data['edit'])) {
											$makeFiles->remove(array($filesPath . $prefix . $data['edit']));
										}
										if (file_exists($filesPath . $data['edit'])) {
											$makeFiles->remove(array($filesPath . $data['edit']));
										}*/

                                        if (file_exists($filesPath . $prefix . $filename. $suffix.'.'.$mimeContent['type'])) {
                                            $makeFiles->remove(array($filesPath . $prefix . $filename. $suffix.'.'.$mimeContent['type']));
                                        }
                                        if (file_exists($filesPath . $filename.'.'.$mimeContent['type'])) {
                                            $makeFiles->remove(array($filesPath . $filename.'.'.$mimeContent['type']));
                                        }
									}
								}
							}

                            // Renomme le fichier
                            $makeFiles->rename(
                                array(
                                    'origin' => $dirImg . $this->$img,
                                    'target' => $dirImg . $data['name'] . '.'.$resultUpload['mimecontent']['type']
                                )
                            );
                            //print $dirImg.$data['name'].$fileExtends;

                            /**
                             *
                             * Charge la taille des images des sous catégories du catalogue
                             */
                            if ($fetchConfig != null) {
                                if (is_array($imgCollection)) {
                                    // return array collection
                                    $dirImgArray = $this->dirImgUploadCollection($imgCollection,$debug);
                                    foreach ($fetchConfig as $key => $value) {
                                        if (is_array($dirImgArray)) {
                                            $filesPath = $dirImgArray[$key];
                                        } else {
                                            $filesPath = $dirImgArray;
                                        }
                                        /**
                                         * Initialisation de la classe interventionImage
                                         * @var void
                                         */
                                        try {
                                            $thumb = $this->imageManager->make($dirImg . $data['name'] . '.'.$resultUpload['mimecontent']['type']);
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

                                        if (array_key_exists('suffix', $data)) {
                                            if (is_array($data['suffix'])) {
                                                $suffix = $data['suffix'][$key];
                                            } else {
                                                $suffix = '';
                                            }
                                        } else {
                                            $suffix = '';
                                        }

                                        switch ($value['resize_img']) {
                                            case 'basic':
                                                $thumb->resize($value['width_img'], $value['height_img'], function ($constraint) {
                                                    $constraint->aspectRatio();
                                                    $constraint->upsize();
                                                });
                                                $thumb->save($filesPath . $prefix . $data['name']. $suffix . '.'.$resultUpload['mimecontent']['type'],80);
                                                if (  function_exists('imagewebp')) {
                                                    // Check if webp is defined
                                                    if (!isset($data['webp']) || $data['webp'] != false) {
                                                        $thumb->save($filesPath . $prefix . $data['name'] . $suffix. '.' . $extwebp);
                                                    }
                                                }
                                                break;
                                            case 'adaptive':
                                                //$thumb->adaptiveResize($value['width_img'], $value['height_img']);
                                                $thumb->fit($value['width_img'], $value['height_img']);
                                                $thumb->save($filesPath . $prefix . $data['name']. $suffix . '.'.$resultUpload['mimecontent']['type'],80);
                                                if (  function_exists('imagewebp')) {
                                                    // Check if webp is defined
                                                    if (!isset($data['webp']) || $data['webp'] != false) {
                                                        $thumb->save($filesPath . $prefix . $data['name'] . $suffix. '.' . $extwebp);
                                                    }
                                                }
                                                break;
                                        }
                                        $filesPathDebug[] = $filesPath . $prefix . $data['name']. $suffix . '.'.$resultUpload['mimecontent']['type'];
                                    }

                                    $makeFiles->rename(
                                        array(
                                            'origin' => $dirImg . $data['name'] . '.'.$resultUpload['mimecontent']['type'],
                                            'target' => $dirImgArray . $data['name'] . '.'.$resultUpload['mimecontent']['type']
                                        )
                                    );

                                }
                            }

                            if(isset($data['original_remove']) && $data['original_remove']){
                                if (is_array($imgCollection)) {
                                    $dirImgArray = $this->dirImgUploadCollection($imgCollection);
                                    //Supprime le fichier local
                                    if (file_exists($dirImgArray . $data['name'] . '.' . $resultUpload['mimecontent']['type'])) {
                                        $makeFiles->remove(array($dirImgArray . $data['name'] . '.' . $resultUpload['mimecontent']['type']));
                                    } else {
                                        throw new Exception('file: ' . $this->$img . ' is not found');
                                    }
                                }
                            }

                            if ($debug) {
                                $debugResult = '<pre>';
                                $debugResult .= print_r($fetchConfig,true);
                                $debugResult .= print_r($filesPathDebug,true);
                                $debugResult .= print_r($resultUpload['mimecontent'],true);
                                $debugResult .=  '</pre>';
                            }
                            return array('file' => $data['name'] . '.'.$resultUpload['mimecontent']['type'], 'statut' => $resultUpload['statut'], 'notify' => $resultUpload['notify'], 'msg' => $resultUpload['msg'],'debug'=>$debugResult);


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

    /**
     *
     *
     $this->id = 16;
     $resultUpload = $this->upload->setUploadMultipleImage(
        'img_multiple',
        array(
            'prefix_name'       => '',
            'prefix'            => array('s_'),
            'module_img'        => 'pages',
            'attribute_img'     => 'page',
            'original_remove'   => false,
            'webp'              => true
        ),
        array(
            'upload_root_dir'   => 'upload/pages', //string
            'upload_dir'        => $this->id //string ou array
        ),
        false
    );
     * @param $img_multiple
     * @param $data
     * @param $imgCollection
     * @param bool $debug
     * @return array
     */
    public function setMultipleImageUpload($img_multiple,$data,$imgCollection,$debug=false){
        if(isset($this->$img_multiple)){
            try{
                // Charge la classe pour le traitement du fichier
                $makeFiles = new filesystem_makefile();
                $resultUpload = null;
                $debugResult = null;
                $extwebp = 'webp';
                $dirImg = $this->dirImgUpload(
                    array_merge(
                        array(
                            'upload_root_dir'=>$imgCollection['upload_root_dir']
                        ),
                        array(
                            'imgBasePath'=>true
                        )
                    )
                );
                $fetchConfig = $this->config->fetchData(array('context'=>'all','type'=>'imgSize'),array('module_img'=>$data['module_img'],'attribute_img'=>$data['attribute_img']));

                if(!empty($this->$img_multiple)){
                    /**
                     * Envoi une image dans le dossier "racine" catalogimg
                     */
                    $resultUpload = $this->multiUploadImg(
                        $img_multiple,
                        $this->dirImgUpload(
                            array_merge(
                                array('upload_root_dir'=>$imgCollection['upload_root_dir']),
                                array('imgBasePath'=>false)
                            )
                        ),
                        $data,
                        $debug
                    );

                    foreach($resultUpload as $key => $value){
                        if($value['statut'] != 0){

                            if ($this->imgSizeMin($dirImg . $value['name'], 25, 25)) {
                                //print $dirImg . $value['name'].'<br />';
                                // Renomme le fichier
                                $makeFiles->rename(
                                    array(
                                        'origin' => $dirImg . $value['name'],
                                        'target' => $dirImg . $value['new_name'] . '.'.$value['mimecontent']['type']
                                    )
                                );
                                if ($fetchConfig != null) {
                                    if (is_array($imgCollection)) {
                                        // return array collection
                                        $dirImgArray = $this->dirImgUploadCollection($imgCollection,$debug);

                                        foreach ($fetchConfig as $keyConf => $valueConf) {
                                            if (is_array($dirImgArray)) {
                                                $filesPath = $dirImgArray[$keyConf];
                                            } else {
                                                $filesPath = $dirImgArray;
                                            }
                                            /**
                                             * init interventionImage
                                             */
                                            try {
                                                $thumb = $this->imageManager->make($dirImg . $value['new_name'] . '.'.$value['mimecontent']['type']);
                                            } catch (Exception $e) {
                                                $logger = new debug_logger(MP_LOG_DIR);
                                                $logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
                                            }

                                            if (array_key_exists('prefix', $data)) {
                                                if (is_array($data['prefix'])) {
                                                    $prefix = $data['prefix'][$keyConf];
                                                } else {
                                                    $prefix = '';
                                                }
                                            } else {
                                                $prefix = '';
                                            }

                                            switch ($valueConf['resize_img']) {
                                                case 'basic':
                                                    $thumb->resize($valueConf['width_img'], $valueConf['height_img'], function ($constraint) {
                                                        $constraint->aspectRatio();
                                                        $constraint->upsize();
                                                    });
                                                    $thumb->save($filesPath . $prefix . $value['new_name'] . '.'.$value['mimecontent']['type'],80);
                                                    if (  function_exists('imagewebp')) {
                                                        // Check if webp is defined
                                                        if (!isset($data['webp']) || $data['webp'] != false) {
                                                            $thumb->save($filesPath . $prefix . $value['new_name'] . '.' . $extwebp);
                                                        }
                                                    }
                                                    break;
                                                case 'adaptive':
                                                    $thumb->fit($valueConf['width_img'], $valueConf['height_img']);
                                                    $thumb->save($filesPath . $prefix . $value['new_name'] . '.'.$value['mimecontent']['type'],80);
                                                    if (  function_exists('imagewebp')) {
                                                        // Check if webp is defined
                                                        if (!isset($data['webp']) || $data['webp'] != false) {
                                                            $thumb->save($filesPath . $prefix . $value['new_name'] . '.' . $extwebp);
                                                        }
                                                    }
                                                    break;
                                            }
                                            $filesPathDebug[] = $filesPath . $prefix . $value['new_name'] . '.'.$value['mimecontent']['type'];
                                        }

                                        $makeFiles->rename(
                                            array(
                                                'origin' => $dirImg . $value['new_name'] . '.'.$value['mimecontent']['type'],
                                                'target' => $dirImgArray . $value['new_name'] . '.'.$value['mimecontent']['type']
                                            )
                                        );
                                    }
                                }

                                if(isset($data['original_remove']) && $data['original_remove']){
                                    if (is_array($imgCollection)) {
                                        $dirImgArray = $this->dirImgUploadCollection($imgCollection,$debug);
                                        //Supprime le fichier local
                                        if (file_exists($dirImgArray . $value['new_name'] . '.' . $value['mimecontent']['type'])) {
                                            $makeFiles->remove(array($dirImgArray . $value['new_name'] . '.' . $value['mimecontent']['type']));
                                        } else {
                                            throw new Exception('file: ' . $value['new_name'] . ' is not found');
                                        }
                                    }
                                }

                                $resultData[] = array(
                                    'file'   => $value['new_name'] . '.'.$value['mimecontent']['type'],
                                    'statut' => $value['statut'],
                                    'notify' => $value['notify'],
                                    'msg'    => 'Upload success'
                                );

                            }
                        }
                    }

                    if ($debug) {
                        print '<pre>';
                        print_r($fetchConfig);
                        print '</pre>';
                        print '<pre>';
                        print_r($resultUpload);
                        print '</pre>';
                        print '<pre>';
                        print_r($resultData);
                        print '</pre>';
                    }else{
                        return $resultData;
                    }
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
     * @throws Exception
     */
    public function dirFileUpload($data){
        if(is_array($data)){
            if(array_key_exists('upload_root_dir',$data)){
                $makeFiles = new filesystem_makefile();
                if(array_key_exists('fileBasePath',$data)) {
                    if ($data['fileBasePath']) {
                        if(array_key_exists('upload_dir',$data)){
                            if(is_array('upload_dir')){
                                foreach($data['upload_dir'] as $key){
                                    if(!file_exists($data['upload_root_dir'].DIRECTORY_SEPARATOR.$key)){
                                        $makeFiles->mkdir($data['upload_root_dir'].DIRECTORY_SEPARATOR.$key);
                                    }
                                    $url[] = $this->imgBasePath($data['upload_root_dir'].DIRECTORY_SEPARATOR.$key.DIRECTORY_SEPARATOR);
                                }
                            }else{
                                if(!file_exists($data['upload_root_dir'].DIRECTORY_SEPARATOR.$data['upload_dir'])){
                                    $makeFiles->mkdir($data['upload_root_dir'].DIRECTORY_SEPARATOR.$data['upload_dir']);
                                }
                                $url = $this->imgBasePath($data['upload_root_dir'].DIRECTORY_SEPARATOR.$data['upload_dir'].DIRECTORY_SEPARATOR);
                            }
                        }
                    }else{
                        if(is_array('upload_dir')){
                            foreach($data['upload_dir'] as $key){
                                if(!file_exists($data['upload_root_dir'].DIRECTORY_SEPARATOR.$key)){
                                    $makeFiles->mkdir($data['upload_root_dir'].DIRECTORY_SEPARATOR.$key);
                                }
                                $url[] = $data['upload_root_dir'].DIRECTORY_SEPARATOR.$key.DIRECTORY_SEPARATOR;
                            }
                        }else{
                            if(!file_exists($data['upload_root_dir'].DIRECTORY_SEPARATOR.$data['upload_dir'])){
                                $makeFiles->mkdir($data['upload_root_dir'].DIRECTORY_SEPARATOR.$data['upload_dir']);
                            }
                            $url = $data['upload_root_dir'].DIRECTORY_SEPARATOR.$data['upload_dir'].DIRECTORY_SEPARATOR;
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
     * @return array
     * @throws Exception
     */
    public function uploadFiles($file,$path,$debug=false){
        $msg = null;
        $response = null;
        if (isset($_FILES[$file])) {
            if ($_FILES[$file]['error'] == UPLOAD_ERR_OK){
                $tmpImg = $_FILES[$file]["tmp_name"];
                //Détecte le type mime du fichier
                $mimeContent = $this->mimeContentType(array('filename'=>$tmpImg));
                if(is_uploaded_file($tmpImg)){
                    $source = $tmpImg;
                    $target = component_core_system::basePath().$path.http_url::clean($_FILES[$file]["name"]);
                    if (!move_uploaded_file($source, $target)) {
                        $msg .= 'Temporary File Error';
                    }else{
                        if($debug != false){
                            $response = array('source'=>$source,'target'=>$target);
                        }
                    }
                    if($debug){
                        $result = array('title'=>'Upload result','statut'=>true,'notify'=>'upload','msg'=>'Source: '.$response['source'].' Target: '.$response['target'],'mimecontent'=>array('type'=>$mimeContent['type'],'mime'=>$mimeContent['mime']));
                    }else{
                        $result = array('title'=>'Upload result','statut'=>true,'notify'=>'upload','msg'=>'Upload success','mimecontent'=>array('type'=>$mimeContent['type'],'mime'=>$mimeContent['mime']));
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
                $dirFiles = $this->dirFileUpload(array_merge(array('upload_root_dir'=>$filesCollection['upload_root_dir'],'upload_dir'=>$filesCollection['upload_dir']),array('fileBasePath'=>true)));

                if(!empty($this->$file)){

                    $resultUpload = $this->uploadFiles(
                        $file,
                        $this->dirFileUpload(
                            array_merge(
                                array('upload_root_dir'=>$filesCollection['upload_root_dir'],'upload_dir'=>$filesCollection['upload_dir']),
                                array('fileBasePath'=>false)
                            )
                        ),
                        $debug
                    );

                    if($resultUpload['statut'] != null) {
                        // Renomme le fichier
                        $makeFiles->rename(
                            array(
                                'origin' => $dirFiles . $this->$file,
                                'target' => $dirFiles . $data['name'] .'.'.$resultUpload['mimecontent']['type']
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
                        if ($debug) {
                            $debugResult = '<pre>';
                            $debugResult .= print_r($resultUpload['mimecontent'],true);
                            $debugResult .=  '</pre>';
                        }
                        return array('file' => $data['name'] .'.'.$resultUpload['mimecontent']['type'],'type'=>$resultUpload['mimecontent']['type'], 'mime'=>$resultUpload['mimecontent']['mime'], 'statut' => $resultUpload['statut'], 'notify' => $resultUpload['notify'], 'msg' => $resultUpload['msg'],'debug'=>$debugResult);
                    }else{
                        return array('file'=>null,'type'=>$resultUpload['mimecontent']['type'], 'mime'=>$resultUpload['mimecontent']['mime'],'statut'=>$resultUpload['statut'],'notify'=>$resultUpload['notify'],'msg'=>$resultUpload['msg']);
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