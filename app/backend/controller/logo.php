<?php
class backend_controller_logo extends backend_db_logo {
    /**
     * @var backend_model_template $template
     * @var component_core_message $message
     * @var backend_model_data $data
     * @var backend_model_language $modelLanguage
     * @var component_collections_language $collectionLanguage
     * @var component_files_upload $upload
     * @var component_core_message $config
     * @var component_files_images $imagesComponent
     * @var component_routing_url $routingUrl
     * @var filesystem_makefile $makeFiles
     * @var file_finder $finder
     * @var backend_controller_about $about
     * @var debug_logger $logger
     */
    protected backend_model_template $template;
    protected component_core_message $message;
    protected backend_model_data $data; 
    protected backend_model_language $modelLanguage;
    protected component_collections_language $collectionLanguage;
    protected component_files_upload $upload;
    protected component_files_images $imagesComponent;
    protected component_routing_url $routingUrl;
    protected filesystem_makefile $makeFiles;
    protected file_finder $finder;
    protected backend_controller_about $about;
    protected debug_logger $logger;

    /**
     * @var int
     */
    public int $edit;

    /**
     * @var string
     */
    public string
        $action,
        $tabs,
        $controller;

    /**
     * @var array
     */
    public array
        $search,
        $content;

    public
        $img,
        $iso,
        $del_img,
        $del_holder,
        $ajax,
        $name_img,
        $holder_bg_color,
        $fav,
        $del_favicon,
        $active_logo,
        $logo_percent;
    
