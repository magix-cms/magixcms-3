<?php
// import the Intervention Image Manager Class
use Intervention\Image\ImageManager;

/**
 * Class component_files_images
 */
class component_files_images {

    protected $template;
	protected component_collections_config $configCollection;
	protected component_routing_url $url;
	protected component_core_feedback $progress;
	/**
	 * @deprecated
	 * @var component_collections_config 
	 */
	protected $config;
	
	/**
	 * @var array $imgConfig
	 */
	protected array $imgConfig = [];
    public $imageManager;

    /**
     * component_files_images constructor.
     * @param $template
     */
    public function __construct($template) {
        $this->template = $template;
        $this->configCollection = new component_collections_config();
        $this->url = new component_routing_url();
        $this->imageManager = new ImageManager(['driver' => 'gd']);
        $this->config = $this->configCollection;
    }

    /**
	 * @deprecated
     * @return array
     */
    public function prefix(): array {
        return ['default' => '', 'small' => 's_', 'medium' => 'm_', 'large' => 'l_'];
    }

    /**
	 * @deprecated
     * @return array
     */
    public function type(): array {
        return ['default', 'small', 'medium', 'large'];
    }

    /**
     * @return array
     */
    public function module(): array {
        return ['catalog', 'news', 'pages', 'logo', 'plugins'];
    }

    /**
     * @return array
     */
    public function resize(): array {
        return ['basic', 'adaptive'];
    }

	/**
	 * @param string $src
	 * @return array
	 */
	public function getImageInfos(string $src): array {
		list($width, $height, $type, $attr) = getimagesize($src);

		return [
			'width' => $width,
			'height' => $height,
			'type' => $type,
			'attr' => $attr
		];
    }

    /**
     * @param string $module
     * @param string $attribute
     * @return array
     */
    public function getConfigItems(string $module, string $attribute): array {
    	if(!isset($this->imgConfig[$module][$attribute])) {
			/*$imgConf = $this->configCollection->fetchData(
				$config,[
					'module_img' => $params['module_img'],
					'attribute_img' => $params['attribute_img']
				]
			);*/
			$imgConf = $this->configCollection->fetchData(['context' => 'all', 'type' => 'imgSize'], [$module, $attribute]);
			if(empty($imgConf)) return [];
			$this->imgConfig[$module][$attribute] = $imgConf;
		}
        return $this->imgConfig[$module][$attribute];
    }

    /**
     * @return array
     */
    public function getConfigImages(): array {
		$imgConf = $this->configCollection->fetchData(['context' => 'all', 'type' => 'configImages']);
		if(!empty($imgConf)) {
			foreach ($imgConf as $conf) {
				if(!key_exists($conf['module'],$this->imgConfig)) $this->imgConfig[$conf['module']] = [];
				if(!key_exists($conf['attribute'],$this->imgConfig[$conf['module']])) $this->imgConfig[$conf['module']][$conf['attribute']] = [];
				$this->imgConfig[$conf['module']][$conf['attribute']][] = $conf;
			}
		}
        return $this->imgConfig;
    }

    /**
     * @param array $config
     * @param array $data
     * @return array
     */
    private function setImgData(array $config,array $data): array {
        $newArr = [];
		if(!empty($data)) {
			foreach($data as $key => $value){
				$newArr[$key] = [
					'id' => $value[$config['id']],
					'img' => $value[$config['img']]
				];
			}
		}
        return $newArr;
    }

