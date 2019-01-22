<?php
class backend_controller_language extends backend_db_language{
    public $edit, $action, $tabs;
    protected $message, $template, $header, $data, $arrayTools;
    public $search,$default_lang,$id_lang,$active_lang,$iso_lang,$name_lang, $language;

	/**
	 * backend_controller_language constructor.
	 * @param stdClass $t
	 */
    public function __construct($t = null)
    {
        $this->template = $t ? $t : new backend_model_template;
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $formClean = new form_inputEscape();
        $this->arrayTools = new collections_ArrayTools();

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
            $this->id_lang = $formClean->simpleClean($_POST['id']);
        }
        if (http_request::isPost('default_lang')) {
            $this->default_lang = $formClean->numeric($_POST['default_lang']);
        }
        if (http_request::isPost('active_lang')) {
            $this->active_lang = $formClean->numeric($_POST['active_lang']);
        }
        if (http_request::isPost('iso_lang')) {
            $this->iso_lang = $formClean->simpleClean($_POST['iso_lang']);
        }
        if (http_request::isPost('name_lang')) {
            $this->name_lang = $formClean->simpleClean($_POST['name_lang']);
        }

		// --- Search
		if (http_request::isGet('search')) {
			$this->search = $formClean->arrayClean($_GET['search']);
			$this->search = array_filter($this->search, function ($value) { return $value !== ''; });
		}
        // --- Recursive Actions
        if (http_request::isGet('language')) {
            $this->language = $formClean->arrayClean($_GET['language']);
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
     * @return array
     */
    public function setCollection(){
        return $this->arrayTools->defaultLanguage();
    }

    /**
     *
     */
    public function getCollection(){
        $data = $this->setCollection();
        $this->template->assign('getLanguageCollection',$data);
    }

    /**
     * Insertion de données
     * @param $data
     */
    private function add($data)
    {
        switch ($data['type']) {
            case 'newLang':
                parent::insert(
                    array(
                    'type'=>$data['type']
                    ),array(
                        'iso_lang'      => $this->iso_lang,
                        'name_lang'	    => $this->name_lang,
                        'default_lang'	=> $this->default_lang,
                        'active_lang'	=> $this->active_lang
                    )
                );
                $this->message->json_post_response(true,'add_redirect');
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
            case 'lang':
                parent::update(
                    array(
                        'type'=>$data['type']
                    ),array(
                        'id_lang'       => $this->id_lang,
                        'iso_lang'      => $this->iso_lang,
                        'name_lang'	    => $this->name_lang,
                        'default_lang'	=> $this->default_lang,
                        'active_lang'	=> $this->active_lang
                    )
                );
                break;
            case 'langActive':
                parent::update(
                    array(
                        'context'   =>    'lang',
                        'type'      =>    $data['type']
                    ),
                    $data['data']
                );
                break;
        }
    }
    /**
     * Insertion de données
     * @param $data
     */
    private function del($data){
        switch($data['type']){
            case 'delLang':
                $fetchData = parent::fetchData(array('context'=>'one','type'=>'count'));
                $countID = count(explode(',',$data['data']['id']));
                if(($fetchData['nb'] > 1) && ($fetchData['nb'] > $countID)){
                    $this->message->json_post_response(true,'delete',$data['data']);
                    parent::delete(
                        array(
                            'context'   =>    'language',
                            'type'      =>    $data['type']
                        ),
                        $data['data']
                    );
                }else{
                    $this->message->json_post_response(false,'delete_min');
                }
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
                    if(isset($this->iso_lang) && isset($this->name_lang)){
                        $this->add(
                            array(
                                'type'=>'newLang'
                            )
                        );
                    }else{
                        $this->getCollection();
                        $this->template->display('language/add.tpl');
                    }
                    break;

                case 'edit':
                    if (isset($this->iso_lang)) {
                        $this->upd(
                            array(
                                'type' => 'lang'
                            )
                        );
                        $this->message->json_post_response(true,'update',$this->id_lang);
                    }else{
                        $this->getCollection();
                        //$this->getItemsLanguage($this->edit);
						$this->getItems('lang',$this->edit);
                        $this->template->display('language/edit.tpl');
                    }
                    break;
                case 'delete':
                    if(isset($this->id_lang)) {
                        $this->del(
                            array(
                                'type'=>'delLang',
                                'data'=>array(
                                    'id' => $this->id_lang
                                )
                            )
                        );
                    }
                    break;
                case 'active-selected':
                case 'unactive-selected':
                    if(isset($this->language) && is_array($this->language) && !empty($this->language)) {
                        $this->upd(
                            array(
                                'type'=>'langActive',
                                'data'=>array(
                                    'active_lang' => ($this->action == 'active-selected'?1:0),
                                    'id_lang' => implode($this->language, ',')
                                )
                            )
                        );
                    }
                    //$this->getItemsLanguage();
					$this->getItems('langs');
					$assign = array(
						'id_lang',
						'iso_lang' => ['title' => 'iso_lang'],
						'name_lang',
						'default_lang' => ['title' => 'default_lang'],
						'active_lang'
					);
					$this->data->getScheme(array('mc_lang'),array('id_lang','iso_lang','name_lang','default_lang','active_lang'),$assign);
                    $this->message->getNotify('update',array('method'=>'fetch','assignFetch'=>'message'));
                    $this->template->display('language/index.tpl');
                    break;
            }
        }else{
			$this->getItems('langs');
			$assign = array(
				'id_lang',
				'iso_lang' => ['title' => 'iso_lang'],
				'name_lang',
				'default_lang' => ['title' => 'default_lang'],
				'active_lang'
			);
			$this->data->getScheme(array('mc_lang'),array('id_lang','iso_lang','name_lang','default_lang','active_lang'),$assign);
            $this->template->display('language/index.tpl');
        }
    }
}
?>