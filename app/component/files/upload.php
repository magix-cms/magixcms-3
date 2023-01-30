<?php
/*
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of MAGIX CMS.
# MAGIX CMS, The content management system optimized for users
# Copyright (C) 2008 - 2023 magix-cms.com <support@magix-cms.com>
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
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
# -- END LICENSE BLOCK -----------------------------------
#
# DISCLAIMER
#
# Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
# versions in the future. If you wish to customize MAGIX CMS for your
# needs please refer to http://www.magix-cms.com for more information.
*/

// import the Intervention Image Manager Class
use Intervention\Image\ImageManager;

class component_files_upload {
	protected const WEBP_EXT = '.webp';

	/**
	 * @var component_routing_url $url
	 */
    protected component_routing_url $url;

	/**
	 * @var filesystem_makefile $makeFile
	 */
    protected filesystem_makefile $makeFile;

	/**
	 * @var ImageManager $imageManager
	 */
    protected ImageManager $imageManager;

	/**
	 * @var debug_logger $logger
	 */
    protected debug_logger $logger;

	private array $mimeTypes = [
		'txt' => 'text/plain',
		'htm' => 'text/html',
		'html' => 'text/html',
		'php' => 'text/html',
		'css' => 'text/css',
		'js' => 'application/javascript',
		'json' => 'application/json',
		'xml' => ['application/xml','text/xml'],
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
		'mp4'=>  'video/mp4',
		'mpeg'=> 'video/mpeg',

		// adobe
		'pdf' => 'application/pdf',
		'psd' => 'image/vnd.adobe.photoshop',
		'ai' => 'application/postscript',
		'eps' => 'application/postscript',
		'ps' => 'application/postscript',

		// ms office
		'doc' => 'application/msword',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'rtf' => 'application/rtf',
		'xls' => 'application/vnd.ms-excel',
		'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'ppt' => 'application/vnd.ms-powerpoint',
		'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',

		// open office
		'odt' => 'application/vnd.oasis.opendocument.text',
		'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
	];

	/**
	 * @var string
	 */
    public string
		$image,
		$file;

	/**
	 * @var array
	 */
    public array
		$images,
		$files;

    public function __construct() {
		define('DS',DIRECTORY_SEPARATOR);
        $this->url = new component_routing_url();
		$this->makeFile = new filesystem_makefile();
        $this->imageConfig = new component_files_images(null);
        $this->imageManager = new ImageManager(['driver' => 'gd']);
		if(isset($_FILES['img']["name"])) $this->image = http_url::clean($_FILES['img']["name"]);
		if(isset($_FILES['img_multiple']["name"])) $this->images = $_FILES['img_multiple']["name"];
		if(isset($_FILES['file']["name"])) $this->file = http_url::clean($_FILES['file']["name"]);
		if(isset($_FILES['files']["name"])) $this->files = $_FILES['files']["name"];
		$this->logger = new debug_logger(MP_LOG_DIR);
    }

	// ***** Global Utilities

    /**
     * si fileInfo n'est pas disponible c'est mime_content_type qui
     * prend le relay pour analyser le type de fichier
     * @param string $filename
     * @return mixed
     */
    private function mimeContent(string $filename) {
        $parts = explode('.',$filename);
        $extension = array_pop($parts);
		return array_key_exists($extension, $this->mimeTypes) ? $this->mimeTypes[$extension] : 'application/pdf';
    }

    /**
     * @param array $data
     * @return array
     */
    public function mimeContentType(array $data): array {
		$mimeContent = null;
		if(isset($data['filename'])) {
			$mimeContent = mime_content_type($data['filename']);
		}
		elseif(isset($data['mime'])) {
			$mimeContent = $data['mime'];
		}

		if($mimeContent !== null) {
			foreach($this->mimeTypes as $key => $value){
				if(is_array($value)) {
					if(in_array($mimeContent,$value)) {
						return ['type' => $key, 'mime' => $mimeContent];
					}
				}
				if($value === $mimeContent){
					return ['type' => $key, 'mime' => $mimeContent];
				}
			}
		}
		return ['type' => null, 'mime' => null];
    }

	// ***** Image Utilities

