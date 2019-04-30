<?php
class backend_controller_category extends backend_db_category
{
    public $edit, $action, $tabs, $search;
    protected $message, $template, $header, $data, $modelLanguage, $collectionLanguage, $order, $upload, $config, $imagesComponent,$routingUrl,$makeFiles,$finder;
    public $id_cat,$parent_id,$content,$category,$img,$del_img,$ajax,$tableaction,$tableform,$iso,$offset,$name_img,$menu_cat;
	public $tableconfig = array(
		'all' => array(
			'id_cat',
			'name_cat' => ['title' => 'name'],
			'parent_cat' => ['col' => 'name_cat', 'title' => 'name'],
			'img_cat' => ['type' => 'bin', 'input' => null, 'class' => ''],
			'content_cat' => ['type' => 'bin', 'input' => null],
            'seo_title_cat' => array('title' => 'seo_title', 'class' => '', 'type' => 'bin', 'input' => null),
            'seo_desc_cat' => array('title' => 'seo_desc', 'class' => '', 'type' => 'bin', 'input' => null),
			'menu_cat',
			'date_register'
		),
		'parent' => array(
			'id_cat',
			'name_cat' => ['title' => 'name'],
			'img_cat' => ['type' => 'bin', 'input' => null, 'class' => ''],
			'content_cat' => ['class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null],
            'seo_title_cat' => array('title' => 'seo_title', 'class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null),
            'seo_desc_cat' => array('title' => 'seo_desc', 'class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null),
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
		if (http_request::isGet('offset')) $this->offset = intval($formClean->simpleClean($_GET['offset']));

		if (http_request::isGet('tableaction')) {
			$this->tableaction = $formClean->simpleClean($_GET['tableaction']);
			$this->tableform = new backend_controller_tableform($this,$this->template);
		}

		// --- Search
		if (http_request::isGet('search')) {
			$this->search = $formClean->arrayClean($_GET['search']);
			$this->search = array_filter($this->search, function ($value) { return $value !== ''; });
		}

        // --- ADD or EDIT
        if (http_request::isGet('id')) $this->id_cat = $formClean->simpleClean($_GET['id']);
		elseif (http_request::isPost('id')) $this->id_cat = $formClean->simpleClean($_POST['id']);
        if (http_request::isPost('parent_id')) $this->parent_id = $formClean->simpleClean($_POST['parent_id']);
		if (http_request::isPost('menu_cat')) $this->menu_cat = $formClean->simpleClean($_POST['menu_cat']);
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
		if (http_request::isPost('name_img')) $this->name_img = http_url::clean($_POST['name_img']);

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
	 * @param boolean $pagination
	 * @return mixed
	 */
	private function getItems($type, $id = null, $context = null, $assign = true, $pagination = false) {
		return $this->data->getItems($type, $id, $context, $assign, $pagination);
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
			$results = $this->getItems('pages', array('default_lang' => $defaultLanguage['id_lang']), 'all',false,true);
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
				$img_pages = pathinfo($page['img_cat']);
				$arr[$page['id_cat']]['img_cat'] = $img_pages['filename'];
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
				'alt_img'     		=> $page['alt_img'],
				'title_img'     	=> $page['title_img'],
				'caption_img'       => $page['caption_img'],
                'seo_title_cat'     => $page['seo_title_cat'],
                'seo_desc_cat'      => $page['seo_desc_cat'],
                'published_cat'     => $page['published_cat'],
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
			$content['id_cat'] = $id;
			$content['published_cat'] = (!isset($content['published_cat']) ? 0 : 1);
			$content['resume_cat'] = (!empty($content['resume_cat']) ? $content['resume_cat'] : NULL);
			$content['content_cat'] = (!empty($content['content_cat']) ? $content['content_cat'] : NULL);
			$content['seo_title_cat'] = (!empty($content['seo_title_cat']) ? $content['seo_title_cat'] : NULL);
			$content['seo_desc_cat'] = (!empty($content['seo_desc_cat']) ? $content['seo_desc_cat'] : NULL);
			if (empty($content['url_cat'])) {
				$content['url_cat'] = http_url::clean($content['name_cat'],
					array(
						'dot' => false,
						'ampersand' => 'strict',
						'cspec' => '', 'rspec' => ''
					)
				);
			}

			$contentPage = $this->getItems('content',array('id_cat'=>$id, 'id_lang'=>$lang),'one',false);

			if($contentPage != null) {
				$this->upd(
					array(
						'type' => 'page',
						'data' => array(
							'id_cat' => $id,
							'id_parent' => empty($this->parent_id) ? NULL : $this->parent_id,
							'menu_cat' => isset($this->menu_cat) ? 1 : 0
						)
					)
				);
				$this->upd(
					array(
						'type' => 'content',
						'data' => $content
					)
				);
			}
			else {
				$this->add(
					array(
						'type' => 'content',
						'data' => $content
					)
				);
			}

			if(isset($this->id_cat)) {
				$setEditData = $this->getItems('page', array('edit'=>$this->edit),'all',false);
				$setEditData = $this->setItemData($setEditData);
				$extendData[$lang] = $setEditData[$this->id_cat]['content'][$lang]['public_url'];
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
            case 'page':
			case 'pageActiveMenu':
            case 'content':
			case 'img':
			case 'imgContent':
                parent::update(
                    array(
                        'type'=>$data['type']
                    ),
					$data['data']
                );
                break;
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
                                'order_cat'    => $i + (isset($this->offset) ? ($this->offset + 1) : 0)
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
                    if(isset($this->content)) {
						$this->add(
							array(
								'type' => 'page',
								'data' => array(
									'id_parent' => empty($this->parent_id) ? NULL : $this->parent_id,
									'menu_cat' => isset($this->menu_cat) ? 1 : 0
								)
							)
						);

						$page = $this->getItems('root',null,'one',false);

						if ($page['id_cat']) {
							$this->saveContent($page['id_cat']);
							$this->message->json_post_response(true,'add_redirect');
						}
					}
					else {
						$this->modelLanguage->getLanguage();
						$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
						$this->getItems('pagesSelect',array('default_lang'=>$defaultLanguage['id_lang']),'all');
						$this->template->display('catalog/category/add.tpl');
					}
                    break;
                case 'edit':
					if(isset($this->img) || isset($this->name_img)){
						$defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'one', 'type' => 'default'));
						$page = $this->getItems('pageLang', array('id' => $this->id_cat, 'iso' => $defaultLanguage['iso_lang']), 'one', false);
						$settings = array(
							'name' => $this->name_img !== '' ? $this->name_img : $page['url_cat'],
							'edit' => $page['img_cat'],
							'prefix' => array('s_', 'm_', 'l_'),
							'module_img' => 'catalog',
							'attribute_img' => 'category',
							'original_remove' => false
						);
						$dirs = array(
							'upload_root_dir' => 'upload/catalog/c', //string
							'upload_dir' => $this->id_cat //string ou array
						);
						$filename = '';
						$update = false;

						if(isset($this->img)) {
							$resultUpload = $this->upload->setImageUpload('img', $settings, $dirs, false);
							$filename = $resultUpload['file'];
							$update = true;
						}
						elseif(isset($this->name_img)) {
							$img_pages = pathinfo($page['img_cat']);
							$img_name = $img_pages['filename'];

							if($this->name_img !== $img_name && $this->name_img !== '') {
								$result = $this->upload->renameImages($settings,$dirs);
								$filename = $result;
								$update = true;
							}
						}

						if($filename !== '' && $update) {
							$this->upd(array(
								'type' => 'img',
								'data' => array(
									'id_cat' => $this->id_cat,
									'img_cat' => $filename
								)
							));
						}

						foreach ($this->content as $lang => $content) {
							$content['id_lang'] = $lang;
							$content['id_cat'] = $this->id_cat;
							$content['alt_img'] = (!empty($content['alt_img']) ? $content['alt_img'] : NULL);
							$content['title_img'] = (!empty($content['title_img']) ? $content['title_img'] : NULL);
							$content['caption_img'] = (!empty($content['caption_img']) ? $content['caption_img'] : NULL);
							$this->upd(array(
								'type' => 'imgContent',
								'data' => $content
							));
						}

						$setEditData = $this->getItems('page',array('edit'=>$this->id_cat),'all',false);
						$setEditData = $this->setItemData($setEditData);
						$this->template->assign('page',$setEditData[$this->id_cat]);
						$display = $this->template->fetch('catalog/category/brick/img.tpl');
						$this->message->json_post_response(true, 'update',$display);
					}
					elseif (isset($this->id_cat)) {
						$extendData = $this->saveContent($this->id_cat);
						$this->message->json_post_response(true, 'update', array('result'=>$this->id_cat,'extend'=>$extendData));
					}
					else {
						// Initialise l'API menu des plugins core
						/*$this->modelPlugins->getItems(
							array(
								'type'      =>  'tabs',
								'controller'=>  $this->controller
							)
						);*/
						$this->modelLanguage->getLanguage();
						$setEditData = $this->getItems('page', array('edit'=>$this->edit),'all',false);
						$setEditData = $this->setItemData($setEditData);
						$this->template->assign('page',$setEditData[$this->edit]);
						$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
						$this->getItems('pagesChild', array('default_lang' => $defaultLanguage['id_lang'],'id' => $this->edit), 'all');
						$this->data->getScheme(array('mc_catalog_cat', 'mc_catalog_cat_content'), array('id_cat', 'name_cat', 'img_cat','content_cat','seo_title_cat','seo_desc_cat','menu_cat', 'date_register'), $this->tableconfig['parent']);
						$this->getItems('catalog', array('default_lang' => $defaultLanguage['id_lang'],':id_cat' => $this->edit), 'all');
						$assignCatalog = array(
							'id_catalog',
							'name_p' => ['title' => 'name']
						);
						$this->data->getScheme(array('mc_catalog', 'mc_catalog_product_content'), array('id_catalog', 'name_p'), $assignCatalog, 'schemeCatalog');
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
                            'type'    => 'img',
							'data' => array(
								'id_cat' => $this->del_img,
								'img_cat' => NULL
							)
                        ));

						$setEditData = $this->getItems('page',array('edit'=>$this->del_img),'all',false);
                        $setEditData = $this->setItemData($setEditData);

						$setImgDirectory = $this->upload->dirImgUpload(
							array_merge(
								array('upload_root_dir'=>'upload/catalog/c/'.$this->del_img),
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
            }
        }
        else {
            $this->modelLanguage->getLanguage();
            $defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'one', 'type' => 'default'));
            $this->getItems('pages', array('default_lang' => $defaultLanguage['id_lang']), 'all',true,true);
            $this->data->getScheme(array('mc_catalog_cat', 'mc_catalog_cat_content'), array('id_cat', 'img_cat', 'name_cat', 'content_cat','seo_title_cat','seo_desc_cat','menu_cat', 'date_register'), $this->tableconfig['parent']);
            $this->template->display('catalog/category/index.tpl');
        }
    }
}