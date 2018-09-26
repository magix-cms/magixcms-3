<?php
class backend_controller_pages extends backend_db_pages
{
    public $edit, $action, $tabs, $search, $plugin, $controller;
    protected $message, $template, $header, $data, $modelLanguage, $collectionLanguage, $order, $upload, $config, $imagesComponent, $modelPlugins,$routingUrl,$makeFiles,$finder;
    public $id_pages,$parent_id,$content,$pages,$img,$iso,$del_img,$ajax,$tableaction,$tableform;
	public $tableconfig = array(
		'all' => array(
			'id_pages',
			'name_pages' => array('title' => 'name'),
			'parent_pages' => array('col' => 'name_pages', 'title' => 'name'),
			'img_pages' => array('type' => 'bin', 'input' => null, 'class' => ''),
			'resume_pages' => array('type' => 'bin', 'input' => null),
			'content_pages' => array('type' => 'bin', 'input' => null),
			'seo_title_pages' => array('title' => 'seo_title', 'class' => '', 'type' => 'bin', 'input' => null),
			'seo_desc_pages' => array('title' => 'seo_desc', 'class' => '', 'type' => 'bin', 'input' => null),
			'menu_pages',
			'date_register'
		),
		'parent' => array(
			'id_pages',
			'name_pages' => array('title' => 'name'),
			'img_pages' => array('type' => 'bin', 'input' => null, 'class' => ''),
			'resume_pages' => array('class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null),
			'content_pages' => array('class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null),
			'seo_title_pages' => array('title' => 'seo_title', 'class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null),
			'seo_desc_pages' => array('title' => 'seo_desc', 'class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null),
			'menu_pages',
			'date_register'
		)
	);

	/**
	 * backend_controller_pages constructor.
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
        $this->modelPlugins = new backend_model_plugins();
        $this->routingUrl = new component_routing_url();
        $this->makeFiles = new filesystem_makefile();
        $this->finder = new file_finder();

        // --- GET
        if(http_request::isGet('controller')) $this->controller = $formClean->simpleClean($_GET['controller']);
        if (http_request::isGet('edit')) $this->edit = $formClean->numeric($_GET['edit']);
        if (http_request::isGet('action')) $this->action = $formClean->simpleClean($_GET['action']);
        elseif (http_request::isPost('action')) $this->action = $formClean->simpleClean($_POST['action']);
        if (http_request::isGet('tabs')) $this->tabs = $formClean->simpleClean($_GET['tabs']);
        if (http_request::isGet('ajax')) $this->ajax = $formClean->simpleClean($_GET['ajax']);

        if (http_request::isGet('tableaction')) {
        	$this->tableaction = $formClean->simpleClean($_GET['tableaction']);
        	$this->tableform = new backend_controller_tableform($this,$this->template);
		}

        // --- Search
        if (http_request::isGet('search')) $this->search = $formClean->arrayClean($_GET['search']);

        // --- ADD or EDIT
        if (http_request::isGet('id')) $this->id_pages = $formClean->simpleClean($_GET['id']);
        elseif (http_request::isPost('id')) $this->id_pages = $formClean->simpleClean($_POST['id']);
        if (http_request::isPost('parent_id')) $this->parent_id = $formClean->simpleClean($_POST['parent_id']);
        if (http_request::isPost('del_img')) $this->del_img = $formClean->simpleClean($_POST['del_img']);
        if (http_request::isPost('content')) {
            $array = $_POST['content'];
            foreach($array as $key => $arr) {
                foreach($arr as $k => $v) {
                    $array[$key][$k] = ($k == 'content_pages') ? $formClean->cleanQuote($v) : $formClean->simpleClean($v);
                }
            }
            $this->content = $array;
        }
        // --- Image Upload
        if (isset($_FILES['img']["name"])) $this->img = http_url::clean($_FILES['img']["name"]);
        // --- Recursive Actions
        if (http_request::isGet('pages'))  $this->pages = $formClean->arrayClean($_GET['pages']);

        # ORDER PAGE
        if (http_request::isPost('pages')) $this->order = $formClean->arrayClean($_POST['pages']);
        if (http_request::isGet('plugin')) $this->plugin = $formClean->simpleClean($_GET['plugin']);

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
	 * @throws Exception
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
		$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
		$params = array();

		if($this->edit) {
			$results = $this->getItems('pagesChild',$this->edit,'all',false);
		}
		else {
			$results = $this->getItems('pages',array('default_lang'=>$defaultLanguage['id_lang']),'all',false);
		}

		$assign = $this->tableconfig[(($ajax || $this->edit) ? 'parent' : 'all')];

		if($ajax) {
			$params['section'] = 'pages';
			$params['idcolumn'] = 'id_pages';
			$params['activation'] = true;
			$params['sortable'] = true;
			$params['checkbox'] = true;
			$params['edit'] = true;
			$params['dlt'] = true;
			$params['readonly'] = array();
			$params['cClass'] = 'backend_controller_pages';
		}

		$this->data->getScheme(array('mc_cms_page','mc_cms_page_content'),array('id_pages','name_pages','img_pages','resume_pages','content_pages','seo_title_pages','seo_desc_pages','menu_pages','date_register'),$assign);

		return array(
			'data' => $results,
			'var' => 'pages',
			'tpl' => 'pages/index.tpl',
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
				'menu_pages' => $params['active'],
				'id_pages' => $params['ids']
			)
		));
		$this->message->getNotify('update',array('method'=>'fetch','assignFetch'=>'message'));
	}

	public function tinymce()
	{
		$langs = $this->modelLanguage->setLanguage();
		foreach($langs as $k => $iso) {
			$list = $this->getItems('pagesPublishedSelect',array('default_lang'=> $k),'all',false);

			$lists[$k] = $this->data->setPagesTree($list,'pages');
		}
		$this->template->assign('langs',$langs);
		$this->template->assign('pages',$lists);
		$this->template->display('tinymce/pages/mc_pages.tpl');
	}

    /**
     * Return Last pages (Dashboard)
     */
    public function getItemsPages(){
        $this->modelLanguage->getLanguage();
        $defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
        $this->getItems('lastPages',array(':default_lang'=>$defaultLanguage['id_lang']),'all');
    }

    /**
     * @param $data
     * @return array
     */
    private function setItemData($data){
        //return $this->getItems('page',$this->edit, 'all',false);
        $imgPath = $this->upload->imgBasePath('upload/pages');
        $arr = array();
        $conf = array();
        $fetchConfig = $this->imagesComponent->getConfigItems(array('module_img'=>'pages','attribute_img'=>'page'));
        $imgPrefix = $this->imagesComponent->prefix();

        foreach ($data as $page) {

            $publicUrl = !empty($page['url_pages']) ? $this->routingUrl->getBuildUrl(array(
                    'type'      =>  'pages',
                    'iso'       =>  $page['iso_lang'],
                    'id'        =>  $page['id_pages'],
                    'url'       =>  $page['url_pages']
                )
            ) : '';

            if (!array_key_exists($page['id_pages'], $arr)) {
                $arr[$page['id_pages']] = array();
                $arr[$page['id_pages']]['id_pages'] = $page['id_pages'];
                $arr[$page['id_pages']]['id_parent'] = $page['id_parent'];
                if($page['img_pages'] != null) {
                    if(file_exists($imgPath.DIRECTORY_SEPARATOR.$page['id_pages'].DIRECTORY_SEPARATOR.$page['img_pages'])){
                        $originalSize = getimagesize($imgPath.DIRECTORY_SEPARATOR.$page['id_pages'].DIRECTORY_SEPARATOR.$page['img_pages']);
                        $arr[$page['id_pages']]['imgSrc']['original']['img'] = $page['img_pages'];
                        $arr[$page['id_pages']]['imgSrc']['original']['width'] = $originalSize[0];
                        $arr[$page['id_pages']]['imgSrc']['original']['height'] = $originalSize[1];
                    }
                    foreach ($fetchConfig as $key => $value) {
                        $size = getimagesize($imgPath.DIRECTORY_SEPARATOR.$page['id_pages'].DIRECTORY_SEPARATOR.$imgPrefix[$value['type_img']] . $page['img_pages']);
                        $arr[$page['id_pages']]['imgSrc'][$value['type_img']]['img'] = $imgPrefix[$value['type_img']] . $page['img_pages'];
                        $arr[$page['id_pages']]['imgSrc'][$value['type_img']]['width'] = $size[0];
                        $arr[$page['id_pages']]['imgSrc'][$value['type_img']]['height'] = $size[1];
                    }
                }
                $arr[$page['id_pages']]['menu_pages'] = $page['menu_pages'];
                $arr[$page['id_pages']]['date_register'] = $page['date_register'];
            }
            $arr[$page['id_pages']]['content'][$page['id_lang']] = array(
                'id_lang'           => $page['id_lang'],
                'iso_lang'          => $page['iso_lang'],
                'name_pages'        => $page['name_pages'],
                'url_pages'         => $page['url_pages'],
                'resume_pages'      => $page['resume_pages'],
                'content_pages'     => $page['content_pages'],
                'seo_title_pages'   => $page['seo_title_pages'],
                'seo_desc_pages'    => $page['seo_desc_pages'],
                'published_pages'   => $page['published_pages'],
                'public_url'        => $publicUrl
            );
        }
        return $arr;
    }

    /**
     * @param $id
     * @return array
     * @throws Exception
     */
	private function saveContent($id)
	{
		$extendData = array();

		foreach ($this->content as $lang => $content) {
			$content['id_lang'] = $lang;
			$content['id_pages'] = $id;
			$content['published_pages'] = (!isset($content['published_pages']) ? 0 : 1);
			if (empty($content['url_pages'])) {
				$content['url_pages'] = http_url::clean($content['name_pages'],
					array(
						'dot' => false,
						'ampersand' => 'strict',
						'cspec' => '', 'rspec' => ''
					)
				);
			}

			$contentPage = $this->getItems('content',array('id_pages'=>$id, 'id_lang'=>$lang),'one',false);

			if($contentPage != null) {
				$this->upd(
					array(
						'context' => 'page',
						'type' => 'page',
						'data' => array(
							'id_pages' => $id,
							'id_parent' => empty($this->parent_id) ? NULL : $this->parent_id
						)
					)
				);
				$this->upd(
					array(
						'context' => 'page',
						'type' => 'content',
						'data' => $content
					)
				);
			}
			else {
				$this->add(
					array(
						'context' => 'page',
						'type' => 'content',
						'data' => $content
					)
				);
			}

			if(isset($this->id_pages)) {
				$setEditData = parent::fetchData(
					array('context'=>'all','type'=>'page'),
					array('edit'=>$this->id_pages)
				);
				$setEditData = $this->setItemData($setEditData);
				$extendData[$lang] = $setEditData[$this->id_pages]['content'][$lang]['public_url'];
			}
		}

		if(!empty($extendData)) return $extendData;
	}

    /**
     * Update data
     * @param $data
     * @throws Exception
     */
	private function add($data)
	{
		switch ($data['type']) {
			case 'page':
			case 'content':
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
            case 'order':
                $p = $this->order;
                for ($i = 0; $i < count($p); $i++) {
                    parent::update(
                        array(
                            'type'=>$data['type']
                        ),array(
                            'id_pages'       => $p[$i],
                            'order_pages'    => $i
                        )
                    );
                }
                break;
			case 'page':
			case 'content':
			case 'img':
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

    /**
     * Insertion de données
     * @param $data
     * @throws Exception
     */
    private function del($data)
	{
        switch($data['type']){
            case 'delPages':
                parent::delete(
                    array(
                        'type' => $data['type']
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
        if(isset($this->plugin)) {
            if(isset($this->action)) {
                switch ($this->action) {
                    case 'edit':
                        // Initialise l'API menu des plugins core
                        $this->modelPlugins->getItems(
                            array(
                                'type'      =>  'tabs',
                                'controller'=>  $this->controller
                            )
                        );
                        $this->modelLanguage->getLanguage();
                        $setEditData = parent::fetchData(
                            array('context' => 'all', 'type' => 'page'),
                            array('edit' => $this->edit)
                        );
                        $setEditData = $this->setItemData($setEditData);
                        $this->template->assign('page', $setEditData[$this->edit]);
                        $assign = array(
                            'id_pages',
                            'name_pages' => array('title' => 'name'),
                            'menu_pages',
                            'date_register'
                        );
                        $this->data->getScheme(array('mc_cms_page', 'mc_cms_page_content'), array('id_pages', 'name_pages', 'menu_pages', 'date_register'), $assign);
                        // Execute un plugin core
                        $this->modelPlugins->getCoreItem();
                        break;
                }
            }
        }
        else {
            if(isset($this->tableaction)) {
            	$this->tableform->run();
			}
            elseif(isset($this->action)) {
                switch ($this->action) {
                    case 'add':
                        if(isset($this->content)) {
							$this->add(
								array(
									'context' => 'page',
									'type' => 'page',
									'data' => array(
										'id_parent' => empty($this->parent_id) ? NULL : $this->parent_id
									)
								)
							);

							$page = $this->getItems('root',null,'one',false);

							if ($page['id_pages']) {
								$this->saveContent($page['id_pages']);
								$this->message->json_post_response(true,'add_redirect');
							}
                        }
                        else {
							$this->modelLanguage->getLanguage();
							$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
							$this->getItems('pagesSelect',array('default_lang'=>$defaultLanguage['id_lang']),'all');
                            $this->template->display('pages/add.tpl');
                        }
                        break;
                    case 'edit':
                        if(isset($this->img)){
							$data = $this->getItems('page',array('id_pages'=>$this->id_pages),'one');
							$resultUpload = $this->upload->setImageUpload(
								'img',
								array(
									'name'              => filter_rsa::randMicroUI(),
									'edit'              => $data['img_pages'],
									'prefix'            => array('s_','m_','l_'),
									'module_img'        => 'pages',
									'attribute_img'     => 'page',
									'original_remove'   => false
								),
								array(
									'upload_root_dir'      => 'upload/pages', //string
									'upload_dir'           => $this->id_pages //string ou array
								),
								false
							);

							$this->upd(array(
								'type' => 'img',
								'data' => array(
									'id_pages' => $this->id_pages,
									'img_pages' => $resultUpload['file']
								)
							));

							$setEditData = $this->getItems('page',array('edit'=>$this->id_pages),'all',false);
							$setEditData = $this->setItemData($setEditData);
							$this->template->assign('page',$setEditData[$this->id_pages]);
							$display = $this->template->fetch('pages/brick/img.tpl');
							$this->message->json_post_response(true, 'update',$display);
						}
						elseif (isset($this->id_pages)) {
							$extendData = $this->saveContent($this->id_pages);
							$this->message->json_post_response(true, 'update', array('result'=>$this->id_pages,'extend'=>$extendData));
						}
                        else {
                            // Initialise l'API menu des plugins core
                            $this->modelPlugins->getItems(
                                array(
                                    'type'      =>  'tabs',
                                    'controller'=>  $this->controller
                                )
                            );
                            $this->modelLanguage->getLanguage();
                            $setEditData = parent::fetchData(array('context'=>'all','type'=>'page'), array('edit'=>$this->edit));
                            $setEditData = $this->setItemData($setEditData);
                            $this->template->assign('page',$setEditData[$this->edit]);
							$this->data->getScheme(array('mc_cms_page','mc_cms_page_content'),array('id_pages','name_pages','img_pages','resume_pages','content_pages','seo_title_pages','seo_desc_pages','menu_pages','date_register'),$this->tableconfig['parent']);
                            $this->getItems('pagesChild',$this->edit,'all');
							$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
							$this->getItems('pagesSelect',array('default_lang'=>$defaultLanguage['id_lang']),'all');
							$this->template->display('pages/edit.tpl');
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
                        if(isset($this->id_pages)) {
                            $this->del(
                                array(
                                    'type'=>'delPages',
                                    'data'=>array(
                                        'id' => $this->id_pages
                                    )
                                )
                            );
                        }
                        elseif(isset($this->del_img)) {
                            $this->upd(array(
                                'type' => 'img',
                                'data' => array(
                                    'id_pages' => $this->del_img,
                                    'img_pages' => NULL
                                )
                            ));

                            $setEditData = $this->getItems('page',array('edit'=>$this->del_img),'all',false);
                            $setEditData = $this->setItemData($setEditData);

                            $setImgDirectory = $this->upload->dirImgUpload(
                                array_merge(
                                    array('upload_root_dir'=>'upload/pages/'.$this->del_img),
                                    array('imgBasePath'=>true)
                                )
                            );

                            if(file_exists($setImgDirectory)){
                                $setFiles = $this->finder->scanDir($setImgDirectory);
                                $clean = '';
                                if($setFiles != null){
                                    foreach($setFiles as $file){
                                        $clean .= $this->makeFiles->remove($setImgDirectory.$file);
                                    }
                                }
                            }
                            $this->template->assign('page',$setEditData[$this->del_img]);
                            $display = $this->template->fetch('pages/brick/img.tpl');
                            $this->message->json_post_response(true, 'update',$display);
                        }
                        break;
					case 'getLink':
						if(isset($this->id_pages) && isset($this->iso)) {
							$page = $this->getItems('pageLang',array('id' => $this->id_pages,'iso' => $this->iso),'one',false);
							if($page) {
								$page['url'] = $this->routingUrl->getBuildUrl(array(
									'type'      =>  'pages',
									'iso'       =>  $page['iso_lang'],
									'id'        =>  $page['id_pages'],
									'url'       =>  $page['url_pages']
								));
								$link = '<a title="'.$page['url'].'" href="'.$page['name_pages'].'">'.$page['name_pages'].'</a>';
								$this->header->set_json_headers();
								print '{"name":'.json_encode($page['name_pages']).',"url":'.json_encode($page['url']).'}';
							}
							else {
								print false;
							}
						}
						break;
                }
            }
            else {
				$this->modelLanguage->getLanguage();
				$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
				$this->getItems('pages',array('default_lang'=>$defaultLanguage['id_lang']),'all');
                $this->data->getScheme(array('mc_cms_page','mc_cms_page_content'),array('id_pages','name_pages','img_pages','resume_pages','content_pages','seo_title_pages','seo_desc_pages','menu_pages','date_register'),$this->tableconfig['parent']);
                $this->template->display('pages/index.tpl');
            }
        }
    }
}