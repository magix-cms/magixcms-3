<?php
class backend_controller_category extends backend_db_category
{
    public $edit, $action, $tabs, $search;
    protected $message, $template, $header, $data, $modelLanguage, $collectionLanguage, $order, $upload, $config, $imagesComponent,$routingUrl,$makeFiles,$finder;
    public $id_cat,$parent_id,$content,$category,$img,$del_img,$ajax,$tableaction,$tableform,$iso;
	public $tableconfig = array(
		'all' => array(
			'id_cat',
			'name_cat' => ['title' => 'name'],
			'parent_cat' => ['col' => 'name_cat', 'title' => 'name'],
			'img_cat' => ['type' => 'bin', 'input' => null, 'class' => ''],
			'content_cat' => ['type' => 'bin', 'input' => null],
			'menu_cat',
			'date_register'
		),
		'parent' => array(
			'id_cat',
			'name_cat' => ['title' => 'name'],
			'img_cat' => ['type' => 'bin', 'input' => null, 'class' => ''],
			'content_cat' => ['class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null],
			'menu_cat',
			'date_register'
		)
	);

	/**
	 * backend_controller_category constructor.
	 * @param null|object $t
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
        $this->routingUrl = new component_routing_url();
        $this->makeFiles = new filesystem_makefile();
        $this->finder = new file_finder();

        // --- GET
        if (http_request::isGet('edit')) $this->edit = $formClean->numeric($_GET['edit']);
        if (http_request::isGet('action')) $this->action = $formClean->simpleClean($_GET['action']);
        elseif (http_request::isPost('action')) $this->action = $formClean->simpleClean($_POST['action']);
        if (http_request::isGet('tabs')) $this->tabs = $formClean->simpleClean($_GET['tabs']);

		if (http_request::isGet('tableaction')) {
			$this->tableaction = $formClean->simpleClean($_GET['tableaction']);
			$this->tableform = new backend_controller_tableform($this,$this->template);
		}

        // --- Search
        if (http_request::isGet('search')) $this->search = $formClean->arrayClean($_GET['search']);
        // --- ADD or EDIT
        if (http_request::isGet('id')) $this->id_cat = $formClean->simpleClean($_GET['id']);
		elseif (http_request::isPost('id')) $this->id_cat = $formClean->simpleClean($_POST['id']);
        if (http_request::isPost('parent_id')) $this->parent_id = $formClean->simpleClean($_POST['parent_id']);
        if (http_request::isPost('del_img')) $this->del_img = $formClean->simpleClean($_POST['del_img']);

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
        if (isset($_FILES['img']["name"])) $this->img = http_url::clean($_FILES['img']["name"]);
        // --- Recursive Actions
        if (http_request::isGet('category')) $this->category = $formClean->arrayClean($_GET['category']);

        # ORDER PAGE
        if (http_request::isPost('category')) $this->order = $formClean->arrayClean($_POST['category']);
        elseif (http_request::isPost('product')) $this->order = $formClean->arrayClean($_POST['product']);

		# JSON LINK (TinyMCE)
		if (http_request::isGet('iso')) $this->iso = $formClean->simpleClean($_GET['iso']);
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
	 * @param $ajax
	 * @return mixed
	 * @throws Exception
	 */
	public function tableSearch($ajax = false)
	{
		$this->modelLanguage->getLanguage();
		$defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'one', 'type' => 'default'));
		$params = array();

		if($this->edit) {
			$results = $this->getItems('pagesChild',$this->edit,'all',false);
		}
		else {
			$results = $this->getItems('pages', array('default_lang' => $defaultLanguage['id_lang']), 'all',false);
		}

		$assign = $this->tableconfig[(($ajax || $this->edit) ? 'parent' : 'all')];

		if($ajax) {
			$params['section'] = 'pages';
			$params['idcolumn'] = 'id_cat';
			$params['activation'] = true;
			$params['sortable'] = true;
			$params['checkbox'] = true;
			$params['edit'] = true;
			$params['dlt'] = true;
			$params['readonly'] = array();
			$params['cClass'] = 'backend_controller_category';
		}

		$this->data->getScheme(
			array('mc_catalog_cat', 'mc_catalog_cat_content'),
			array('id_cat', 'img_cat', 'name_cat', 'content_cat','menu_cat', 'date_register'),
			$assign);

