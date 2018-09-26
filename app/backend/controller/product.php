<?php
class backend_controller_product extends backend_db_product
{
	public $edit, $action, $tabs, $search;
	protected $message, $template, $header, $progress, $data, $modelLanguage, $collectionLanguage, $order, $upload, $config, $imagesComponent, $dbCategory,$routingUrl;
	public $id_product, $id_img, $parent_id, $content, $productData, $imgData, $img_multiple, $editimg, $product_cat, $parent, $default_cat,$product_id, $id_product_2,$ajax,$tableaction,$tableform,$iso;
	public $tableconfig = array(
		'id_product',
		'name_p' => ['title' => 'name'],
		'price_p' => ['type' => 'price','input' => null],
		'reference_p' => ['title' => 'reference'],
		'resume_p' => ['class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null],
		'content_p' => ['class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null],
		'date_register'
	);

	/**
	 * backend_controller_catalog constructor.
	 * @param stdClass $t
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
		$this->dbCategory = new backend_db_category();
		$this->routingUrl = new component_routing_url();

		// --- GET
		if (http_request::isGet('edit')) $this->edit = $formClean->numeric($_GET['edit']);
		if (http_request::isGet('action')) $this->action = $formClean->simpleClean($_GET['action']);
		elseif (http_request::isPost('action')) $this->action = $formClean->simpleClean($_POST['action']);
        if (http_request::isGet('tabs')) $this->tabs = $formClean->simpleClean($_GET['tabs']);
		if (http_request::isGet('editimg')) $this->editimg = $formClean->numeric($_GET['editimg']);
		if (http_request::isGet('parentid')) $this->parent_id = $formClean->numeric($_GET['parentid']);
        if (http_request::isGet('product_id')) $this->product_id = $formClean->numeric($_GET['product_id']);

		if (http_request::isGet('tableaction')) {
			$this->tableaction = $formClean->simpleClean($_GET['tableaction']);
			$this->tableform = new backend_controller_tableform($this,$this->template);
		}

		// --- Search
		if (http_request::isGet('search')) $this->search = $formClean->arrayClean($_GET['search']);
        #similar
        if (http_request::isPost('product_id')) $this->id_product_2 = $formClean->numeric($_POST['product_id']);
		// --- ADD or EDIT
		if (http_request::isGet('id')) $this->id_product = $formClean->simpleClean($_GET['id']);
		elseif (http_request::isPost('id')) $this->id_product = $formClean->simpleClean($_POST['id']);
		if (http_request::isPost('id_img')) $this->id_img = $formClean->simpleClean($_POST['id_img']);
		if (http_request::isPost('productData')) $this->productData = $formClean->arrayClean($_POST['productData']);
		if (http_request::isPost('content')) {
			$array = $_POST['content'];
			foreach ($array as $key => $arr) {
				foreach ($arr as $k => $v) {
					$array[$key][$k] = ($k == 'content_p') ? $formClean->cleanQuote($v) : $formClean->simpleClean($v);
				}
			}
			$this->content = $array;
		}
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
		if (http_request::isPost('product_cat')) $this->product_cat = $formClean->simpleClean($_POST['product_cat']);
		if (http_request::isPost('parent')) $this->parent = $formClean->arrayClean($_POST['parent']);
		if (http_request::isPost('default_cat')) $this->default_cat = $formClean->numeric($_POST['default_cat']);

		# ORDER PAGE
		if (http_request::isPost('image')) $this->order = $formClean->arrayClean($_POST['image']);

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
		$results = $this->getItems('pages', array('default_lang' => $defaultLanguage['id_lang']), 'all',false);
		$params = array();

		if($ajax) {
			$params['section'] = 'pages';
			$params['idcolumn'] = 'id_pages';
			$params['activation'] = true;
			$params['sortable'] = true;
			$params['checkbox'] = true;
			$params['edit'] = true;
			$params['dlt'] = true;
			$params['readonly'] = array();
			$params['cClass'] = 'backend_controller_product';
		}

		$this->data->getScheme(array('mc_catalog_product', 'mc_catalog_product_content'), array('id_product', 'name_p', 'price_p', 'reference_p', 'resume_p', 'content_p', 'date_register'), $this->tableconfig);

		return array(
			'data' => $results,
			'var' => 'pages',
			'tpl' => 'catalog/product/index.tpl',
			'params' => $params
		);
	}

	public function tinymce()
	{
		$langs = $this->modelLanguage->setLanguage();
		foreach($langs as $k => $iso) {
			$list = $this->getItems('pagesPublishedSelect',array('default_lang'=> $k),'all',false);
			//var_dump($list);
			//$list = $this->data->setPagesTree($list,'product');
			//var_dump($list);
			$lists[$k] = $list;
		}
		$this->template->assign('langs',$langs);
		$this->template->assign('products',$lists);
		$this->template->display('tinymce/product/mc_product.tpl');
	}

	/**
	 * Return Last pages (Dashboard)
	 */
	public function getItemsProduct(){
		$this->modelLanguage->getLanguage();
		$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
		$this->getItems('lastProducts',array(':default_lang'=>$defaultLanguage['id_lang']),'all');
	}

	/**
	 * @return array
	 */
	private function setItemsData()
	{
		$defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'one', 'type' => 'default'));

		$arr = array();
		$data = parent::fetchData(
			array('context' => 'all', 'type' => 'pages', 'search' => $this->search),
			array(':default_lang' => $defaultLanguage['id_lang'])
		);

		return $data;
	}

