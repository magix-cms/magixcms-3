<?php
class backend_controller_theme extends backend_db_theme{
    public $edit, $action, $tabs;
    protected $message, $template, $header, $data, $modelLanguage, $collectionLanguage;
    public $theme, $content, $type, $id, $link, $order, $share, $twitter_id;

    public $roots = array('home','about','catalog','news','plugin');

    /**
	 * backend_controller_theme constructor.
	 * @param stdClass $t
	 */
    public function __construct($t = null)
    {
        $this->template = $t ? $t : new backend_model_template;
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

		if(http_request::isPost('share')){
			$this->share = $formClean->arrayClean($_POST['share']);
		}
		if (http_request::isPost('twitter_id')) {
			$this->twitter_id = $formClean->simpleClean($_POST['twitter_id']);
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

				if($item['type_link'] === 'plugin') {
					$plugin = $this->getItems('plugin',array('id' => $item['id_page']),'one',false);
					$plugin_class = 'plugins_'.$plugin['name'].'_admin';

					if(method_exists($plugin_class,'menu_mode')) {
						$plugin_instance = new $plugin_class;
						$arr[$item['id_link']]['mode_opt'] = $plugin_instance->menu_mode();
					}
				}
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
	 *
	 */
	private function setShare()
	{

		$config = array(
			'facebook' => 0,
			'twitter' => 0,
			'viadeo' => 0,
			'google' => 0,
			'linkedin' => 0,
			'pinterest' => 0
		);

		if(isset($this->share) && is_array($this->share)) {
			foreach ($this->share as $k => $v) {
				$config[$k] = 1;
			}
		}

		$config['twitter_id'] = $this->twitter_id;
		return $config;
    }

	/**
	 * @return array
	 */
	private function setShareConfig()
	{
		$config = $this->getItems('shareConfig',null,'one',false);
		$this->template->assign('twitter_id',$config['twitter_id']);
		unset($config['twitter_id']);
		$this->template->assign('shareConfig',$config);
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
				$this->message->json_post_response(true,'update',$data['type']);
				break;
            case 'share':
				parent::update(
					array(
						'type' => $data['type']
					),
					$this->setShare()
				);
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
							if($this->type === 'plugin') {
								$plugin = $this->getItems($this->type,$this->id,'one',false);
							}
							foreach ($langs as $lang) {
								$url = '/'.$lang['iso_lang'].'/'.(isset($plugin) ? $plugin['name'].'/' : ($this->type !== 'home' ? $this->type.'/' : ''));
								$name = isset($plugin) ? $plugin['name'] : $this->template->getConfigVars($this->type);
								$this->add(array(
									'type' => 'link_content',
									'data' => array(
										'id' => $link['id_link'],
										'id_lang' => $lang['id_lang'],
										'name_link' => $name,
										'url_link' => $url
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
						$this->message->json_post_response(true,'add',$display);
					}
                	break;
                case 'edit':
					if (isset($this->share)) {
						$this->upd(array('type'=>'share'));
					}
					elseif (isset($this->theme)) {
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

						foreach ($this->link['content'] as $k => $l) {
							$link = array();
							$cl = $this->getItems('link_content',array('id' => $this->edit, 'lang' => $k),'one',false);

							if($cl) {
								$link['id'] = $this->edit;
								$link['id_lang'] = $k;
								$link['name_link'] = $l['name_link'] === '' ? NULL : $l['name_link'];
								$link['title_link'] = $l['title_link'] === '' ? NULL : $l['title_link'];

								$this->upd(array(
									'type' => 'link_content',
									'data' => $link
								));
							}
							else {
								$link['id'] = $this->edit;
								$link['id_lang'] = $k;

								$lang = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'lang'),array('id' => $k));
								$cl = $this->getItems('link',array('id' => $this->edit),'one',false);

								if(in_array($cl['type_link'],$this->roots)) {
									$this->template->configLoad();
									if($cl['type_link'] === 'plugin') {
										$plugin = $this->getItems($cl['type_link'],$this->id,'one',false);
									}

									$url = '/'.$lang['iso_lang'].'/'.(isset($plugin) ? $plugin['name'].'/' : ($cl['type_link'] !== 'home' ? $cl['type_link'].'/' : ''));
									$name = isset($plugin) ? $plugin['name'] : $this->template->getConfigVars($cl['type_link']);
								}
								else {
									$page = $this->getItems($cl['type_link'],array('id' => $cl['id_page'],'id_lang' => $k),'one',false);
									$name = $page['name'] ? $page['name'] : null;
								}

								$link['url_link'] = $url ? $url : null;
								$link['name_link'] = $l['name_link'] === '' ? $name : $l['name_link'];

								$this->add(array(
									'type' => 'link_content',
									'data' => $link
								));
							}
						}
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
						if($this->content === 'plugin') {
							$plugins = $this->getItems($this->content,null,'all',false);
							foreach ($plugins as $k => $plugin) {
								$pluginClass = 'plugins_'.$plugin['name'].'_public';
								$frontrun =  class_exists($pluginClass) ? method_exists($pluginClass,'run') : false;
								if(!$frontrun) unset($plugins[$k]);
							}
							$this->template->assign('links',$plugins);
						}
						else {
							$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
							$this->setPagesTree($this->getItems($this->content,array('idlang'=>$defaultLanguage['id_lang']),'all',false));
						}
						$this->template->display('theme/loop/page.tpl');
					}
					break;
            }
        }
        else {
			$this->modelLanguage->getLanguage();
            $this->setItemsSkin();
            $this->setLinksData();
            $this->setShareConfig();
            $this->template->display('theme/index.tpl');
        }
    }
}