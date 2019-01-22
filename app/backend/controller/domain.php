<?php
class backend_controller_domain extends backend_db_domain
{
    public $edit, $action, $tabs, $search;
    protected $message, $template, $header, $data, $xml;
    protected $collectionLanguage;
    public $id_domain,$url_domain,$default_domain, $data_type,$id_lang,$default_lang,$tracking_domain,$config;

	/**
	 * backend_controller_domain constructor.
	 * @param stdClass $t
	 */
    public function __construct($t = null)
    {
        $this->template = $t ? $t : new backend_model_template;
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $formClean = new form_inputEscape();
        $this->xml = new backend_model_sitemap($this->template);
        $this->collectionLanguage = new component_collections_language();

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

        // --- POST

        if (http_request::isPost('id')) {
            $this->id_domain = $formClean->simpleClean($_POST['id']);
        }
        if (http_request::isPost('url_domain')) {
            $this->url_domain = $formClean->simpleClean($_POST['url_domain']);
        }
        if (http_request::isPost('default_domain')) {
            $this->default_domain = $formClean->numeric($_POST['default_domain']);
        }
        if (http_request::isPost('default_lang')) {
            $this->default_lang = $formClean->numeric($_POST['default_lang']);
        }
        if (http_request::isPost('id_lang')) {
            $this->id_lang = $formClean->numeric($_POST['id_lang']);
        }
        if (http_request::isPost('data_type')) {
            $this->data_type = $formClean->simpleClean($_POST['data_type']);
        }
        if (http_request::isPost('tracking_domain')) {
            $this->tracking_domain = $formClean->cleanQuote($_POST['tracking_domain']);
        }
        if (http_request::isPost('config')) {
            $this->config = $formClean->arrayClean($_POST['config']);
        }else{
            $this->config = array();
        }

		// --- Search
		if (http_request::isGet('search')) {
			$this->search = $formClean->arrayClean($_GET['search']);
			$this->search = array_filter($this->search, function ($value) { return $value !== ''; });
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
	 * Return the valid domains
	 */
	public function getValidDomains()
	{
		return $this->getItems('domain',null,'all',false);
	}

    private function getBuildXmlItems($config){
        $langDomain = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'currentDomain'),array('url'=>$config['url_domain']));
        $setLang = $this->collectionLanguage->fetchData(array('context'=>'all','type'=>'domain'),array('id'=>$langDomain['id_domain']));
        if($setLang != null){
            $lang = $setLang;
        }else{
            $lang = $this->collectionLanguage->fetchData(array('context'=>'all','type'=>'langs'));
        }

        $basePath = component_core_system::basePath();

        $newData = array();
        $newBaseXml = array();
        $newImgXml = array();
        $parentXML = $this->xml->url(array($config['url_domain'], 'domain' => $config['url_domain'], 'url' => '/'. 'sitemap-' . $config['url_domain'] . '.xml'));

        if($lang != null) {
            foreach ($lang as $key => $value) {
                $newBaseXml[$key] = $this->xml->url(array('domain' => $config['url_domain'], 'url' => '/'.$value['iso_lang'] . '-sitemap-' . $config['url_domain'] . '.xml'));
                $newImgXml[$key] = $this->xml->url(array('domain' => $config['url_domain'], 'url' => '/'.$value['iso_lang'] . '-sitemap-image-' . $config['url_domain'] . '.xml'));
            }
            $newData = array_merge($newBaseXml,$newImgXml);
            array_unshift($newData,$parentXML);
        }
        if(file_exists($basePath . 'sitemap-' . $config['url_domain'] . '.xml')) {
            $this->template->assign('xmlItems', $newData);
        }
    }
    /**
     * Insertion de données
     * @param $data
     */
    private function add($data)
    {
        switch ($data['type']) {
            case 'newDomain':
                parent::insert(
                    array(
                        'type'=>$data['type']
                    ),array(
                        'url_domain'      => $this->url_domain,
                        'default_domain'  => $this->default_domain
                    )
                );
                $this->message->json_post_response(true,'add_redirect');
                break;
            case 'newLanguage':
                parent::insert(
                    array(
                        'type' => 'newLanguage'
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
            case 'domain':
                parent::update(
                    array(
                        'type'=>$data['type']
                    ),array(
                        'id_domain'       => $this->id_domain,
                        'url_domain'      => $this->url_domain,
                        'tracking_domain' => $this->tracking_domain,
                        'default_domain'  => $this->default_domain
                    )
                );
                break;
            case 'modules':
                if(!isset($this->config['pages'])){
                    $pages = '0';
                }else{
                    $pages = '1';
                }

                if(!isset($this->config['news'])){
                    $news = '0';
                }else{
                    $news = '1';
                }
                if(!isset($this->config['catalog'])){
                    $catalog = '0';
                }else{
                    $catalog = '1';
                }
                if(!isset($this->config['about'])){
                    $about = '0';
                }else{
                    $about = '1';
                }
                parent::update(
                    array(
                        'type'=>$data['type']
                    ),array(
                        'pages'	    => $pages,
                        'news'	    => $news,
                        'catalog'	=> $catalog,
                        'about'	    => $about
                    )
                );
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
            case 'delDomain':
                parent::delete(
                    array(
                        'context'   =>    'domain',
                        'type'      =>    $data['type']
                    ),
                    $data['data']
                );
                $this->message->json_post_response(true,'delete',$data['data']);
                break;
            case 'delLanguage':
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
                    if(isset($this->url_domain)){
                        $this->add(
                            array(
                                'type'=>'newDomain'
                            )
                        );
                    }elseif(isset($this->id_lang)) {
                        $this->add(array(
                            'type' => 'newLanguage',
                            'data' => array(
                                'id_domain'     => $this->id_domain,
                                'id_lang'       => $this->id_lang,
                                'default_lang'  => $this->default_lang
                            )
                        ));
                        $this->getItems('lastLanguage',array('id'=>$this->id_domain),'one','row');
                        $display = $this->template->fetch('domain/loop/langs.tpl');
                        $this->message->json_post_response(true,'add',$display);

                    }else{
                        $this->template->display('domain/add.tpl');
                    }
                    break;
                case 'edit':
                    if (isset($this->data_type)) {
                        if (isset($this->url_domain) && $this->data_type === 'domain') {

                            $this->upd(
                                array(
                                    'type' => 'domain'
                                )
                            );
                            $this->message->json_post_response(true,'update',$this->id_domain);

                        }elseif ($this->data_type === 'modules') {

                            $this->upd(
                                array(
                                    'type' => 'modules'
                                )
                            );
                            $this->message->json_post_response(true,'update',$this->data_type);

                        }else{
                            $data = parent::fetchData(
                                array(
                                    'context'   =>'one',
                                    'type'      =>'domain'
                                ),array(
                                    'id'       => $this->edit
                                )
                            );
                            $newData = array('domain'=>$data['url_domain']);
                            $this->xml->setItems($newData);
                        }
                    }else{
                        //$this->getItemsDomain($this->edit);
                        $collectionLanguage = new component_collections_language();
                        $language = $collectionLanguage->fetchData(array('context'=>'all','type'=>'active'));
                        $this->template->assign('language',$language);
                        $domain = $this->getItems('domain',$this->edit,null,false);
                        $this->template->assign('domain',$domain);
                        // ---- languages
                        $this->getItems('langs',array(':id'=>$this->edit),'all');

                        $this->getBuildXmlItems($domain);

                        $this->template->display('domain/edit.tpl');
                    }

                    break;
                case 'delete':
                    if(isset($this->id_domain)) {
                        if(isset($this->tabs)) {
                            switch ($this->tabs) {
                                case 'langs':
                                    $this->del(
                                        array(
                                            'type' => 'delLanguage',
                                            'data' => array(
                                                'id' => $this->id_domain
                                            )
                                        )
                                    );
                                    $this->message->json_post_response(true, 'delete', array('id' => $this->id_domain));
                                    break;
                            }
                        }else{
                            $this->del(
                                array(
                                    'type'=>'delDomain',
                                    'data'=>array(
                                        'id' => $this->id_domain
                                    )
                                )
                            );
                        }
                    }
                    break;
            }
        }else{
			$this->getItems('domain');
			$assign = array(
				'id_domain',
				'url_domain' => ['title' => 'url_domain', 'class' => ''],
				'default_domain' => ['title' => 'default_domain']
			);
			$this->data->getScheme(array('mc_domain'),array('id_domain','url_domain','default_domain'),$assign);

            $this->template->assign('setConfig',$this->xml->setConfigData());
            $this->template->display('domain/index.tpl');
        }
    }

}