    /**
	 * @deprecated
	 * @see component_files_upload::batchRegenerate()
     * @param $config
     * @param $data
     * @throws Exception
     */
    public function getThumbnailItems($config,$data){
        $extwebp = 'webp';
        $this->template->configLoad();
        usleep(200000);
        $this->progress = new component_core_feedback($this->template);
        $this->progress->sendFeedback(['message' => $this->template->getConfigVars('control_of_data'),'progress' => 10]);

        if(!empty($data)) {

			$fetchConfig = $this->getConfigItems($config['module_img'],$config['attribute_img']);
            $fetchImg = $this->setImgData($config, $data);
            $total = count($fetchImg);
            $preparePercent =  100/$total;
            $percent = 0;

            if(!empty($fetchImg)) {

                foreach ($fetchImg as $item) {

                    $imgData = pathinfo($item['img']);
                    $filename = $imgData['filename'];

                    $percent = $percent + $preparePercent;
                    usleep(200000);
                    $this->progress->sendFeedback(['message' => $this->template->getConfigVars('creating_thumbnails'), 'progress' => $percent]);

                    // Loop config
                    foreach ($fetchConfig as $key => $value) {

                        $imgPath = $this->url->basePath($config['upload_root_dir'].'/'.$item['id'].'/'.$item['img']);

                        try {
                            $thumb = $this->imageManager->make($imgPath);
                        } catch (Exception $e) {
                            $logger = new debug_logger(MP_LOG_DIR);
                            $logger->log('php', 'error', 'An error has occured : '.$e->getMessage().' '.$imgPath, debug_logger::LOG_MONTH);
                        }

                        switch ($value['resize']) {
                            case 'basic':
                                $thumb->resize($value['width'], $value['height'], function ($constraint) {
                                    $constraint->aspectRatio();
                                    $constraint->upsize();
                                });
                                /*$thumb->save($this->url->basePath($config['upload_root_dir'] . '/' . $item['id'] . '/' . $value['prefix'] . '_' . $item['img']), 80);
                                if (function_exists('imagewebp')) {
                                    if (!isset($data['webp']) || $data['webp'] != false) {
                                        $thumb->save($this->url->basePath($config['upload_root_dir'] . '/' . $item['id'] . '/' . $value['prefix'] . '_' . $filename . '.' . $extwebp));
                                    }
                                }*/
                                break;
                            case 'adaptive':
                                //$thumb->adaptiveResize($value['width_img'], $value['height_img']);
                                $thumb->fit($value['width'], $value['height']);
                                break;
                        }

						$thumb->save($this->url->basePath($config['upload_root_dir'].'/'.$item['id'].'/'.$value['prefix'].'_'.$item['img']), 80);

						if (function_exists('imagewebp')) $thumb->save($this->url->basePath($config['upload_root_dir'].'/'.$item['id'].'/'. $value['prefix'].'_'.$filename.'.'.$extwebp));
                    }
                }
            }
            usleep(200000);
            $this->progress->sendFeedback(['message' => $this->template->getConfigVars('creating_thumbnails_success'),'progress' => 100,'status' => 'success']);
        }
		else{
            usleep(200000);
            $this->progress->sendFeedback(['message' => $this->template->getConfigVars('creating_thumbnails_error'),'progress' => 100,'status' => 'error','error_code' => 'error_data']);
        }
    }

	/**
	 * @param string $path
	 * @param string $name
	 * @param array $conf
	 * @return array
	 */
	private function setImageData(string $path, string $name, array $conf): array {
		$image = [];
		$url = component_core_system::basePath();
		if(file_exists($url.$path.$conf['prefix'].'_'.$name)) {
			$imageInfos = $this->getImageInfos($url.$path.$conf['prefix'].'_'.$name);
			$filename = pathinfo($name)['filename'];
			$image = [
				'src' => '/'.$path.$conf['prefix'].'_'.$name,
				'src_webp' => '/'.$path.$conf['prefix'].'_'.$filename.'.webp',
				'w' => $conf['resize'] === 'basic' ? $imageInfos['width'] : $conf['width'],
				'h' => $conf['resize'] === 'basic' ? $imageInfos['height'] : $conf['height'],
				'crop' => $conf['resize'],
				'ext' => mime_content_type($url.$path.$conf['prefix'].'_'.$name)
			];
		}
		return $image;
	}

	/**
	 * @param string $module
	 * @param string $attribute
	 * @param string $name
	 * @param int|null $id
	 * @return array|false
	 */
	public function setModuleImage(string $module, string $attribute, string $name = '', int $id = null, string $alt = '', string $title = ''): array {
		$image = [];
		$config = $this->getConfigItems($module,$attribute);
		if(!empty($name)) {
			$imgPath = 'upload/'.$module.($attribute !== $module ? '/'.$attribute : '').($id ? '/'.$id.'/' : '/');
			foreach ($config as $v) {
				$image[$v['type']] = $this->setImageData($imgPath,$name,$v);
			}
			$image['name'] = $name;
            $image['alt'] = $alt;
            $image['title'] = $title;
		}
		else {
			$defaultPath = 'img/default/'.$module.($attribute !== $module ? '/'.$attribute.'/' : '/');
			$default = '';
			foreach ($config as $v) {
				if($default === '') $default = (file_exists(component_core_system::basePath().$defaultPath.$v['prefix'].'_default.png')) ? 'default.png' : 'default.jpg';
				$image[$v['type']] = $this->setImageData($defaultPath,$default,$v);
			}
		}
		return $image;
    }

	/**
	 * @param string $module
	 * @param string $attribute
	 * @param array $images
	 * @param int $id
	 * @return array
	 */
	public function setModuleImages(string $module, string $attribute, array $images, int $id): array {
		if(!empty($images)) {
			foreach ($images as &$image) {
				$image['img'] = $this->setModuleImage($module, $attribute, $image['name'], $id);
			}
		}
		return $images;
    }

