<?php
class backend_controller_news extends backend_db_news{
    public $edit, $action, $tabs, $search;
    protected $message, $template, $header, $data, $modelLanguage, $collectionLanguage, $order, $upload, $config, $imagesComponent;
    public $id_news,$content,$news,$img,$id_lang,$name_tag;

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
            $this->id_news = $formClean->simpleClean($_POST['id']);
        }

        if (http_request::isPost('content')) {
            $array = $_POST['content'];
            foreach($array as $key => $arr) {
                foreach($arr as $k => $v) {
                    $array[$key][$k] = ($k == 'content_news') ? $formClean->cleanQuote($v) : $formClean->simpleClean($v);
                }
            }
            $this->content = $array;
        }
        // --- Image Upload
        if(isset($_FILES['img']["name"])){
            $this->img = http_url::clean($_FILES['img']["name"]);
        }
        // --- Recursive Actions
        if (http_request::isGet('news')) {
            $this->news = $formClean->arrayClean($_GET['news']);
        }

        # ORDER PAGE
        if(http_request::isPost('news')){
            $this->order = $formClean->arrayClean($_POST['news']);
        }

        # REMOVE TAG
        if (http_request::isPost('id_lang')) {
            $this->id_lang = $formClean->simpleClean($_POST['id_lang']);
        }
        if (http_request::isPost('name_tag')) {
            $this->name_tag = $formClean->simpleClean($_POST['name_tag']);
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
     * Return Last pages (Dashboard)
     */
    public function getItemsNews(){
        $this->modelLanguage->getLanguage();
        $defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
        $this->getItems('lastNews',array(':default_lang'=>$defaultLanguage['id_lang']),'all');
    }
    /**
     * @param $data
     * @return array
     */
    private function setItemData($data){
        $imgPath = $this->upload->imgBasePath('upload/news');
        $arr = array();
        $conf = array();
        $fetchConfig = $this->imagesComponent->getConfigItems(array('module_img'=>'news','attribute_img'=>'news'));
        $imgPrefix = $this->imagesComponent->prefix();

        foreach ($data as $page) {
            $dateFormat = new date_dateformat();
            $datePublish = !empty($page['date_publish']) ? $dateFormat->dateToDefaultFormat($page['date_publish']) : $dateFormat->dateToDefaultFormat();
            $publicUrl = !empty($page['url_news']) ? '/'.$page['iso_lang'].'/news/'.$datePublish.'/'.$page['id_news'].'-'.$page['url_news'].'/' : '';
            if (!array_key_exists($page['id_news'], $arr)) {
                $arr[$page['id_news']] = array();
                $arr[$page['id_news']]['id_news'] = $page['id_news'];
                if($page['img_news'] != null) {
                    $originalSize = getimagesize($imgPath.DIRECTORY_SEPARATOR.$page['id_news'].DIRECTORY_SEPARATOR.$page['img_news']);
                    $arr[$page['id_news']]['imgSrc']['original']['img'] = $page['img_news'];
                    $arr[$page['id_news']]['imgSrc']['original']['width'] = $originalSize[0];
                    $arr[$page['id_news']]['imgSrc']['original']['height'] = $originalSize[1];
                    foreach ($fetchConfig as $key => $value) {
                        $size = getimagesize($imgPath.DIRECTORY_SEPARATOR.$page['id_news'].DIRECTORY_SEPARATOR.$imgPrefix[$value['type_img']] . $page['img_news']);
                        $arr[$page['id_news']]['imgSrc'][$value['type_img']]['img'] = $imgPrefix[$value['type_img']] . $page['img_news'];
                        $arr[$page['id_news']]['imgSrc'][$value['type_img']]['width'] = $size[0];
                        $arr[$page['id_news']]['imgSrc'][$value['type_img']]['height'] = $size[1];
                    }
                }
                //$arr[$page['id_news']]['menu_news'] = $page['menu_news'];
                $arr[$page['id_news']]['date_register'] = $page['date_register'];
            }
            $tagData = parent::fetchData(
                array('context'=>'all','type'=>'tags'),
                array('id_lang'=>$page['id_lang'])
            );

            if($tagData != null){
                $newArrayTags = array();
                foreach($tagData as $item){
                    $newArrayTags[]=$item['name_tag'];
                }
                $tags = implode(',',$newArrayTags);
            }else{
                $tags = '';
            }

            $arr[$page['id_news']]['content'][$page['id_lang']] = array(
                'id_lang'           => $page['id_lang'],
                'iso_lang'          => $page['iso_lang'],
                'name_news'         => $page['name_news'],
                'url_news'          => $page['url_news'],
                'content_news'      => $page['content_news'],
                'date_publish'      => $datePublish,
                'published_news'    => $page['published_news'],
                'public_url'        => $publicUrl,
                'tags_news'         => $page['tags_news'],
                'tags'              => $tags
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
                        'id_news'	       => $data['id_news'],
                        'name_news'       => $data['name_news'],
                        'url_news'        => $data['url_news'],
                        'content_news'    => $data['content_news'],
                        'resume_news'    => $data['resume_news'],
                        'date_publish'    => $data['date_publish'],
                        'published_news'  => $data['published_news']
                    )
                );
                break;
            case 'img':
                parent::update(
                    array(
                        'type'=>$data['type']
                    ),array(
                        'id_news'	      => $data['id_news'],
                        'img_news'        => $data['img_news']
                    )
                );
                break;
        }
    }
    private function save()
    {
        if (isset($this->content) && isset($this->id_news)) {
            foreach ($this->content as $lang => $content) {
                $content['published_news'] = (!isset($content['published_news']) ? 0 : 1);
                if (empty($content['url_news'])) {
                    $content['url_news'] = http_url::clean($content['name_news'],
                        array(
                            'dot' => false,
                            'ampersand' => 'strict',
                            'cspec' => '', 'rspec' => ''
                        )
                    );
                }
                $dateFormat = new date_dateformat();
                $datePublish = !empty($content['date_publish']) ? $dateFormat->SQLDateTime($content['date_publish']) : $dateFormat->SQLDateTime($dateFormat->dateToDefaultFormat());
                $checkLangData = parent::fetchData(
                    array('context'=>'one','type'=>'content'),
                    array('id_news'=>$this->id_news,'id_lang'=>$lang)
                );
                // Check language page content
                if($checkLangData!= null){
                    $this->upd(array(
                        'type' => 'content',
                        'id_lang' => $lang,
                        'id_news' => $this->id_news,
                        'name_news' => $content['name_news'],
                        'url_news' => $content['url_news'],
                        'content_news' => $content['content_news'],
                        'resume_news' => $content['resume_news'],
                        'date_publish' => $datePublish,
                        'published_news' => $content['published_news']
                    ));
                }else{
                    parent::insert(
                        array(
                            'type' => 'newContent',
                        ),
                        array(
                            'id_lang' => $lang,
                            'id_news' => $this->id_news,
                            'name_news' => $content['name_news'],
                            'url_news' => $content['url_news'],
                            'content_news' => $content['content_news'],
                            'date_publish' => $datePublish,
                            'published_news' => $content['published_news']
                        )
                    );
                }

                // Add Tags
                if(!empty($content['tag_news'])) {
                    $tagNews = explode(',', $content['tag_news']);
                    if ($tagNews != null) {
                        foreach ($tagNews as $key => $value) {
                            $setTags = parent::fetchData(
                                array('context' => 'one', 'type' => 'tag'),
                                array(':id_news' => $this->id_news, ':id_lang' => $lang, ':name_tag' => $value)
                            );
                            if ($setTags['id_tag'] != null) {
                                if ($setTags['rel_tag'] == null) {
                                    parent::insert(
                                        array(
                                            'type' => 'newTagRel'
                                        ),
                                        array(
                                            'id_news'=> $this->id_news,
                                            'id_tag' => $setTags['id_tag']
                                        )
                                    );
                                }
                            } else {
                                parent::insert(
                                    array(
                                        'type' => 'newTagComb'
                                    ),
                                    array(
                                        'id_news' => $this->id_news,
                                        'id_lang' => $lang,
                                        'name_tag'=> $value
                                    )
                                );
                            }
                        }
                    }
                }

                $setEditData = parent::fetchData(
                    array('context' => 'all', 'type' => 'page'),
                    array('edit' => $this->id_news)
                );
                $setEditData = $this->setItemData($setEditData);
                $extendData[$lang] = $setEditData[$this->id_news]['content'][$lang]['public_url'];
            }

            $this->header->set_json_headers();
            $this->message->json_post_response(true, 'update', array('result' => $this->id_news, 'extend' => $extendData));

        }else if (isset($this->content) && !isset($this->id_news)) {

            parent::insert(
                array(
                    'type'=>'newPages'
                )
            );

            $setNewData = parent::fetchData(
                array('context' => 'one', 'type' => 'root')
            );

            if ($setNewData['id_news']) {
                foreach ($this->content as $lang => $content) {

                    $content['published_news'] = (!isset($content['published_news']) ? 0 : 1);
                    $url_news = http_url::clean($content['name_news'],
                        array(
                            'dot' => false,
                            'ampersand' => 'strict',
                            'cspec' => '', 'rspec' => ''
                        )
                    );
                    $dateFormat = new date_dateformat();
                    $datePublish = !empty($content['date_publish']) ? $dateFormat->SQLDateTime($content['date_publish']) : $dateFormat->SQLDateTime($dateFormat->dateToDefaultFormat());
                    parent::insert(
                        array(
                            'type' => 'newContent',
                        ),
                        array(
                            'id_lang' => $lang,
                            'id_news' => $setNewData['id_news'],
                            'name_news' => $content['name_news'],
                            'url_news' => $url_news,
                            'content_news' => $content['content_news'],
                            'date_publish' => $datePublish,
                            'published_news' => $content['published_news']
                        )
                    );
                }

                $this->header->set_json_headers();
                $this->message->json_post_response(true,'add_redirect');
            }

        }elseif(isset($this->img)){
            $data = parent::fetchData(array('context'=>'one','type'=>'page'),array('id_news'=>$this->id_news));
            $resultUpload = $this->upload->setImageUpload(
                'img',
                array(
                    'name'              => filter_rsa::randMicroUI(),
                    'edit'              => $data['img_news'],
                    'prefix'            => array('s_','m_','l_'),
                    'module_img'        => 'news',
                    'attribute_img'     => 'news',
                    'original_remove'   => false
                ),
                array(
                    'upload_root_dir'      => 'upload/news', //string
                    'upload_dir'           => $this->id_news //string ou array
                ),
                false
            );

            $this->upd(array(
                'type'             => 'img',
                'id_news'          => $this->id_news,
                'img_news'         => $resultUpload['file']
            ));
            $this->header->set_json_headers();

            $setEditData = parent::fetchData(
                array('context'=>'all','type'=>'page'),
                array('edit'=>$this->id_news)
            );
            $setEditData = $this->setItemData($setEditData);
            $this->template->assign('page',$setEditData[$this->id_news]);
            $display = $this->template->fetch('news/brick/img.tpl');

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
    public function run()
    {
        if (isset($this->action)) {
            switch ($this->action) {
                case 'add':
                    if(isset($this->content)){
                        $this->save();
                    }else {
                        $this->modelLanguage->getLanguage();
                        $this->template->display('news/add.tpl');
                    }
                    break;
                case 'edit':
                    if (isset($this->id_news)) {
                        $this->save();
                    }else{
                        $this->modelLanguage->getLanguage();
                        $setEditData = parent::fetchData(
                            array('context'=>'all','type'=>'page'),
                            array('edit'=>$this->edit)
                        );
                        $setEditData = $this->setItemData($setEditData);
                        $this->template->assign('page',$setEditData[$this->edit]);
                        $this->template->display('news/edit.tpl');
                    }
                    break;
                case 'delete':
                    if(isset($this->name_tag)) {
                        $setTags = parent::fetchData(
                            array('context' => 'one', 'type' => 'tag'),
                            array(':id_news' => $this->id_news, ':id_lang' => $this->id_lang, ':name_tag' => $this->name_tag)
                        );
                        if ($setTags['id_tag'] != null && $setTags['rel_tag'] != null) {
                            parent::delete(array('type' => 'tagRel'), array('id_rel' => $setTags['rel_tag']));
                        }
                    }
                    elseif(isset($this->id_news)) {
                        $this->del(
                            array(
                                'type'=>'delPages',
                                'data'=>array(
                                    'id' => $this->id_news
                                )
                            )
                        );
                    }
                    break;
            }
        }
        else {
			$this->modelLanguage->getLanguage();
			$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
			$this->getItems('news',array(':default_lang'=>$defaultLanguage['id_lang']),'all');
			$assign = array(
				'id_news',
				'name_news',
				'content_news' => ['type' => 'bin', 'input' => null],
				'img_news' => ['type' => 'bin', 'input' => null, 'class' => ''],
				'last_update' => ['title' => 'last_update', 'input' => ['type' => 'text', 'class' => 'date-input']],
				'date_publish',
				'published_news'
			);
			$this->data->getScheme(array('mc_news','mc_news_content'),array('id_news','name_news','content_news','img_news','last_update','date_publish','published_news'),$assign);
			$this->template->display('news/index.tpl');
		}
    }
}
?>