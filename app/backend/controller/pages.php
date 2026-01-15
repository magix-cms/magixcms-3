<?php
class backend_controller_pages extends backend_db_pages {
    public $edit, $action, $tabs, $search, $plugin, $controller;
    protected $message, $template, $progress, $header, $data, $modelLanguage, $collectionLanguage, $order, $order_img, $upload, $config, $imagesComponent, $modelPlugins,$routingUrl,$makeFiles,$finder;
    public $id_pages,$parent_id,$content,$pages,$img,$iso,$del_img,$ajax,$tableaction,$tableform,$offset,$name_img,$menu_pages,$id_img,$img_multiple,$imgData,$editimg;
	public $tableconfig = array(
		'all' => array(
			'id_pages' => ['title' => 'id', 'type' => 'text', 'class' => 'fixed-td-md text-center'],
			'name_pages' => array('title' => 'name'),
			'parent_pages' => array('col' => 'name_pages', 'title' => 'name'),
			'default_img' => array('title' => 'img','type' => 'bin', 'input' => null, 'class' => ''),
			'resume_pages' => array('type' => 'bin', 'input' => null),
			'content_pages' => array('type' => 'bin', 'input' => null),
			'seo_title_pages' => array('title' => 'seo_title', 'class' => '', 'type' => 'bin', 'input' => null),
			'seo_desc_pages' => array('title' => 'seo_desc', 'class' => '', 'type' => 'bin', 'input' => null),
			'menu_pages' => array('type' => 'bin'),
			'date_register'
		),
		'parent' => array(
			'id_pages' => ['title' => 'id', 'type' => 'text', 'class' => 'fixed-td-md text-center'],
			'name_pages' => array('title' => 'name'),
			'default_img' => array('title' => 'img','type' => 'bin', 'input' => null, 'class' => ''),
			'resume_pages' => array('class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null),
			'content_pages' => array('class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null),
			'seo_title_pages' => array('title' => 'seo_title', 'class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null),
			'seo_desc_pages' => array('title' => 'seo_desc', 'class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null),
			'menu_pages' => array('type' => 'bin'),
			'date_register'
		)
	);

	/**
	 * backend_controller_pages constructor.
	 * @param null|object $t
	 */
    public function __construct($t = null) {
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
        if (http_request::isGet('id')) $this->id_pages = $formClean->simpleClean($_GET['id']);
        elseif (http_request::isPost('id')) $this->id_pages = $formClean->simpleClean($_POST['id']);
        if (http_request::isPost('parent_id')) $this->parent_id = $formClean->simpleClean($_POST['parent_id']);
        if (http_request::isPost('menu_pages')) $this->menu_pages = $formClean->simpleClean($_POST['menu_pages']);
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
        if (http_request::isGet('editimg')) $this->editimg = $formClean->numeric($_GET['editimg']);
        if (http_request::isPost('id_img')) $this->id_img = $formClean->simpleClean($_POST['id_img']);
        if (isset($_FILES['img_multiple']["name"])) $this->img_multiple = ($_FILES['img_multiple']["name"]);
        if (http_request::isPost('imgData')) {
            $array = $_POST['imgData'];
            foreach ($array as $key => $arr) {
                foreach ($arr as $k => $v) {
                    $array[$key][$k] = $formClean->simpleClean($v);
                }
            }
            $this->imgData = $array;
        }
        // --- Image Upload
        if (isset($_FILES['img']["name"])) $this->img = http_url::clean($_FILES['img']["name"]);
		if (http_request::isPost('name_img')) $this->name_img = http_url::clean($_POST['name_img']);

        // --- Recursive Actions
        if (http_request::isGet('pages'))  $this->pages = $formClean->arrayClean($_GET['pages']);

        # ORDER PAGE
        if (http_request::isPost('pages')) $this->order = $formClean->arrayClean($_POST['pages']);
        # ORDER IMAGE
        if (http_request::isPost('image')) $this->order_img = $formClean->arrayClean($_POST['image']);
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
		$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
		$params = array();

		if($this->edit) {
			$results = $this->getItems('pagesChild',$this->edit,'all',false);
		}
		else {
			$results = $this->getItems('pages',array('default_lang'=>$defaultLanguage['id_lang']),'all',false, true);
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

		$this->data->getScheme(array('mc_cms_page','mc_cms_page_content','mc_cms_page_img','mc_cms_page_img_content'),array('id_pages','name_pages','default_img','resume_pages','content_pages','seo_title_pages','seo_desc_pages','menu_pages','date_register'),$assign);

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
        $arr = [];
        $conf = [];

        foreach ($data as $page) {

            $publicUrl = !empty($page['url_pages']) ? $this->routingUrl->getBuildUrl([
				'type' => 'pages',
				'iso' => $page['iso_lang'],
				'id' => $page['id_pages'],
				'url' => $page['url_pages']
			]) : '';

            if (!array_key_exists($page['id_pages'], $arr)) {
                $arr[$page['id_pages']] = [
					'id_pages' => $page['id_pages'],
					'id_parent' => $page['id_parent'],
					'menu_pages' => $page['menu_pages'],
					'date_register' => $page['date_register']
				];
            }
            $arr[$page['id_pages']]['content'][$page['id_lang']] = [
				'id_lang' => $page['id_lang'],
				'iso_lang' => $page['iso_lang'],
				'name_pages' => $page['name_pages'],
				'url_pages' => $page['url_pages'],
				'link_label_pages' => $page['link_label_pages'],
				'link_title_pages' => $page['link_title_pages'],
				'resume_pages' => $page['resume_pages'],
				'content_pages' => $page['content_pages'],
				'seo_title_pages' => $page['seo_title_pages'],
				'seo_desc_pages' => $page['seo_desc_pages'],
				'published_pages' => $page['published_pages'],
				'public_url' => $publicUrl
			];
        }
        return $arr;
    }

    /**
     * Load img Data
     * @param $data
     * @return array
     */
    private function setItemsImgData($data){
        $arr = array();

        foreach ($data as $page) {

            if (!array_key_exists($page['id_img'], $arr)) {
                $arr[$page['id_img']] = array();
                $arr[$page['id_img']]['id_img'] = $page['id_img'];
                $arr[$page['id_img']]['id_pages'] = $page['id_pages'];
                $arr[$page['id_img']]['name_img'] = $page['name_img'];
                $img_pages = pathinfo($page['name_img']);
                $arr[$page['id_img']]['name_img_we'] = $img_pages['filename'];
            }
            if($page['id_lang'] != null) {
                $arr[$page['id_img']]['content'][$page['id_lang']] = array(
                    'id_lang' => $page['id_lang'],
                    'iso_lang' => $page['iso_lang'],
                    'alt_img' => $page['alt_img'],
                    'title_img' => $page['title_img'],
                    'caption_img' => $page['caption_img']
                );
            }
        }
        return $arr;
    }

    /**
     * @param int $id
     * @return array|void
     * @throws Exception
     */
	private function saveContent(int $id) {
		$extendData = [];

		foreach ($this->content as $lang => $content) {
			$content['id_lang'] = $lang;
			$content['id_pages'] = $id;
			$content['published_pages'] = (!isset($content['published_pages']) ? 0 : 1);
			$content['resume_pages'] = (!empty($content['resume_pages']) ? $content['resume_pages'] : NULL);
			$content['content_pages'] = (!empty($content['content_pages']) ? $content['content_pages'] : NULL);
			$content['link_label_pages'] = (!empty($content['link_label_pages']) ? $content['link_label_pages'] : NULL);
			$content['link_title_pages'] = (!empty($content['link_title_pages']) ? $content['link_title_pages'] : NULL);
			$content['seo_title_pages'] = (!empty($content['seo_title_pages']) ? $content['seo_title_pages'] : NULL);
			$content['seo_desc_pages'] = (!empty($content['seo_desc_pages']) ? $content['seo_desc_pages'] : NULL);

			if (empty($content['url_pages'])) {
				$content['url_pages'] = http_url::clean($content['name_pages'],[
					'dot' => false,
					'ampersand' => 'strict',
					'cspec' => '', 'rspec' => ''
				]);
			}

			$contentPage = $this->getItems('content',['id_pages'=>$id, 'id_lang'=>$lang],'one',false);

			if($contentPage != null) {
				$this->upd([
					'type' => 'page',
					'data' => [
						'id_pages' => $id,
						'id_parent' => empty($this->parent_id) ? NULL : $this->parent_id,
						'menu_pages' => isset($this->menu_pages) ? 1 : 0
					]
				]);
				$this->upd([
					'type' => 'content',
					'data' => $content
				]);
			}
			else {
				$this->add([
					'type' => 'content',
					'data' => $content
				]);
			}

			if(isset($this->id_pages)) {
				$setEditData = $this->getItems('page', array('edit'=>$this->edit),'all',false);
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
            case 'newImgContent':
            case 'newImg':
				parent::insert(
					array(
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
                            'order_pages'    => $i + (isset($this->offset) ? ($this->offset + 1) : 0)
                        )
                    );
                }
                break;
            case 'order_img':
                $p = $this->order_img;
                for ($i = 0; $i < count($p); $i++) {
                    parent::update(
                        array(
                            'type' => $data['type']
                        ),
                        array(
                            'id_img'       => $p[$i],
                            'order_img'    => $i
                        )
                    );
                }
                break;
			case 'page':
			case 'content':
            case 'img':
            case 'imgContent':
            case 'firstImageDefault':
			case 'pageActiveMenu':
				parent::update(
					array(
						'type' => $data['type']
					),
					$data['data']
				);
				break;
            case 'imageDefault':
                parent::update(
                    array(
                        'type' => 'imageDefault'
                    ),
                    $data['data']
                );
                $this->message->json_post_response(true,'update');
                break;
        }
    }

    /**
     * Insertion de données
     * @param $data
     * @throws Exception
     */
    private function del($data) {
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
            case 'delImages':
                $makeFiles = new filesystem_makefile();
                $imgArray = explode(',',$data['data']['id']);
                $fetchConfig = $this->imagesComponent->getConfigItems('pages','pages');
                $defaultErased = false;
				$id_pages = false;
                $extwebp = 'webp';
				$toRemove = [];

                foreach($imgArray as $value){
                    $img = $this->getItems('img',$value,'one',false);

					if(!empty($img) && !empty($img['id_pages']) && !empty($img['name_img'])) {
						// Get the product's id
						$id_pages = $img['id_pages'];
						// Check if it's the default image that's going to be erased
						if($img['default_img']) $defaultErased = true;
						// Concat the image directory path
						//$imgPath = $this->upload->dirFileUpload(['upload_root_dir' => 'upload/catalog/product', 'upload_dir' => $id_product, 'fileBasePath'=>true]);
						$imgPath = $this->routingUrl->dirUpload('upload/pages/'.$id_pages, true);

						// Original file of the image
						$original = $imgPath.$img['name_img'];
						if(file_exists($original)) $toRemove[] = $original;

						// Loop over each version of the image
						foreach ($fetchConfig as $configKey => $confiValue) {
							$image = $imgPath.$confiValue['prefix'].'_'.$img['name_img'];
							if(file_exists($image)) $toRemove[] = $image;

							// Check if the image with webp extension exist
							$imgData = pathinfo($img['name_img']);
							$filename = $imgData['filename'];
							$webpImg = $imgPath.$confiValue['prefix'].'_'.$filename.'.'.$extwebp;
							if(file_exists($webpImg)) $toRemove[] = $webpImg;
						}
					}
                }

				// If files had been found
				if(!empty($toRemove)) {
					// Erased images
					$makeFiles->remove($toRemove);

					// Remove from database
					parent::delete(
						['type' => $data['type']],
						$data['data']
					);

					// Count the remaining images
					$imgs = $this->getItems('countImages',$id_pages,'one',false);

					// If there is at leats one image left and the default image has been erased, set the first remaining image as default
					if($imgs['tot'] > 0 && $defaultErased) {
						$this->upd([
							'type' => 'firstImageDefault',
							'data' => ['id' => $id_pages]
						]);
					}
				}

                break;
            case 'delImagesPages':
                $makeFiles = new filesystem_makefile();
                $imgArray = explode(',',$data['data']['id']);

                foreach($imgArray as $value){
					$imgPath = $this->routingUrl->dirUpload('upload/catalog/pages/'.$value,true);

                    if(file_exists($imgPath)) {
                        try {
                            $makeFiles->remove(array($imgPath));
                        } catch(Exception $e) {
                            $logger = new debug_logger(MP_LOG_DIR);
                            $logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
                        }
                    }
                }
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
									'type' => 'page',
									'data' => array(
										'id_parent' => empty($this->parent_id) ? NULL : $this->parent_id,
										'menu_pages' => isset($this->menu_pages) ? 1 : 0
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
                        if (isset($this->content)) {
							$extendData = $this->saveContent($this->id_pages);
							$this->message->json_post_response(true, 'update', array('result'=>$this->id_pages,'extend'=>$extendData));
						}
                        elseif (isset($this->img_multiple)) {
                            $this->template->configLoad();
                            $this->progress = new component_core_feedback($this->template);

                            usleep(200000);
                            $this->progress->sendFeedback(['message' => $this->template->getConfigVars('control_of_data'), 'progress' => 30]);

                            $defaultLanguage = $this->collectionLanguage->fetchData(['context' => 'one', 'type' => 'default']);
                            $page = $this->getItems('pageLang', ['id' => $this->id_pages, 'iso' => $defaultLanguage['iso_lang']], 'one', false);
                            $newimg = $this->getItems('lastImgId', ['id_pages' => $this->id_pages], 'one', false);
                            $newimg['index'] = $newimg['index'] ?? 0;

							$resultUpload = $this->upload->multipleImageUpload('pages','pages','upload/pages',["$this->id_pages"],[
								'name' => $page['url_pages'],
								'suffix' => (int)$newimg['index'],
								'suffix_increment' => true,
								'progress' => $this->progress,
								'template' => $this->template
							],false);

                            if (!empty($resultUpload)) {
								$totalUpload = count($resultUpload);
								$percent = $this->progress->progress;
								$preparePercent = (90 - $percent) / $totalUpload;
								$i = 1;

                                foreach ($resultUpload as $value) {
                                    if ($value['status']) {
                                        $percent = $percent + $preparePercent;

										usleep(200000);
										$this->progress->sendFeedback(['message' => sprintf($this->template->getConfigVars('creating_records'),$i,$totalUpload), 'progress' => $percent]);

                                        $this->add([
											'type' => 'newImg',
											'data' => [
												'id_pages' => $this->id_pages,
												'name_img' => $value['file']
											]
										]);
                                    }
									$i++;
                                }

                                usleep(200000);
                                $this->progress->sendFeedback(array('message' => $this->template->getConfigVars('creating_thumbnails_success'), 'progress' => 90));

                                usleep(200000);
                                $this->progress->sendFeedback(array('message' => $this->template->getConfigVars('upload_done'), 'progress' => 100, 'status' => 'success'));
                            }
							else {
                                usleep(200000);
                                $this->progress->sendFeedback(array('message' => $this->template->getConfigVars('creating_thumbnails_error'), 'progress' => 100, 'status' => 'error', 'error_code' => 'error_data'));
                            }
                        }
						elseif (isset($this->editimg)) {
                            if (isset($this->id_img)) {
                                foreach ($this->imgData as $lang => $content) {
                                    $content['id_img'] = $this->id_img;
                                    $content['id_lang'] = $lang;
                                    $content['alt_img'] = (!empty($content['alt_img']) ? $content['alt_img'] : NULL);
                                    $content['title_img'] = (!empty($content['title_img']) ? $content['title_img'] : NULL);
                                    $content['caption_img'] = (!empty($content['caption_img']) ? $content['caption_img'] : NULL);

                                    $checkLangData = parent::fetchData(
                                        array('context' => 'one', 'type' => 'imgContent'),
                                        array('id_img' => $this->id_img, 'id_lang' => $lang)
                                    );

                                    // Check language page content
                                    if ($checkLangData != null) {
                                        $this->upd(array(
                                            'type' => 'imgContent',
                                            'data' => $content
                                        ));
                                    } else {
                                        $this->add(array(
                                            'type' => 'newImgContent',
                                            'data' => $content
                                        ));
                                    }
                                }

                                if (isset($this->name_img)) {
                                    $page = $this->getItems('img', array('id' => $this->id_img), 'one', false);
                                    $img_pages = pathinfo($page['name_img']);
                                    $img_name = $img_pages['filename'];

                                    if ($this->name_img !== $img_name && $this->name_img !== '') {
										$result = $this->upload->renameImages('pages','pages',$page['name_img'],$this->name_img,'upload/pages',[$page['id_pages']]);

										if($result) {
											$this->upd([
												'type' => 'img',
												'data' => [
													'id_img' => $this->id_img,
													'name_img' => $this->name_img.'.'.$img_pages['extension']
												]
											]);
										}
                                    }
                                }

                                $this->message->json_post_response(true, 'add_redirect');
                            }
							else {
                                $this->modelLanguage->getLanguage();
                                $setEditData = parent::fetchData(
                                    array('context' => 'all', 'type' => 'imgData'),
                                    array('edit' => $this->editimg)
                                );
                                $setEditData = $this->setItemsImgData($setEditData);
                                $this->template->assign('img', $setEditData[$this->editimg]);
                                $this->template->display('pages/edit-img.tpl');
                            }
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
							$setEditData = $this->getItems('page', array('edit'=>$this->edit),'all',false);
                            $setEditData = $this->setItemData($setEditData);
                            $this->template->assign('page',$setEditData[$this->edit]);
							$this->data->getScheme(array('mc_cms_page','mc_cms_page_content','mc_cms_page_img','mc_cms_page_img_content'),array('id_pages','name_pages','default_img','resume_pages','content_pages','seo_title_pages','seo_desc_pages','menu_pages','date_register'),$this->tableconfig['parent']);
							$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
                            $this->getItems('pagesChild',array('id'=>$this->edit,'default_lang'=>$defaultLanguage['id_lang']),'all');
							$this->getItems('pagesSelect',array('default_lang'=>$defaultLanguage['id_lang']),'all');
                            // --- pages images
                            $this->getItems('images', $this->edit, 'all');
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
                    case 'setImgDefault':
                        if (isset($this->id_img)) {
                            $this->upd(array(
                                'type' => 'imageDefault',
                                'data' => array(':id' => $this->edit, ':id_img' => $this->id_img)
                            ));
                        }
                        break;
                    case 'getImgDefault':
                        if (isset($this->edit)) {
                            $imgDefault = $this->getItems('imgDefault', $this->edit, 'one', false);
                            print $imgDefault['id_img'];
                        }
                        break;
                    case 'getImages':
                        if (isset($this->edit)) {
                            $this->getItems('images', $this->edit, 'all');
                            $display = $this->template->fetch('pages/brick/img.tpl');
                            $this->message->json_post_response(true, '', $display);
                        }
                        break;
                    case 'orderImages':
                        if (isset($this->order_img)) {
                            $this->upd(
                                array(
                                    'type' => 'order_img'
                                )
                            );
                        }
                        break;
                    case 'delete':
                        if(isset($this->id_pages)) {
                            if (isset($this->tabs)) {
                                switch ($this->tabs) {
                                    case 'image':
                                        $this->del(
                                            array(
                                                'type' => 'delImages',
                                                'data' => array(
                                                    'id' => $this->id_pages
                                                )
                                            )
                                        );
                                        $this->message->json_post_response(true, 'delete', array('id' => $this->id_pages));
                                        break;
                                }
                            }
							else{
                                $this->del(
                                    array(
                                        'type'=>'delPages',
                                        'data'=>array(
                                            'id' => $this->id_pages
                                        )
                                    )
                                );
                                $this->del(
                                    array(
                                        'type' => 'delImagesPages',
                                        'data' => array(
                                            'id' => $this->id_pages
                                        )
                                    )
                                );
                            }
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
				$this->getItems('pages',array('default_lang'=>$defaultLanguage['id_lang']),'all',true,true);
                $this->data->getScheme(array('mc_cms_page','mc_cms_page_content','mc_cms_page_img','mc_cms_page_img_content'),array('id_pages','name_pages','default_img','resume_pages','content_pages','seo_title_pages','seo_desc_pages','menu_pages','date_register'),$this->tableconfig['parent']);
                $this->template->display('pages/index.tpl');
            }
        }
    }
}