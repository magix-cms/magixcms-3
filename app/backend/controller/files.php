<?php
class backend_controller_files extends backend_db_files{
    public $edit, $action, $tabs;
    public $id_config_img, $module_img,$attribute_img,$width_img,$height_img,$type_img,$resize_img;
    protected $message, $template, $header, $data,$imagesComponent, $DBpages,$DBnews,$DBcategory,$DBproduct, $configCollection,$modelPlugins;
    public $attr_name,$module_name;

	/**
	 * backend_controller_files constructor.
	 * @param stdClass $t
	 */
    public function __construct($t = null)
    {
        $this->template = $t ? $t : new backend_model_template;
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $formClean = new form_inputEscape();
        $this->configCollection = new component_collections_config();
        $this->imagesComponent = new component_files_images($this->template);
        $this->DBpages = new backend_db_pages();
        $this->DBnews = new backend_db_news();
        $this->DBcategory = new backend_db_category();
        $this->DBproduct = new backend_db_product();
        $this->modelPlugins = new backend_model_plugins();

        // --- GET
        if (http_request::isGet('edit')) {
            $this->edit = $formClean->numeric($_GET['edit']);
        }
        if (http_request::isGet('action')) {
            $this->action = $formClean->simpleClean($_GET['action']);
        } elseif (http_request::isPost('action')) {
            $this->action = $formClean->simpleClean($_POST['action']);
        }
        if (http_request::isGet('tabs')) {
            $this->tabs = $formClean->simpleClean($_GET['tabs']);
        }

        // --- ADD or EDIT
        if (http_request::isPost('id')) {
            $this->id_config_img = $formClean->simpleClean($_POST['id']);
        }
        // Config IMG
        if (http_request::isPost('module_img')) {
            $this->module_img = $formClean->simpleClean($_POST['module_img']);
        }
        if (http_request::isPost('attribute_img')) {
            $this->attribute_img = $formClean->simpleClean($_POST['attribute_img']);
        }
        if (http_request::isPost('width_img')) {
            $this->width_img = $formClean->simpleClean($_POST['width_img']);
        }
        if (http_request::isPost('height_img')) {
            $this->height_img = $formClean->simpleClean($_POST['height_img']);
        }
        if (http_request::isPost('type_img')) {
            $this->type_img = $formClean->simpleClean($_POST['type_img']);
        }
        if (http_request::isPost('resize_img')) {
            $this->resize_img = $formClean->simpleClean($_POST['resize_img']);
        }
        // Thumbnail Manager
        if (http_request::isPost('attr_name')) {
            $this->attr_name = $formClean->simpleClean($_POST['attr_name']);
        }
        if (http_request::isPost('module_name')) {
            $this->module_name = $formClean->simpleClean($_POST['module_name']);
        }
    }

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param string|int|null $id
	 * @param string $context
	 * @param boolean $assign
	 * @return mixed
	 */
	private function getItems($type, $id = null, $context = null, $assign = true) {
		return $this->data->getItems($type, $id, $context, $assign);
	}

    /**
     * Save Data
     */
    private function save(){

        if(isset($this->id_config_img)){
            parent::update(
                array('type'=>'resize'),
                array(
                    'id_config_img'    => $this->id_config_img,
                    'module_img'       => $this->module_img,
                    'attribute_img'    => $this->attribute_img,
                    'width_img'        => $this->width_img,
                    'height_img'       => $this->height_img,
                    'type_img'         => $this->type_img,
                    'resize_img'       => $this->resize_img
                )
            );
            $this->message->json_post_response(true,'update',$this->id_config_img);
        }else{

            parent::insert(
                array('type'=>'newResize'),
                array(
                    'module_img'       => $this->module_img,
                    'attribute_img'    => $this->attribute_img,
                    'width_img'        => $this->width_img,
                    'height_img'       => $this->height_img,
                    'type_img'         => $this->type_img,
                    'resize_img'       => $this->resize_img
                )
            );
            $this->message->json_post_response(true,'add_redirect');

        }
    }

    /**
     * Insertion de données
     * @param $data
     */
    private function del($data){
        switch($data['type']){
            case 'delResize':
                parent::delete(
                    array(
                        'type'      =>    $data['type']
                    ),
                    $data['data']
                );
                $this->message->json_post_response(true,'delete',$data['data']);
                break;
        }
    }


