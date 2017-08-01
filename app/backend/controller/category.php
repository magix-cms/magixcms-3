<?php
class backend_controller_category extends backend_db_category {
    public $edit, $action, $tabs, $search;
    protected $message, $template, $header, $data, $modelLanguage, $collectionLanguage, $order, $upload, $config, $imagesComponent;

    public $id_cat,$parent_id,$content,$category,$img;

    public function __construct()
    {
        $this->template = new backend_model_template();
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $formClean = new form_inputEscape();
        $this->modelLanguage = new backend_model_language($this->template);
        $this->collectionLanguage = new component_collections_language();
        $this->upload = new component_files_upload();
        $this->imagesComponent = new component_files_images($this->template);
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

        // --- Search
        if (http_request::isGet('search')) {
            $this->search = $formClean->arrayClean($_GET['search']);
        }
        // --- ADD or EDIT
        if (http_request::isPost('id')) {
            $this->id_cat = $formClean->simpleClean($_POST['id']);
        }
        if (http_request::isPost('parent_id')) {
            $this->parent_id = $formClean->simpleClean($_POST['parent_id']);
        }

        if (http_request::isPost('content')) {
            $array = $_POST['content'];
            foreach($array as $key => $arr) {
                foreach($arr as $k => $v) {
                    $array[$key][$k] = ($k == 'content_cat') ? $formClean->cleanQuote($v) : $formClean->simpleClean($v);
                }
            }
            $this->content = $array;
        }
        // --- Image Upload
        if(isset($_FILES['img']["name"])){
            $this->img = http_url::clean($_FILES['img']["name"]);
        }
        // --- Recursive Actions
        if (http_request::isGet('category')) {
            $this->category = $formClean->arrayClean($_GET['category']);
        }

        # ORDER PAGE
        if(http_request::isPost('category')){
            $this->order = $formClean->arrayClean($_POST['category']);
        }
    }
    /**
     * Assign data to the defined variable or return the data
     * @param string $context
     * @param string $type
     * @param string|int|null $id
     * @return mixed
     */
    private function getItems($type, $id = null, $context = null) {
        return $this->data->getItems($type, $id, $context);
    }

