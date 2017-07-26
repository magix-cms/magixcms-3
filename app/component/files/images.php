<?php
class component_files_images{

    protected $configCollection, $fileUpload, $progress;

    /**
     * component_files_images constructor.
     * @param $template
     */
    public function __construct($template){
        $this->template = $template;
        $this->configCollection = new component_collections_config();
        $this->fileUpload = new component_files_upload();
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
        return array('catalog','news','pages','about','plugins');
    }

    /**
     * @return array
     */
    public function resize(){
        return array('basic','adaptive');
    }

    /**
     * @param $data
     * @return mixed|null
     */
    public function getConfigItems($data){
        return $this->configCollection->fetchData(
            array(
                'context'=>'all',
                'type'=>'imgSize'
            ),
            array(
                'module_img'    =>$data['module_img'],
                'attribute_img' =>$data['attribute_img']
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
     */
    public function getThumbnailItems($config,$data){
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
                $percent = $percent+$preparePercent;
                usleep(200000);
                $this->progress->sendFeedback(array('message' => $this->template->getConfigVars('creating_thumbnails'),'progress' => $percent));

                // Loop config
                foreach ($fetchConfig as $key => $value) {
                    $imgPath = $this->fileUpload->imgBasePath($config['upload_root_dir'] . '/' . $item['id'] . '/' . $item['img']);
                    try {
                        $thumb = PhpThumbFactory::create($imgPath, array('jpegQuality' => 70));
                    } catch (Exception $e) {
                        $logger = new debug_logger(MP_LOG_DIR);
                        $logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
                    }

                    switch ($value['resize_img']) {
                        case 'basic':
                            $thumb->resize($value['width_img'], $value['height_img']);
                            $thumb->save($this->fileUpload->imgBasePath($config['upload_root_dir'] . '/' . $item['id'] . '/' . $prefix[$value['type_img']] . $item['img']));
                            break;
                        case 'adaptive':
                            $thumb->adaptiveResize($value['width_img'], $value['height_img']);
                            $thumb->save($this->fileUpload->imgBasePath($config['upload_root_dir'] . '/' . $item['id'] . '/' . $prefix[$value['type_img']] . $item['img']));
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
}
?>