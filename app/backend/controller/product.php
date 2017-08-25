<?php
class backend_controller_product extends backend_db_product
{

	public $edit, $action, $tabs, $search;
	protected $message, $template, $header, $data, $modelLanguage, $collectionLanguage, $order, $upload, $config, $imagesComponent, $dbCategory;

	public $id_product, $id_img, $parent_id, $content, $productData, $imgData, $img_multiple, $editimg, $product_cat, $parent, $default_cat;

	/**
	 * backend_controller_catalog constructor.
	 */
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
		$this->dbCategory = new backend_db_category();
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
		if (http_request::isGet('editimg')) {
			$this->editimg = $formClean->numeric($_GET['editimg']);
		}
		if (http_request::isGet('parentid')) {
			$this->parent_id = $formClean->numeric($_GET['parentid']);
		}
		// --- ADD or EDIT
		if (http_request::isPost('id')) {
			$this->id_product = $formClean->simpleClean($_POST['id']);
		}
		if (http_request::isPost('id_img')) {
			$this->id_img = $formClean->simpleClean($_POST['id_img']);
		}
		if (http_request::isPost('productData')) {
			$this->productData = $formClean->arrayClean($_POST['productData']);
		}
		if (http_request::isPost('content')) {
			$array = $_POST['content'];
			foreach ($array as $key => $arr) {
				foreach ($arr as $k => $v) {
					$array[$key][$k] = ($k == 'content_p') ? $formClean->cleanQuote($v) : $formClean->simpleClean($v);
				}
			}
			$this->content = $array;
		}
		if (isset($_FILES['img_multiple']["name"])) {
			$this->img_multiple = ($_FILES['img_multiple']["name"]);
		}
		if (http_request::isPost('imgData')) {
			$this->imgData = $formClean->arrayClean($_POST['imgData']);
		}
		if (http_request::isPost('product_cat')) {
			$this->product_cat = $formClean->simpleClean($_POST['product_cat']);
		}
		if (http_request::isPost('parent')) {
			$this->parent = $formClean->arrayClean($_POST['parent']);
		}
		if (http_request::isPost('default_cat')) {
			$this->default_cat = $formClean->numeric($_POST['default_cat']);
		}
	}

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $context
	 * @param string $type
	 * @param string|int|null $id
	 * @return mixed
	 */
	private function getItems($type, $id = null, $context = null)
	{
		return $this->data->getItems($type, $id, $context);
	}

	/**
	 * @return array
	 */
	private function setItemsData()
	{
		$defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'unique', 'type' => 'default'));

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
				'content_p' => $page['content_p'],
				'published_p' => $page['published_p']/*,
				'public_url' => $publicUrl*/
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
			case 'product':
				parent::update(
					array(
						'type' => $data['type']
					), array(
						'id_product' => $data['id_product'],
						'price_p' => number_format(str_replace(",", ".", $data['price_p']), 6, '.', ''),
						'reference_p' => !empty($data['reference_p']) ? $data['reference_p'] : NULL
					)
				);
				break;
			case 'content':
				parent::update(
					array(
						'type' => $data['type']
					), array(
						'id_lang' => $data['id_lang'],
						'id_product' => $data['id_product'],
						'name_p' => $data['name_p'],
						'url_p' => $data['url_p'],
						'content_p' => $data['content_p'],
						'published_p' => $data['published_p']
					)
				);
				break;
			case 'img':
				parent::update(
					array(
						'type' => $data['type']
					), array(
						'id_img' => $data['id_img'],
						'alt_img' => !empty($data['alt_img']) ? $data['alt_img'] : NULL,
						'reference_p' => !empty($data['title_img']) ? $data['title_img'] : NULL
					)
				);
				break;
			case 'catRel':
				parent::update(
					array(
						'type' => 'catRel'
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

				$this->header->set_json_headers();
				$this->message->json_post_response(true,'update');
				break;
		}
	}

	/**
	 * @return array
	 */
	private function setCategoriesTree()
	{
		$defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'unique', 'type' => 'default'));

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
		$rels = $this->getItems('catRel', $this->edit, 'return');
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
			case 'newCatRel':
				parent::insert(
					array(
						'type' => 'catRel'
					),
					$data['data']
				);
				break;
		}
	}

	/**
	 *
	 */
	private function save()
	{
		if (isset($this->content) && isset($this->id_product)) {
			$this->upd(array(
				'type' => 'product',
				'id_product' => $this->id_product,
				'price_p' => $this->productData['price'],
				'reference_p' => $this->productData['reference']
			));

			foreach ($this->content as $lang => $content) {
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
					array('context' => 'unique', 'type' => 'content'),
					array('id_product' => $this->id_product, 'id_lang' => $lang)
				);
				// Check language page content
				if ($checkLangData != null) {
					$this->upd(array(
						'type' => 'content',
						'id_lang' => $lang,
						'id_product' => $this->id_product,
						'name_p' => $content['name_p'],
						'url_p' => $content['url_p'],
						'content_p' => $content['content_p'],
						'published_p' => $content['published_p']
					));
				} else {
					parent::insert(
						array(
							'type' => 'newContent',
						),
						array(
							'id_lang' => $lang,
							'id_product' => $this->id_product,
							'name_p' => $content['name_p'],
							'url_p' => $content['url_p'],
							'content_p' => $content['content_p'],
							'published_p' => $content['published_p']
						)
					);
				}

				$setEditData = parent::fetchData(
					array('context' => 'all', 'type' => 'page'),
					array('edit' => $this->id_product)
				);
				$setEditData = $this->setItemData($setEditData);
				$extendData[$lang] = $setEditData[$this->id_product]['content'][$lang]['public_url'];
			}

			$this->header->set_json_headers();
			$this->message->json_post_response(true, 'update', array('result' => $this->id_product, 'extend' => $extendData));

		} else if (isset($this->content) && !isset($this->id_product)) {

			parent::insert(
				array(
					'type' => 'newPages'
				),
				array(
					'price_p' => $this->productData['price'],
					'reference_p' => $this->productData['reference']
				)
			);
			$setNewData = parent::fetchData(
				array('context' => 'unique', 'type' => 'root')
			);

			foreach ($this->content as $lang => $content) {

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

				parent::insert(
					array(
						'type' => 'newContent',
					),
					array(
						'id_lang' => $lang,
						'id_product' => $setNewData['id_product'],
						'name_p' => $content['name_p'],
						'url_p' => $content['url_p'],
						'content_p' => $content['content_p'],
						'published_p' => $content['published_p']
					)
				);
			}

			$this->header->set_json_headers();
			$this->message->json_post_response(true, 'add_redirect');

		} else if (isset($this->img_multiple)) {
			$this->template->configLoad();
			usleep(200000);
			$this->progress = new component_core_feedback($this->template);
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
				$total = count($resultUpload);
				$preparePercent = 100 / $total;
				$percent = 0;
				foreach ($resultUpload as $key => $value) {
					if ($value['statut'] == '1') {
						$percent = $percent + $preparePercent;
						usleep(200000);
						$this->progress->sendFeedback(array('message' => $this->template->getConfigVars('creating_thumbnails'), 'progress' => $percent));
						parent::insert(
							array(
								'type' => 'img',
							),
							array(
								'id_product' => $this->id_product,
								'name_img' => $value['file']
							)
						);
					}
				}

				usleep(200000);
				$this->progress->sendFeedback(array('message' => $this->template->getConfigVars('creating_thumbnails_success'), 'progress' => 100, 'status' => 'success'));

				usleep(200000);
				//$this->header->set_json_headers();

				$setImagesData = parent::fetchData(
					array('context' => 'all', 'type' => 'images'),
					array('edit' => $this->edit)
				);

				$this->template->assign('images', $setImagesData);
				$display = $this->template->fetch('catalog/product/brick/img.tpl');

				$this->message->json_post_response(true, 'update', $display);
			} else {
				usleep(200000);
				$this->progress->sendFeedback(array('message' => $this->template->getConfigVars('creating_thumbnails_error'), 'progress' => 100, 'status' => 'error', 'error_code' => 'error_data'));
			}

		} else if (isset($this->id_img)) {
			$this->upd(array(
				'type' => 'img',
				'id_img' => $this->id_img,
				'alt_img' => $this->imgData['alt_img'],
				'title_img' => $this->imgData['title_img']
			));
			$this->header->set_json_headers();
			$this->message->json_post_response(true, 'add_redirect');
		}
	}

	/**
	 * @param $idImages
	 */
	private function deleteImages($idImages)
	{
        $makeFiles = new filesystem_makefile();
        /*$setEditImg = parent::fetchData(
            array('context' => 'unique', 'type' => 'img'),
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
                $this->header->set_json_headers();
				$this->message->json_post_response(true, 'delete', $data['data']);
				break;
			case 'delImages':
                $makeFiles = new filesystem_makefile();
			    $newArr = array();
			    $imgArray = explode(',',$data['data']['id']);
                $fetchConfig = $this->imagesComponent->getConfigItems(array('module_img'=>'catalog','attribute_img'=>'product'));
                $imgPrefix = $this->imagesComponent->prefix();
			    foreach($imgArray as $key => $value){
                    $setEditImg = parent::fetchData(
                        array('context' => 'unique', 'type' => 'img'),
                        array(':editimg' => $value)
                    );
                    $imgPath = $this->upload->dirFileUpload(
                        array_merge(
                            array(
                                'upload_root_dir' => 'upload/catalog/p',
                                'upload_dir' => $setEditImg['id_product'])
                            ,array(
                                'fileBasePath'=>true
                            )
                        )
                    );

                    $newArr[$key]['img']['original'] = $imgPath.$setEditImg['name_img'];
                    if(file_exists($newArr[$key]['img']['original'])) {
                        $makeFiles->remove(array(
                            $newArr[$key]['img']['original']
                        ));
                    }
                    foreach ($fetchConfig as $configKey => $confiValue) {
                        $newArr[$key]['img'][$confiValue['type_img']] = $imgPath.$imgPrefix[$confiValue['type_img']].$setEditImg['name_img'];
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


                    $this->header->set_json_headers();
                    $this->message->json_post_response(true, 'delete', $data['data']);
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
					if (isset($this->content)) {
						$this->save();
					}
					else {
						$this->modelLanguage->getLanguage();
						$this->template->display('catalog/product/add.tpl');
					}

					break;
				case 'edit':
					if (isset($this->id_product)) {
						$this->save();
					}
					elseif (isset($this->editimg)) {
						if (isset($this->id_img)) {
							$this->save();
						} else {
							$setEditImg = parent::fetchData(
								array('context' => 'unique', 'type' => 'img'),
								array(':editimg' => $this->editimg)
							);
							$this->template->assign('img', $setEditImg);
							$this->template->display('catalog/product/edit-img.tpl');
						}
					}
					elseif (isset($this->product_cat)) {
						if (isset($this->parent)) {
							$ids = array();

							foreach ($this->parent as $id => $val) {
								$ids[] = $id;
								$link = parent::fetchData( array('context' => 'unique', 'type' => 'catRel'), array(':id' => $this->edit, ':id_cat' => $id) );

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

						$this->header->set_json_headers();
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
									break;
							}
						}
						else {
                            $this->del(
                                array(
                                    'type' => 'delImages',
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
			}
		}
		else {
			$this->modelLanguage->getLanguage();
			$defaultLanguage = $this->collectionLanguage->fetchData(array('context' => 'unique', 'type' => 'default'));
			$this->getItems('pages', array(':default_lang' => $defaultLanguage['id_lang']), 'all');
			$assign = array(
				'id_product',
				'name_p' => ['title' => 'name'],
                'price_p' => ['type' => 'price','input' => null],
                'reference_p' => ['title' => 'reference'],
				'content_p' => ['class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null],
				'date_register'
			);
			if (isset($this->search)) {
				$search = $this->search;
				$search = array_filter($search);

				if (is_array($search) && !empty($search)) {
					$assign = array(
						'id_product',
						'name_p' => ['title' => 'name'],
                        'price_p' => ['type' => 'price','input' => null],
                        'reference_p' => ['title' => 'reference'],
						'content_p' => ['type' => 'bin', 'input' => null],
						'date_register'
					);
				}
			}
			$this->data->getScheme(array('mc_catalog_product', 'mc_catalog_product_content'), array('id_product', 'name_p', 'price_p', 'reference_p', 'content_p', 'date_register'), $assign);
			$this->template->display('catalog/product/index.tpl');
		}
	}
}
?>