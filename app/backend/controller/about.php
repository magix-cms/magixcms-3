<?php
class backend_controller_about extends backend_db_about{

    public $edit, $action, $tabs, $search;

    protected $message, $template, $header, $data, $modelLanguage, $collectionLanguage, $country, $language, $languages, $id_pages ,$parent_id, $order;
    public $content, $dataType, $enable_op, $send = array('openinghours' => ''),$ajax,$tableaction,$tableform,$menu_pages;
	public $tableconfig = array(
		'all' => array(
			'id_pages',
			'name_pages' => array('title' => 'name'),
			'parent_pages' => array('col' => 'name_pages', 'title' => 'name'),
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
			'resume_pages' => array('class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null),
			'content_pages' => array('class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null),
			'seo_title_pages' => array('title' => 'seo_title', 'class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null),
			'seo_desc_pages' => array('title' => 'seo_desc', 'class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null),
			'menu_pages',
			'date_register'
		)
	);

    /**
     * @var array, type of website allowed
     */
    public $type = array(
        'org' 		=> array(
            'schema' => 'Organization',
            'label' => 'Organisation'
        ),
        'locb' 		=> array(
            'schema' => 'LocalBusiness',
            'label' => 'Entreprise locale'
        ),
        'corp' 		=> array(
            'schema' => 'Corporation',
            'label' => 'Société'
        ),
        'store' 	=> array(
            'schema' => 'Store',
            'label' => 'Magasin'
        ),
        'food' 		=> array(
            'schema' => 'FoodEstablishment',
            'label' => 'Restaurant'
        ),
        'place' 	=> array(
            'schema' => 'Place',
            'label' => 'Lieu'
        ),
        'person' 	=> array(
            'schema' => 'Person',
            'label' => 'Personne physique'
        )
    );

    /**
     * @var array, Company informations
     */
    public $company = array(
        'name' 		=> NULL,
        'desc'	    => NULL,
        'slogan'	=> NULL,
        'type' 		=> NULL,
        'eshop' 	=> '0',
        'tva' 		=> NULL,
        'contact' 	=> array(
            'mail' 			=> NULL,
            'click_to_mail' => '0',
            'crypt_mail' 	=> '1',
            'phone' 		=> NULL,
            'mobile' 		=> NULL,
            'click_to_call' => '1',
            'fax' 			=> NULL,
            'adress' 		=> array(
                'adress' 		=> NULL,
                'street' 		=> NULL,
                'postcode' 		=> NULL,
                'city' 			=> NULL
            ),
			'languages' => 'Français'
        ),
        'socials' => array(
            'facebook' 	 => NULL,
            'twitter' 	 => NULL,
            'google' 	 => NULL,
            'linkedin' 	 => NULL,
            'viadeo' 	 => NULL,
			'pinterest'  => NULL,
			'instagram'  => NULL,
			'github' 	 => NULL,
			'soundcloud' => NULL
        ),
        'openinghours' => '0',
        'specifications' => array(
            'Mo' => array(
                'open_day' 		=> '0',
                'open_time' 	=> NULL,
                'close_time' 	=> NULL,
                'noon_time' 	=> '0',
                'noon_start' 	=> NULL,
                'noon_end' 		=> NULL
            ),
            'Tu' => array(
                'open_day' 		=> '0',
                'open_time' 	=> NULL,
                'close_time'	=> NULL,
                'noon_time' 	=> '0',
                'noon_start'	=> NULL,
                'noon_end'		=> NULL
            ),
            'We' => array(
                'open_day' 		=> '0',
                'open_time' 	=> NULL,
                'close_time' 	=> NULL,
                'noon_time' 	=> '0',
                'noon_start' 	=> NULL,
                'noon_end' 		=> NULL
            ),
            'Th' => array(
                'open_day' 		=> '0',
                'open_time' 	=> NULL,
                'close_time' 	=> NULL,
                'noon_time' 	=> '0',
                'noon_start' 	=> NULL,
                'noon_end' 		=> NULL
            ),
            'Fr' => array(
                'open_day' 		=> '0',
                'open_time' 	=> NULL,
                'close_time' 	=> NULL,
                'noon_time' 	=> '0',
                'noon_start' 	=> NULL,
                'noon_end'		=> NULL
            ),
            'Sa' => array(
                'open_day' 		=> '0',
                'open_time' 	=> NULL,
                'close_time' 	=> NULL,
                'noon_time' 	=> '0',
                'noon_start' 	=> NULL,
                'noon_end' 		=> NULL
            ),
            'Su' => array(
                'open_day' 		=> '0',
                'open_time' 	=> NULL,
                'close_time' 	=> NULL,
                'noon_time' 	=> '0',
                'noon_start' 	=> NULL,
                'noon_end' 		=> NULL
            )
        )
    );

	/**
	 * backend_controller_about constructor.
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
        $this->language = new backend_controller_language();
        $this->languages = $this->language->setCollection();

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
		if (http_request::isGet('search')) {
			$this->search = $formClean->arrayClean($_GET['search']);
			$this->search = array_filter($this->search, function ($value) { return $value !== ''; });
		}

        /* Global about edition */
        if (http_request::isPost('data_type')) $this->dataType = $formClean->simpleClean($_POST['data_type']);
        if (http_request::isPost('company_name')) $this->company['name'] = $formClean->simpleClean($_POST['company_name']);
        /* if (http_request::isPost('company_slogan')) $this->company['slogan'] = $formClean->simpleClean($_POST['company_slogan']);*/
        if (http_request::isPost('company_type')) $this->company['type'] = $formClean->simpleClean($_POST['company_type']);
        if (http_request::isPost('company_tva')) $this->company['tva'] = $formClean->simpleClean($_POST['company_tva']);

        $this->company['eshop'] = http_request::isPost('company_eshop') ? '1' : '0';
		$this->company['contact']['click_to_mail'] = http_request::isPost('click_to_mail') ? '1' : '0';
		$this->company['contact']['click_to_call'] = http_request::isPost('click_to_call') ? '1' : '0';
		$this->company['contact']['crypt_mail'] = http_request::isPost('crypt_mail') ? '1' : '0';
		$this->enable_op = http_request::isPost('enable_op') ? '1' : '0';

        /* Contact about edition */
        if (http_request::isPost('company_mail')) $this->company['contact']['mail'] = $formClean->simpleClean($_POST['company_mail']);
        if (http_request::isPost('company_phone')) $this->company['contact']['phone'] = $formClean->simpleClean($_POST['company_phone']);
        if (http_request::isPost('company_mobile')) $this->company['contact']['mobile'] = $formClean->simpleClean($_POST['company_mobile']);
        if (http_request::isPost('company_mail')) $this->company['contact']['fax'] = $formClean->simpleClean($_POST['company_fax']);
        if(http_request::isPost('company_adress')){
            $this->company['contact']['adress'] = $formClean->arrayClean($_POST['company_adress']);
			$this->company['contact']['adress']['adress'] = $this->company['contact']['adress']['street'].', '.$this->company['contact']['adress']['postcode'].' '.$this->company['contact']['adress']['city'];
        }

		// --- ADD or EDIT
		if (http_request::isPost('id')) $this->id_pages = $formClean->simpleClean($_POST['id']);
		if (http_request::isPost('parent_id')) $this->parent_id = $formClean->simpleClean($_POST['parent_id']);
		if (http_request::isPost('menu_pages')) $this->menu_pages = $formClean->simpleClean($_POST['menu_pages']);

        if (http_request::isPost('content')) {
            $array = $_POST['content'];
            foreach($array as $key => $arr) {
                foreach($arr as $k => $v) {
                    $array[$key][$k] = ($k == 'company_content' || $k == 'content_pages') ? $formClean->cleanQuote($v) : $formClean->simpleClean($v);
                }
            }
            $this->content = $array;
        }

		/* Socials links edition */
        if (http_request::isPost('company_socials')) $this->company['socials'] = $formClean->arrayClean($_POST['company_socials']);

		# ORDER PAGE
		if (http_request::isPost('pages')) $this->order = $formClean->arrayClean($_POST['pages']);

        /* Opening Hours links edition */
        if (http_request::isPost('openinghours')) $this->send['openinghours'] = $formClean->arrayClean($_POST['openinghours']);
    }

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param string|int|null $id
	 * @param string $context
	 * @param boolean $assign
	 * @param boolean $pagination
	 * @return mixed
	 * @throws Exception
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
			$results = $this->getItems('pages',array('default_lang'=>$defaultLanguage['id_lang']),'all',false,true);
		}

		$assign = $this->tableconfig[(($ajax || $this->edit) ? 'parent' : 'all')];

		if($ajax) {
			$params['section'] = 'pages';
			$params['tab'] = 'pages';
			$params['idcolumn'] = 'id_pages';
			$params['activation'] = true;
			$params['sortable'] = true;
			$params['checkbox'] = true;
			$params['edit'] = true;
			$params['dlt'] = true;
			$params['readonly'] = array();
			$params['cClass'] = 'backend_controller_about';
		}

		$this->data->getScheme(
			array('mc_about_page','mc_about_page_content'),
			array('id_pages','name_pages','content_pages','seo_title_pages','seo_desc_pages','menu_pages','date_register'),
			$assign);

		return array(
			'data' => $results,
			'var' => 'pages',
			'tpl' => 'about/index.tpl',
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
			'context' => 'page',
			'type' => 'pageActiveMenu',
			'data' => array(
				'menu_pages' => $params['active'],
				'id_pages' => $params['ids']
			)
		));
		$this->message->getNotify('update',array('method'=>'fetch','assignFetch'=>'message'));
	}

