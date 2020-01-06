<?php
// import the Intervention Image Manager Class
use Intervention\Image\ImageManager;

/**
 * Class component_files_images
 */
class component_files_images{

    protected $template,$configCollection, $fileUpload, $progress, $config;
    public $imageManager;

    /**
     * component_files_images constructor.
     * @param $template
     */
    public function __construct($template){
        $this->template = $template;
        $this->configCollection = new component_collections_config();
        $this->fileUpload = new component_files_upload();
        $this->imageManager = new ImageManager(array('driver' => 'gd'));
        $this->config = new component_collections_config();
    }

    /**
     * @return array
     */
    public function prefix(){
        return array('small'=>'s_','medium'=>'m_','large'=>'l_');
    }

    /**
     * @return array
     */
    public function type(){
        return array('small','medium','large');
    }

    /**
     * @return array
     */
    public function module(){
        return array('catalog','news','pages','logo','plugins');
    }

    /**
     * @return array
     */
    public function resize(){
        return array('basic','adaptive');
    }

	/**
	 * @param $src
	 * @return array
	 */
	public function getImageInfos($src)
	{
		list($width, $height, $type, $attr) = getimagesize($src);

		return array(
			'width' => $width,
			'height' => $height,
			'type' => $type,
			'attr' => $attr
		);
    }

    /**
     * @param $params
     * @param array $config
     * @return mixed|null
     * @throws Exception
     */
    public function getConfigItems($params,$config = array('context'=>'all','type'=>'imgSize')){
        return $this->configCollection->fetchData(
            $config,
            array(
                'module_img'    =>$params['module_img'],
                'attribute_img' =>$params['attribute_img']
            )
        );
    }

    /**
     * @param $config
     * @param $data
     * @return array
     */
    private function setImgData($config,$data){
        $newArr = array();
        foreach($data as $key => $value){
            $newArr[$key]['id'] = $value[$config['id']];
            $newArr[$key]['img'] = $value[$config['img']];
        }
        return $newArr;
    }

    /**
     * @param $config
     * @param $data
     * @throws Exception
     */
    public function getThumbnailItems($config,$data){
        $extwebp = 'webp';
        $this->template->configLoad();
        usleep(200000);
        $this->progress = new component_core_feedback($this->template);
        $this->progress->sendFeedback(array('message' => $this->template->getConfigVars('control_of_data'),'progress' => 10));
        if($data != null) {
            $fetchConfig = $this->configCollection->fetchData(
                array(
                    'context'   =>  'all',
                    'type'      =>  'imgSize'
                ),
                array(
                    'module_img'    =>  $config['module_img'],
                    'attribute_img' =>  $config['attribute_img']
                )
            );
            $prefix = $this->prefix();
            $fetchImg = $this->setImgData($config, $data);
            //print_r($fetchImg);
            $total = count($fetchImg);
            $preparePercent =  100/$total;
            $percent = 0;
            foreach ($fetchImg as $item) {
                $imgData = pathinfo($item['img']);
                $filename = $imgData['filename'];

                $percent = $percent+$preparePercent;
                usleep(200000);
                $this->progress->sendFeedback(array('message' => $this->template->getConfigVars('creating_thumbnails'),'progress' => $percent));

                // Loop config
                foreach ($fetchConfig as $key => $value) {
                    $imgPath = $this->fileUpload->imgBasePath($config['upload_root_dir'] . '/' . $item['id'] . '/' . $item['img']);
                    try {
                        $thumb = $this->imageManager->make($imgPath);
                    } catch (Exception $e) {
                        $logger = new debug_logger(MP_LOG_DIR);
                        $logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
                    }

                    switch ($value['resize_img']) {
                        case 'basic':
                            $thumb->resize($value['width_img'], $value['height_img'], function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            });
                            $thumb->save($this->fileUpload->imgBasePath($config['upload_root_dir'] . '/' . $item['id'] . '/' . $prefix[$value['type_img']] . $item['img']),80);
                            if (  function_exists('imagewebp')) {
                                if (!isset($data['webp']) || $data['webp'] != false) {
                                    $thumb->save($this->fileUpload->imgBasePath($config['upload_root_dir'] . '/' . $item['id'] . '/' . $prefix[$value['type_img']] . $filename . '.' . $extwebp));
                                }
                            }
                            break;
                        case 'adaptive':
                            //$thumb->adaptiveResize($value['width_img'], $value['height_img']);
                            $thumb->fit($value['width_img'], $value['height_img']);
                            $thumb->save($this->fileUpload->imgBasePath($config['upload_root_dir'] . '/' . $item['id'] . '/' . $prefix[$value['type_img']] . $item['img']),80);
                            if (  function_exists('imagewebp')) {
                                if (!isset($data['webp']) || $data['webp'] != false) {
                                    $thumb->save($this->fileUpload->imgBasePath($config['upload_root_dir'] . '/' . $item['id'] . '/' . $prefix[$value['type_img']] . $filename . '.' . $extwebp));
                                }
                            }
                            break;
                    }
                }
            }
            usleep(200000);
            $this->progress->sendFeedback(array('message' => $this->template->getConfigVars('creating_thumbnails_success'),'progress' => 100,'status' => 'success'));
        }else{
            usleep(200000);
            $this->progress->sendFeedback(array('message' => $this->template->getConfigVars('creating_thumbnails_error'),'progress' => 100,'status' => 'error','error_code' => 'error_data'));
        }
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
                $dirImgTemp = $this->fileUpload->dirImgUpload(
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
                    $mimeContent = $this->fileUpload->mimeContentType(array('filename' => $dirImgTemp . $imgTempCollection['upload_dir'] . $item['name_img']));

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
                $dirImg = $this->fileUpload->dirImgUpload(
                    array_merge(
                        array(
                            'upload_root_dir'=>$imgCollection['upload_root_dir']
                        ),
                        array(
                            'imgBasePath' => true
                        )
                    )
                );
                $fetchConfig = $this->config->fetchData(array('context' => 'all', 'type' => 'imgSize'), array('module_img' => $data['module_img'], 'attribute_img' => $data['attribute_img']));

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
                                $dirImgArray = $this->upload->dirImgUploadCollection($imgCollection, $debug = true);

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
}
?>