<?php
class backend_controller_theme extends backend_db_theme{
    public $edit, $action, $tabs;
    protected $message, $template, $header, $data, $modelLanguage, $collectionLanguage;
    public $theme, $content, $type, $id, $link, $order;

    public $roots = array('home','about','catalog','news','contact');
	/**
	 * backend_controller_theme constructor.
	 */
    public function __construct()
    {
        $this->template = new backend_model_template();
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $this->modelLanguage = new backend_model_language($this->template);
		$this->collectionLanguage = new component_collections_language();
        $formClean = new form_inputEscape();

        // --- GET
        if (http_request::isGet('edit')) {
            $this->edit = $formClean->numeric($_GET['edit']);
        } elseif (http_request::isPost('edit')) {
			$this->edit = $formClean->numeric($_POST['edit']);
		}
        if (http_request::isGet('action')) {
            $this->action = $formClean->simpleClean($_GET['action']);
        } elseif (http_request::isPost('action')) {
            $this->action = $formClean->simpleClean($_POST['action']);
        }
        if (http_request::isGet('tabs')) {
            $this->tabs = $formClean->simpleClean($_GET['tabs']);
        }
        if (http_request::isGet('content')) {
            $this->content = $formClean->simpleClean($_GET['content']);
        }
        // --- POST
		$this->theme = http_request::isPost('theme') ? $formClean->simpleClean($_POST['theme']) : array() ;

		if (http_request::isPost('type')) {
			$this->type = $formClean->simpleClean($_POST['type']);
		}
		if (http_request::isPost('pages_id')) {
			$this->id = intval($formClean->numeric($_POST['pages_id']));
		}
		if (http_request::isPost('id')) {
			$this->id = intval($formClean->simpleClean($_POST['id']));
		}

		if(http_request::isPost('link')){
			$this->link = $formClean->arrayClean($_POST['link']);
		}

		if(http_request::isPost('order')){
			$this->order = $formClean->arrayClean($_POST['order']);
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
	 * @param bool $links
	 * @param bool $single
	 */
	private function setLinksData($links = false, $single = false){
		$links = $links ? $links : $this->getItems('links',null,'all',false);
		$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
		$arr = array();

		foreach ($links as $item) {
			if (!array_key_exists($item['id_link'], $arr)) {
				$arr[$item['id_link']] = array();
				$arr[$item['id_link']]['id_link'] = $item['id_link'];
				$arr[$item['id_link']]['type_link'] = $item['type_link'];
				$arr[$item['id_link']]['mode_link'] = $item['mode_link'];
			}
			$arr[$item['id_link']]['content'][$item['id_lang']] = array(
				'id_lang'    => $item['id_lang'],
				'name_link'  => $item['name_link'],
				'title_link' => $item['title_link'],
				'url_link'   => $item['url_link'],
				'active_link'   => $item['active_link']
			);
			if($item['id_lang'] === $defaultLanguage['id_lang']) {
				$arr[$item['id_link']]['name_link'] = $item['name_link'];
			}
		}

		$varName = $single ? 'link' : 'links';
		$var = $single ? array_values($arr)[0] : $arr;
		$this->template->assign($varName,$var);
	}

	/**
	 * @return array
	 */
	private function setPagesTree($pages)
	{
		$childs = array();

		foreach($pages as &$item) {
			$k = $item['parent'] == null ? 'root' : $item['parent'];
			$childs[$k][] = &$item;
		}
		unset($item);

		foreach($pages as &$item) {
			if (isset($childs[$item['id']])) {
				$item['child'] = $childs[$item['id']];
			}
		}

		$this->template->assign('links', $childs['root']);
	}

    /**
     * Return skin array Data
     */
    private function setItemsSkin() {
        $currentSkin = parent::fetchData(array('context'=>'one','type'=>'skin'));
        $finder = new file_finder();
        $basePath = component_core_system::basePath().'skin';
        $skin = $finder->scanRecursiveDir($basePath);
        $newSkin = array();
        foreach($skin as $key => $value){
            if($value === $currentSkin['value']){
                $current = 'true';
            }else{
                $current = 'false';
            }
            if(file_exists($basePath.DIRECTORY_SEPARATOR.$value.DIRECTORY_SEPARATOR.'screenshot_s.jpg')){
                $screenshot['small'] = DIRECTORY_SEPARATOR.'skin'.DIRECTORY_SEPARATOR.$value.DIRECTORY_SEPARATOR.'screenshot_s.jpg';
                $screenshot['large'] = DIRECTORY_SEPARATOR.'skin'.DIRECTORY_SEPARATOR.$value.DIRECTORY_SEPARATOR.'screenshot_l.jpg';
            }else{
                $screenshot['small'] = false;
                $screenshot['large'] = false;
            }
            $newSkin[$key]['name'] = $value;
            $newSkin[$key]['current'] = $current;
            $newSkin[$key]['screenshot']['small'] = $screenshot['small'];
            $newSkin[$key]['screenshot']['large'] = $screenshot['large'];
        }
        $this->template->assign('skin',$newSkin);
    }

	/**
	 * Update data
	 * @param $data
	 */
	private function add($data)
	{
		switch ($data['type']) {
			case 'link':
			case 'link_content':
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
    private function upd($data) {
        switch ($data['type']) {
            case 'theme':
				parent::update(
					array(
						'type' => $data['type']
					),array(
						'theme' => $this->theme
					)
				);
				$this->header->set_json_headers();
				$this->message->json_post_response(true,'update',$data['type']);
				break;
            case 'link':
            case 'link_content':
                parent::update(
                    array(
                        'type' => $data['type']
                    ),
					$data['data']
                );
                break;
			case 'order':
				$p = $this->order;
				for ($i = 0; $i < count($p); $i++) {
					parent::update(
						array(
							'context' => 'page',
							'type' => $data['type']
						),
						array(
							'id' => $p[$i],
							'order_link' => $i
						)
					);
				}
				$this->header->set_json_headers();
				$this->message->json_post_response(true,'update',$data['type']);
				break;
        }
    }

	/**
	 * Insertion de données
	 * @param $data
	 */
	private function del($data){
		switch($data['type']){
			case 'link':
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
                	if(isset($this->type)) {
						$this->add(array(
							'type' => 'link',
							'data' => array(
								'type' => $this->type,
								'id_page' => $this->id ? $this->id : null
							)
						));

						$link = $this->getItems('newLink',null,'one',false);
						$langs = $this->collectionLanguage->fetchData(array('context'=>'all','type'=>'langs'));

                		if(in_array($this->type,$this->roots)) {
							$this->template->configLoad();
							foreach ($langs as $lang) {
								$this->add(array(
									'type' => 'link_content',
									'data' => array(
										'id' => $link['id_link'],
										'id_lang' => $lang['id_lang'],
										'name_link' => $this->template->getConfigVars($this->type),
										'url_link' => '/'.$lang['iso_lang'].'/'.$this->type.'/'
									)
								));
							}
						}
						elseif (isset($this->id)) {
							foreach ($langs as $lang) {
								$page = $this->getItems($this->type,array('id' => $this->id,'id_lang' => $lang['id_lang']),'one',false);

								$this->add(array(
									'type' => 'link_content',
									'data' => array(
										'id' => $link['id_link'],
										'id_lang' => $lang['id_lang'],
										'name_link' => $page['name'] ? $page['name'] : null,
										'url_link' => null
									)
								));
							}
						}

						$this->modelLanguage->getLanguage();
						$links = $this->getItems('link',$link['id_link'],'all',false);
						$this->setLinksData($links,true);
						$display = $this->template->fetch('theme/loop/link.tpl');
						$this->header->set_json_headers();
						$this->message->json_post_response(true,'add',$display);
					}
                	break;
                case 'edit':
                    if (isset($this->theme)) {
                        if($this->type === 'theme'){
                            $this->upd(array('type'=>'theme'));
                        }
                    }
                    break;
				case 'editlink':
					if(isset($this->edit) && isset($this->link)) {
						$this->link = $this->link[$this->edit];
						$this->upd(array(
							'type' => 'link',
							'data' => array(
								'mode_link' => $this->link['mode'],
								'id' => $this->edit
							)
						));

						foreach ($this->link['content'] as $k => $link) {
							$link['id'] = $this->edit;
							$link['id_lang'] = $k;
							$link['title_link'] = empty($link['title_link']) ? NULL : $link['title_link'];
							$this->upd(array(
								'type' => 'link_content',
								'data' => $link
							));
						}

						$this->header->set_json_headers();
						$this->message->json_post_response(true,'update',$this->edit);
					}
					break;
				case 'delete':
					if(isset($this->id)) {
						$this->del(array(
							'type' => 'link',
							'data' => array(
								'id' => strval($this->id)
							)
						));
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
				case 'get':
					if(isset($this->content)) {
						$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
						$this->setPagesTree($this->getItems($this->content,array('idlang'=>$defaultLanguage['id_lang']),'all',false));
						$this->template->display('theme/loop/page.tpl');
					}
					break;
            }
        }
        else {
			$this->modelLanguage->getLanguage();
            $this->setItemsSkin();
            $this->setLinksData();
            $this->template->display('theme/index.tpl');
        }
    }
}
?>