    /**
     * getTypes
     */
    private function getTypes()
    {
        $this->template->assign('schemaTypes', $this->type);
    }

	/**
	 * Update data
	 * @param $data
	 */
	private function add($data)
	{
		switch ($data['type']) {
			case 'page':
			case 'content':
			case 'close_txt':
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
	 * Update data
	 * @param $data
	 */
	private function upd($data)
	{
		switch ($data['type']) {
			case 'company':
			case 'page':
			case 'content':
			case 'contact':
			case 'refesh_lang':
			case 'enable_op':
			case 'socials':
			case 'openinghours':
			case 'pageActiveMenu':
			case 'close_txt':
				parent::update(
					array(
						'context' => $data['context'],
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
							'id_pages'    => $p[$i],
							'order_pages' => $i
						)
					);
				}
				break;
		}
    }

	/**
	 * Update data
	 * @param $data
	 */
	private function del($data)
	{
		switch ($data['type']) {
			case 'page':
				parent::delete(
					array(
						'context' => $data['context'],
						'type' => $data['type']
					),
					$data['data']
				);
				$this->message->json_post_response(true,'delete',$data['data']);
				break;
		}
    }

	/**
	 * @param $data
	 * @return array
	 */
	private function setItemData($data){
		$arr = array();
		$conf = array();

		foreach ($data as $page) {

			$publicUrl = !empty($page['url_pages']) ? '/'.$page['iso_lang'].'/about/'.$page['id_pages'].'-'.$page['url_pages'].'/' : '';
			if (!array_key_exists($page['id_pages'], $arr)) {
				$arr[$page['id_pages']] = array();
				$arr[$page['id_pages']]['id_pages'] = $page['id_pages'];
				$arr[$page['id_pages']]['id_parent'] = $page['id_parent'];
				$arr[$page['id_pages']]['menu_pages'] = $page['menu_pages'];
				$arr[$page['id_pages']]['date_register'] = $page['date_register'];
			}
			$arr[$page['id_pages']]['content'][$page['id_lang']] = array(
				'id_lang'           => $page['id_lang'],
				'iso_lang'          => $page['iso_lang'],
				'name_pages'        => $page['name_pages'],
                'resume_pages'      => $page['resume_pages'],
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
	 * @param $idpage
	 * @return array
	 */
	private function saveContent($idpage)
	{
		$extendData = array();

		foreach ($this->content as $lang => $content) {
			$content['id_lang'] = $lang;
			$content['id_pages'] = $idpage;
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

			$contentPage = $this->getItems('contentPage',array('id_pages'=>$idpage, 'id_lang'=>$lang),'one',false);

			if($contentPage != null) {
				$this->upd(
					array(
						'context' => 'page',
						'type' => 'page',
						'data' => array(
							'id_pages' => $idpage,
							'id_parent' => empty($this->parent_id) ? NULL : $this->parent_id,
							'menu_pages' => isset($this->menu_pages) ? 1 : 0
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
	 * parseOpHours
	 */
    private function parseOpHours()
	{
		/* Update openinghours */
		foreach ($this->company['specifications'] as $day => $opt) {
			if(isset($this->send['openinghours'][$day])) {
				$this->company['specifications'][$day]['open_day'] = (isset($this->send['openinghours'][$day]['open_day']) ? '1' : '0');

				if(isset($this->send['openinghours'][$day]['open_day'])) {
					if(isset($this->send['openinghours'][$day]['noon_time'])) {
						$this->company['specifications'][$day]['noon_time'] = '1';

						$this->company['specifications'][$day]['noon_start'] = ($this->send['openinghours'][$day]['noon_start']['hh'] ? ($this->send['openinghours'][$day]['noon_start']['hh'].':'.$this->send['openinghours'][$day]['noon_start']['mm']) : null);
						$this->company['specifications'][$day]['noon_end'] = ($this->send['openinghours'][$day]['noon_end']['hh'] ? ($this->send['openinghours'][$day]['noon_end']['hh'].':'.$this->send['openinghours'][$day]['noon_end']['mm']) : null);
					} else {
						$this->company['specifications'][$day]['noon_time'] = '0';
					}

					$this->company['specifications'][$day]['open_time'] = ($this->send['openinghours'][$day]['open']['hh'] ? ($this->send['openinghours'][$day]['open']['hh'].':'.$this->send['openinghours'][$day]['open']['mm']) : null);
					$this->company['specifications'][$day]['close_time'] = ($this->send['openinghours'][$day]['close']['hh'] ? ($this->send['openinghours'][$day]['close']['hh'].':'.$this->send['openinghours'][$day]['close']['mm']) : null);
				}
				else {
					foreach ($this->send['openinghours'][$day]['content'] as $lang => $content) {
						$contentPage = $this->getItems('close_txt',array('id_lang'=>$lang),'one',false);

						if($contentPage != null) {
							$this->upd(
								array(
									'context' => 'about',
									'type' => 'close_txt',
									'data' => array(
										'id' => $contentPage['id_content'],
										'column' => 'text_'.$day,
										'value' => $this->send['openinghours'][$day]['content'][$lang]['txt']
									)
								)
							);
						}
						else {
							$this->add(
								array(
									'context' => 'about',
									'type' => 'close_txt',
									'data' => array(
										'id_lang' => $lang,
										'column' => 'text_'.$day,
										'value' => $this->send['openinghours'][$day]['content'][$lang]['txt']
									)
								)
							);
						}
					}
				}
			}
			else {
				$this->company['specifications'][$day]['open_day'] = '0';
			}
		}
    }

	/**
	 * @return array
	 */
    public function getCompanyData()
    {
		$infoData = parent::fetchData(array('context'=>'all','type'=>'info'));
		$about = array();
		foreach ($infoData as $item) {
			$about[$item['name_info']] = $item['value_info'];
		}
        $schedule = array();

        foreach ($this->company as $info => $value) {
        	switch ($info) {
				case 'contact':
					foreach ($value as $contact_info => $val) {
						if($contact_info == 'adress') {
							$this->company['contact'][$contact_info]['adress'] = $about['adress'];
							$this->company['contact'][$contact_info]['street'] = $about['street'];
							$this->company['contact'][$contact_info]['postcode'] = $about['postcode'];
							$this->company['contact'][$contact_info]['city'] = $about['city'];
						} elseif ($contact_info == 'languages') {
							$this->company['contact'][$contact_info] = $this->getActiveLang();
						} else {
							$this->company['contact'][$contact_info] = $about[$contact_info];
						}
					}
					break;
				case 'socials':
					foreach ($value as $social_name => $link) {
						$this->company['socials'][$social_name] = $about[$social_name];
					}
					break;
				case 'specifications':
					foreach ($value as $day => $op_info) {
						foreach ($op_info as $t => $v) {
							$this->company['specifications'][$day][$t] = $schedule[$day][$t];
						}
					}
					break;
				case 'openinghours':
					$this->company[$info] = $about['openinghours'];

					$op = parent::fetchData(array('context'=>'all','type'=>'op'));
					$op_content = parent::fetchData(array('context'=>'all','type'=>'op_content'));

					foreach ($op as $d) {
						$abbr = $d['day_abbr'];
						$schedule[$abbr] = $d;
						array_shift($schedule[$abbr]);

						foreach ($op_content as $opc) {
							$schedule[$abbr]['close_txt'][$opc['id_lang']] = $opc['text_'.strtolower($abbr)];
						}

						$schedule[$abbr]['open_time'] = explode(':',$d['open_time']);
						$schedule[$abbr]['close_time'] = explode(':',$d['close_time']);
						$schedule[$abbr]['noon_start'] = explode(':',$d['noon_start']);
						$schedule[$abbr]['noon_end'] = explode(':',$d['noon_end']);
					}
					break;
				default:
					$this->company[$info] = $about[$info];
			}
        }

        return $this->company;
    }

    /**
     * @return array
     */
    private function getContentData(){
        $data = parent::fetchData(array('context'=>'all','type'=>'content'));
        $newArr = array();
        foreach ($data as $item) {
            $newArr[$item['id_lang']][$item['name_info']] = $item['value_info'];
        }
        return $newArr;
    }

	/**
	 * @param string (languages|iso) $mode
	 * @return string
	 */
	private function getActiveLang($mode = 'languages')
	{
		$langs = $this->getItems($mode,null,'all',false);

		$list = array();
		foreach ($langs as $lang) {
			$list[] = $mode === 'iso' ? ucfirst($this->languages[$lang['iso_lang']]) : ucfirst($lang['name_lang']);
		}

		$langs = implode(', ',$list);

		return $langs;
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
                case 'addpage':
					if(isset($this->content)) {
						$this->add(
							array(
								'context' => 'page',
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
						$this->template->display('about/pages/add.tpl');
					}
                	break;
                case 'edit':
                	$msg = 'update';
                	$data = array();

                	if(isset($this->tabs) && $this->tabs = 'pages') {
						if(isset($this->id_pages)) {
							$extendData = $this->saveContent($this->id_pages);
							$this->message->json_post_response(true, 'update', array('result'=>$this->id_pages,'extend'=>$extendData));
						}
						else {
							$this->modelLanguage->getLanguage();
							$setEditData = parent::fetchData(array('context'=>'all','type'=>'page'), array('edit'=>$this->edit));
							$setEditData = $this->setItemData($setEditData);
							$this->template->assign('page',$setEditData[$this->edit]);

							/*$assign = array(
								'id_pages',
								'name_pages' => ['title' => 'name'],
								'content_pages' => ['class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null],
								'seo_title_pages' => ['title' => 'seo_title', 'class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null],
								'seo_desc_pages' => ['title' => 'seo_desc', 'class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null],
								'menu_pages',
								'date_register'
							);*/
							$this->data->getScheme(
								array('mc_about_page','mc_about_page_content'),
								array('id_pages','name_pages','resume_pages','content_pages','seo_title_pages','seo_desc_pages','menu_pages','date_register'),
								$this->tableconfig['parent']);
							$pageChild = $this->getItems('pagesChild',$this->edit,'all');

							$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
							$this->getItems('pagesSelect',array('default_lang'=>$defaultLanguage['id_lang']),'all');
							$this->template->display('about/pages/edit.tpl');
						}
					}
					else {
						switch ($this->dataType) {
							case 'text':
								foreach ($this->content as $lang => $content) {
									$config = array(
										'context' => 'about',
										'type' => 'content',
										'data' => array(
											'desc'      => $content['company_desc'],
											'slogan'    => $content['company_slogan'],
											'content'   => $content['company_content'],
											'seo_title' => $content['seo_title'],
											'seo_desc'  => $content['seo_desc'],
											'id_lang'   => $lang
										)
									);

									if (parent::fetchData(array('context' => 'one', 'type' => 'content'), array('id_lang' => $lang))) {
										$this->upd($config);
									}
									else {
										$this->add($config);
									}
								}
								break;
							case 'refesh_lang':
								$data = array('languages' => $this->getActiveLang('iso'));
								$msg = 'refresh_lang';
								break;
							case 'enable_op':
								$data = array('enable_op' => $this->enable_op);
								break;
							case 'openinghours':
								$this->parseOpHours();
								$data = $this->company;
								break;
							case 'contact':
							case 'company':
							case 'socials':
								$this->company['socials'] = array_map(function($v){ return empty($v) ? null : $v; },$this->company['socials']);
								$data = $this->company;
								break;
						}

						if(!empty($data)) {
							$this->upd(
								array(
									'context' => 'about',
									'type' => $this->dataType,
									'data' => $data
								)
							);
						}

						$this->message->json_post_response(true, $msg);
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
								'type' => 'page',
								'data' => array(
									'id' => $this->id_pages
								)
							)
						);
					}
					break;
            }
        }
        else {
			$this->modelLanguage->getLanguage();
			$defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));

			$this->getItems('pages',array('default_lang'=>$defaultLanguage['id_lang']),'all',true,true);
			$this->data->getScheme(
				array('mc_about_page','mc_about_page_content'),
				array('id_pages','name_pages','resume_pages','content_pages','seo_title_pages','seo_desc_pages','menu_pages','date_register'),
				$this->tableconfig['parent']);

            $this->getTypes();
            $this->template->assign('contentData',$this->getContentData());
            $this->template->assign('companyData',$this->getCompanyData());
            $this->template->display('about/index.tpl');
        }
    }
}