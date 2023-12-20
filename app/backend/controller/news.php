<?php
class backend_controller_news extends backend_db_news {
    public $edit, $action, $tabs, $search, $plugin, $controller, $lang;
    protected $message, $template, $header, $data, $modelLanguage, $collectionLanguage, $order, $upload, $config, $imagesComponent, $modelPlugins,$makeFiles,$finder,$routingUrl;
    public $id_news,$content,$news,$id_img,$id_lang,$name_tag,$del_img,$ajax,$tableaction,$tableform,$iso,$name_img,$newsData,$imgData, $img_multiple, $progress, $editimg;
	public $tableconfig = [
		'id_news',
		'name_news',
		'content_news' => ['type' => 'bin', 'input' => null],
        'default_img' => ['title' => 'img', 'class' => 'fixed-td-md text-center', 'type' => 'bin', 'input' => null],
		'seo_title_news' => ['title' => 'seo_title', 'class' => '', 'type' => 'bin', 'input' => null],
		'seo_desc_news' => ['title' => 'seo_desc', 'class' => '', 'type' => 'bin', 'input' => null],
		'last_update' => ['title' => 'last_update', 'input' => ['type' => 'text', 'class' => 'date-input']],
		'date_publish',
		'published_news'
	];

    /**
     * backend_controller_news constructor.
     * @param ?backend_model_template $t
     * @throws Exception
     */
    public function __construct(?backend_model_template $t = null) {
        $this->template = $t instanceof backend_model_template ? $t : new backend_model_template;
        $this->lang = $this->template->lang;
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
        if (http_request::isGet('controller')) $this->controller = $formClean->simpleClean($_GET['controller']);
        if (http_request::isGet('edit')) $this->edit = $formClean->numeric($_GET['edit']);
        if (http_request::isGet('action')) $this->action = $formClean->simpleClean($_GET['action']);
        elseif (http_request::isPost('action')) $this->action = $formClean->simpleClean($_POST['action']);
        if (http_request::isGet('tabs')) $this->tabs = $formClean->simpleClean($_GET['tabs']);
        if (http_request::isGet('editimg')) $this->editimg = $formClean->numeric($_GET['editimg']);

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
        if (http_request::isGet('id')) $this->id_news = $formClean->simpleClean($_GET['id']);
        elseif (http_request::isPost('id')) $this->id_news = $formClean->simpleClean($_POST['id']);
        if (http_request::isPost('id_img')) $this->id_img = $formClean->simpleClean($_POST['id_img']);
        if (http_request::isPost('del_img')) $this->del_img = $formClean->simpleClean($_POST['del_img']);
        if (http_request::isPost('content')) {
            $array = $_POST['content'];
            foreach($array as $key => $arr) {
                foreach($arr as $k => $v) {
                    $array[$key][$k] = ($k == 'content_news') ? $formClean->cleanQuote($v) : $formClean->simpleClean($v);
                }
            }
            $this->content = $array;
        }
        if (http_request::isPost('newsData')) $this->newsData = $formClean->arrayClean($_POST['newsData']);

        // --- Image Upload
        //if (isset($_FILES['img']["name"])) $this->img = http_url::clean($_FILES['img']["name"]);
		if (http_request::isPost('name_img')) $this->name_img = http_url::clean($_POST['name_img']);
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
		// --- Recursive Actions
        if (http_request::isGet('news')) $this->news = $formClean->arrayClean($_GET['news']);

        # ORDER PAGE
        //if (http_request::isPost('news')) $this->order = $formClean->arrayClean($_POST['news']);
        # ORDER PAGE
        if (http_request::isPost('image')) $this->order = $formClean->arrayClean($_POST['image']);

        # REMOVE TAG
        if (http_request::isPost('id_lang')) $this->id_lang = $formClean->simpleClean($_POST['id_lang']);
        if (http_request::isPost('name_tag')) $this->name_tag = $formClean->simpleClean($_POST['name_tag']);
        # plugin
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
		$defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'one', 'type' => 'default'));
		$results = $this->getItems('news', array('default_lang' => $defaultLanguage['id_lang']), 'all',false,true);
		$params = array();

		if($ajax) {
			$params['section'] = 'news';
			$params['idcolumn'] = 'id_news';
			$params['activation'] = true;
			$params['sortable'] = true;
			$params['checkbox'] = true;
			$params['edit'] = true;
			$params['dlt'] = true;
			$params['readonly'] = array();
			$params['cClass'] = 'backend_controller_news';
		}

		$this->data->getScheme(
			array('mc_news', 'mc_news_content'),
			array('id_news', 'name_news', 'content_news', 'img_news', 'last_update', 'date_publish', 'published_news'),
			$this->tableconfig);

		return array(
			'data' => $results,
			'var' => 'news',
			'tpl' => 'news/index.tpl',
			'params' => $params
		);
	}

	public function tinymce()
	{
		$langs = $this->modelLanguage->setLanguage();
		foreach($langs as $k => $iso) {
			$list = $this->getItems('pagesPublishedSelect',array('lang'=> $k),'all',false);

			$lists[$k] = $this->data->setPagesTree($list,'news');
		}
		$this->template->assign('langs',$langs);
		$this->template->assign('news',$lists);
		$this->template->display('tinymce/news/mc_news.tpl');
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
     * @throws Exception
     */
    private function setItemData($data){
        $imgPath = $this->routingUrl->basePath('upload/news');
        $arr = [];
        $fetchConfig = $this->imagesComponent->getConfigItems('news','news');
        //$imgPrefix = $this->imagesComponent->prefix();

        foreach ($data as $page) {
            $dateFormat = new date_dateformat();
            $datePublish = !empty($page['date_publish']) ? $dateFormat->dateToDefaultFormat($page['date_publish']) : $dateFormat->dateToDefaultFormat();
            $dateEventStart = !empty($page['date_event_start']) ? $dateFormat->dateToDefaultFormat($page['date_event_start']) : NULL;
            $dateEventEnd = !empty($page['date_event_end']) ? $dateFormat->dateToDefaultFormat($page['date_event_end']) : NULL;
            $publicUrl = !empty($page['url_news']) ? '/'.$page['iso_lang'].'/news/'.$datePublish.'/'.$page['id_news'].'-'.$page['url_news'].'/' : '';
            if (!array_key_exists($page['id_news'], $arr)) {
                $arr[$page['id_news']] = array();
                $arr[$page['id_news']]['id_news'] = $page['id_news'];
                $arr[$page['id_news']]['date_publish'] = $datePublish;
                $arr[$page['id_news']]['date_event_start'] = $dateEventStart;
                $arr[$page['id_news']]['date_event_end'] = $dateEventEnd;
				/*$img_pages = pathinfo($page['img_news']);
				$arr[$page['id_news']]['img_news'] = $img_pages['filename'];
                if($page['img_news'] != null) {
                    if(file_exists($imgPath.DIRECTORY_SEPARATOR.$page['id_news'].DIRECTORY_SEPARATOR.$page['img_news'])) {
                        $originalSize = getimagesize($imgPath . DIRECTORY_SEPARATOR . $page['id_news'] . DIRECTORY_SEPARATOR . $page['img_news']);
                        $arr[$page['id_news']]['imgSrc']['original']['img'] = $page['img_news'];
                        $arr[$page['id_news']]['imgSrc']['original']['width'] = $originalSize[0];
                        $arr[$page['id_news']]['imgSrc']['original']['height'] = $originalSize[1];
                    }
                    foreach ($fetchConfig as $key => $value) {
                        $size = getimagesize($imgPath.DIRECTORY_SEPARATOR.$page['id_news'].DIRECTORY_SEPARATOR.$value['prefix'] . '_' . $page['img_news']);
                        $arr[$page['id_news']]['imgSrc'][$value['type']]['img'] = $value['prefix'] . '_' . $page['img_news'];
                        $arr[$page['id_news']]['imgSrc'][$value['type']]['width'] = $size[0];
                        $arr[$page['id_news']]['imgSrc'][$value['type']]['height'] = $size[1];
                    }
                }*/
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

            $arr[$page['id_news']]['content'][$page['id_lang']] = [
				'id_lang' => $page['id_lang'],
				'iso_lang' => $page['iso_lang'],
				'name_news' => $page['name_news'],
                'longname_news'=>$page['longname_news'],
				'url_news' => $page['url_news'],
				'resume_news' => $page['resume_news'],
				'content_news' => $page['content_news'],
				/*'alt_img' => $page['alt_img'],
				'title_img' => $page['title_img'],
				'caption_img' => $page['caption_img'],*/
				'link_label_news' => $page['link_label_news'],
				'link_title_news' => $page['link_title_news'],
				'seo_title_news' => $page['seo_title_news'],
				'seo_desc_news' => $page['seo_desc_news'],
				'published_news' => $page['published_news'],
				'public_url' => $publicUrl,
				'tags_news' => $page['tags_news'],
				'tags' => $tags
			];
        }

        return $arr;
    }

	/**
	 * @param $id
	 * @throws Exception
	 */
	private function checkTag($id)
	{
		// On compte le nombre de tags restant
		$countTags = parent::fetchData(
			array('context' => 'one', 'type' => 'countTags'),
			array('id_tag' => $id)
		);
		//Si le nombre est égal 0 on supprime le tag définitivement.
		if($countTags['tags'] == '0'){
			parent::delete(array('type' => 'tags'), array('id_tag' => $id));
		}
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
			$data = $content;
			unset($data['tag_news']);
			$data['id_lang'] = $lang;
			$data['id_news'] = $id;
            $data['name_news'] = (!empty($content['name_news']) ? $content['name_news'] : NULL);
            $data['longname_news'] = (!empty($content['longname_news']) ? $content['longname_news'] : NULL);
			$data['published_news'] = (!isset($content['published_news']) ? 0 : 1);
            $data['resume_news'] = (!empty($content['resume_news']) ? $content['resume_news'] : NULL);
            $data['content_news'] = (!empty($content['content_news']) ? $content['content_news'] : NULL);
            $data['link_label_news'] = (!empty($content['link_label_news']) ? $content['link_label_news'] : NULL);
            $data['link_title_news'] = (!empty($content['link_title_news']) ? $content['link_title_news'] : NULL);
            $data['seo_title_news'] = (!empty($content['seo_title_news']) ? $content['seo_title_news'] : NULL);
            $data['seo_desc_news'] = (!empty($content['seo_desc_news']) ? $content['seo_desc_news'] : NULL);
			if (empty($content['url_news'])) {
				$data['url_news'] = http_url::clean($content['name_news'],
					array(
						'dot' => false,
						'ampersand' => 'strict',
						'cspec' => '', 'rspec' => ''
					)
				);
			}

			$contentPage = $this->getItems('content',array('id_news'=>$id, 'id_lang'=>$lang),'one',false);

			if($contentPage != null) {
				$this->upd(
					array(
						'type' => 'content',
						'data' => $data
					)
				);
			}
			else {
				$this->add(
					array(
						'type' => 'content',
						'data' => $data
					)
				);
			}

			if(isset($this->id_news)) {
				// Add Tags
				if(!empty($content['tag_news'])) {
					$tagNews = explode(',', $content['tag_news']);
					if ($tagNews != null) {
						foreach ($tagNews as $key => $value) {
							$setTags = $this->getItems('tag',array(':id_news' => $this->id_news, ':id_lang' => $lang, ':name_tag' => $value),'one',false);
							if ($setTags['id_tag'] != null) {
								if ($setTags['rel_tag'] == null) {
									$this->add(array(
										'type' => 'newTagRel',
										'data' => array(
											'id_news'=> $this->id_news,
											'id_tag' => $setTags['id_tag']
										)
									));
								}
							} else {
								$this->add(array(
									'type' => 'newTagComb',
									'data' => array(
										'id_news' => $this->id_news,
										'id_lang' => $lang,
										'name_tag'=> $value
									)
								));
							}
						}
					}
				}

				$setEditData = $this->getItems('page', array('edit'=>$this->id_news),'all',false);
				$setEditData = $this->setItemData($setEditData);
				$extendData[$lang] = $setEditData[$this->id_news]['content'][$lang]['public_url'];
			}
		}

		if(!empty($extendData)) return $extendData;
	}
    /**
     * @param $data
     * @return array
     */
    private function setItemsImgData($data){
        $arr = array();

        foreach ($data as $page) {

            if (!array_key_exists($page['id_img'], $arr)) {
                $arr[$page['id_img']] = array();
                $arr[$page['id_img']]['id_img'] = $page['id_img'];
                $arr[$page['id_img']]['id_news'] = $page['id_news'];
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
	 * Update data
	 * @param $data
	 */
	private function add($data)
	{
		switch ($data['type']) {
			case 'page':
			case 'newTagRel':
			case 'newTagComb':
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
     */
    private function upd($data)
    {
        switch ($data['type']) {
            case 'page':
            case 'content':
            case 'img':
            case 'imgContent':
            case 'firstImageDefault':
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
            case 'order':
                $p = $this->order;
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
        }
    }

    /**
     * Insertion de données
     * @param $data
     * @throws Exception
     */
    private function del($data){
        switch($data['type']){
			case 'delPages':
				$ids = explode(',',$this->id_news);
				foreach ($ids as $id) {
					$tags = $this->getItems('tags_rel',$id,'all',false);
					parent::delete(
						array('type' => $data['type']),
						array('id' => $id)
					);
					foreach ($tags as $tag) {
						$this->checkTag($tag['id_tag']);
					}
				}

				$this->message->json_post_response(true,'delete',$data['data']);
				break;
            case 'delImages':
                $makeFiles = new filesystem_makefile();
                $imgArray = explode(',',$data['data']['id']);
                $fetchConfig = $this->imagesComponent->getConfigItems('news','news');
                $defaultErased = false;
                $id_news = false;
                $extwebp = 'webp';
                // Array of images to erased at the end
                $toRemove = [];

                foreach($imgArray as $value){
                    // Get images stored information
                    $img = $this->getItems('img',$value,'one',false);

                    if(!empty($img) && !empty($img['id_news']) && !empty($img['name_img'])) {
                        // Get the product's id
                        $id_news = $img['id_news'];
                        // Check if it's the default image that's going to be erased
                        if($img['default_img']) $defaultErased = true;
                        // Concat the image directory path
                        //$imgPath = $this->upload->dirFileUpload(['upload_root_dir' => 'upload/catalog/product', 'upload_dir' => $id_news, 'fileBasePath'=>true]);
                        $imgPath = $this->routingUrl->dirUpload('upload/news/'.$id_news, true);

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
                    $imgs = $this->getItems('countImages',$id_news,'one',false);

                    // If there is at leats one image left and the default image has been erased, set the first remaining image as default
                    if($imgs['tot'] > 0 && $defaultErased) {
                        $this->upd([
                            'type' => 'firstImageDefault',
                            'data' => ['id' => $id_news]
                        ]);
                    }
                }
                break;
        }
    }
    /**
     *
     */
    public function run() {
        $this->modelPlugins->getItems(
            array(
                'type'      =>  'tabs',
                'controller'=>  $this->controller
            )
        );
        if(isset($this->plugin)) {
            // Execute un plugin core
            $this->modelLanguage->getLanguage();
            $defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'one', 'type' => 'default'));
            $this->getItems('news', array('default_lang' => $defaultLanguage['id_lang']), 'all',true,true);

            if(isset($this->action) && $this->edit !== '') {
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
                        // Execute un plugin core
                        $this->modelPlugins->getCoreItem();
                        break;
                }
            }
            else {
                $this->modelPlugins->getCoreItem();
            }
        }
        else {
			if(isset($this->tableaction)) {
				$this->tableform->run();
			}
			elseif (isset($this->action)) {
                switch ($this->action) {
                    case 'add':
						if(isset($this->content)) {
                            $dateFormat = new date_dateformat();
                            $datePublish = !empty($this->newsData['date_publish']) ? $dateFormat->SQLDateTime($this->newsData['date_publish']) : $dateFormat->SQLDateTime($dateFormat->dateToDefaultFormat());
                            $dateEventStart = !empty($this->newsData['date_event_start']) ? $dateFormat->SQLDateTime($this->newsData['date_event_start']) : NULL;
                            $dateEventEnd = !empty($this->newsData['date_event_end']) ? $dateFormat->SQLDateTime($this->newsData['date_event_end']) : NULL;

							$this->add(
								array(
									'type' => 'page',
                                    'data' => array(
                                        'date_publish' => $datePublish,
                                        'date_event_start' => $dateEventStart,
                                        'date_event_end' => $dateEventEnd
                                    )
								)
							);

							$page = $this->getItems('root',null,'one',false);

							if ($page['id_news']) {
								$this->saveContent($page['id_news']);
								$this->message->json_post_response(true,'add_redirect');
							}
						}
						else {
							$this->modelLanguage->getLanguage();
							$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
							$this->getItems('pagesSelect',array('default_lang'=>$defaultLanguage['id_lang']),'all');
							$this->template->display('news/add.tpl');
						}
                        break;
                    case 'edit':
						if (isset($this->id_news)) {
                            if($this->content) {
                                $dateFormat = new date_dateformat();
                                $datePublish = !empty($this->newsData['date_publish']) ? $dateFormat->SQLDateTime($this->newsData['date_publish']) : $dateFormat->SQLDateTime($dateFormat->dateToDefaultFormat());
                                $dateEventStart = !empty($this->newsData['date_event_start']) ? $dateFormat->SQLDateTime($this->newsData['date_event_start']) : NULL;
                                $dateEventEnd = !empty($this->newsData['date_event_end']) ? $dateFormat->SQLDateTime($this->newsData['date_event_end']) : NULL;

                                $this->upd(array(
                                    'type' => 'page',
                                    'data' => array(
                                        'id_news' => $this->id_news,
                                        'date_publish' => $datePublish,
                                        'date_event_start' => $dateEventStart,
                                        'date_event_end' => $dateEventEnd
                                    )
                                ));
                                $extendData = $this->saveContent($this->id_news);
                                $this->message->json_post_response(true, 'update', array('result' => $this->id_news, 'extend' => $extendData));
                            }
                            elseif (isset($this->img_multiple)) {
                                $this->template->configLoad();
                                $this->progress = new component_core_feedback($this->template);

                                usleep(200000);
                                $this->progress->sendFeedback(array('message' => $this->template->getConfigVars('control_of_data'), 'progress' => 30));

                                $defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'one', 'type' => 'default'));
                                $news = $this->getItems('content', array('id_news' => $this->id_news, 'id_lang' => $defaultLanguage['id_lang']), 'one', false);
                                $newimg = $this->getItems('lastImgId', ['id_news' => $this->id_news], 'one', false);
                                // If $newimg = NULL return 0
                                $newimg['index'] = $newimg['index'] ?? 0;

                                $resultUpload = $this->upload->multipleImageUpload(
                                    'news','news','upload/news',["$this->id_news"],[
                                    'name' => $news['url_news'],
                                    'suffix' => (int)$newimg['index'],
                                    'suffix_increment' => true,
                                    'progress' => $this->progress,
                                    'template' => $this->template
                                ],false);

                                if(!empty($resultUpload)) {
                                    $totalUpload = count($resultUpload);
                                    $percent = $this->progress->progress;
                                    $preparePercent = (90 - $percent) / $totalUpload;
                                    $i = 1;

                                    foreach ($resultUpload as $value) {
                                        if($value['status']) {
                                            $percent = $percent + $preparePercent;

                                            usleep(200000);
                                            $this->progress->sendFeedback(['message' => sprintf($this->template->getConfigVars('creating_records'),$i,$totalUpload), 'progress' => $percent]);

                                            $this->add([
                                                'type' => 'newImg',
                                                'data' => [
                                                    'id_news' => $this->id_news,
                                                    'name_img' => $value['file']
                                                ]
                                            ]);
                                        }
                                        $i++;
                                    }

                                    usleep(200000);
                                    $this->progress->sendFeedback(['message' => $this->template->getConfigVars('creating_thumbnails_success'), 'progress' => 90]);

                                    usleep(200000);
                                    $this->progress->sendFeedback(['message' => $this->template->getConfigVars('upload_done'), 'progress' => 100, 'status' => 'success']);
                                }
                                else {
                                    usleep(200000);
                                    $this->progress->sendFeedback(['message' => $this->template->getConfigVars('creating_thumbnails_error'), 'progress' => 100, 'status' => 'error', 'error_code' => 'error_data']);
                                }
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
                                        ['context' => 'one', 'type' => 'imgContent'],
                                        ['id_img' => $this->id_img, 'id_lang' => $lang]
                                    );

                                    // Check language page content
                                    if ($checkLangData != null) {
                                        $this->upd([
                                            'type' => 'imgContent',
                                            'data' => $content
                                        ]);
                                    }
                                    else {
                                        $this->add([
                                            'type' => 'newImgContent',
                                            'data' => $content
                                        ]);
                                    }
                                }

                                if (isset($this->name_img)) {
                                    $page = $this->getItems('img', ['id' => $this->id_img], 'one', false);
                                    $img_pages = pathinfo($page['name_img']);
                                    $img_name = $img_pages['filename'];

                                    if ($this->name_img !== $img_name && $this->name_img !== '') {
                                        $result = $this->upload->renameImages('news','news',$page['name_img'],$this->name_img,'upload/news',[$page['id_news']]);

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
                            }else {
                                $this->modelLanguage->getLanguage();
                                $setEditData = parent::fetchData(
                                    array('context' => 'all', 'type' => 'imgData'),
                                    array('edit' => $this->editimg)
                                );
                                $setEditData = $this->setItemsImgData($setEditData);
                                $this->template->assign('img', $setEditData[$this->editimg]);
                                $this->template->display('news/edit-img.tpl');
                            }
                        }
                        else {
                            // Initialise l'API menu des plugins core
                            $this->modelPlugins->getItems(
                                array(
                                    'type' => 'tabs',
                                    'controller' => $this->controller
                                )
                            );
                            $this->modelLanguage->getLanguage();
                            $setEditData = $this->getItems('page', array('edit' => $this->edit), 'all', false);
                            $setEditData = $this->setItemData($setEditData);
                            // --- Product images
                            $this->getItems('images', $this->edit, 'all');

                            $this->template->assign('page', $setEditData[$this->edit]);
                            $this->template->display('news/edit.tpl');
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
                            $display = $this->template->fetch('news/brick/img.tpl');
                            $this->message->json_post_response(true, '', $display);
                        }
                        break;
                    case 'orderImages':
                        if (isset($this->order)) {
                            $this->upd(
                                array(
                                    'type' => 'order'
                                )
                            );
                        }
                        break;
                    case 'delete':
                        if (isset($this->name_tag)) {
                            $setTags = parent::fetchData(
                                array('context' => 'one', 'type' => 'tag'),
                                array(':id_news' => $this->id_news, ':id_lang' => $this->id_lang, ':name_tag' => $this->name_tag)
                            );
                            if ($setTags['id_tag'] != null && $setTags['rel_tag'] != null) {
                                parent::delete(array('type' => 'tagRel'), array('id_rel' => $setTags['rel_tag']));

								$this->checkTag($setTags['id_tag']);
                            }
                        }
                        elseif (isset($this->id_news)) {
                            if (isset($this->tabs)) {
                                switch ($this->tabs) {
                                    case 'images':
                                        $this->del([
                                            'type' => 'delImages',
                                            'data' => ['id' => $this->id_news]
                                        ]);
                                        $this->message->json_post_response(true, 'delete', ['id' => $this->id_news]);
                                        break;
                                }
                            }else{
                                $this->del(
                                    array(
                                        'type' => 'delPages',
                                        'data' => array(
                                            'id' => $this->id_news
                                        )
                                    )
                                );
                            }
                        }
                        /*elseif(isset($this->del_img)) {
                            $this->upd(array(
                                'type' => 'img',
                                'data' => array(
									'id_news' => $this->del_img,
									'img_news' => NULL
								)
                            ));

							$setEditData = $this->getItems('page',array('edit'=>$this->del_img),'all',false);
                            $setEditData = $this->setItemData($setEditData);

                            $setImgDirectory = $this->upload->dirImgUpload(
                                array_merge(
                                    array('upload_root_dir'=>'upload/news/'.$this->del_img),
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
                            $display = $this->template->fetch('news/brick/img.tpl');
                            $this->message->json_post_response(true, 'update',$display);
                        }*/
                        break;
					case 'getLink':
						if(isset($this->id_news) && isset($this->iso)) {
							$news = $this->getItems('pageLang',array('id' => $this->id_news,'iso' => $this->iso),'one',false);
							if($news) {
								$news['url'] = $this->routingUrl->getBuildUrl(array(
									'type' => 'news',
									'iso'  => $news['iso_lang'],
									'id'   => $news['id_news'],
									'date' => $news['date_publish'],
									'url'  => $news['url_news']
								));
								$this->header->set_json_headers();
								print '{"name":'.json_encode($news['name_news']).',"url":'.json_encode($news['url']).'}';
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
                $this->getItems('news', array('default_lang' => $defaultLanguage['id_lang']), 'all',true,true);
                $this->data->getScheme(
                	array('mc_news', 'mc_news_content'),
					array('id_news', 'name_news', 'content_news', 'default_img','seo_title_news','seo_desc_news', 'last_update', 'date_publish', 'published_news'),
					$this->tableconfig);
                $this->template->display('news/index.tpl');
            }
        }
    }
}