    /**
     * Check if the uploaded file type is a accepted image type
     * @param string $filename
     * @return bool
     */
    private function imageValid(string $filename): bool {
        try {
            if (!function_exists('exif_imagetype')){
                $size = @getimagesize($filename);
                switch ($size['mime']) {
                    case "image/gif":
                    case "image/jpeg":
                    case "image/png":
						return true;
                    default:
						return false;
                }
            }
			else {
                $size = exif_imagetype($filename);
                switch ($size) {
                    case IMAGETYPE_GIF:
                    case IMAGETYPE_JPEG:
                    case IMAGETYPE_PNG:
						return true;
					default:
						return false;
                }
            }
        }
		catch(Exception $e) {
            $this->logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
		return false;
    }

    /**
     * @param string $filename
     * @return string
     * @deprecated
     */
    private function imageAnalyze(string $filename): string {
		$imgType = '';
        try {
            $size = @getimagesize($filename);
            if($size){
                switch ($size['mime']) {
                    case "image/gif":
                        $imgType = '.gif';
                        break;
                    case "image/jpeg":
                        $imgType = '.jpg';
                        break;
                    case "image/png":
                        $imgType = '.png';
                        break;
                }
            }
        }
		catch(Exception $e) {
            $this->logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
		return $imgType;
    }

    /**
     * function fixe maxsize
     * @param int $maxh hauteur maximum
     * @param int $maxw largeur maximum
     * @param string $source
     * @return bool
     */
    private function imgSizeMax(string $source, int $maxw, int $maxh): bool {
        list($width, $height) = @getimagesize($source);
		return !($width>$maxw || $height>$maxh);
    }

    /**
     * function fixe minsize
     * @param int $minh hauteur minimum
     * @param int $minw largeur minimum
     * @param string $source
     * @return bool
     */
    private function imgSizeMin(string $source,int $minw,int $minh){
        list($width, $height) = @getimagesize($source);
        if($width<$minw || $height<$minh){
            return false;
        }else{
            return true;
        }
    }

	/**
	 * convert the $_FILES array to the cleaner (IMHO) array
	 * @param $postFiles
	 * @return array
	 */
	private function reArrayFiles($postFiles) {
		$files = [];
		$file_count = count($postFiles['name']);
		$keys = array_keys($postFiles);

		for ($i=0; $i<$file_count; $i++) {
			foreach ($keys as $key) {
				$files[$i][$key] = $postFiles[$key][$i];
			}
		}

		return $files;
	}

	/**
	 * @param string $module
	 * @param string $attribute
	 * @param string $edit
	 * @param string $name
	 * @param string $root
	 * @param array $directories
	 * @return bool
	 */
	public function renameImages(string $module, string $attribute, string $edit, string $name, string $root, array $directories = []): bool {
		$imageData = pathinfo($edit);
		$ext = '.'.$imageData['extension'];
		$filename = $imageData['filename'];
		$imageDirectories = $this->url->dirUploadCollection($root,$directories);
		$imageConfig = $this->imageConfig->getConfigItems($module,$attribute);

		try {
			// Rename the image
			foreach ($imageDirectories as $dirPath) {
				// Check if the image to rename exists and if there is not already an image with this name and extension
				if(!file_exists($dirPath.$name.$ext) && file_exists($dirPath.$filename.$ext)) {
					$this->makeFile->rename([
						'origin' => $dirPath.$filename.$ext,
						'target' => $dirPath.$name.$ext
					]);
				}
				else {
					return false;
				}

				foreach ($imageConfig as $value) {
					$prefix = $value['prefix'].'_';
					// Check if the original image still exists:
					// - if it is, the renaming has failed, the process should not continue
					// - if not, the image has been correctly renamed or the image has never exist, the process can continue
					if(!file_exists($dirPath.$edit)) {
						// Check if the image to rename exists and if there is not already an image with this name and extension
						if(file_exists($dirPath.$prefix.$filename.$ext) && !file_exists($dirPath.$prefix.$name.$ext)) {
							$this->makeFile->rename([
								'origin' => $dirPath.$prefix.$filename.$ext,
								'target' => $dirPath.$prefix.$name.$ext
							]);
						}

						// Check if the image to rename exists and if there is not already an image with this name and webp extension
						if(file_exists($dirPath.$prefix.$filename.self::WEBP_EXT) && !file_exists($dirPath.$prefix.$name.self::WEBP_EXT)){
							$this->makeFile->rename([
								'origin' => $dirPath.$prefix.$filename.self::WEBP_EXT,
								'target' => $dirPath.$prefix.$name.self::WEBP_EXT
							]);
						}
					}
					else {
						return false;
					}
				}
			}

			return true;
		}
		catch (Exception $e){
			$this->logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
		}
		return false;
	}

	/**
	 * @param string $path
	 * @param string $filename
	 * @param string $ext
	 * @param int $width
	 * @param int $height
	 * @param string $resize
	 * @param string $prefix
	 * @return string
	 */
	private function createFormat(string $path, string $filename, string $ext, int $width, int $height, string $resize = 'basic', string $prefix = ''): string {
		if(!empty($prefix)) $prefix = $prefix.'_';

		try {
			$thumb = $this->imageManager->make($path.$filename.$ext);

			switch ($resize) {
				case 'adaptive':
					$thumb->fit($width, $height);
					break;
				case 'basic':
				default:
					$thumb->resize($width, $height, function ($constraint) {
						$constraint->aspectRatio();
						$constraint->upsize();
					});
			}

			$thumb->save($path.$prefix.$filename.$ext, 80);
			if (function_exists('imagewebp')) $thumb->save($path.$prefix.$filename.self::WEBP_EXT, 80);
			return $path.$filename.$ext;
		}
		catch (Exception $e) {
			$this->logger->log('php', 'error', 'An error has occured : '.$e->getMessage().' '.$path, debug_logger::LOG_MONTH);
		}
		return '';
	}

	/**
	 * Change images prefix
	 * @param string $module
	 * @param string $attribute
	 * @param string $root
	 * @param array $images
	 * @param string $type
	 * @param string $oldPrefix
	 * @param string $newPrefix
	 * @param array $options
	 * @return void
	 */
	public function batchPrefixRename(string $module, string $attribute, string $root, array $images, string $type, string $oldPrefix, string $newPrefix, array $options = []) {
		$default = [
			'progress' => true,
			'template' => null
		];
		if(!empty($options)) $options = array_merge($default,$options);
		$isProgressBar = $options['progress'] instanceof component_core_feedback && $options['template'];
		$imageConfig = $this->imageConfig->getConfigItems($module,$attribute);
		$dirPath = $this->url->dirUpload($root,true);

		if($isProgressBar) {
			usleep(200000);
			$options['progress']->sendFeedback(['message' => $options['template']->getConfigVars('control_of_data'),'progress' => 10]);
			$total = count($images);
			$preparePercent = 100 / $total;
			$percent = 0;
		}

		if(!empty($images)) {
			foreach ($images as $image) {
				$imgData = pathinfo($image['img']);
				$filename = $imgData['filename'];
				$ext = '.'.$imgData['extension'];

				if($isProgressBar) {
					$percent = $percent + $preparePercent;
					usleep(200000);
					$options['progress']->sendFeedback(['message' => $options['template']->getConfigVars('creating_thumbnails'), 'progress' => $percent]);
				}

				$imgPath = $dirPath.$image['id'].DIRECTORY_SEPARATOR;

				foreach ($imageConfig as $value) {
					if($value['type'] === $type) {
						$oldPrefix = $oldPrefix.'_';
						$newPrefix = $newPrefix.'_';

						if(file_exists($imgPath)) {
							// Check if the image to rename exists and if there is not already an image with this name and extension
							if(file_exists($imgPath.$oldPrefix.$filename.$ext)
								&& !file_exists($imgPath.$newPrefix.$filename.$ext)) {
								$this->makeFile->rename([
									'origin' => $imgPath.$oldPrefix.$filename.$ext,
									'target' => $imgPath.$newPrefix.$filename.$ext
								]);
							}

							// Check if the image to rename exists and if there is not already an image with this name and webp extension
							if(file_exists($imgPath.$oldPrefix.$filename.self::WEBP_EXT)
								&& !file_exists($imgPath.$newPrefix.$filename.self::WEBP_EXT)){
								$this->makeFile->rename([
									'origin' => $imgPath.$oldPrefix.$filename.self::WEBP_EXT,
									'target' => $imgPath.$newPrefix.$filename.self::WEBP_EXT
								]);
							}
						}
					}
				}
			}
		}

		if($isProgressBar) {
			usleep(200000);
			$options['progress']->sendFeedback(['message' => $options['template']->getConfigVars('creating_thumbnails_success'),'progress' => 100,'status' => 'success']);
		}
	}

	/**
	 * @param string $module
	 * @param string $attribute
	 * @param string $root
	 * @param array $images
	 * @param string|null $type
	 * @param array $options
	 * @return void
	 */
	public function batchRegenerate(string $module, string $attribute, string $root, array $images, string $type = null, array $options = []) {
		$default = [
			'progress' => true,
			'template' => null
		];
		if(!empty($options)) $options = array_merge($default,$options);
		$isProgressBar = $options['progress'] instanceof component_core_feedback && $options['template'];
		$imageConfig = $this->imageConfig->getConfigItems($module,$attribute);
		$dirPath = $this->url->dirUpload($root,true);

		if($isProgressBar) {
			usleep(200000);
			$options['progress']->sendFeedback(['message' => $options['template']->getConfigVars('control_of_data'),'progress' => 10]);
			$total = count($images);
			$preparePercent = 100 / $total;
			$percent = 0;
		}

		if(!empty($images)) {
			foreach ($images as $image) {
				$imgData = pathinfo($image['img']);
				$filename = $imgData['filename'];
				$ext = '.'.$imgData['extension'];
				$imgPath = $dirPath.$image['id'].DIRECTORY_SEPARATOR;

				if($isProgressBar) {
					$percent = $percent + $preparePercent;
					usleep(200000);
					$options['progress']->sendFeedback(['message' => $options['template']->getConfigVars('creating_thumbnails'), 'progress' => $percent]);
				}

				foreach ($imageConfig as $value) {
					if($type === null || $type === $value['type']) $this->createFormat($imgPath,$filename,$ext,$value['width'],$value['height'],$value['resize'],$value['prefix']);
				}
			}
		}

		if($isProgressBar) {
			usleep(200000);
			$options['progress']->sendFeedback(['message' => $options['template']->getConfigVars('creating_thumbnails_success'),'progress' => 100,'status' => 'success']);
		}
	}

	// ***** Images

    /**
     * @param array $image
     * @param string $path
     * @param bool $debug
     * @return array
     */
	public function getUploadImg(array $image, string $path, bool $debug = false): array {
        $msg = '';
        $mimeContent = null;
		
		if ($image['error'] === UPLOAD_ERR_OK) {
			if($this->imageValid($image['tmp_name'])) {
				$tmpImg = $image["tmp_name"];
				$mimeContent = $this->mimeContentType(['filename' => $tmpImg]);

				if(is_uploaded_file($image["tmp_name"])) {
					$source = $tmpImg;
					$target = component_core_system::basePath().$path.http_url::clean($image["name"]);

					if(!move_uploaded_file($source, $target)) $msg .= 'Temporary File Error';
				}
				else {
					$msg .= 'Disk write error';
				}
			}
			else {
				$msg .= 'Bad file format (only gif,png,jpeg)';
			}
		}
		else {
			switch (true) {
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					$msg .= 'The file is too large';
					break;
				case UPLOAD_ERR_CANT_WRITE:
					$msg .= 'Disk write error';
					break;
				case UPLOAD_ERR_NO_FILE:
					$msg .= 'No file';
					break;
				default:
					$msg .= 'Disk write error';
			}
		}

		$result = [
			'status' => empty($msg),
			'notify' => empty($msg) ? 'upload' : 'upload_error',
			'name' => $image["name"],
			'tmp_name' => $image["tmp_name"],
			'mimecontent' => $mimeContent,
			'msg' => empty($msg) ? 'Upload success' : $msg
		];
		
        if($debug) $this->logger->tracelog(json_encode($result));
        return $result;
    }

	/**
	 * @param array $upload
	 * @param string $module
	 * @param string $attribute
	 * @param string $root
	 * @param array $directories
	 * @param array $options
	 * @param bool $debug
	 * @return array
	 */
	private function imagePostUploadProcess(array $upload, string $module, string $attribute, string $root, array $directories = [], array $options = [], bool $debug = false): array {
		if($upload['status']) {
			$debugResult = '';
			$dirImg = $this->url->dirUpload($root, true);
			$imageDirectories = $this->url->dirUploadCollection($root,$directories);
			$imageConfig = $this->imageConfig->getConfigItems($module,$attribute);

			if($debug) $this->logger->tracelog('statut : '.json_encode($upload['status']));

			if ($this->imgSizeMin($dirImg . $upload['name'], 25, 25)) {
				// Remove old image
				if (!empty($options['edit'])) {
					if(!empty($imageDirectories)) {
						foreach ($imageDirectories as $dirPath) {
							$imgData = pathinfo($dirPath.$options['edit']);
							$filename = $imgData['filename'];
							$mimeContent = $this->mimeContentType(['filename' => $dirPath.$options['edit']]);
							$files = [];
							$toRemove = [];
							$ext = '.'.$mimeContent['type'];
							foreach ($imageConfig as $key => $value) {
								if (array_key_exists('prefix', $options)) $prefix = (is_array($options['prefix']) ? $options['prefix'][$key] : $options['prefix']).'_';
								else $prefix = $value['prefix'].'_';

								$files[] = $dirPath . $prefix . $filename.$ext;
								$files[] = $dirPath . $prefix . $filename.self::WEBP_EXT;
							}
							$files[] = $dirPath . $filename.$ext;
							$files[] = $dirPath . $filename.self::WEBP_EXT;

							foreach ($files as $file) {
								if(file_exists($file)) $toRemove[] = $file;
							}
							if (!empty($toRemove)) $this->makeFile->remove($toRemove);
						}
					}
				}
				if($debug) $this->logger->tracelog('rename format');

                $fileInfo = pathinfo($upload['name']);
				$ext = '.'.$fileInfo['extension'];
				$originName = $fileInfo['filename'];
				$filename = $originName;

				// Rename file
				if(!empty($options['name'])) {
					$filename = $options['name'].(!empty($options['suffix'] && !is_array($options['suffix'])) ? '_'.$options['suffix'] : '');
					$this->makeFile->rename([
						'origin' => $dirImg.$originName.$ext,
						'target' => $dirImg.$filename.$ext
					]);
				}
				elseif(!empty($options['suffix']) && !is_array($options['suffix'])) {
					$filename = $originName.'_'.$options['suffix'];
					$this->makeFile->rename([
						'origin' => $dirImg.$originName.$ext,
						'target' => $dirImg.$filename.$ext
					]);
				}

				$source = $dirImg.$filename.$ext;

				if(!empty($imageDirectories)) {
					foreach ($imageDirectories as $dirPath) {
						if (!empty($imageConfig)) {
							foreach ($imageConfig as $key => $value) {
								try {
									$thumb = $this->imageManager->make($source);

									if (array_key_exists('prefix', $options)) $prefix = (is_array($options['prefix']) ? $options['prefix'][$key] : $options['prefix']).'_';
									else $prefix = $value['prefix'].'_';

                                    if (array_key_exists('suffix', $options) && is_array($options['suffix'])) $suffix = $options['suffix'][$key];
                                    else $suffix = '';

									switch ($value['resize']) {
										case 'adaptive':
											$thumb->fit($value['width'], $value['height']);
											break;
										case 'basic':
										default:
											$thumb->resize($value['width'], $value['height'], function ($constraint) {
												$constraint->aspectRatio();
												$constraint->upsize();
											});
									}
									$thumb->save($dirPath.$prefix.$filename.$suffix.$ext,80);
									if(function_exists('imagewebp')) $thumb->save($dirPath.$prefix.$filename.$suffix.self::WEBP_EXT, 80);

									$filesPathDebug[] = $dirPath.$prefix.$filename.$suffix.$ext;
								}
								catch (Exception $e) {
									$this->logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
								}
							}

							if($debug) $this->logger->tracelog('rename format');

							try {
								$thumb = $this->imageManager->make($source);
								$thumb->save($dirPath.$filename.$ext);
							}
							catch (Exception $e) {
								$this->logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
							}
						}
					}
					if(!empty($dirImg)) $this->makeFile->remove([$source]);
				}

				if(isset($options['original_remove']) && $options['original_remove']){
					if($debug) $this->logger->tracelog('delete original');
					foreach ($imageDirectories as $dirPath) {
						if(file_exists($dirPath.$filename.$ext)) $this->makeFile->remove(array($dirPath.$filename.$ext));
					}
				}

				if ($debug) {
					$debugResult = '<pre>';
					$debugResult .= print_r($imageConfig,true);
					$debugResult .= print_r($filesPathDebug,true);
					$debugResult .= print_r($upload['mimecontent'],true);
					$debugResult .=  '</pre>';
				}
				$resultData = [
					'file' => $filename.$ext,
					'status' => $upload['status'],
					'notify' => $upload['notify'], 
					'msg' => $upload['msg'],
					'debug' => $debugResult
				];
				if ($debug) $this->logger->tracelog(json_encode($resultData));
				return $resultData;
			}
			else {
				if (file_exists($dirImg . $upload['name'])) $this->makeFile->remove([$dirImg . $upload['name']]);
			}
		}
		return [];
	}

    /**
     * Set Upload Image
     * @param string $module
     * @param string $attribute
     * @param string $root
     * @param array $directories
     * @param array $options
     * @param bool $debug
     * @return array
     */
    public function imageUpload(string $module, string $attribute, string $root, array $directories = [], array $options = [], bool $debug = false): array {
		if(isset($options['postKey']) && !empty($options['postKey']) && isset($_FILES[$options['postKey']]["name"])) $this->image = http_url::clean($_FILES[$options['postKey']]["name"]);

		$default = [
			'postKey' => '',
			'name' => '',
			'edit' => false,
			'suffix' => null,
			'suffix_increment' => false,
			'original_remove' => false,
			'progress' => true,
			'template' => null
		];

		if(!empty($options)) $options = array_merge($default,$options);

        if(isset($this->image) && !empty($this->image)) {
            try {
                if ($debug) $this->logger->tracelog('start upload');
				
				$postKey = (isset($options['postKey']) && !empty($options['postKey']) ? $options['postKey'] : 'img');
				$resultUpload = $this->getUploadImg(
					$_FILES[$postKey],
					$this->url->dirUpload($root, false),
					$debug
				);
				if($debug) $this->logger->tracelog(json_encode($resultUpload));

				return $this->imagePostUploadProcess($resultUpload,$module,$attribute,$root,$directories,$options,$debug);
            }
			catch (Exception $e){
                $this->logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
            }
        }
		return [];
    }

    /**
     * @param string $img_multiple
     * @param array $data
     * @param array $imgCollection
     * @param bool $debug
     * @return array
     */
    public function multipleImageUpload(string $module,string $attribute, string $root, array $directories = [], array $options = [], bool $debug = false): array {
		if(isset($options['postKey']) && !empty($options['postKey']) && isset($_FILES[$options['postKey']]["name"])) $this->images = http_url::clean($_FILES[$options['postKey']]["name"]);

		$default = [
			'postKey' => '',
			'name' => '',
			'edit' => false,
			'suffix' => null,
			'suffix_increment' => false,
			'original_remove' => false,
			'progress' => true,
			'template' => null
		];

		if(!empty($options)) $options = array_merge($default,$options);

		if(isset($this->images) && !empty($this->images)) {
            try {
                if($debug) $this->logger->tracelog('start upload');
				$resultData = [];
				
				$postKey = (isset($options['postKey']) && !empty($options['postKey']) ? $options['postKey'] : 'img_multiple');
				$uploadedFiles = $this->reArrayFiles($_FILES[$postKey]);
				
				if(!empty($uploadedFiles)) {
					$isProgressBar = $options['progress'] instanceof component_core_feedback && $options['template'];
					$totalFiles = count($uploadedFiles);

					if ($isProgressBar) {
						$percent = $options['progress']->progress;
						$preparePercent = (50 - $percent) / $totalFiles;
						$i = 1;
					}

					foreach ($uploadedFiles as $file) {
						if($isProgressBar) {
							$percent = $percent + $preparePercent;
							usleep(100000);
							$options['progress']->sendFeedback(['message' => sprintf($options['template']->getConfigVars('checking_images'),$i,$totalFiles), 'progress' => $percent]);
							$i++;
						}
						$resultUpload[] = $this->getUploadImg($file, $this->url->dirUpload($root, false), $debug);
						if($debug) $this->logger->tracelog(json_encode($resultUpload));
					}

					$totalUpload = count($resultUpload);
					if ($isProgressBar) {
						$percent = $options['progress']->progress;
						$preparePercent = (80 - $percent) / $totalUpload;
						$i = 1;
					}

					foreach($resultUpload as $upload) {
						if($isProgressBar) {
							$percent = $percent + $preparePercent;
							usleep(100000);
							$options['progress']->sendFeedback(['message' => sprintf($options['template']->getConfigVars('resizing_images'),$i,$totalUpload), 'progress' => $percent]);
							$i++;
						}

						if(isset($options['suffix']) && isset($options['suffix_increment'])) $options['suffix']++;
						
						$resultData[] = $this->imagePostUploadProcess($upload,$module,$attribute,$root,$directories,$options,$debug);
					}

					if ($debug) $this->logger->tracelog(json_encode($resultData));
					return $resultData;
				}
            }
			catch (Exception $e){
                $this->logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
            }
        }
		return [];
    }

	// ***** Files
	
    /**
     * Return path string for upload
	 * @deprecated
	 * @see component_routing_url::dirUpload()
	 * @see component_routing_url::dirUploadCollection()
     * @param array $data
     * @return string|array
     */
    public function dirFileUpload(array $data) {
		if(isset($data['upload_root_dir'])) {
			$basePath = $data['fileBasePath'] ?? false;

			if(isset($data['upload_dir'])) {
				return $this->url->dirUploadCollection($data['upload_root_dir'],[$data['upload_dir']],$basePath);
			}
			else {
				return $this->url->dirUpload($data['upload_root_dir'],$basePath);
			}
		}
		return '';
        /*if(is_array($data)){
            if(array_key_exists('upload_root_dir',$data)){
                $makeFiles = new filesystem_makefile();
                if(array_key_exists('fileBasePath',$data)) {
                    if ($data['fileBasePath']) {
                        if(array_key_exists('upload_dir',$data)){
                            if(is_array('upload_dir')){
                                foreach($data['upload_dir'] as $key){
                                    if(!file_exists($data['upload_root_dir'].DS.$key)){
                                        $makeFiles->mkdir($data['upload_root_dir'].DS.$key);
                                    }
                                    $url[] = $this->imgBasePath($data['upload_root_dir'].DS.$key.DS);
                                }
                            }else{
                                if(!file_exists($data['upload_root_dir'].DS.$data['upload_dir'])){
                                    $makeFiles->mkdir($data['upload_root_dir'].DS.$data['upload_dir']);
                                }
                                $url = $this->imgBasePath($data['upload_root_dir'].DS.$data['upload_dir'].DS);
                            }
                        }
                    }else{
                        if(is_array('upload_dir')){
                            foreach($data['upload_dir'] as $key){
                                if(!file_exists($data['upload_root_dir'].DS.$key)){
                                    $makeFiles->mkdir($data['upload_root_dir'].DS.$key);
                                }
                                $url[] = $data['upload_root_dir'].DS.$key.DS;
                            }
                        }else{
                            if(!file_exists($data['upload_root_dir'].DS.$data['upload_dir'])){
                                $makeFiles->mkdir($data['upload_root_dir'].DS.$data['upload_dir']);
                            }
                            $url = $data['upload_root_dir'].DS.$data['upload_dir'].DS;
                        }
                    }
                }
                return $url;
            }
        }*/
        /*$makeFiles = new filesystem_makefile();
        if(is_array($data)){
            if(array_key_exists('upload_root_dir',$data)){
                $extendPath = '';
                if(array_key_exists('upload_dir',$data)){
                    $extendPath = $data['upload_dir'].DS;
                }
                if(!file_exists($this->imgBasePath($data['upload_root_dir'].DS.$extendPath))){
                    $makeFiles->mkdir($this->imgBasePath($data['upload_root_dir'].DS.$extendPath));
                }
                if(array_key_exists('fileBasePath',$data)){

                    if($data['fileBasePath']){
                        $url = $this->imgBasePath($data['upload_root_dir'].DS.$extendPath);
                    }else{
                        $url = $data['upload_root_dir'].DS.$extendPath;
                    }
                }

                return $url;
            }
        }*/
    }

    /**
     * Upload un fichier
     * @param string $file file
     * @param string $path directory
     * @param bool $debug
     * @return array
     * @throws Exception
     */
    public function uploadFiles($file,$path,$debug=false){
        $msg = null;
        $response = null;
        if (isset($_FILES[$file])) {
            if ($_FILES[$file]['error'] == UPLOAD_ERR_OK){
                $tmpFile = $_FILES[$file]["tmp_name"];
                //Détecte le type mime du fichier
                $mimeContent = $this->mimeContentType(array('filename'=>$tmpFile));
                if(is_uploaded_file($tmpFile)){
                    $source = $tmpFile;
                    $target = component_core_system::basePath().$path.http_url::clean($_FILES[$file]["name"]);

                    if (!move_uploaded_file($source, $target)) {
                        $msg .= 'Temporary File Error';
                    }else{
                        if($debug != false){
                            $response = array('source'=>$source,'target'=>$target);
                        }
                    }
                    /*if($debug){
                        $result = array('title'=>'Upload result','statut'=>true,'notify'=>'upload','msg'=>'Source: '.$response['source'].' Target: '.$response['target'],'mimecontent'=>array('type'=>$mimeContent['type'],'mime'=>$mimeContent['mime']));
                    }else{
                        $result = array('title'=>'Upload result','statut'=>true,'notify'=>'upload','msg'=>'Upload success','mimecontent'=>array('type'=>$mimeContent['type'],'mime'=>$mimeContent['mime']));
                    }*/
                }else{
                    $msg .= 'Disk write error';
                }
            }elseif (UPLOAD_ERR_INI_SIZE == true || UPLOAD_ERR_FORM_SIZE == true){
                $msg .=  'The file is too large';
            }elseif (UPLOAD_ERR_CANT_WRITE == true){
                $msg .= 'Disk write error';
            }
        }elseif (UPLOAD_ERR_NO_FILE == true){
            $msg .= 'No file';
        }else{
            $msg .= 'Disk write error';
        }
        /*if($msg != null){
            $result = array('title'=>'Upload result','statut'=>false,'notify'=>'upload_error','msg'=>$msg);
        }*/
        if($msg != null){
            $result = array(
                'title'=>'Upload result',
                'statut'=>false,
                'notify'=>'upload_error',
                'msg' => $msg
            );
        }else{
            $result = array(
                'title'=>'Upload result',
                'statut'=>true,
                'notify'=>'upload',
                'msg'=>'Upload success',
                'mimecontent'=>
                    array(
                        'type'=>$mimeContent['type'],
                        'mime'=>$mimeContent['mime']
                    )
            );
        }
        if($debug){
            $log = new debug_logger(MP_LOG_DIR);
            $log->tracelog(json_encode($result));
        }
        return $result;
    }

    /**
     * @param $file
     * @param $data
     * @param $filesCollection
     * @param null $accept
     * @param bool $debug
     * @return array
     * @throws Exception
     */
    public function setUploadFile($file,$data,$filesCollection,$accept=null,$debug=false)
    {
		if(isset($_FILES[$file]["name"])) $this->file = http_url::clean($_FILES[$file]["name"]);

        if (isset($this->file)) {
            try {
                if ($debug) {
                    $log = new debug_logger(MP_LOG_DIR);
                }
                // Charge la classe pour le traitement du fichier
                $makeFiles = new filesystem_makefile();
                $resultUpload = null;
                $dirconf = [
					'upload_root_dir' => $filesCollection['upload_root_dir'],
					'upload_dir' => $filesCollection['upload_dir'],
					'fileBasePath'=> true//'fileBasePath' => true
				];

                $dirFiles = $this->dirFileUpload($dirconf);//$this->dirFileUpload($dirconf);
                if ($debug) {
                    $log->tracelog(json_encode($dirFiles));
                }
                if(!empty($this->file)) {
                    if ($debug) {
                        $log->tracelog('start upload');
                    }
                    if ($debug) {
                        $log->tracelog('dir files start');
                        $log->tracelog(json_encode($this->dirFileUpload($dirconf)));
                    }
                    $filename = $this->file;
                    $dirconf['fileBasePath'] = false;
					$mimeContent = $this->mimeContentType(['mime'=>$_FILES[$file]['type']]);
                    if ($debug) {
                        $log->tracelog(json_encode($mimeContent));
                    }
					if(is_array($accept) && !empty($accept)) {
						if(!in_array($mimeContent['type'],$accept)) {
							return [
								'file' => $filename,
								'type' => $mimeContent['type'],
								'mime' => $mimeContent['mime'],
								'status' => false,
								'notify' => 'error_file_type',
								'msg' => 'File type '.$mimeContent['type'].' not supported',
								'debug' => null
							];
						}
					}
                    if ($debug) {
                        $log->tracelog('dir files');
                        $log->tracelog(json_encode($this->dirFileUpload($dirconf)));
                    }
					$resultUpload = $this->uploadFiles(
                        $file,
                        $this->dirFileUpload($dirconf),//$this->dirFileUpload($dirconf),
                        $debug
                    );
                    if ($debug) {
                        $log->tracelog('result upload');
                        $log->tracelog(json_encode($resultUpload));
                    }
                    if($resultUpload['statut'] != false) {
						if(!empty($data)) {
							// Renomme le fichier
							if (!empty($data['name'])) {
                                $data['name'] = http_url::clean($data['name']);
								$makeFiles->rename(
									array(
										'origin' => $dirFiles . $filename,
										'target' => $dirFiles . $data['name'] . '.' . $resultUpload['mimecontent']['type']
									)
								);
								$filename = $data['name'] . '.' . $resultUpload['mimecontent']['type'];
							}
							// Supprime l'ancien fichier
							if (!empty($data['edit'])) {
								if (is_array($filesCollection)) {
									if (file_exists($dirFiles . $data['edit'])) {
										$makeFiles->remove(array($dirFiles . $data['edit']));
									}
								}
							}
						}
                    }

                    $resultData = [
						'file' => $filename,
						'path' => $dirFiles,
						'type' => $resultUpload['mimecontent']['type'],
						'mime' => $resultUpload['mimecontent']['mime'],
						'status' => $resultUpload['statut'],
						'notify' => $resultUpload['notify'],
						'msg' => $resultUpload['msg']
					];
                    if ($debug) {
                        $log->tracelog(json_encode($resultData));
                        $log->tracelog('end upload');
                    }
                    return $resultData;
                }
            }
            catch (Exception $e) {
                $logger = new debug_logger(MP_LOG_DIR);
                $logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
            }
        }
    }

	// ***** Depracated

	/**
	 * Upload multiple image
	 * @param string $images
	 * @param string $path
	 * @param array $options
	 * @param bool $debug
	 * @return array
	 */
	public function multiUploadImg(string $images, string $path, array $options = [], bool $debug = false){
		$msg = null;
		$result = null;
		$name = null;
		$mimeContent = null;
		$setUpload = $this->reArrayFiles($_FILES[$images]);

		if (isset($setUpload)) {

			if ($options['progress'] instanceof component_core_feedback && $options['template']) {
				$percent = $options['progress']->progress;
				$preparePercent = (50 - $percent) / count($setUpload);
			}

			foreach ($setUpload as $item) {

				if($options['progress'] instanceof component_core_feedback && $options['template']) {
					$percent = $percent + $preparePercent;
					usleep(100000);
					$options['progress']->sendFeedback(['message' => $options['template']->getConfigVars('checking_images'), 'progress' => $percent]);
				}

				if ($setUpload['error'][$item] == UPLOAD_ERR_OK) {
					//print_r($item);
					if($this->imageValid($item['tmp_name']) === false) {
						$msg .= 'Bad file format (only gif,png,jpeg)';
					}
					else {
						$tmpImg = $item["tmp_name"];
						$mimeContent = $this->mimeContentType(array('filename'=>$tmpImg));

						if(is_uploaded_file($item["tmp_name"])) {
							$source = $tmpImg;
							$target = component_core_system::basePath().$path.http_url::clean($item["name"]);

							if (!move_uploaded_file($source, $target)) {
								$msg .= 'Temporary File Error';
							}

							$prefix = '';
							$name = filter_rsa::randMicroUI();

							if(is_array($options)){
								if(isset($options['prefix_name'])) {
									if(isset($options['prefix_increment']) && $options['prefix_increment']) {
										$options['prefix_name']++;
										$prefix = $options['prefix_name'].'_';
									}
									else {
										$prefix = $options['prefix_name'];
									}
								}

								if(isset($options['name'])) {
									$name = $options['name'];
								}
							}
							$name = $prefix.$name;

						}
						else {
							$msg .= 'Disk write error';
						}
					}
				}
				elseif (UPLOAD_ERR_INI_SIZE == true || UPLOAD_ERR_FORM_SIZE == true) {
					$msg .=  'The file is too large';
				}
				elseif (UPLOAD_ERR_CANT_WRITE == true) {
					$msg .= 'Disk write error';
				}
				if($msg != null) {
					$result[] = array(
						'statut' => false,
						'notify' => 'upload',
						'name' => $item["name"],
						'tmp_name' => $item["tmp_name"],
						'new_name' => $name,
						'mimecontent' =>$mimeContent,
						'msg' => $msg
					);
				}
				else {
					$result[] = array(
						'statut'=> true,
						'notify'=> 'upload',
						'name'=> $item["name"],
						'tmp_name'=> $item["tmp_name"],
						'new_name'=> $name,
						'mimecontent'=>$mimeContent
					);
				}
			}
		}

		if($debug) $this->logger->tracelog(json_encode($result));
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
	'webp'              => true,
	'progress' 			=> $this->progress, // component_core_feedback
	'template' 			=> $this->template
	),
	array(
	'upload_root_dir'   => 'upload/test', //string
	'upload_dir'        => array($this->id) //string ou array
	),
	$debug
	);
	 * Set Upload files
	 * @deprecated
	 * @see self::imageUpload()
	 * @param $img
	 * @param $data
	 * @param $imgCollection
	 * @param bool $debug
	 * @return array
	 */
	public function setImageUpload($img,$data,$imgCollection,$debug=false){
		return $this->imageUpload($data['module_img'], $data['attribute_img'], $imgCollection['upload_root_dir'], $imgCollection['upload_dir'], $data ,$debug);

		if(isset($this->$img)){
			try{
				// Charge la classe pour le traitement du fichier
				$makeFiles = new filesystem_makefile();
				$resultUpload = null;
				$debugResult = null;
				$extwebp = 'webp';
				$dirImg = $this->url->dirUpload(['upload_root_dir'=>$imgCollection['upload_root_dir'],'imgBasePath'=>true]);
				$fetchConfig = $this->config->fetchData(
					['context'=>'all', 'type'=>'imgSize'],
					['module_img'=>$data['module_img'], 'attribute_img'=>$data['attribute_img']]
				);
				//print_r($fetchConfig);
				if ($debug) {
					$log = new debug_logger(MP_LOG_DIR);
					$log->tracelog('start upload');
				}
				if(!empty($this->$img)){
					/**
					 * Envoi une image dans le dossier "racine" du module
					 */
					$resultUpload = $this->uploadImg(
						$img,
						$this->url->dirUpload(['upload_root_dir'=>$imgCollection['upload_root_dir'],'imgBasePath'=>false]),
						$debug
					);
					if($debug) $log->tracelog(json_encode($resultUpload));

					if($resultUpload['statut'] != false) {
						if($data['progress'] instanceof component_core_feedback && $data['template']) {
							usleep(100000);
							$data['progress']->sendFeedback(['message' => $data['template']->getConfigVars('resizing_images'), 'progress' => 80]);
						}
						/**
						 * Analyze l'extension du fichier en traitement
						 * @var $fileextends
						 */
						if($debug) $log->tracelog('statut : '.json_encode($resultUpload['statut']));

						$data['name'] = http_url::clean($data['name']);
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
									$dirImgArray = $this->url->dirUploadCollection($imgCollection);
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

										if (file_exists($filesPath . $prefix . $filename. $suffix.'.'.$mimeContent['type'])) {
											$makeFiles->remove(array($filesPath . $prefix . $filename. $suffix.'.'.$mimeContent['type']));
										}
										if (file_exists($filesPath . $filename.'.'.$mimeContent['type'])) {
											$makeFiles->remove(array($filesPath . $filename.'.'.$mimeContent['type']));
										}
									}
								}
							}
							if($debug) $log->tracelog('rename format');
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
									$dirImgArray = $this->url->dirUploadCollection($imgCollection,$debug);
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

										switch ($value['resize']) {
											case 'basic':
												$thumb->resize($value['width'], $value['height'], function ($constraint) {
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
												$thumb->fit($value['width'], $value['height']);
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
									if($debug) $log->tracelog('rename format');
									if($imgCollection['upload_dir'] !== '') {
										$makeFiles->rename(
											array(
												'origin' => $dirImg . $data['name'] . '.'.$resultUpload['mimecontent']['type'],
												'target' => $dirImgArray . $data['name'] . '.'.$resultUpload['mimecontent']['type']
											)
										);
									}

								}
							}

							if(isset($data['original_remove']) && $data['original_remove']){
								if($debug) $log->tracelog('delete original');
								if (is_array($imgCollection)) {
									$dirImgArray = $this->url->dirUploadCollection($imgCollection);
									//Supprime le fichier local
									if (file_exists($dirImgArray . $data['name'] . '.' . $resultUpload['mimecontent']['type'])) {
										$makeFiles->remove(array($dirImgArray . $data['name'] . '.' . $resultUpload['mimecontent']['type']));
									} else {
										throw new Exception('file: ' . $this->$img . ' is not found');
									}
								}
							}

							/*if ($debug) {
								$debugResult = '<pre>';
								$debugResult .= print_r($fetchConfig,true);
								$debugResult .= print_r($filesPathDebug,true);
								$debugResult .= print_r($resultUpload['mimecontent'],true);
								$debugResult .=  '</pre>';
							}*/
							$resultData = array('file' => $data['name'] . '.'.$resultUpload['mimecontent']['type'], 'statut' => $resultUpload['statut'], 'notify' => $resultUpload['notify'], 'msg' => $resultUpload['msg'],'debug'=>$debugResult);
							if ($debug)  $log->tracelog(json_encode($resultData));
							return $resultData;


						} else {
							//Supprime le fichier local
							if (file_exists($dirImg . $this->$img)) {
								$makeFiles->remove(array($dirImg . $this->$img));
							} else {
								throw new Exception('file: ' . $this->$img . ' is not found');
							}
						}
					}/*else{
                        $resultData = array('file'=>null,'statut'=>$resultUpload['statut'],'notify'=>$resultUpload['notify'],'msg'=>$resultUpload['msg']);
                        return $resultData;
                    }*/
				}/*else{
                    if(!empty($data['edit'])){
                        if(is_array($imgCollection)) {
                            $dirImgArray = $this->url->dirUploadCollection($imgCollection);
                            foreach($fetchConfig as $key => $value) {
                                if (file_exists($dirImgArray[$key] . $data['edit'])) {
                                    $makeFiles->remove(array($dirImgArray[$key] . $data['edit']));
                                }
                            }
                        }
                    }
                    return array('file'=>null,'statut'=>$resultUpload['statut'],'notify'=>$resultUpload['notify'],'msg'=>$resultUpload['msg']);
                }*/
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
	'webp'              => true,
	'progress' 			=> $this->progress, // component_core_feedback
	'template' 			=> $this->template
	),
	array(
	'upload_root_dir'   => 'upload/pages', //string
	'upload_dir'        => $this->id //string ou array
	),
	false
	);
	 * @deprecated
	 * @see self::multipleImageUpload()
	 * @param string $img_multiple
	 * @param array $data
	 * @param array $imgCollection
	 * @param bool $debug
	 * @return array
	 */
	public function setMultipleImageUpload(string $img_multiple, array $data, array $imgCollection, bool $debug=false): array {
		if(isset($data['prefix_name'])) $options['suffix'] = $data['prefix_name'];
		if(isset($data['prefix_increment'])) $options['suffix_increment'] = $data['prefix_increment'];

		return $this->multipleImageUpload($data['module_img'], $data['attribute_img'], $imgCollection['upload_root_dir'], $imgCollection['upload_dir'], $data ,$debug);

		if(isset($this->$img_multiple)) {
			try {
				$makeFiles = new filesystem_makefile();
				$dirImg = $this->url->dirUpload(['upload_root_dir'=>$imgCollection['upload_root_dir'],'imgBasePath'=>true]);
				$fetchConfig = $this->config->fetchData(['context'=>'all','type'=>'imgSize'],['module_img'=>$data['module_img'],'attribute_img'=>$data['attribute_img']]);

				if ($debug) $this->logger->tracelog('start upload');

				if(!empty($this->$img_multiple)){
					$makeFiles = new filesystem_makefile();
					$dirImg = $this->url->dirUpload(['upload_root_dir'=>$imgCollection['upload_root_dir'],'imgBasePath'=>true]);
					$imageConfig = $this->images->getConfigItems($data['module_img'],$data['attribute_img']);

					$resultData = [];

					/**
					 * Envoi une image dans le dossier "racine" catalogimg
					 */
					$resultUpload = $this->multiUploadImg(
						$img_multiple,
						$this->url->dirUpload(['upload_root_dir'=>$imgCollection['upload_root_dir'],'imgBasePath'=>false]),
						$data,
						$debug
					);

					//if($debug) $log->tracelog(json_encode($resultUpload));
					if ($data['progress'] instanceof component_core_feedback && $data['template']) {
						$percent = $data['progress']->progress;
						$preparePercent = (80 - $percent) / count($resultUpload);
					}

					foreach($resultUpload as $value){
						if($data['progress'] instanceof component_core_feedback && $data['template']) {
							$percent = $percent + $preparePercent;
							usleep(100000);
							$data['progress']->sendFeedback(['message' => $data['template']->getConfigVars('resizing_images'), 'progress' => $percent]);
						}

						//if($debug) $log->tracelog('statut : '.json_encode($value['statut']));
						if($value['statut']){
							$value['name'] = http_url::clean($value['name']);

							//if ($this->imgSizeMin($dirImg . $value['name'], 25, 25)) {
							//print $dirImg . $value['name'].'<br />';
							// Renomme le fichier
							if($debug) $log->tracelog('rename source');
							$makeFiles->rename([
								'origin' => $dirImg . $value['name'],
								'target' => $dirImg . $value['new_name'] . '.'.$value['mimecontent']['type']
							]);

							if ($fetchConfig != null) {
								if (is_array($imgCollection)) {
									// return array collection
									$dirImgArray = $this->url->dirUploadCollection($imgCollection,$debug);

									foreach ($fetchConfig as $keyConf => $valueConf) {
										$filesPath = is_array($dirImgArray) ? $dirImgArray[$keyConf] : $dirImgArray;
										//
										// init interventionImage
										//
										try {
											$thumb = $this->imageManager->make($dirImg . $value['new_name'] . '.'.$value['mimecontent']['type']);
										} catch (Exception $e) {
											$logger = new debug_logger(MP_LOG_DIR);
											$logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
										}

										// $thumb = $this->imageManager->make($dirImg . $value['new_name'] . '.'.$value['mimecontent']['type']);

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
														$thumb->save($filesPath . $prefix . $value['new_name'] . '.' . self::WEBP_EXT);
													}
												}
												break;
											case 'adaptive':
												$thumb->fit($valueConf['width_img'], $valueConf['height_img']);
												$thumb->save($filesPath . $prefix . $value['new_name'] . '.'.$value['mimecontent']['type'],80);
												if (  function_exists('imagewebp')) {
													// Check if webp is defined
													if (!isset($data['webp']) || $data['webp'] != false) {
														$thumb->save($filesPath . $prefix . $value['new_name'] . '.' . self::WEBP_EXT);
													}
												}
												break;
										}
										$filesPathDebug[] = $filesPath . $prefix . $value['new_name'] . '.'.$value['mimecontent']['type'];
									}

									if($debug) $log->tracelog('rename format');
									if($imgCollection['upload_dir'] !== '') {
										$makeFiles->rename(
											array(
												'origin' => $dirImg . $value['new_name'] . '.'.$value['mimecontent']['type'],
												'target' => $dirImgArray . $value['new_name'] . '.'.$value['mimecontent']['type']
											)
										);
									}
								}
							}

							if(isset($data['original_remove']) && $data['original_remove']){
								if($debug) $log->tracelog('delete original');
								if (is_array($imgCollection)) {
									$dirImgArray = $this->url->dirUploadCollection($imgCollection,$debug);
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
							/*}
							else {
								if($debug) $log->tracelog('too small');
							}*/
						}
					}

					if ($debug)  $log->tracelog(json_encode($resultData));
					return $resultData;
				}
			}
			catch (Exception $e){
				$this->logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
			}
		}
		return [];
	}
	
}