    /**
     * @return array
     */
    private function setItemsData(){
        $defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'unique','type'=>'default'));

        $arr = array();
        if(isset($this->edit)){
            $data = parent::fetchData(
                array('context'=>'all','type'=>'pagesChild','search'=>$this->search),
                array(':edit'=>$this->edit)
            );
        }else{
            $data = parent::fetchData(
                array('context'=>'all','type'=>'pages','search'=>$this->search),
                array(':default_lang'=>$defaultLanguage['id_lang'])
            );
        }

        return $data;
    }
    /**
     * @param $data
     * @return array
     */
    private function setItemData($data){

        $imgPath = $this->upload->imgBasePath('upload/catalog/c');
        $arr = array();
        $conf = array();
        $fetchConfig = $this->imagesComponent->getConfigItems(array('module_img'=>'catalog','attribute_img'=>'category'));
        $imgPrefix = $this->imagesComponent->prefix();

        foreach ($data as $page) {

            $publicUrl = !empty($page['url_cat']) ? '/'.$page['iso_lang'].'/'.$page['id_cat'].'-'.$page['url_cat'].'/' : '';
            if (!array_key_exists($page['id_cat'], $arr)) {
                $arr[$page['id_cat']] = array();
                $arr[$page['id_cat']]['id_cat'] = $page['id_cat'];
                $arr[$page['id_cat']]['id_parent'] = $page['id_parent'];
                if($page['img_cat'] != null) {
                    $originalSize = getimagesize($imgPath.DIRECTORY_SEPARATOR.$page['id_cat'].DIRECTORY_SEPARATOR.$page['img_cat']);
                    $arr[$page['id_cat']]['imgSrc']['original']['img'] = $page['img_cat'];
                    $arr[$page['id_cat']]['imgSrc']['original']['width'] = $originalSize[0];
                    $arr[$page['id_cat']]['imgSrc']['original']['height'] = $originalSize[1];
                    foreach ($fetchConfig as $key => $value) {
                        $size = getimagesize($imgPath.DIRECTORY_SEPARATOR.$page['id_cat'].DIRECTORY_SEPARATOR.$imgPrefix[$value['type_img']] . $page['img_cat']);
                        $arr[$page['id_cat']]['imgSrc'][$value['type_img']]['img'] = $imgPrefix[$value['type_img']] . $page['img_cat'];
                        $arr[$page['id_cat']]['imgSrc'][$value['type_img']]['width'] = $size[0];
                        $arr[$page['id_cat']]['imgSrc'][$value['type_img']]['height'] = $size[1];
                    }
                }
                $arr[$page['id_cat']]['date_register'] = $page['date_register'];
            }
            $arr[$page['id_cat']]['content'][$page['id_lang']] = array(
                'id_lang'           => $page['id_lang'],
                'iso_lang'          => $page['iso_lang'],
                'name_cat'          => $page['name_cat'],
                'url_cat'           => $page['url_cat'],
                'content_cat'       => $page['content_cat'],
                'published_cat'     => $page['published_cat'],
                'public_url'        => $publicUrl
            );
        }
        return $arr;
    }
    /**
     * Mise a jour des données
     * @param $data
     */
    private function upd($data)
    {
        switch ($data['type']) {
            case 'content':
                parent::update(
                    array(
                        'type'=>$data['type']
                    ),array(
                        'id_lang'	       => $data['id_lang'],
                        'id_cat'	       => $data['id_cat'],
                        'name_cat'       => $data['name_cat'],
                        'url_cat'        => $data['url_cat'],
                        'content_cat'    => $data['content_cat'],
                        'published_cat'  => $data['published_cat']
                    )
                );
                break;
            case 'img':
                parent::update(
                    array(
                        'type'=>$data['type']
                    ),array(
                        'id_cat'	       => $data['id_cat'],
                        'img_cat'        => $data['img_cat']
                    )
                );
                break;
            case 'order':
                $p = $this->order;
                for ($i = 0; $i < count($p); $i++) {
                    parent::update(
                        array(
                            'type'=>$data['type']
                        ),array(
                            'id_cat'       => $p[$i],
                            'order_cat'    => $i
                        )
                    );
                }
                break;
        }
    }
    private function save(){
        if (isset($this->content) && isset($this->id_cat)) {
            foreach ($this->content as $lang => $content) {
                $content['published_cat'] = (!isset($content['published_cat']) ? 0 : 1);
                if (empty($content['url_cat'])) {
                    $content['url_cat'] = http_url::clean($content['name_cat'],
                        array(
                            'dot' => false,
                            'ampersand' => 'strict',
                            'cspec' => '', 'rspec' => ''
                        )
                    );
                }
                $checkLangData = parent::fetchData(
                    array('context'=>'unique','type'=>'content'),
                    array('id_cat'=>$this->id_cat,'id_lang'=>$lang)
                );
                // Check language page content
                if($checkLangData!= null){
                    $this->upd(array(
                        'type'            => 'content',
                        'id_lang'         => $lang,
                        'id_cat'          => $this->id_cat,
                        'name_cat'        => $content['name_cat'],
                        'url_cat'         => $content['url_cat'],
                        'content_cat'     => $content['content_cat'],
                        'published_cat'   => $content['published_cat']
                    ));
                }else{
                    parent::insert(
                        array(
                            'type' => 'newContent',
                        ),
                        array(
                            'id_lang'         => $lang,
                            'id_cat'          => $this->id_cat,
                            'name_cat'        => $content['name_cat'],
                            'url_cat'         => $content['url_cat'],
                            'content_cat'     => $content['content_cat'],
                            'published_cat'   => $content['published_cat']
                        )
                    );
                }

                $setEditData = parent::fetchData(
                    array('context'=>'all','type'=>'page'),
                    array('edit'=>$this->id_cat)
                );
                $setEditData = $this->setItemData($setEditData);
                $extendData[$lang] = $setEditData[$this->id_cat]['content'][$lang]['public_url'];
            }

            $this->header->set_json_headers();
            $this->message->json_post_response(true, 'update', array('result'=>$this->id_cat,'extend'=>$extendData));

        }else if (isset($this->content) && !isset($this->id_cat)) {
            if(empty($this->parent_id)){
                $parentId = NULL;
            }else{
                $parentId = $this->parent_id;
            }

            parent::insert(
                array(
                    'type'=>'newPages'
                ),array(
                    'id_parent'     =>  $parentId
                )
            );

            $setNewData = parent::fetchData(
                array('context' => 'unique', 'type' => 'root')
            );

            if ($setNewData['id_cat']) {
                foreach ($this->content as $lang => $content) {

                    $content['published_cat'] = (!isset($content['published_cat']) ? 0 : 1);
                    $url_cat = http_url::clean($content['name_cat'],
                        array(
                            'dot' => false,
                            'ampersand' => 'strict',
                            'cspec' => '', 'rspec' => ''
                        )
                    );

                    parent::insert(
                        array(
                            'type' => 'newContent',
                        ),
                        array(
                            'id_lang'           => $lang,
                            'id_cat'          => $setNewData['id_cat'],
                            'name_cat'        => $content['name_cat'],
                            'url_cat'         => $url_cat,
                            'content_cat'     => $content['content_cat'],
                            'published_cat'   => $content['published_cat']
                        )
                    );
                }

                $this->header->set_json_headers();
                $this->message->json_post_response(true,'add_redirect');
            }
        }else  if(isset($this->img)){
            $data = parent::fetchData(array('context'=>'unique','type'=>'page'),array('id_cat'=>$this->id_cat));
            $resultUpload = $this->upload->setImageUpload(
                'img',
                array(
                    'name'              => filter_rsa::randMicroUI(),
                    'edit'              => $data['img_cat'],
                    'prefix'            => array('s_','m_','l_'),
                    'module_img'        => 'catalog',
                    'attribute_img'     => 'category',
                    'original_remove'   => false
                ),
                array(
                    'upload_root_dir'      => 'upload/catalog/c', //string
                    'upload_dir'           => $this->id_cat //string ou array
                ),
                false
            );

            $this->upd(array(
                'type'           => 'img',
                'id_cat'         => $this->id_cat,
                'img_cat'        => $resultUpload['file']
            ));
            $this->header->set_json_headers();

            $setEditData = parent::fetchData(
                array('context'=>'all','type'=>'page'),
                array('edit'=>$this->id_cat)
            );
            $setEditData = $this->setItemData($setEditData);
            $this->template->assign('page',$setEditData[$this->id_cat]);
            $display = $this->template->fetch('catalog/category/brick/img.tpl');

            $this->message->json_post_response(true, 'update',$display);
        }
    }

    /**
     * Insertion de données
     * @param $data
     */
    private function del($data){
        switch($data['type']){
            case 'delPages':
                parent::delete(
                    array(
                        'type'      =>    $data['type']
                    ),
                    $data['data']
                );
                $this->header->set_json_headers();
                $this->message->json_post_response(true,'delete',$data['data']);
                break;
        }
    }
    /**
     * 
     */
    public function run(){
        if(isset($this->action)) {
            switch ($this->action) {
                case 'add':
                    if(isset($this->content)){
                        $this->save();
                    }else{
                        $defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'unique','type'=>'default'));
                        $data = parent::fetchData(
                            array('context'=>'all','type'=>'pagesSelect'),
                            array(':default_lang'=>$defaultLanguage['id_lang'])
                        );
                        $this->template->assign('pagesSelect',$data);
                        $this->modelLanguage->getLanguage();
                        $this->template->display('catalog/category/add.tpl');
                    }

                    break;
                case 'edit':
                    if (isset($this->id_cat)) {
                        $this->save();
                    } else {
                        $this->modelLanguage->getLanguage();
                        $setEditData = parent::fetchData(
                            array('context' => 'all', 'type' => 'page'),
                            array('edit' => $this->edit)
                        );
                        $setEditData = $this->setItemData($setEditData);
                        $this->template->assign('page', $setEditData[$this->edit]);
                        //$pages = $this->setItemsData();
                        //$this->template->assign('pages', $pages);

                        $assign = array(
                            'id_cat',
                            'name_cat' => ['title' => 'name'],
                            'img_cat' => ['type' => 'bin', 'input' => null, 'class' => ''],
                            'date_register'
                        );
                        $this->data->getScheme(array('mc_catalog_cat', 'mc_catalog_cat_content'), array('id_cat', 'name_cat', 'img_cat', 'date_register'), $assign);
                        $pageChild = $this->getItems('pagesChild', $this->edit, 'all');

                        if (isset($this->search)) {
                            $this->template->assign('ajax_form', true);
                            $this->template->assign('data', $pageChild);
                            $this->template->assign('section', 'pages');
                            $this->template->assign('idcolumn', 'id_cat');
                            $this->template->assign('controller', 'category');
                            $this->template->assign('readonly', array());
                            $this->template->assign('cClass', 'backend_controller_category');
                            $display = $this->template->fetch('section/form/loop/rows-2.tpl');
                            $this->header->set_json_headers();
                            $this->message->json_post_response(true, '', $display);
                        } else {
                            $this->template->display('catalog/category/edit.tpl');
                        }
                    }
                    break;
                case 'order':
                    if (isset($this->order)) {
                        $this->upd(
                            array(
                                'type' => 'order'
                            )
                        );
                    }
                    break;
                case 'delete':
                    if(isset($this->id_cat)) {
                        $this->del(
                            array(
                                'type'=>'delPages',
                                'data'=>array(
                                    'id' => $this->id_cat
                                )
                            )
                        );
                    }
                    break;
            }
        }else {

            $this->modelLanguage->getLanguage();
            $defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'unique', 'type' => 'default'));
            $this->getItems('pages', array(':default_lang' => $defaultLanguage['id_lang']), 'all');
            $assign = array(
                'id_cat',
                'name_cat' => ['title' => 'name'],
                'img_cat' => ['type' => 'bin', 'input' => null, 'class' => ''],
                'content_cat' => ['class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null],
                'date_register'
            );
            if (isset($this->search)) {
                $search = $this->search;
                $search = array_filter($search);

                if (is_array($search) && !empty($search)) {
                    $assign = array(
                        'id_cat',
                        'name_cat' => ['title' => 'name'],
                        'img_cat' => ['type' => 'bin', 'input' => null, 'class' => ''],
                        'parent_cat' => ['col' => 'name_cat', 'title' => 'name'],
                        'content_cat' => ['type' => 'bin', 'input' => null],
                        'date_register'
                    );
                }
            }
            $this->data->getScheme(array('mc_catalog_cat', 'mc_catalog_cat_content'), array('id_cat', 'img_cat', 'name_cat', 'content_cat', 'date_register'), $assign);
            $this->template->display('catalog/category/index.tpl');
        }
    }
}
?>