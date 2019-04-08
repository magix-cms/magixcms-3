<?php
class backend_controller_logo extends backend_db_logo
{
    public $edit, $action, $tabs, $search, $controller;
    protected $message, $template, $header, $data, $modelLanguage, $collectionLanguage, $upload, $config, $imagesComponent,$routingUrl,$makeFiles,$finder,$about;
    public $id_logo,$content,$img,$iso,$del_img,$del_holder,$ajax,$name_img,$color,$fav,$del_favicon,$active_logo;
    /**
     * backend_controller_logo constructor.
     * @param null|object $t
     * @throws Exception
     */
    public function __construct($t = null)
    {
        $this->template = $t ? $t : new backend_model_template;
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $formClean = new form_inputEscape();
        $this->modelLanguage = new backend_model_language($this->template);
        $this->collectionLanguage = new component_collections_language();
        $this->upload = new component_files_upload();
        $this->imagesComponent = new component_files_images($this->template);
        $this->makeFiles = new filesystem_makefile();
        $this->finder = new file_finder();
        $this->about = new backend_controller_about();
        // --- GET
        if(http_request::isGet('controller')) {
            $this->controller = $formClean->simpleClean($_GET['controller']);
        }
        if (http_request::isGet('edit')) {
            $this->edit = $formClean->numeric($_GET['edit']);
        }
        if (http_request::isGet('action')) {
            $this->action = $formClean->simpleClean($_GET['action']);
        } elseif (http_request::isPost('action')) {
            $this->action = $formClean->simpleClean($_POST['action']);
        }
        if (http_request::isGet('tabs')) $this->tabs = $formClean->simpleClean($_GET['tabs']);
        if (http_request::isPost('content')) {
            $array = $_POST['content'];
            /*foreach($array as $key => $arr) {
                foreach($arr as $k => $v) {
                    $array[$key][$k] = ($k == 'content_pages') ? $formClean->cleanQuote($v) : $formClean->simpleClean($v);
                }
            }*/
            $this->content = $array;
        }
        if (http_request::isPost('color')) {
            $array = $_POST['color'];
            $this->color = $array;
        }
        // --- Image Upload
        if (isset($_FILES['img']["name"])) $this->img = http_url::clean($_FILES['img']["name"]);
        if (http_request::isPost('name_img')) $this->name_img = http_url::clean($_POST['name_img']);
        if (http_request::isPost('del_img')) $this->del_img = $formClean->simpleClean($_POST['del_img']);
        if (http_request::isPost('del_holder')) $this->del_holder = $formClean->simpleClean($_POST['del_holder']);
        if (http_request::isPost('del_favicon')) $this->del_favicon = $formClean->simpleClean($_POST['del_favicon']);
        if (http_request::isPost('active_logo')) $this->active_logo = $formClean->simpleClean($_POST['active_logo']);
        if(isset($_FILES['fav']["name"])){
            $this->fav = http_url::clean($_FILES['fav']["name"]);
        }
        //del_img
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param string|int|null $id
     * @param string $context
     * @param boolean $assign
     * @param boolean $pagination
     * @return mixed
     */
    private function getItems($type, $id = null, $context = null, $assign = true, $pagination = false) {
        return $this->data->getItems($type, $id, $context, $assign, $pagination);
    }
    /**
     * scans the directory and returns one files with size
     * @param string $directory
     * @return array|null
     */
    public function scanDir($directory){
        try{
            $file = array();
            $it = new DirectoryIterator($directory);
            for($it->rewind(); $it->valid(); $it->next()) {
                if(!$it->isDir() && !$it->isDot() && $it->isFile()){
                    $size = getimagesize($it->getPath().DIRECTORY_SEPARATOR.$it->getFilename());
                    $file['img']['filename'] =  $it->getFilename();
                    $file['img']['width'] =  $size[0];
                    $file['img']['height'] =  $size[1];
                }
            }
            return $file;
        }catch (Exception $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('error', 'php', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_VOID);
        }
    }

    /**
     * @param $data
     * @return array
     * @throws Exception
     */
    private function setItemData($data){
        $arr = array();
        $imgPath = $this->upload->imgBasePath('img/logo');
        //$imgPrefix = $this->imagesComponent->prefix();
        $fetchConfig = $this->imagesComponent->getConfigItems(array(
            'module_img'    =>'logo',
            'attribute_img' =>'logo'
        ));

        foreach ($data as $page) {

            if (!array_key_exists($page['id_logo'], $arr)) {
                $arr[$page['id_logo']] = array();
                $arr[$page['id_logo']]['id_logo'] = $page['id_logo'];

                $img_pages = pathinfo($page['img_logo']);
                $arr[$page['id_logo']]['img_pages'] = $img_pages['filename'];
                $mimeContent = $this->upload->mimeContentType(array('filename'=>$imgPath.DIRECTORY_SEPARATOR.$page['img_logo']));

                if($page['img_logo'] != null) {
                    if(file_exists($imgPath.DIRECTORY_SEPARATOR.$page['img_logo'])){
                        $originalSize = getimagesize($imgPath.DIRECTORY_SEPARATOR.$page['img_logo']);
                        $arr[$page['id_logo']]['imgSrc']['original']['img'] = $page['img_logo'];
                        $arr[$page['id_logo']]['imgSrc']['original']['width'] = $originalSize[0];
                        $arr[$page['id_logo']]['imgSrc']['original']['height'] = $originalSize[1];
                    }
                    foreach ($fetchConfig as $key => $value) {
                        $size = getimagesize($imgPath.DIRECTORY_SEPARATOR.$img_pages['filename']. '@'.$value['width_img'].'.'.$mimeContent['type']);
                        $arr[$page['id_logo']]['imgSrc'][$value['type_img']]['img'] = $img_pages['filename']. '@'.$value['width_img'].'.'.$mimeContent['type'];
                        $arr[$page['id_logo']]['imgSrc'][$value['type_img']]['width'] = $size[0];
                        $arr[$page['id_logo']]['imgSrc'][$value['type_img']]['height'] = $size[1];
                    }
                }
                $arr[$page['id_logo']]['active_logo'] = $page['active_logo'];
                $arr[$page['id_logo']]['date_register'] = $page['date_register'];
            }
            $arr[$page['id_logo']]['content'][$page['id_lang']] = array(
                'id_lang'           => $page['id_lang'],
                'iso_lang'          => $page['iso_lang'],
                'alt_logo'     		=> $page['alt_logo'],
                'title_logo'     	=> $page['title_logo']
            );
        }
        return $arr;
    }

    /**
     * Update data
     * @param $data
     * @throws Exception
     */
    private function add($data)
    {
        switch ($data['type']) {
            case 'img':
            case 'imgContent':
                parent::insert(
                    array(
                        'context' => $data['context'],
                        'type' => $data['type']
                    ),
                    $data['data']
                );
                break;
        }
    }

    /**
     * Mise a jour des données
     * @param $data
     * @throws Exception
     */
    private function upd($data)
    {
        switch ($data['type']) {
            case 'img':
            case 'active':
            case 'imgContent':
                parent::update(
                    array(
                        'context' => $data['context'],
                        'type' => $data['type']
                    ),
                    $data['data']
                );
                break;
        }
    }
    /**
     * Mise a jour des données
     */
    private function save()
    {
        $about =  $this->about->getCompanyData();
        $company = !empty($about['name']) ? '-'.http_url::clean($about['name']) : '';
        $fetchConfig = $this->imagesComponent->getConfigItems(array(
            'module_img'    =>'logo',
            'attribute_img' =>'logo'
        ));

        $newSuffix = array();
        foreach ($fetchConfig as $key => $value) {
            $newSuffix[] = '@'.$value['width_img'];
        }

        $fetchRootData = $this->getItems('root', NULL,'one',false);
        switch($this->active_logo){
            case 'off':
                $active_logo = 0;
                break;
            case 'on':
                $active_logo = 1;
                break;
        }

        if($fetchRootData != null){
            $id_page = $fetchRootData['id_logo'];
            $settings = array(
                'name' => $this->name_img !== '' ? $this->name_img : 'logo'.$company,
                'edit' => $fetchRootData['img_logo'],
                //'prefix' => array('s_', 'm_', 'l_'),
                'suffix' => $newSuffix,
                'module_img' => 'logo',
                'attribute_img' => 'logo',
                'original_remove' => false
            );
            $dirs = array(
                'upload_root_dir' => 'img/logo', //string
                'upload_dir' => '' //string ou array
            );
            $filename = '';
            $update = false;

            if (isset($this->img)) {
                $resultUpload = $this->upload->setImageUpload('img', $settings, $dirs, false);
                $filename = $resultUpload['file'];
                $update = true;
            } elseif (isset($this->name_img)) {
                $img_pages = pathinfo($fetchRootData['img_logo']);
                $img_name = $img_pages['filename'];

                if ($this->name_img !== $img_name && $this->name_img !== '') {
                    $result = $this->upload->renameImages($settings, $dirs);
                    $filename = $result;
                    $update = true;
                }
            }

            if ($filename !== '' && $update) {
                $this->upd(array(
                    'type' => 'img',
                    'data' => array(
                        'id_logo' => $id_page,
                        'img_logo' => $filename,
                        'active_logo' => $active_logo
                    )
                ));
            }else{
                $this->upd(array(
                    'type' => 'active',
                    'data' => array(
                        'id_logo' => $id_page,
                        'active_logo' => $active_logo
                    )
                ));
            }
        }else{
            $settings = array(
                'name' => $this->name_img !== '' ? $this->name_img : 'logo'.$company,
                'edit' => '',
                //'prefix' => array('s_', 'm_', 'l_'),
                'suffix' => $newSuffix,
                'module_img' => 'logo',
                'attribute_img' => 'logo',
                'original_remove' => false
            );
            $dirs = array(
                'upload_root_dir' => 'img/logo', //string
                'upload_dir' => '' //string ou array
            );
            $filename = '';
            $update = false;

            if (isset($this->img)) {
                $resultUpload = $this->upload->setImageUpload('img', $settings, $dirs, false);
                $filename = $resultUpload['file'];
                $update = true;
            }elseif (isset($this->name_img)) {
                $img_pages = pathinfo($filename);
                $img_name = $img_pages['filename'];

                if ($this->name_img !== $img_name && $this->name_img !== '') {
                    $result = $this->upload->renameImages($settings, $dirs);
                    $filename = $result;
                    $update = true;
                }
            }

            $this->add(array(
                'type' => 'img',
                'data' => array(
                    'img_logo' => $filename,
                    'active_logo' => $active_logo
                )
            ));
            //parent::insert(array('type'=>'img'));
            $newData = $this->getItems('root', NULL,'one',false);
            $id_page = $newData['id_logo'];
        }

        if($id_page) {

            foreach ($this->content as $lang => $content) {
                //$content['published'] = (!isset($content['published']) ? 0 : 1);
                $content['id_lang'] = $lang;
                $content['id_logo'] = $id_page;
                $content['alt_logo'] = (!empty($content['alt_logo']) ? $content['alt_logo'] : NULL);
                $content['title_logo'] = (!empty($content['title_logo']) ? $content['title_logo'] : NULL);

                if ($this->getItems('content', array('id_logo' => $id_page, 'id_lang' => $lang),'one',false) != null) {

                    $this->upd(array(
                        'type' => 'imgContent',
                        'data' => $content
                    ));
                } else {
                    $this->add(array(
                        'type' => 'imgContent',
                        'data' => $content
                    ));
                }
            }
            $setEditData = $this->getItems('page',array('edit'=>$id_page),'all',false);
            $setEditData = $this->setItemData($setEditData);
            $this->template->assign('page',$setEditData[$id_page]);
            $display = $this->template->fetch('logo/brick/img.tpl');
            $this->message->json_post_response(true, 'update',$display);
        }
    }

    /**
     * @throws Exception
     */
    private function setImagePlaceHolder(){
        if($this->color['canvas'] === 'transparent'){
            $color = 'rgba(0, 0, 0, 0)';
            $ext = '.png';
        }else{
            $color = $this->color['canvas'];
            $ext = '.jpg';
        }

        $fetchRootData = $this->getItems('root', NULL, 'one', false);

        if ($fetchRootData != null) {
            $fetchConfig = $this->imagesComponent->getConfigItems(
                array(
                    'module_img' => 'logo',
                    'attribute_img' => 'logo'
                ),
                array(
                    'context' => 'all',
                    'type' => 'attribute'
                )
            );
            /*print '<pre>';
            print_r($fetchConfig);
            print '</pre>';*/
            foreach ($fetchConfig as $key => $value) {
                switch ($value['attribute_img']) {
                    case 'page':
                        $dirImgArray = $this->upload->dirImgUploadCollection(array(
                            'upload_root_dir' => 'img/default', //string
                            'upload_dir' => 'pages' //string ou array
                        ));
                        break;
                    default:
                        $dirImgArray = $this->upload->dirImgUploadCollection(array(
                            'upload_root_dir' => 'img/default', //string
                            'upload_dir' => $value['attribute_img'] //string ou array
                        ));
                        break;
                }
                //print $value['width_img'];

                $logo = $this->imagesComponent->imageManager->make(
                    $this->upload->imgBasePath('img/logo/') . $fetchRootData['img_logo']
                );
                $percentage = 50;
                $width = ($percentage / 100) * $value['width_img'];
                $height = ($percentage / 100) * $value['height_img'];

                $logo->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $logo->save($dirImgArray . 'logo.png');

                //$mimeContent = $this->upload->mimeContentType(array('filename'=>$dirImgArray.$data['edit']));
                $image = $this->imagesComponent->imageManager->canvas($value['width_img'], $value['height_img'], $color);
                $image->save($dirImgArray . 'default'.$ext);
                // create a new Image instance for inserting
                $watermark = $this->imagesComponent->imageManager->make($dirImgArray . 'logo.png');
                $image->insert($watermark, 'center');
                $image->save($dirImgArray . 'default'.$ext);
                if (file_exists($dirImgArray . 'logo.png')) {
                    $this->makeFiles->remove($dirImgArray . 'logo.png');
                }
            }
        }
        $dirImgArray = $this->upload->imgBasePath('img/default/');
        $scanRecursiveDir = $this->finder->scanRecursiveDir($dirImgArray);

        if(is_array($scanRecursiveDir)) {
            $newItems = array();
            foreach ($scanRecursiveDir as $items) {

                $newItems[$items] = $this->scanDir($this->upload->imgBasePath('img/default/' . $items));

            }
            $this->template->assign('holder',$newItems);
            $display = $this->template->fetch('logo/brick/holder.tpl');
            $this->message->json_post_response(true, 'update',$display);
        }

    }

    /**
     * @param $debug
     * @return array
     * @throws Exception
     */
    private function setImageFavicon($debug){
        if(isset($this->fav)) {

            $makeFiles = new filesystem_makefile();
            $dirImg = $this->upload->dirImgUpload(
                array_merge(
                    array('upload_root_dir'=>'img/favicon'),
                    array('imgBasePath'=>true)
                )
            );
            if (file_exists($dirImg . 'fav.png')) {
                $makeFiles->remove(array($dirImg . 'fav.png',$dirImg . 'favicon.png'));
            }
            if (file_exists($dirImg . 'fav.jpg')) {
                $makeFiles->remove(array($dirImg . 'fav.jpg',$dirImg . 'favicon.png'));
            }
            $resultUpload = $this->upload->uploadImg(
                'fav',
                $this->upload->dirImgUpload(
                    array_merge(
                        array('upload_root_dir' => 'img/favicon'),
                        array('imgBasePath' => false)
                    )
                ),
                false
            );
            if($resultUpload['statut'] != null) {
                // Renomme le fichier
                $makeFiles->rename(
                    array(
                        'origin' => $dirImg . $this->fav,
                        'target' => $dirImg . 'fav' . '.'.$resultUpload['mimecontent']['type']
                    )
                );
                try {
                    $thumb = $this->imagesComponent->imageManager->make($dirImg . 'fav' . '.'.$resultUpload['mimecontent']['type']);
                } catch (Exception $e) {
                    $logger = new debug_logger(MP_LOG_DIR);
                    $logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
                }
                /* ### Favicon png ####*/
                $thumb->resize(16, 16);
                $thumb->save($dirImg . 'favicon'. '.'.'png',80);
                //$filesPathDebug[] = $filesPath . $prefix . $data['name']. $suffix . '.'.$resultUpload['mimecontent']['type'];
                /* ### Favicon ico ####*/
                $source = $dirImg . 'fav' . '.'.$resultUpload['mimecontent']['type'];
                $destination = $dirImg . '/favicon.ico';

                $sizes = array(
                    array( 16, 16 )
                );

                $ico_lib = new PHP_ICO( $source, $sizes );
                $ico_lib->save_ico( $destination );
                /* ##### homescreen #####*/
                try {
                    $thumb = $this->imagesComponent->imageManager->make($dirImg . 'fav' . '.'.$resultUpload['mimecontent']['type']);
                } catch (Exception $e) {
                    $logger = new debug_logger(MP_LOG_DIR);
                    $logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
                }
                $dirTouchImg = $this->upload->dirImgUpload(
                    array_merge(
                        array('upload_root_dir'=>'img/touch'),
                        array('imgBasePath'=>true)
                    )
                );
                $touchSize = array(
                    array( 192, 192 ),
                    array( 168, 168 ),
                    array( 144, 144 ),
                    array( 96, 96 ),
                    array( 72, 72 ),
                    array( 48, 48 )
                );
                foreach($touchSize as $key => $value){
                    $thumb->resize($value[0], $value[1]);
                    $thumb->save($dirTouchImg . 'homescreen'.$value[0]. '.'.'png',80);
                }

                if ($debug) {
                    $debugResult = '<pre>';
                    //$debugResult .= print_r($fetchConfig,true);
                    //$debugResult .= print_r($filesPathDebug,true);
                    $debugResult .= print_r($resultUpload['mimecontent'],true);
                    $debugResult .=  '</pre>';
                }
                return array('file' => 'fav' . '.'.$resultUpload['mimecontent']['type'], 'statut' => $resultUpload['statut'], 'notify' => $resultUpload['notify'], 'msg' => $resultUpload['msg'],'debug'=>$debugResult);
            }else{
                //Supprime le fichier local
                if (file_exists($dirImg . $this->fav)) {
                    $makeFiles->remove(array($dirImg . $this->fav));
                } else {
                    throw new Exception('file: ' . $this->fav . ' is not found');
                }
            }
        }
    }
    /**
     * @throws Exception
     */
    public function run(){
         if(isset($this->action)) {
             switch ($this->action) {
                 case 'edit':
                     if(isset($this->img) || isset($this->name_img)){
                         $this->save();
                     }elseif(isset($this->color['canvas'])){
                         $this->setImagePlaceHolder();
                     }elseif(isset($this->fav)){
                         $resultUpload = $this->setImageFavicon(false);
                         $filename = $resultUpload['file'];
                         $update = true;
                         /* ##### favicon ######*/
                         $favCollection = $this->finder->scanDir($this->upload->imgBasePath('img/favicon/'),'.gitignore');
                         $favicon = null;
                         if(is_array($favCollection)) {
                             $favicon = array();
                             foreach ($favCollection as $key => $value) {
                                 $size = getimagesize($this->upload->imgBasePath('img/favicon/') . DIRECTORY_SEPARATOR . $value);
                                 $favicon[$key]['img']['filename'] = $value;
                                 $favicon[$key]['img']['width'] = $size[0];
                                 $favicon[$key]['img']['height'] = $size[1];
                             }
                         }
                         /* ##### Touch (Homescreen) ######*/
                         $touchCollection = $this->finder->scanDir($this->upload->imgBasePath('img/touch/'),'.gitignore');
                         $touch = null;
                         if(is_array($touchCollection)) {
                             $touch = array();
                             foreach ($touchCollection as $key => $value) {
                                 $size = getimagesize($this->upload->imgBasePath('img/touch/') . DIRECTORY_SEPARATOR . $value);
                                 $touch[$key]['img']['filename'] = $value;
                                 $touch[$key]['img']['width'] = $size[0];
                                 $touch[$key]['img']['height'] = $size[1];
                             }
                         }
                         $this->template->assign('favicon',$favicon);
                         $this->template->assign('homescreen',$touch);
                         $display = $this->template->fetch('logo/brick/favicon.tpl');

                         $this->message->json_post_response(true, 'update', $display);
                     }
                     break;
                 case 'delete':
                     if(isset($this->del_img)) {
                         /* Remove files logo image and update data in database */
                         $fetchRootData = $this->getItems('root', NULL, 'one', false);
                         $id_page = $fetchRootData['id_logo'];
                         $this->upd(array(
                             'type' => 'img',
                             'data' => array(
                                 'id_logo' => $id_page,
                                 'img_logo' => NULL,
                                 'active_logo' => 0
                             )
                         ));

                         $setEditData = $this->getItems('page', array('edit' => $id_page), 'all', false);
                         $setEditData = $this->setItemData($setEditData);

                         $setImgDirectory = $this->upload->dirImgUpload(
                             array_merge(
                                 array('upload_root_dir' => 'img/logo'),
                                 array('imgBasePath' => true)
                             )
                         );

                         if (file_exists($setImgDirectory)) {
                             $setFiles = $this->finder->scanDir($setImgDirectory);
                             $clean = '';
                             if ($setFiles != null) {
                                 foreach ($setFiles as $file) {
                                     $clean .= $this->makeFiles->remove($setImgDirectory . $file);
                                 }
                             }
                         }

                         $this->template->assign('page', $setEditData[$id_page]);
                         $display = $this->template->fetch('logo/brick/img.tpl');

                         $this->message->json_post_response(true, 'update', $display);

                     }elseif(isset($this->del_holder)){
                         /* Remove files holder image */
                         $dirImgDefault = $this->upload->imgBasePath('img/default/');
                         $scanRecursiveDir = $this->finder->scanRecursiveDir($dirImgDefault);
                         if (file_exists($dirImgDefault)) {

                             $clean = '';
                             if ($scanRecursiveDir != null) {
                                 foreach ($scanRecursiveDir as $file) {
                                     if (file_exists($dirImgDefault . $file)) {
                                         $clean .= $this->makeFiles->remove($dirImgDefault . $file);
                                     }
                                 }
                             }
                         }
                         $newItems = array();
                         foreach ($scanRecursiveDir as $items) {

                             $newItems[$items] = $this->scanDir($this->upload->imgBasePath('img/default/' . $items));

                         }
                         $this->template->assign('holder',$newItems);
                         $display = $this->template->fetch('logo/brick/holder.tpl');
                         $this->message->json_post_response(true, 'update',$display);

                     }elseif(isset($this->del_favicon)){
                         /* Remove files favicon image && homescreen image */
                         $dirImgFavicon = $this->upload->imgBasePath('img/favicon/');
                         $favArray = $this->finder->scanDir($dirImgFavicon);
                         if (file_exists($dirImgFavicon)) {
                             $clean = '';
                             if(is_array($favArray)) {
                                 foreach ($favArray as $file) {
                                     if (file_exists($dirImgFavicon . $file)) {
                                         $clean .= $this->makeFiles->remove($dirImgFavicon . $file);
                                     }
                                 }
                             }
                         }

                         $dirImgTouch = $this->upload->imgBasePath('img/touch/');
                         $touchArray = $this->finder->scanDir($dirImgTouch);
                         if(is_array($touchArray)) {
                             if (file_exists($dirImgTouch)) {
                                 $clean = '';
                                 foreach ($touchArray as $file) {
                                     if (file_exists($dirImgTouch . $file)) {
                                         $clean .= $this->makeFiles->remove($dirImgTouch . $file);
                                     }
                                 }
                             }
                         }
                         /* ##### favicon ######*/
                         $favicon = array();
                         /* ##### Touch (Homescreen) ######*/
                         $touch = array();

                         $this->template->assign('favicon',$favicon);
                         $this->template->assign('homescreen',$touch);
                         $display = $this->template->fetch('logo/brick/favicon.tpl');

                         $this->message->json_post_response(true, 'update', $display);

                     }
                     break;
             }
         }else{
             $fetchRootData = $this->getItems('root', NULL,'one',false);
             /* ##### image placeholder ######*/
             $dirImgArray = $this->upload->imgBasePath('img/default/');
             $scanRecursiveDir = $this->finder->scanRecursiveDir($dirImgArray);

             if(is_array($scanRecursiveDir)) {
                 $newItems = array();
                 foreach ($scanRecursiveDir as $items) {

                     $newItems[$items] = $this->scanDir($this->upload->imgBasePath('img/default/' . $items));

                 }
                 $this->template->assign('holder',$newItems);
             }
             /* ##### favicon ######*/
             $favCollection = $this->finder->scanDir($this->upload->imgBasePath('img/favicon/'),'.gitignore');
             $favicon = null;
             if(is_array($favCollection)) {
                 $favicon = array();
                 foreach ($favCollection as $key => $value) {
                     $size = getimagesize($this->upload->imgBasePath('img/favicon/') . DIRECTORY_SEPARATOR . $value);
                     $favicon[$key]['img']['filename'] = $value;
                     $favicon[$key]['img']['width'] = $size[0];
                     $favicon[$key]['img']['height'] = $size[1];
                 }
             }
             /* ##### Touch (Homescreen) ######*/
             $touchCollection = $this->finder->scanDir($this->upload->imgBasePath('img/touch/'),'.gitignore');
             $touch = null;
             if(is_array($touchCollection)) {
                 $touch = array();
                 foreach ($touchCollection as $key => $value) {
                     $size = getimagesize($this->upload->imgBasePath('img/touch/') . DIRECTORY_SEPARATOR . $value);
                     $touch[$key]['img']['filename'] = $value;
                     $touch[$key]['img']['width'] = $size[0];
                     $touch[$key]['img']['height'] = $size[1];
                 }
             }
             $this->template->assign('favicon',$favicon);
             $this->template->assign('homescreen',$touch);

             if($fetchRootData != null) {
                 $id_page = $fetchRootData['id_logo'];
                 $setEditData = $this->getItems('page', array('edit'=>$id_page),'all',false);
                 $setEditData = $this->setItemData($setEditData);
                 $this->template->assign('page',$setEditData[$id_page]);
             }
             $this->modelLanguage->getLanguage();
             $this->template->display('logo/index.tpl');
         }
    }
}
?>