    public function run(){
        if(isset($this->action)) {
            switch ($this->action) {
                case 'add':
                    if(isset($this->module_img) && isset($this->attribute_img)){
                        $this->save();
                    }else{
                        $module = $this->imagesComponent->module();
                        $this->template->assign('module',$module);
                        $type = $this->imagesComponent->type();
                        $this->template->assign('type',$type);
                        $resize = $this->imagesComponent->resize();
                        $this->template->assign('resize',$resize);
                        $this->template->display('files/add.tpl');
                    }
                    break;

                case 'edit':
                    if(isset($this->id_config_img)){
                        $this->save();
                    }elseif(isset($this->module_name)){
                        switch($this->module_name){
                            case 'pages':
                                $fetchImg = $this->DBpages->fetchData(array('context'=>'all','type'=>'img'));
                                $this->imagesComponent->getThumbnailItems(array(
                                    'type'              => $this->module_name,
                                    'upload_root_dir'   => 'upload/pages',
                                    'module_img'        => $this->module_name,
                                    'attribute_img'     => 'page',
                                    'id'                =>'id_pages',
                                    'img'               =>'img_pages',
                                    'webp'              => true
                                ),
                                    $fetchImg
                                );
                                break;
                            case 'news':
                                $fetchImg = $this->DBnews->fetchData(array('context'=>'all','type'=>'img'));
                                $this->imagesComponent->getThumbnailItems(array(
                                    'type'              => $this->module_name,
                                    'upload_root_dir'   => 'upload/news',
                                    'module_img'        => $this->module_name,
                                    'attribute_img'     => 'news',
                                    'id'                =>'id_news',
                                    'img'               =>'img_news',
                                    'webp'              => true
                                ),
                                    $fetchImg
                                );
                                break;
                            case 'catalog':
                                if(isset($this->attr_name) && !empty($this->attr_name)){
                                    switch($this->attr_name){
                                        case 'category':
                                            $fetchImg = $this->DBcategory->fetchData(array('context'=>'all','type'=>'img'));
                                            $this->imagesComponent->getThumbnailItems(array(
                                                'type'              => $this->module_name,
                                                'upload_root_dir'   => 'upload/catalog/c',
                                                'module_img'        => $this->module_name,
                                                'attribute_img'     => 'category',
                                                'id'                =>'id_cat',
                                                'img'               =>'img_cat',
                                                'webp'              => true
                                            ),
                                                $fetchImg
                                            );
                                            break;
                                        case 'product':
                                            $fetchImg = $this->DBproduct->fetchData(array('context' => 'all', 'type' => 'imagesAll'));
                                            $this->imagesComponent->getThumbnailItems(array(
                                                'type'              => $this->module_name,
                                                'upload_root_dir'   => 'upload/catalog/p',
                                                'module_img'        => $this->module_name,
                                                'attribute_img'     => 'product',
                                                'id'                =>'id_product',
                                                'img'               =>'name_img',
                                                'webp'              => true
                                            ),
                                                $fetchImg
                                            );
                                    }
                                }
                                break;
                            default:
                                    //preg_grep('!^car_!', $array);
                                    $plugin = 'plugins_' . $this->module_name . '_admin';
                                    if (class_exists($plugin)) {
                                        //Si la méthode run existe on ajoute le plugin dans le menu
                                        if (method_exists($plugin, 'getItemsImages')) {
                                            $class = new $plugin();
                                            $fetchImg = $class->getItemsImages();
                                            $this->imagesComponent->getThumbnailItems(array(
                                                'type'              => $this->module_name,
                                                'upload_root_dir'   => 'upload/'.$this->module_name,
                                                'module_img'        => 'plugins',
                                                'attribute_img'     => $this->module_name,
                                                'id'                =>'id',
                                                'img'               =>'img',
                                                'webp'              => true
                                            ),
                                                $fetchImg
                                            );
                                        }
                                    }
                                    break;
                        }
                    }else{
                        $this->getItems('size',$this->edit);
                        $module = $this->imagesComponent->module();
                        $this->template->assign('module',$module);
                        $type = $this->imagesComponent->type();
                        $this->template->assign('type',$type);
                        $resize = $this->imagesComponent->resize();
                        $this->template->assign('resize',$resize);
                        $this->template->display('files/edit.tpl');
                    }
                    break;

                case 'delete':
                    if(isset($this->id_config_img)) {
                        $this->del(
                            array(
                                'type'=>'delResize',
                                'data'=>array(
                                    'id' => $this->id_config_img
                                )
                            )
                        );
                    }
                    break;
            }
        }else{
            $this->getItems('sizes');

			$this->data->getScheme(array('mc_config_img'),array('id_config_img','module_img','attribute_img','width_img','height_img','type_img','resize_img'));

            $config = $this->configCollection->fetchData(array('context'=>'all','type'=>'config'));
            $plugins = $this->modelPlugins->getItems(array('type'=>'thumbnail'));
            if($plugins != NULL) {
                foreach ($plugins as $items) {
                    $config[]['attr_name'] = $items['name'];
                }
            }

            $this->template->assign('setConfig',$config);
            $this->template->display('files/index.tpl');
        }
    }
}
?>