	/**
	 * @param $data
	 * @return array
	 */
	private function setItemData($data)
	{
		$arr = array();
		$conf = array();

		foreach ($data as $page) {

			//$publicUrl = !empty($page['url_p']) ? '/' . $page['iso_lang'] . '/' . $page['id_product'] . '-' . $page['url_p'] . '/' : '';
			if (!array_key_exists($page['id_product'], $arr)) {
				$arr[$page['id_product']] = array();
				$arr[$page['id_product']]['id_product'] = $page['id_product'];
				$arr[$page['id_product']]['price_p'] = $page['price_p'];
				$arr[$page['id_product']]['reference_p'] = $page['reference_p'];
				$arr[$page['id_product']]['width_p'] = $page['width_p'];
				$arr[$page['id_product']]['height_p'] = $page['height_p'];
				$arr[$page['id_product']]['depth_p'] = $page['depth_p'];
				$arr[$page['id_product']]['weight_p'] = $page['weight_p'];
				$arr[$page['id_product']]['date_register'] = $page['date_register'];
			}
			$arr[$page['id_product']]['content'][$page['id_lang']] = array(
				'id_lang' => $page['id_lang'],
				'iso_lang' => $page['iso_lang'],
				'name_p' => $page['name_p'],
				'url_p' => $page['url_p'],
				'resume_p' => $page['resume_p'],
				'content_p' => $page['content_p'],
				'published_p' => $page['published_p']/*,
				'public_url' => $publicUrl*/
			);
		}
		return $arr;
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
                $arr[$page['id_img']]['id_product'] = $page['id_product'];
                $arr[$page['id_img']]['name_img'] = $page['name_img'];
            }
            if($page['id_lang'] != null) {
                $arr[$page['id_img']]['content'][$page['id_lang']] = array(
                    'id_lang' => $page['id_lang'],
                    'iso_lang' => $page['iso_lang'],
                    'alt_img' => $page['alt_img'],
                    'title_img' => $page['title_img']
                );
            }
        }
        return $arr;
    }

	/**
	 * @return array
	 */
	private function setCategoriesTree()
	{
		$defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'one', 'type' => 'default'));

		$childs = array();

		$cats = $this->dbCategory->fetchData(
			array('context' => 'all', 'type' => 'cats'),
			array(':default_lang' => $defaultLanguage['id_lang'])
		);

		foreach($cats as &$item) {
			$k = $item['id_parent'] == null ? 'root' : $item['id_parent'];
			$childs[$k][] = &$item;
		}
		unset($item);

		foreach($cats as &$item) {
			if (isset($childs[$item['id_cat']])) {
				$item['subcat'] = $childs[$item['id_cat']];
			}
		}

		$this->template->assign('catTree', $childs['root']);
	}

	/**
	 * @return array
	 */
	private function getCatRels()
	{
		$rels = $this->getItems('catRel', $this->edit,'all', false);
		$catRels = array();

		foreach($rels as $rel) {
			$catRels[$rel['id_cat']] = $rel;
		}

		$this->template->assign('catRel',$catRels);
	}

	/**
	 * Insertion de données
	 * @param $data
	 */
	private function add($data){
		switch($data['type']){
			case 'newPages':
			case 'newContent':
            case 'newImgContent':
			case 'newImg':
				parent::insert(
					array(
						'type' => $data['type']
					),
					$data['data']
				);
				break;
			case 'newCatRel':
				parent::insert(
					array(
						'type' => 'catRel'
					),
					$data['data']
				);
				break;
            case 'newProductRel':
                parent::insert(
                    array(
                        'type' => 'productRel'
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
			case 'product':
			case 'content':
            case 'imgContent':
			//case 'img':
			case 'firstImageDefault':
			case 'catRel':
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
	 * @param $idImages
	 */
	private function deleteImages($idImages)
	{
        $makeFiles = new filesystem_makefile();
        /*$setEditImg = parent::fetchData(
            array('context' => 'one', 'type' => 'img'),
            array(':editimg' => $this->editimg)
        );*/
        /*if (file_exists($filesPath . $data['edit'])) {
            $makeFiles->remove(array($filesPath . $data['edit']));
        }*/
	}

	/**
	 * Remove product
	 * @param $data
	 */
	private function del($data)
	{
		switch ($data['type']) {
			case 'delPages':
				parent::delete(
					array(
						'type' => $data['type']
					),
					$data['data']
				);
				$this->message->json_post_response(true, 'delete', $data['data']);
				break;
			case 'delImages':
                $makeFiles = new filesystem_makefile();
			    $newArr = array();
			    $imgArray = explode(',',$data['data']['id']);
                $fetchConfig = $this->imagesComponent->getConfigItems(array('module_img'=>'catalog','attribute_img'=>'product'));
                $imgPrefix = $this->imagesComponent->prefix();
                $defaultErased = false;
                $id_product = false;

			    foreach($imgArray as $key => $value){
                    $img = $this->getItems('img',$value,'one',false);
					$id_product = $img['id_product'];
					if($img['default_img']) $defaultErased = true;

                    $imgPath = $this->upload->dirFileUpload(
                        array_merge(
                            array(
                                'upload_root_dir' => 'upload/catalog/p',
                                'upload_dir' => $img['id_product'])
                            ,array(
                                'fileBasePath'=>true
                            )
                        )
                    );

                    $newArr[$key]['img']['original'] = $imgPath.$img['name_img'];
                    if(file_exists($newArr[$key]['img']['original'])) {
                        $makeFiles->remove(array(
                            $newArr[$key]['img']['original']
                        ));
                    }
                    foreach ($fetchConfig as $configKey => $confiValue) {
                        $newArr[$key]['img'][$confiValue['type_img']] = $imgPath.$imgPrefix[$confiValue['type_img']].$img['name_img'];
                        if(file_exists($newArr[$key]['img'][$confiValue['type_img']])) {
                            $makeFiles->remove(array(
                                $newArr[$key]['img'][$confiValue['type_img']]
                            ));
                        }
                    }
                }

                if($newArr) {
                    parent::delete(
                        array(
                            'type' => $data['type']
                        ),
                        $data['data']
                    );

                    $imgs = $this->getItems('images',$id_product,'all',false);
                    if($imgs != null && $defaultErased) {
						$this->upd(array(
							'type' => 'firstImageDefault',
							'data' => array(
								':id' => $id_product
							)
						));
					}
                }
				break;
			case 'delImagesProducts':
                $makeFiles = new filesystem_makefile();
			    $imgArray = explode(',',$data['data']['id']);

			    foreach($imgArray as $key => $value){
                    $imgPath = $this->upload->dirFileUpload(
                        array_merge(
                            array(
                                'upload_root_dir' => 'upload/catalog/p',
                                'upload_dir' => $value
							),
							array(
                                'fileBasePath'=>true
                            )
                        )
                    );

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
			case 'oldCatRel':
			case 'catRel':
				parent::delete(
					array(
						'type' => $data['type']
					),
					$data['data']
				);
				break;
            case 'productRel':
                parent::delete(
                    array(
                        'type' => $data['type']
                    ),
                    $data['data']
                );
                break;
		}
	}

	/**
	 *
	 */
	public function run()
	{
		if(isset($this->tableaction)) {
			$this->tableform->run();
		}
		elseif (isset($this->action)) {
			switch ($this->action) {
				case 'add':
					if (isset($this->content)) {
						$this->add(array(
							'type' => 'newPages',
							'data' => array(
								'price_p' => $this->productData['price'],
								'reference_p' => $this->productData['reference']
							)
						));

						$product = parent::fetchData(array('context' => 'one', 'type' => 'root'));

						foreach ($this->content as $lang => $content) {
							$content['id_product'] = $product['id_product'];
							$content['id_lang'] = $lang;
							$content['published_p'] = (!isset($content['published_p']) ? 0 : 1);

							if (empty($content['url_p'])) {
								$content['url_p'] = http_url::clean($content['name_p'],
									array(
										'dot' => false,
										'ampersand' => 'strict',
										'cspec' => '', 'rspec' => ''
									)
								);
							}

							$this->add(array( 'type' => 'newContent', 'data' => $content ));
						}
						$this->message->json_post_response(true, 'add_redirect');
					}
					elseif(isset($this->id_product_2)) {
						$this->add(array(
							'type' => 'newProductRel',
							'data' => array(
								'id_product'    => $this->id_product,
								'id_product_2'  => $this->id_product_2,
							)
						));


						$this->modelLanguage->getLanguage();
						$defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'one', 'type' => 'default'));
						$this->getItems('lastProductRel',array('default_lang' => $defaultLanguage['id_lang'],'id'=>$this->id_product),'one','row');
						$display = $this->template->fetch('catalog/product/loop/similar.tpl');
						$this->message->json_post_response(true,'add',$display);
						//$this->header->set_json_headers();
						//$this->message->json_post_response(true, 'add');
					}
                    else {
						$this->modelLanguage->getLanguage();
						$this->template->display('catalog/product/add.tpl');
					}
					break;
				case 'edit':
					if (isset($this->id_product)) {
						if(isset($this->content)) {
							$this->productData['price'] = number_format(str_replace(",", ".", $this->productData['price']), 6, '.', '');
							$this->productData['reference'] = !empty($this->productData['reference']) ? $this->productData['reference'] : NULL;

							$this->upd(array(
								'type' => 'product',
								'data' => array(
									'id_product' => $this->id_product,
									'price_p' => $this->productData['price'],
									'reference_p' => $this->productData['reference']
								)
							));

							$extendData = array();

							foreach ($this->content as $lang => $content) {
								$content['id_product'] = $this->id_product;
								$content['id_lang'] = $lang;
								$content['published_p'] = (!isset($content['published_p']) ? 0 : 1);

								if (empty($content['url_p'])) {
									$content['url_p'] = http_url::clean($content['name_p'],
										array(
											'dot' => false,
											'ampersand' => 'strict',
											'cspec' => '', 'rspec' => ''
										)
									);
								}

								$checkLangData = parent::fetchData(
									array('context' => 'one', 'type' => 'content'),
									array('id_product' => $this->id_product, 'id_lang' => $lang)
								);

								// Check language page content
								if ($checkLangData != null) {
									$this->upd(array(
										'type' => 'content',
										'data' => $content
									));
								}
								else {
									$this->add(array(
										'type' => 'newContent',
										'data' => $content
									));
								}

								/*$setEditData = parent::fetchData(
									array('context' => 'all', 'type' => 'page'),
									array('edit' => $this->id_product)
								);
								$setEditData = $this->setItemData($setEditData);
								$extendData[$lang] = $setEditData[$this->id_product]['content'][$lang]['public_url'];*/
							}
							$this->message->json_post_response(true, 'update', array('result' => $this->id_product, 'extend' => $extendData));
						}
						elseif (isset($this->img_multiple)) {
							$this->template->configLoad();
							$this->progress = new component_core_feedback($this->template);

							usleep(200000);
							$this->progress->sendFeedback(array('message' => $this->template->getConfigVars('control_of_data'), 'progress' => 10));

							$resultUpload = $this->upload->setMultipleImageUpload(
								'img_multiple',
								array(
									'prefix_name' => '',
									'prefix' => array('s_', 'm_', 'l_'),
									'module_img' => 'catalog',
									'attribute_img' => 'product',
									'original_remove' => false
								),
								array(
									'upload_root_dir' => 'upload/catalog/p', //string
									'upload_dir' => $this->id_product //string ou array
								),
								false
							);

							if ($resultUpload != null) {
								$preparePercent = 80 / count($resultUpload);
								$percent = 10;

								foreach ($resultUpload as $key => $value) {
									if ($value['statut'] == '1') {
										$percent = $percent + $preparePercent;

										usleep(200000);
										$this->progress->sendFeedback(array('message' => $this->template->getConfigVars('creating_thumbnails'), 'progress' => $percent));

										$this->add(array(
											'type' => 'newImg',
											'data' => array(
												'id_product' => $this->id_product,
												'name_img' => $value['file']
											)
										));
									}
								}

								usleep(200000);
								$this->progress->sendFeedback(array('message' => $this->template->getConfigVars('creating_thumbnails_success'), 'progress' => 90));

								usleep(200000);
								$this->progress->sendFeedback(array('message' => $this->template->getConfigVars('upload_done'), 'progress' => 100, 'status' => 'success', 'result' => $display));
							}
							else {
								usleep(200000);
								$this->progress->sendFeedback(array('message' => $this->template->getConfigVars('creating_thumbnails_error'), 'progress' => 100, 'status' => 'error', 'error_code' => 'error_data'));
							}
						}
					}
					elseif (isset($this->editimg)) {
						if (isset($this->id_img)) {
                            foreach ($this->imgData as $lang => $content) {

                                $content['id_img'] = $this->id_img;
                                $content['id_lang'] = $lang;

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
                                }
                                else {
                                    $this->add(array(
                                        'type' => 'newImgContent',
                                        'data' => $content
                                    ));
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
							$this->template->display('catalog/product/edit-img.tpl');
						}
					}
					elseif (isset($this->product_cat)) {
						if (isset($this->parent)) {
							$ids = array();

							foreach ($this->parent as $id => $val) {
								$ids[] = $id;
								$link = parent::fetchData( array('context' => 'one', 'type' => 'catRel'), array(':id' => $this->edit, ':id_cat' => $id) );

								if($link == null) {
									$data = array(':id' => $this->edit, ':id_cat' => $id, ':default_c' => 0);

									$this->add(array(
										'type' => 'newCatRel',
										'data' => $data
									));
								}

								if($this->default_cat == $id) {
									$this->upd(array(
										'type' => 'catRel',
										'data' => array(':id' => $this->edit, ':id_cat' => $id)
									));
								}
							}

							$this->del(array(
								'type' => 'oldCatRel',
								'data' => array(':id' => $this->edit, ':id_cat' => implode(',',$ids))
							));
						}
						else {
							$this->del(array(
								'type' => 'catRel',
								'data' => array(':id' => $this->edit)
							));
						}
						$this->message->json_post_response(true,'update');
					}
					else {
						// --- Product content
						$this->modelLanguage->getLanguage();
						$setEditData = parent::fetchData(
							array('context' => 'all', 'type' => 'page'),
							array('edit' => $this->edit)
						);
						$setEditData = $this->setItemData($setEditData);
						$this->template->assign('page', $setEditData[$this->edit]);

						// --- Product images
						$this->getItems('images',$this->edit,'all');

						// --- Categories
						$this->setCategoriesTree();
						$this->getCatRels();
						// ---- Similar
                        $defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'one', 'type' => 'default'));
                        $this->getItems('productRel',array(':default_lang' => $defaultLanguage['id_lang'],':id'=>$this->edit),'all');
                        /*$assign = array(
                            'id_rel',
                            'name_p' => ['title' => 'name']
                        );
                        $this->data->getScheme(array('mc_catalog_product_rel', 'mc_catalog_product_content'), array('id_rel', 'name_p'), $assign);*/
						$this->getItems('pages', array('default_lang' => $defaultLanguage['id_lang']), 'all','products');
						$this->template->display('catalog/product/edit.tpl');
					}
					break;
				case 'setImgDefault':
					if(isset($this->id_img)) {
						$this->upd(array(
							'type' => 'imageDefault',
							'data' => array(':id' => $this->edit, ':id_img' => $this->id_img)
						));
					}
					break;
				case 'getImgDefault':
					if(isset($this->edit)) {
						$imgDefault = $this->getItems('imgDefault',$this->edit,'one',false);
						print $imgDefault['id_img'];
					}
					break;
				case 'getImages':
					if(isset($this->edit)) {
						$this->getItems('images',$this->edit, 'all');
						$display = $this->template->fetch('catalog/product/brick/img.tpl');
						$this->message->json_post_response(true,'',$display);
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
					if (isset($this->id_product)) {
						if(isset($this->tabs)) {
							switch ($this->tabs) {
								case 'image':
									$this->del(
										array(
											'type' => 'delImages',
											'data' => array(
												'id' => $this->id_product
											)
										)
									);
									$this->message->json_post_response(true, 'delete', array('id' => $this->id_product));
									break;
                                case 'similar':
                                    $this->del(
                                        array(
                                            'type' => 'productRel',
                                            'data' => array(
                                                'id' => $this->id_product
                                            )
                                        )
                                    );
                                    $this->message->json_post_response(true, 'delete', array('id' => $this->id_product));
                                    break;
							}
						}
						else {
                            $this->del(
                                array(
                                    'type' => 'delImagesProducts',
                                    'data' => array(
                                        'id' => $this->id_product
                                    )
                                )
                            );
							$this->del(
								array(
									'type' => 'delPages',
									'data' => array(
										'id' => $this->id_product
									)
								)
							);
						}
					}
					break;
				case 'getLink':
					if(isset($this->id_product) && isset($this->iso)) {
						$product = $this->getItems('pageLang',array('id' => $this->id_product,'iso' => $this->iso),'one',false);
						if($product) {
							$product['url'] = $this->routingUrl->getBuildUrl(array(
								'type' => 'product',
								'iso'  => $product['iso_lang'],
								'id'   => $product['id_product'],
								'url'  => $product['name_p'],
								'id_parent'   => $product['id_parent'],
								'url_parent'  => $product['name_parent']
							));
							//$link = '<a title="'.$cat['url'].'" href="'.$cat['name_cat'].'">'.$cat['name_cat'].'</a>';
							$this->header->set_json_headers();
							print '{"name":'.json_encode($product['name_p']).',"url":'.json_encode($product['url']).'}';
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
			$this->getItems('pages', array('default_lang' => $defaultLanguage['id_lang']), 'all');
			$this->data->getScheme(array('mc_catalog_product', 'mc_catalog_product_content'), array('id_product', 'name_p', 'price_p', 'reference_p', 'resume_p', 'content_p', 'date_register'), $this->tableconfig);
			$this->template->display('catalog/product/index.tpl');
		}
	}
}