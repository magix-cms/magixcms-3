<?php
class backend_controller_pages extends backend_db_pages
{

    public $edit, $action, $tabs, $search;
    protected $message, $template, $header, $data, $modelLanguage, $collectionLanguage, $order, $upload, $config, $imagesComponent;
    public $id_pages,$parent_id,$content,$pages,$img;

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
            $this->id_pages = $formClean->simpleClean($_POST['id']);
        }
        if (http_request::isPost('parent_id')) {
            $this->parent_id = $formClean->simpleClean($_POST['parent_id']);
        }

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
        if(isset($_FILES['img']["name"])){
            $this->img = http_url::clean($_FILES['img']["name"]);
        }
        // --- Recursive Actions
        if (http_request::isGet('pages')) {
            $this->pages = $formClean->arrayClean($_GET['pages']);
        }

        # ORDER PAGE
        if(http_request::isPost('pages')){
            $this->order = $formClean->arrayClean($_POST['pages']);
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
            /*foreach ($data as $key => $value) {
                $arr[$key]['id_pages'] = $value['id_pages'];
                $arr[$key]['name_pages'] = $value['name_pages'];
                $arr[$key]['menu_pages'] = $value['menu_pages'];
                $arr[$key]['date_register'] = $value['date_register'];
            }*/
        }else{
            $data = parent::fetchData(
                array('context'=>'all','type'=>'pages','search'=>$this->search),
                array(':default_lang'=>$defaultLanguage['id_lang'])
            );
            /*if($this->search) {
                foreach ($data as $key => $value) {
                    $arr[$key]['id_pages'] = $value['id_pages'];
                    $arr[$key]['name_pages'] = $value['name_pages'];
                    $arr[$key]['parent_pages'] = $value['parent_pages'];
                    $arr[$key]['menu_pages'] = $value['menu_pages'];
                    $arr[$key]['date_register'] = $value['date_register'];
                }
            }else{
                foreach ($data as $key => $value) {
                    $arr[$key]['id_pages'] = $value['id_pages'];
                    $arr[$key]['name_pages'] = $value['name_pages'];
                    $arr[$key]['menu_pages'] = $value['menu_pages'];
                    $arr[$key]['date_register'] = $value['date_register'];
                }
            }*/
        }

        return $data;
    }

    /**
     * @param $data
     * @return array
     */
    private function setItemData($data){
        //return $this->getItems('page',$this->edit, 'return');
        $imgPath = $this->upload->imgBasePath('upload/pages');
        $arr = array();
        $conf = array();
        $fetchConfig = $this->imagesComponent->getConfigItems(array('module_img'=>'pages','attribute_img'=>'page'));
        $imgPrefix = $this->imagesComponent->prefix();

        foreach ($data as $page) {

            $publicUrl = !empty($page['url_pages']) ? '/'.$page['iso_lang'].'/pages/'.$page['id_pages'].'-'.$page['url_pages'].'/' : '';
            if (!array_key_exists($page['id_pages'], $arr)) {
                $arr[$page['id_pages']] = array();
                $arr[$page['id_pages']]['id_pages'] = $page['id_pages'];
                $arr[$page['id_pages']]['id_parent'] = $page['id_parent'];
                if($page['img_pages'] != null) {
                    $originalSize = getimagesize($imgPath.DIRECTORY_SEPARATOR.$page['id_pages'].DIRECTORY_SEPARATOR.$page['img_pages']);
                    $arr[$page['id_pages']]['imgSrc']['original']['img'] = $page['img_pages'];
                    $arr[$page['id_pages']]['imgSrc']['original']['width'] = $originalSize[0];
                    $arr[$page['id_pages']]['imgSrc']['original']['height'] = $originalSize[1];
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
                        'id_pages'	       => $data['id_pages'],
                        'name_pages'       => $data['name_pages'],
                        'url_pages'        => $data['url_pages'],
                        'content_pages'    => $data['content_pages'],
                        'seo_title_pages'  => $data['seo_title_pages'],
                        'seo_desc_pages'   => $data['seo_desc_pages'],
                        'published_pages'  => $data['published_pages']
                    )
                );
                break;
            case 'img':
                parent::update(
                    array(
                        'type'=>$data['type']
                    ),array(
                        'id_pages'	       => $data['id_pages'],
                        'img_pages'        => $data['img_pages']
                    )
                );
                break;
            case 'pageActiveMenu':
                parent::update(
                    array(
                        'type'      =>    $data['type']
                    ),
                    $data['data']
                );
                break;
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
        }
    }

    private function save(){
        if (isset($this->content) && isset($this->id_pages)) {
            foreach ($this->content as $lang => $content) {
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

                $this->upd(array(
                    'type'              => 'content',
                    'id_lang'           => $lang,
                    'id_pages'          => $this->id_pages,
                    'name_pages'        => $content['name_pages'],
                    'url_pages'         => $content['url_pages'],
                    'content_pages'     => $content['content_pages'],
                    'seo_title_pages'   => $content['seo_title_pages'],
                    'seo_desc_pages'    => $content['seo_desc_pages'],
                    'published_pages'   => $content['published_pages']
                ));

                $setEditData = parent::fetchData(
                    array('context'=>'all','type'=>'page'),
                    array('edit'=>$this->id_pages)
                );
                $setEditData = $this->setItemData($setEditData);
                $extendData[$lang] = $setEditData[$this->id_pages]['content'][$lang]['public_url'];
            }

            $this->header->set_json_headers();
            $this->message->json_post_response(true, 'update', array('result'=>$this->id_pages,'extend'=>$extendData));

        }else if (isset($this->content) && !isset($this->id_pages)) {
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

            if ($setNewData['id_pages']) {
                foreach ($this->content as $lang => $content) {

                    $content['published_pages'] = (!isset($content['published_pages']) ? 0 : 1);
                    $url_pages = http_url::clean($content['name_pages'],
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
                            'id_pages'          => $setNewData['id_pages'],
                            'name_pages'        => $content['name_pages'],
                            'url_pages'         => $url_pages,
                            'content_pages'     => $content['content_pages'],
                            'seo_title_pages'   => $content['seo_title_pages'],
                            'seo_desc_pages'    => $content['seo_desc_pages'],
                            'published_pages'   => $content['published_pages']
                        )
                    );
                }

                $this->header->set_json_headers();
                $this->message->json_post_response(true,'add_redirect');
            }
        }else  if(isset($this->img)){
            $data = parent::fetchData(array('context'=>'unique','type'=>'page'),array('id_pages'=>$this->id_pages));
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
                'type'              => 'img',
                'id_pages'          => $this->id_pages,
                'img_pages'        => $resultUpload['file']
            ));
            $this->header->set_json_headers();

            $setEditData = parent::fetchData(
                array('context'=>'all','type'=>'page'),
                array('edit'=>$this->id_pages)
            );
            $setEditData = $this->setItemData($setEditData);
            $this->template->assign('page',$setEditData[$this->id_pages]);
            $display = $this->template->fetch('pages/brick/img.tpl');

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
                        $this->template->display('pages/add.tpl');
                    }

                    break;
                case 'edit':
                    if (isset($this->id_pages)) {
                        $this->save();
                    }else{
                        $this->modelLanguage->getLanguage();
                        $setEditData = parent::fetchData(
                            array('context'=>'all','type'=>'page'),
                            array('edit'=>$this->edit)
                        );
                        $setEditData = $this->setItemData($setEditData);
                        $this->template->assign('page',$setEditData[$this->edit]);
                        //$pages = $this->setItemsData();
                        //$this->template->assign('pages', $pages);

						$assign = array(
							'id_pages',
							'name_pages' => ['title' => 'name'],
							'menu_pages',
							'date_register'
						);
						$this->data->getScheme(array('mc_cms_page','mc_cms_page_content'),array('id_pages','name_pages','menu_pages','date_register'),$assign);
						$pageChild = $this->getItems('pagesChild',$this->edit,'all');

						if(isset($this->search)) {
							$this->template->assign('ajax_form',true);
							$this->template->assign('data',$pageChild);
							$this->template->assign('section','pages');
							$this->template->assign('idcolumn','id_pages');
							$this->template->assign('controller','pages');
							$this->template->assign('readonly',array());
							$this->template->assign('cClass','backend_controller_pages');
							$display = $this->template->fetch('section/form/loop/rows-2.tpl');
							$this->header->set_json_headers();
							$this->message->json_post_response(true,'',$display);
						}
						else {
							$this->template->display('pages/edit.tpl');
						}
                    }
                    break;
                case 'active-selected':
                case 'unactive-selected':
					if(isset($this->pages) && is_array($this->pages) && !empty($this->pages)) {
						$this->upd(
							array(
								'type'=>'pageActiveMenu',
								'data'=>array(
									'menu_pages' => ($this->action == 'active-selected'?1:0),
									'id_pages' => implode($this->pages, ',')
								)
							)
						);
					}
					$this->message->getNotify('update',array('method'=>'fetch','assignFetch'=>'message'));
					//$pages = $this->setItemsData();
					//$this->template->assign('pages', $pages);
					$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'unique','type'=>'default'));
					$assign = array(
						'id_pages',
						'name_pages' => ['title' => 'name'],
						'menu_pages',
						'date_register'
					);
					$this->data->getScheme(array('mc_cms_page','mc_cms_page_content'),array('id_pages','name_pages','menu_pages','date_register'),$assign);
					$this->getItems('pages',array(':default_lang'=>$defaultLanguage['id_lang']),'all');
					$this->template->display('pages/index.tpl');
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
                    break;
            }
        }else{
            $this->modelLanguage->getLanguage();
            //$pages = $this->setItemsData();
			//$this->template->assign('pages', $pages);
			$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'unique','type'=>'default'));
			$this->getItems('pages',array(':default_lang'=>$defaultLanguage['id_lang']),'all');
			$assign = array(
				'id_pages',
				'name_pages' => ['title' => 'name'],
				'menu_pages',
				'date_register'
			);

			if(isset($this->search)) {
				$search = $this->search;
				$search = array_filter($search);

				if(is_array($search) && !empty($search)) {
					$assign = array(
						'id_pages',
						'name_pages' => ['title' => 'name'],
						'parent_pages' => ['col' => 'name_pages', 'title' => 'name'],
						'menu_pages',
						'date_register'
					);
				}
			}
			$this->data->getScheme(array('mc_cms_page','mc_cms_page_content'),array('id_pages','name_pages','menu_pages','date_register'),$assign);
            $this->template->display('pages/index.tpl');
        }
    }
}