    /**
     *
     *
    $this->edit = 4;
    $this->id = 16;
    $moveResult = $this->images->setMoveMultiImg(
        $filesData,
        array(
            'name'              => ''
            'prefix_name'       => '',
            'prefix_increment'  => true,
            'prefix'            => array('s_'),
            'module_img'        => 'catalog',
            'attribute_img'     => 'product',
            'webp'              => true
        ),
        array(
            'upload_root_dir'   => 'upload/temp/p', //string
            'upload_dir'        => $this->edit //string ou array
        ),
        array(
            'upload_root_dir'   => 'upload/catalog/p', //string
            'upload_dir'        => $this->id //string ou array
        ),
        false
    );
     *
     * @param $filesData
     * @param $data
     * @param $imgTempCollection
     * @param $imgCollection
     * @param bool $debug
     * @return array
     */
    public function setMoveMultiImg($filesData,$data,$imgTempCollection,$imgCollection,$debug=false){
        try{
            $makeFiles = new filesystem_makefile();
            if($filesData != null) {
                $resultUpload = null;

                $extwebp = 'webp';
                // ---- Dossier temporaire des images
                $dirImgTemp = $this->url->dirUpload(
                    array_merge(
                        array(
                            'upload_root_dir' => $imgTempCollection['upload_root_dir']
                        ),
                        array(
                            'imgBasePath' => true
                        )
                    )
                );
                // ---- Parcours des images temporaires en base de données
                foreach ($filesData as $item) {
                    //Détecte le type mime du fichier
                    $mimeContent = $this->url->mimeContentType(array('filename' => $dirImgTemp . $imgTempCollection['upload_dir'] . $item['name_img']));

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

                    $resultUpload[] = array(
                        'statut' => true,
                        'notify' => 'upload',
                        'name' => $item["name"],
                        //'tmp_name'=> $item["tmp_name"],
                        'new_name' => $name,//filter_rsa::randMicroUI(),
                        'mimecontent' =>
                            array(
                                'type' => $mimeContent['type'],
                                'mime' => $mimeContent['mime']
                            )
                    );
                }
                // ---- Chemin des dossiers définitif pour les images
                $dirImg = $this->url->dirUpload(
                    array_merge(
                        array(
                            'upload_root_dir'=>$imgCollection['upload_root_dir']
                        ),
                        array(
                            'imgBasePath' => true
                        )
                    )
                );
                $fetchConfig = $this->configCollection->fetchData(array('context' => 'all', 'type' => 'imgSize'), array('module_img' => $data['module_img'], 'attribute_img' => $data['attribute_img']));

                foreach ($resultUpload as $key => $value) {
                    if ($value['statut'] != 0) {

                        // Renomme le fichier
                        $makeFiles->rename(
                            array(
                                'origin' => $dirImgTemp . $imgTempCollection['upload_dir'] . $value['name'],
                                'target' => $dirImg . $value['new_name'] . '.' . $value['mimecontent']['type']
                            )
                        );
                        if ($fetchConfig != null) {
                            if (is_array($imgCollection)) {
                                // return array collection
                                $dirImgArray = $this->url->dirUploadCollection($imgCollection, $debug = true);

                                foreach ($fetchConfig as $keyConf => $valueConf) {
                                    if (is_array($dirImgArray)) {
                                        $filesPath = $dirImgArray[$keyConf];
                                    } else {
                                        $filesPath = $dirImgArray;
                                    }

                                    try {
                                        $thumb = $this->imageManager->make($dirImg . $value['new_name'] . '.' . $value['mimecontent']['type']);
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
                                            $thumb->save($filesPath . $prefix . $value['new_name'] . '.' . $value['mimecontent']['type'], 80);
                                            if (function_exists('imagewebp')) {
                                                // Check if webp is defined
                                                if (!isset($data['webp']) || $data['webp'] != false) {
                                                    $thumb->save($filesPath . $prefix . $value['new_name'] . '.' . $extwebp);
                                                }
                                            }
                                            break;
                                        case 'adaptive':
                                            $thumb->fit($valueConf['width_img'], $valueConf['height_img']);
                                            $thumb->save($filesPath . $prefix . $value['new_name'] . '.' . $value['mimecontent']['type'], 80);
                                            if (function_exists('imagewebp')) {
                                                // Check if webp is defined
                                                if (!isset($data['webp']) || $data['webp'] != false) {
                                                    $thumb->save($filesPath . $prefix . $value['new_name'] . '.' . $extwebp);
                                                }
                                            }
                                            break;
                                    }
                                    $filesPathDebug[] = $filesPath . $prefix . $value['new_name'] . '.' . $value['mimecontent']['type'];
                                }

                                $makeFiles->rename(
                                    array(
                                        'origin' => $dirImg . $value['new_name'] . '.'.$value['mimecontent']['type'],
                                        'target' => $dirImgArray . $value['new_name'] . '.'.$value['mimecontent']['type']
                                    )
                                );

                            }
                        }


                        $resultData[] = array(
                            'file' => $value['new_name'] . '.' . $value['mimecontent']['type'],
                            'statut' => $value['statut'],
                            'notify' => $value['notify'],
                            'msg' => 'Upload success'
                        );

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
                }
            }
        }catch (Exception $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

	/**
	 * @param string $module
	 * @param string $attribute
	 * @param string $old
	 * @param string $new
	 * @param string $root
	 * @param array $directories
	 * @return bool
	 */
	public function rePrefixImages(string $module, string $attribute, string $old, string $new, string $root, array $directories = []): bool {

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
}