    /**
     * backend_controller_logo constructor.
     * @param null|backend_model_template $t
     */
    public function __construct(backend_model_template $t = null) {
        $this->template = $t instanceof backend_model_template ? $t : new backend_model_template;
        $this->message = new component_core_message($this->template);
        $this->data = new backend_model_data($this);
        $this->modelLanguage = new backend_model_language($this->template);
        $this->collectionLanguage = new component_collections_language();
        $this->upload = new component_files_upload();
        $this->routingUrl = new component_routing_url();
        $this->imagesComponent = new component_files_images($this->template);
        $this->makeFiles = new filesystem_makefile();
        $this->finder = new file_finder();
        $this->about = new backend_controller_about();
        // --- GET
        if (http_request::isGet('controller')) $this->controller = form_inputEscape::simpleClean($_GET['controller']);
        if (http_request::isGet('edit')) $this->edit = form_inputEscape::numeric($_GET['edit']);
        if (http_request::isRequest('action')) $this->action = form_inputEscape::simpleClean($_REQUEST['action']);
        if (http_request::isGet('tabs')) $this->tabs = form_inputEscape::simpleClean($_GET['tabs']);
        if (http_request::isPost('content')) $this->content = $_POST['content'];
        if (http_request::isPost('holder_bg_color')) $this->holder_bg_color = form_inputEscape::simpleClean($_POST['holder_bg_color']);
        if (http_request::isPost('logo_percent')) $this->logo_percent = form_inputEscape::simpleClean($_POST['logo_percent']);
        // --- Image Upload
        if (http_request::isPost('name_img')) $this->name_img = http_url::clean($_POST['name_img']);
        if (http_request::isPost('del_img')) $this->del_img = form_inputEscape::simpleClean($_POST['del_img']);
        if (http_request::isPost('del_holder')) $this->del_holder = form_inputEscape::simpleClean($_POST['del_holder']);
        if (http_request::isPost('del_favicon')) $this->del_favicon = form_inputEscape::simpleClean($_POST['del_favicon']);
        if (http_request::isPost('active_logo')) $this->active_logo = form_inputEscape::simpleClean($_POST['active_logo']);
        if (isset($_FILES['img']["name"])) $this->img = http_url::clean($_FILES['img']["name"]);
        if (isset($_FILES['fav']["name"])) $this->fav = http_url::clean($_FILES['fav']["name"]);
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param array|int|null $id
     * @param string|null $context
     * @param bool|string $assign
     * @param bool $pagination
     * @return mixed
     */
    private function getItems(string $type, $id = null, string $context = null, $assign = true, bool $pagination = false) {
        return $this->data->getItems($type, $id, $context, $assign, $pagination);
    }

    /**
     * scans the directory and returns one files with size
     * @param string $directory
     * @return array
     */
    public function scanDir(string $directory): array {
        $file = [];
        try {
            $it = new DirectoryIterator($directory);
            for($it->rewind(); $it->valid(); $it->next()) {
                if(!$it->isDir() && !$it->isDot() && $it->isFile()){
                    $size = getimagesize($it->getPath().DIRECTORY_SEPARATOR.$it->getFilename());
                    $file['img']['filename'] =  $it->getFilename();
                    $file['img']['width'] =  $size[0];
                    $file['img']['height'] =  $size[1];
                }
            }
        }
        catch (Exception $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('error', 'php', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_VOID);
        }
        return $file;
    }

    /**
     * @param string $directory
     * @return array
     */
    private function scanDirFiles(string $directory): array {
        $directoryFiles = $this->finder->scanDir($this->routingUrl->basePath($directory),'.gitignore');
        $files = [];
        if(is_array($directoryFiles)) {
            foreach ($directoryFiles as $file) {
                $size = getimagesize($this->routingUrl->basePath($directory) . DIRECTORY_SEPARATOR . $file);
                $files[$size[0]]['img']['filename'] = $file;
                $files[$size[0]]['img']['width'] = $size[0];
                $files[$size[0]]['img']['height'] = $size[1];
            }
            ksort($files);
        }
        return $files;
    }

    /**
     * @param string $directory
     * @return void
     */
    private function emptyDir(string $directory) {
        $dirPath = $this->routingUrl->basePath($directory);
        $scanRecursiveDir = $this->finder->scanDir($dirPath);
        if (file_exists($dirPath)) {
            if (!empty($scanRecursiveDir)) {
                foreach ($scanRecursiveDir as $file) {
                    if (file_exists($dirPath . $file)) {
                        $this->makeFiles->remove($dirPath . $file);
                    }
                }
            }
        }
    }

    /**
     * @param array $data
     * @return array
     */
    private function setItemData(array $data): array {
        $arr = [];
        $imgPath = $this->routingUrl->basePath('img/logo');
        $fetchConfig = $this->imagesComponent->getConfigItems('logo','logo');

        foreach ($data as $page) {

            if (!array_key_exists($page['id_logo'], $arr)) {
                $img_pages = pathinfo($page['img_logo']);

                $arr[$page['id_logo']] = [
                    'id_logo' => $page['id_logo'],
                    'img_pages' => $img_pages['filename'],
                ];
                $mimeContent = $this->upload->mimeContentType(['filename'=>$imgPath.DIRECTORY_SEPARATOR.$page['img_logo']]);

                if(!empty($page['img_logo'])) {
                    if(file_exists($imgPath.DIRECTORY_SEPARATOR.$page['img_logo'])){
                        $originalSize = getimagesize($imgPath.DIRECTORY_SEPARATOR.$page['img_logo']);
                        $arr[$page['id_logo']]['imgSrc']['original']['img'] = $page['img_logo'];
                        $arr[$page['id_logo']]['imgSrc']['original']['width'] = $originalSize[0];
                        $arr[$page['id_logo']]['imgSrc']['original']['height'] = $originalSize[1];
                    }
                    foreach ($fetchConfig as $value) {
                        $size = getimagesize($imgPath.DIRECTORY_SEPARATOR.$value['prefix'].'_'.$img_pages['filename']. '@'.$value['width'].'.'.$mimeContent['type']);
                        $arr[$page['id_logo']]['imgSrc'][$value['type']]['img'] = $value['prefix'].'_'.$img_pages['filename']. '@'.$value['width'].'.'.$mimeContent['type'];
                        $arr[$page['id_logo']]['imgSrc'][$value['type']]['width'] = $size[0];
                        $arr[$page['id_logo']]['imgSrc'][$value['type']]['height'] = $size[1];
                    }
                }
                $arr[$page['id_logo']]['active_logo'] = $page['active_logo'];
                $arr[$page['id_logo']]['date_register'] = $page['date_register'];
            }
            $arr[$page['id_logo']]['content'][$page['id_lang']] = [
                'id_lang' => $page['id_lang'],
                'iso_lang' => $page['iso_lang'],
                'alt_logo' => $page['alt_logo'],
                'title_logo' => $page['title_logo']
            ];
        }
        return $arr;
    }

    /**
     * @param string $type
     * @param array $params
     * @return void
     */
    private function add(string $type, array $params = []) {
        switch ($type) {
            case 'logo':
            case 'imgContent':
                parent::insert(['type' => $type], $params);
                break;
        }
    }

    /**
     * @param string $type
     * @param array $params
     * @return void
     */
    private function upd(string $type, array $params = []) {
        switch ($type) {
            case 'logo':
            case 'active':
            case 'imgContent':
            case 'placeholder':
                parent::update(['type' => $type], $params);
                break;
        }
    }
    /**
     * @return array
     */
    public function settingsData(): array {
        //$settingsData = $this->getItems('settings',null,'all',false);
        $settingsData = $this->getItems('placeholder',NULL,'all',false);
        return empty($settingsData) ? [] : array_column($settingsData,'value','name');
    }
    /**
     * @throws Exception
     */
    public function run() {
         if(isset($this->action)) {
             switch ($this->action) {
                 case 'edit':
                     switch ($this->tabs) {
                         case 'logo':
                             if(isset($this->img) || isset($this->name_img)) {
                                 $about =  $this->about->getCompanyData();
                                 $company = !empty($about['name']) ? '-'.http_url::clean($about['name']) : '';
                                 $fetchConfig = $this->imagesComponent->getConfigItems('logo','logo');

                                 $newSuffix = [];
                                 foreach ($fetchConfig as $value) {
                                     $newSuffix[] = '@'.$value['width'];
                                 }

                                 $fetchRootData = $this->getItems('root', NULL,'one',false);
                                 $active_logo = $this->active_logo === 'on' ? 1 : 0;

                                 if(!empty($fetchRootData)) {
                                     $id_page = $fetchRootData['id_logo'];
                                     /*$settings = array(
                                         'name' => $this->name_img !== '' ? $this->name_img : 'logo'.$company,
                                         'edit' => $fetchRootData['img_logo'],
                                         'suffix' => $newSuffix,
                                         'module_img' => 'logo',
                                         'attribute_img' => 'logo',
                                         'original_remove' => false
                                     );
                                     $dirs = array(
                                         'upload_root_dir' => 'img/logo',
                                         'upload_dir' => ''
                                     );*/
                                     $filename = '';
                                     $update = false;

                                     if (isset($this->img)) {
                                         /*$resultUpload = $this->upload->setImageUpload('img', $settings, $dirs, false);
                                         $filename = $resultUpload['file'];*/
                                         $resultUpload = $this->upload->imageUpload(
                                             'logo','logo','img',['logo'],[
                                             'edit' => $fetchRootData['img_logo'],
                                             'name' => $this->name_img !== '' ? $this->name_img : 'logo'.$company,
                                             'suffix' => $newSuffix
                                         ],false);

                                         $filename = $resultUpload['file'];
                                         $update = true;
                                     }
                                     elseif (isset($this->name_img)) {
                                         $img_pages = pathinfo($fetchRootData['img_logo']);
                                         $img_name = $img_pages['filename'];

                                         if ($this->name_img !== $img_name && $this->name_img !== '') {
                                             //$result = $this->upload->renameImages($settings, $dirs);
                                             $result = $this->upload->renameImages('logo','logo',$fetchRootData['img_logo'],$this->name_img !== '' ? $this->name_img : 'logo'.$company,'img',['logo']);
                                             $filename = $result;
                                             $update = true;
                                         }
                                     }

                                     if ($filename !== '' && $update) {
                                         $this->upd('logo',[
                                             'id_logo' => $id_page,
                                             'img_logo' => $filename,
                                             'active_logo' => $active_logo
                                         ]);
                                     }
                                     else {
                                         $this->upd('active',[
                                             'id_logo' => $id_page,
                                             'active_logo' => $active_logo
                                         ]);
                                     }
                                 }
                                 else{
                                     $filename = '';
                                     $update = false;

                                     if (isset($this->img)) {
                                         //$resultUpload = $this->upload->setImageUpload('img', $settings, $dirs, false);
                                         $resultUpload = $this->upload->imageUpload(
                                             'logo','logo','img',['logo'],[
                                             'name' => $this->name_img !== '' ? $this->name_img : 'logo'.$company,
                                             'suffix' => $newSuffix
                                         ],false);
                                         $filename = $resultUpload['file'];
                                         $update = true;
                                     }
                                     elseif (isset($this->name_img)) {
                                         $img_pages = pathinfo($filename);
                                         $img_name = $img_pages['filename'];

                                         if ($this->name_img !== $img_name && $this->name_img !== '') {
                                             //$result = $this->upload->renameImages($settings, $dirs);
                                             $result = $this->upload->renameImages('logo','logo','',$this->name_img !== '' ? $this->name_img : 'logo'.$company,'img',['logo']);
                                             $filename = $result;
                                             $update = true;
                                         }
                                     }

                                     $this->add('logo',[
                                         'img_logo' => $filename,
                                         'active_logo' => $active_logo
                                     ]);
                                     $newData = $this->getItems('root', NULL,'one',false);
                                     $id_page = $newData['id_logo'];
                                 }

                                 if($filename != null) {
                                     $settingsData = $this->settingsData();
                                     $ext = '.jpg';
                                     $dirImgArray = $this->routingUrl->dirUpload('img/social',true);
                                     $logo = $this->imagesComponent->imageManager->make(
                                         $this->routingUrl->basePath('img/logo/') . $filename
                                     );

                                     $percentage = $settingsData['logo_percent'] ?? 50;
                                     $width = ($percentage / 100) * 250;
                                     $height = ($percentage / 100) * 250;

                                     $logo->resize($width, $height, function ($constraint) {
                                         $constraint->aspectRatio();
                                         $constraint->upsize();
                                     });
                                     $logo->save($dirImgArray . 'logo.png');
                                     $image = $this->imagesComponent->imageManager->canvas(250, 250, $settingsData['holder_bg_color']);
                                     $image->save($dirImgArray . 'social' . $ext);
                                     $watermark = $this->imagesComponent->imageManager->make($dirImgArray . 'logo.png');
                                     $image->insert($watermark, 'center');
                                     $image->save($dirImgArray . 'social' . $ext, 80);
                                     if(function_exists('imagewebp')) $image->save($dirImgArray . 'social.webp', 80);
                                     if (file_exists($dirImgArray . 'logo.png')) {
                                         $this->makeFiles->remove($dirImgArray . 'logo.png');
                                     }
                                 }

                                 if($id_page) {
                                     foreach ($this->content as $lang => $content) {
                                         $content['id_lang'] = $lang;
                                         $content['id_logo'] = $id_page;
                                         $content['alt_logo'] = (!empty($content['alt_logo']) ? $content['alt_logo'] : NULL);
                                         $content['title_logo'] = (!empty($content['title_logo']) ? $content['title_logo'] : NULL);

                                         if ($this->getItems('content', array('id_logo' => $id_page, 'id_lang' => $lang),'one',false) != null) {

                                             $this->upd('imgContent', $content);
                                         } else {
                                             $this->add('imgContent', $content);
                                         }
                                     }
                                     $setEditData = $this->getItems('page',array('edit'=>$id_page),'all',false);
                                     $setEditData = $this->setItemData($setEditData);
                                     $this->template->assign('page',$setEditData[$id_page]);
                                     $display = $this->template->fetch('logo/brick/img.tpl');
                                     $this->message->json_post_response(true, 'update',$display);
                                 }
                             }
                             break;
                         case 'placeholder':
                             if(isset($this->holder_bg_color) && isset($this->logo_percent)){
                                 $this->upd('placeholder',[
                                     'holder_bg_color' => $this->holder_bg_color,
                                     'logo_percent' => $this->logo_percent
                                 ]);

                                 $fetchRootData = $this->getItems('root', NULL, 'one', false);
                                 $defaultDir = $this->routingUrl->basePath('img/default/');
                                 $directories = $this->finder->scanRecursiveDir($defaultDir);
                                 $fetchConfig = $this->imagesComponent->getConfigImages();

                                 if(!empty($fetchRootData)) {
                                     if(!empty($directories)) {
                                         foreach ($directories as $directory) {
                                             $setFiles = $this->finder->scanDir($defaultDir.$directory,['.htaccess','.gitignore']);
                                             if(!empty($setFiles)){
                                                 foreach($setFiles as $file){
                                                     try {
                                                         $this->makeFiles->remove($defaultDir.$directory.'/'.$file);
                                                     }
                                                     catch(Exception $e) {
                                                         if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
                                                         $this->logger->log('php','clearcache',$e->getMessage(),$this->logger::LOG_MONTH);
                                                     }
                                                 }
                                             }
                                         }
                                     }

                                     $color = $this->holder_bg_color === 'transparent' ? 'rgba(0, 0, 0, 0)' : $this->holder_bg_color;
                                     $ext = $this->holder_bg_color === 'transparent' ? '.png' : '.jpg';
                                     $logo = $this->imagesComponent->imageManager->make(
                                         $this->routingUrl->basePath('img/logo/') . $fetchRootData['img_logo']
                                     );
                                     $percentage = $this->logo_percent ?? 50;

                                     foreach ($fetchConfig as $module => $attributes) {
                                         if($module !== 'logo') {
                                             foreach ($attributes as $attribute => $images) {
                                                 $dirImgArray = $this->routingUrl->dirUpload('img/default/'.($attribute == $module ? $attribute : $module.'/'.$attribute),true);
                                                 foreach ($images as $imageConfig) {
                                                     $width = ($percentage / 100) * $imageConfig['width'];
                                                     $height = ($percentage / 100) * $imageConfig['height'];
                                                     $logo->resize($width, $height, function ($constraint) {
                                                         $constraint->aspectRatio();
                                                         $constraint->upsize();
                                                     });
                                                     $logo->save($dirImgArray . 'logo.png');
                                                     $image = $this->imagesComponent->imageManager->canvas($imageConfig['width'], $imageConfig['height'], $color);
                                                     // create a new Image instance for inserting
                                                     $watermark = $this->imagesComponent->imageManager->make($dirImgArray . 'logo.png');
                                                     $image->insert($watermark, 'center');
                                                     $image->save($dirImgArray . $imageConfig['prefix'].'_'.'default'.$ext);
                                                     if(function_exists('imagewebp')) $image->save($dirImgArray . $imageConfig['prefix'].'_'.'default.webp', 80);
                                                     //$image->save($dirImgArray . 'default'.$ext, 80);
                                                     //if(function_exists('imagewebp')) $image->save($dirImgArray . 'default.webp', 80);
                                                     if(file_exists($dirImgArray . 'logo.png')) $this->makeFiles->remove($dirImgArray . 'logo.png');
                                                 }
                                             }
                                         }
                                     }
                                 }

                                 if(!empty($directories)) {
                                     $this->template->assign('holders',$fetchConfig);
                                     $display = $this->template->fetch('logo/brick/holder.tpl');
                                     $this->message->json_post_response(true, 'update',$display);
                                 }
                             }
                             break;
                         case 'favicon':
                             if(isset($this->fav)){
                                 $makeFiles = new filesystem_makefile();
                                 $dirImg = $this->routingUrl->dirUpload('img/favicon',true);
                                 if (file_exists($dirImg . 'fav.png') || file_exists($dirImg . 'fav.jpg')) {
                                     $makeFiles->remove([$dirImg . 'fav.png',$dirImg . 'favicon.png']);
                                 }
                                 $resultUpload = $this->upload->getUploadImg(
                                     $_FILES['fav'],
                                     $this->routingUrl->dirUpload('img/favicon',false),
                                     false
                                 );

                                 if($resultUpload['status']) {
                                     $makeFiles->rename([
                                         'origin' => $dirImg . $this->fav,
                                         'target' => $dirImg . 'fav' . '.'.$resultUpload['mimecontent']['type']
                                     ]);
                                     try {
                                         $thumb = $this->imagesComponent->imageManager->make($dirImg . 'fav' . '.'.$resultUpload['mimecontent']['type']);
                                         $thumb->resize(16, 16);
                                         $thumb->save($dirImg . 'favicon'. '.'.'png',80);
                                     }
                                     catch (Exception $e) {
                                         if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
                                         $this->logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
                                     }

                                     $source = $dirImg . 'fav' . '.'.$resultUpload['mimecontent']['type'];
                                     $destination = $dirImg . '/favicon.ico';

                                     $sizes = [
                                         [16, 16]
                                     ];

                                     $ico_lib = new PHP_ICO( $source, $sizes );
                                     $ico_lib->save_ico( $destination );

                                     $dirTouchImg = $this->routingUrl->dirUpload('img/touch',true);
                                     $touchSize = [
                                         [512, 512],
                                         [192, 192],
                                         [168, 168],
                                         [144, 144],
                                         [96, 96],
                                         [72, 72],
                                         [48, 48]
                                     ];
                                     try {
                                         $thumb = $this->imagesComponent->imageManager->make($dirImg . 'fav' . '.'.$resultUpload['mimecontent']['type']);
                                         foreach($touchSize as $value){
                                             $thumb->resize($value[0], $value[1]);
                                             $thumb->save($dirTouchImg . 'homescreen'.$value[0]. '.'.'png',80);
                                         }
                                     }
                                     catch (Exception $e) {
                                         if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
                                         $this->logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
                                     }

                                     $favicon = $this->scanDirFiles('img/favicon/');
                                     $touch = $this->scanDirFiles('img/touch/');
                                     $this->template->assign('favicon',$favicon);
                                     $this->template->assign('homescreen',$touch);
                                     $display = $this->template->fetch('logo/brick/favicon.tpl');
                                     $this->message->json_post_response(true, 'update', $display);
                                 }
                                 else {
                                     if (file_exists($dirImg . $this->fav)) {
                                         $makeFiles->remove(array($dirImg . $this->fav));
                                     }
                                     else {
                                         throw new Exception('file: ' . $this->fav . ' is not found');
                                     }
                                 }
                             }
                             break;
                     }
                     break;
                 case 'delete':
                     switch ($this->tabs) {
                         case 'logo':
                             if(isset($this->del_img)) {
                                 $fetchRootData = $this->getItems('root', NULL, 'one', false);
                                 $id_page = $fetchRootData['id_logo'];
                                 $this->upd('logo',[
                                     'id_logo' => $id_page,
                                     'img_logo' => NULL,
                                     'active_logo' => 0
                                 ]);
                                 $setEditData = $this->getItems('page', ['edit' => $id_page], 'all', false);
                                 $setEditData = $this->setItemData($setEditData);
                                 $this->emptyDir('img/logo/');

                                 // Remove social image
								 $this->emptyDir('img/social/');
                                 //$socialPath = $this->routingUrl->basePath('img/social/');
                                 //if (file_exists($socialPath.'social.jpg')) $this->makeFiles->remove($socialPath.'social.jpg');

                                 $this->template->assign('page', $setEditData[$id_page]);
                                 $display = $this->template->fetch('logo/brick/img.tpl');
                                 $this->message->json_post_response(true, 'update', $display);
                             }
                             break;
                         case 'placeholder':
                             if(isset($this->del_holder)) {
                                 $this->emptyDir('img/default/');
                                 $this->template->assign('holder',[]);
                                 $display = $this->template->fetch('logo/brick/holder.tpl');
                                 $this->message->json_post_response(true, 'update',$display);
                             }
                             break;
                         case 'favicon':
                             if(isset($this->del_favicon)) {
                                 $this->emptyDir('img/favicon/');
                                 $this->emptyDir('img/touch/');
                                 $this->template->assign('favicon',[]);
                                 $this->template->assign('homescreen',[]);
                                 $display = $this->template->fetch('logo/brick/favicon.tpl');
                                 $this->message->json_post_response(true, 'update', $display);
                             }
                             break;
                     }
                     break;
             }
         }
		 else {
             $fetchRootData = $this->getItems('root', NULL,'one',false);
             $dirImgArray = $this->routingUrl->basePath('img/default/');
             $scanRecursiveDir = $this->finder->scanRecursiveDir($dirImgArray);
             if(is_array($scanRecursiveDir)) {
				 $fetchConfig = $this->imagesComponent->getConfigImages();
                 $this->template->assign('holder',$fetchConfig);
             }
             $favicon = $this->scanDirFiles('img/favicon/');
             $touch = $this->scanDirFiles('img/touch/');
             $this->template->assign('favicon',$favicon);
             $this->template->assign('homescreen',$touch);
             if(!empty($fetchRootData)) {
                 $id_page = $fetchRootData['id_logo'];
                 $setEditData = $this->getItems('page', array('edit'=>$id_page),'all',false);
                 $setEditData = $this->setItemData($setEditData);
                 $this->template->assign('page',$setEditData[$id_page]);
             }
             $this->modelLanguage->getLanguage();
             $this->template->assign('placeholder',$this->settingsData());
             $this->template->display('logo/index.tpl');
         }
    }
}