		return array(
			'data' => $results,
			'var' => 'pages',
			'tpl' => 'catalog/category/index.tpl',
			'params' => $params
		);
	}

	/**
	 * Active / Unactive page(s)
	 * @param $params
	 * @throws Exception
	 */
	public function tableActive($params)
	{
		$this->upd(array(
			'type' => 'pageActiveMenu',
			'data' => array(
				'menu_cat' => $params['active'],
				'id_cat' => $params['ids']
			)
		));
		$this->message->getNotify('update',array('method'=>'fetch','assignFetch'=>'message'));
	}

	public function tinymce()
	{
		$langs = $this->modelLanguage->setLanguage();
		foreach($langs as $k => $iso) {
			$list = $this->getItems('pagesPublishedSelect',array('default_lang'=> $k),'all',false);

			$lists[$k] = $this->data->setPagesTree($list,'cat');
		}
		$this->template->assign('langs',$langs);
		$this->template->assign('cats',$lists);
		$this->template->display('tinymce/category/mc_cat.tpl');
	}

	/**
	 * Return Last pages (Dashboard)
	 */
	public function getItemsCat(){
		$this->modelLanguage->getLanguage();
		$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
		$this->getItems('lastCats',array(':default_lang'=>$defaultLanguage['id_lang']),'all');
	}

    /**
     * @return array
     */
    private function setItemsData(){
        $defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));

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

            $publicUrl = !empty($page['url_cat']) ? $this->routingUrl->getBuildUrl(array(
                    'type'      =>  'category',
                    'iso'       =>  $page['iso_lang'],
                    'id'        =>  $page['id_cat'],
                    'url'       =>  $page['url_cat']
                )
            ) : '';

            if (!array_key_exists($page['id_cat'], $arr)) {
                $arr[$page['id_cat']] = array();
                $arr[$page['id_cat']]['id_cat'] = $page['id_cat'];
                $arr[$page['id_cat']]['id_parent'] = $page['id_parent'];
                $arr[$page['id_cat']]['menu_cat'] = $page['menu_cat'];
                if($page['img_cat'] != null) {
                    if(file_exists($imgPath.DIRECTORY_SEPARATOR.$page['id_cat'].DIRECTORY_SEPARATOR.$page['img_cat'])) {
                        $originalSize = getimagesize($imgPath . DIRECTORY_SEPARATOR . $page['id_cat'] . DIRECTORY_SEPARATOR . $page['img_cat']);
                        $arr[$page['id_cat']]['imgSrc']['original']['img'] = $page['img_cat'];
                        $arr[$page['id_cat']]['imgSrc']['original']['width'] = $originalSize[0];
                        $arr[$page['id_cat']]['imgSrc']['original']['height'] = $originalSize[1];
                    }
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
                'resume_cat'        => $page['resume_cat'],
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
     * @throws Exception
     */
    private function upd($data)
    {
        switch ($data['type']) {
            case 'page':
            case 'content':
                parent::update(
                    array(
                        'type'=>$data['type']
                    ),
					$data['data']
                );
                break;
            case 'img':
                parent::update(
                    array(
                        'type'=>$data['type']
                    ),array(
                        'id_cat'	       => $data['id_cat'],
                        'img_cat'          => $data['img_cat']
                    )
                );
                break;
            case 'order':
                $p = $this->order;
                for ($i = 0; $i < count($p); $i++) {
                    if(isset($_POST['category'])){
                        parent::update(
                            array(
                                'type'=>'order'
                            ),array(
                                'id_cat'       => $p[$i],
                                'order_cat'    => $i
                            )
                        );
                    }elseif(isset($_POST['product'])){
                        parent::update(
                            array(
                                'type'=>'order_p'
                            ),array(
                                'id_catalog'       => $p[$i],
                                'order_p'      => $i
                            )
                        );
                    }
                }
                break;
            case 'pageActiveMenu':
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
    private function save(){
        if (isset($this->content) && isset($this->id_cat)) {
			$this->upd(array(
				'type' => 'page',
				'data' => array(
					'id_cat' => $this->id_cat,
					'id_parent' => empty($this->parent_id) ? NULL : $this->parent_id
				)
			));

            foreach ($this->content as $lang => $content) {
            	$content['id_lang'] = $lang;
            	$content['id_cat'] = $this->id_cat;
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
                    array('context'=>'one','type'=>'content'),
                    array('id_cat'=>$this->id_cat,'id_lang'=>$lang)
                );

                // Check language page content
                if($checkLangData!= null){
                    $this->upd(array(
                        'type' => 'content',
                        'data' => $content
                    ));
                }else{
                    parent::insert(
                        array(
                            'type' => 'newContent',
                        ),
                        $content
                    );
                }

                $setEditData = parent::fetchData(
                    array('context'=>'all','type'=>'page'),
                    array('edit'=>$this->id_cat)
                );
                $setEditData = $this->setItemData($setEditData);
                $extendData[$lang] = $setEditData[$this->id_cat]['content'][$lang]['public_url'];
            }
            $this->message->json_post_response(true, 'update', array('result'=>$this->id_cat,'extend'=>$extendData));

        }
        else if (isset($this->content) && !isset($this->id_cat)) {
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
                array('context' => 'one', 'type' => 'root')
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
                            'resume_cat'      => $content['resume_cat'],
                            'content_cat'     => $content['content_cat'],
                            'published_cat'   => $content['published_cat']
                        )
                    );
                }
                $this->message->json_post_response(true,'add_redirect');
            }
        }
        else if(isset($this->img)){
            $data = parent::fetchData(array('context'=>'one','type'=>'page'),array('id_cat'=>$this->id_cat));
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
     * Remove product
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
                $this->message->json_post_response(true,'delete',$data['data']);
                break;
            case 'delProduct':
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

    /**
     * 
     */
    public function run(){
		if(isset($this->tableaction)) {
			$this->tableform->run();
		}
		elseif(isset($this->action)) {
            switch ($this->action) {
                case 'add':
                    if(isset($this->content)){
                        $this->save();
                    }
                    else{
                        $defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
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
                    }
                    else {
                        $this->modelLanguage->getLanguage();
                        $setEditData = parent::fetchData(
                            array('context' => 'all', 'type' => 'page'),
                            array('edit' => $this->edit)
                        );
                        $setEditData = $this->setItemData($setEditData);
                        $this->template->assign('page', $setEditData[$this->edit]);
                        //$pages = $this->setItemsData();
                        //$this->template->assign('pages', $pages);

                        $this->data->getScheme(array('mc_catalog_cat', 'mc_catalog_cat_content'), array('id_cat', 'name_cat', 'img_cat','menu_cat', 'date_register'), $this->tableconfig['parent']);
                        $this->getItems('pagesChild', $this->edit, 'all');
                        // catalog (category => product)
                        $defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'one', 'type' => 'default'));
                        $this->getItems('catalog', array('default_lang' => $defaultLanguage['id_lang'],':id_cat' => $this->edit), 'all');
                        $assignCatalog = array(
                            'id_catalog',
                            'name_p' => ['title' => 'name']
                        );
                        $this->data->getScheme(array('mc_catalog', 'mc_catalog_product_content'), array('id_catalog', 'name_p'), $assignCatalog, 'schemeCatalog');
						$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
						$this->getItems('pagesSelect',array('default_lang'=>$defaultLanguage['id_lang']),'all');
						$this->template->display('catalog/category/edit.tpl');
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
                        if(isset($this->tabs)){
                            if($this->tabs === 'product') {
                                $this->del(
                                    array(
                                        'type' => 'delProduct',
                                        'data' => array(
                                            'id' => $this->id_cat
                                        )
                                    )
                                );
                            }
                        }
                        else {
                            $this->del(
                                array(
                                    'type'=>'delPages',
                                    'data'=>array(
                                        'id' => $this->id_cat
                                    )
                                )
                            );
                        }
                    }
                    elseif(isset($this->del_img)) {
                        $this->upd(array(
                            'type'           => 'img',
                            'id_cat'         => $this->del_img,
                            'img_cat'        => NULL
                        ));

                        $setEditData = parent::fetchData(
                            array('context'=>'all','type'=>'page'),
                            array('edit'=>$this->del_img)
                        );
                        $setEditData = $this->setItemData($setEditData);
                        $this->template->assign('page',$setEditData[$this->del_img]);
                        $display = $this->template->fetch('catalog/category/brick/img.tpl');

                        $this->message->json_post_response(true, 'update',$display);
                    }
                    break;
				case 'getLink':
					if(isset($this->id_cat) && isset($this->iso)) {
						$cat = $this->getItems('pageLang',array('id' => $this->id_cat,'iso' => $this->iso),'one',false);
						if($cat) {
							$cat['url'] = $this->routingUrl->getBuildUrl(array(
								'type' => 'category',
								'iso'  => $cat['iso_lang'],
								'id'   => $cat['id_cat'],
								'url'  => $cat['url_cat']
							));
							//$link = '<a title="'.$cat['url'].'" href="'.$cat['name_cat'].'">'.$cat['name_cat'].'</a>';
							$this->header->set_json_headers();
							print '{"name":'.json_encode($cat['name_cat']).',"url":'.json_encode($cat['url']).'}';
						}
						else {
							print false;
						}
					}
					break;
                /*case 'active-selected':
                case 'unactive-selected':
					if(isset($this->category) && is_array($this->category) && !empty($this->category)) {
						$this->upd(
							array(
								'type'=>'pageActiveMenu',
								'data'=>array(
									'menu_cat' => ($this->action == 'active-selected'?1:0),
									'id_cat' => implode($this->category, ',')
								)
							)
						);
					}
					$this->message->getNotify('update',array('method'=>'fetch','assignFetch'=>'message'));

					$defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'one', 'type' => 'default'));
					$this->getItems('pages', array(':default_lang' => $defaultLanguage['id_lang']), 'all');
					$assign = array(
						'id_cat',
						'name_cat' => ['title' => 'name'],
						'img_cat' => ['type' => 'bin', 'input' => null, 'class' => ''],
						'content_cat' => ['class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null],
						'menu_cat',
						'date_register'
					);
					$this->data->getScheme(array('mc_catalog_cat', 'mc_catalog_cat_content'), array('id_cat', 'img_cat', 'name_cat', 'content_cat','menu_cat', 'date_register'), $assign);
					$this->template->display('catalog/category/index.tpl');
					break;*/
            }
        }
        else {
            $this->modelLanguage->getLanguage();
            $defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'one', 'type' => 'default'));
            $this->getItems('pages', array('default_lang' => $defaultLanguage['id_lang']), 'all');
            $this->data->getScheme(array('mc_catalog_cat', 'mc_catalog_cat_content'), array('id_cat', 'img_cat', 'name_cat', 'content_cat','menu_cat', 'date_register'), $this->tableconfig['parent']);
            $this->template->display('catalog/category/index.tpl');
        }
    }
}