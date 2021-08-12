<?php
class backend_controller_catalog extends backend_db_catalog {

    public $edit, $action, $tabs, $search;

    protected $message, $template, $header, $data, $modelLanguage, $collectionLanguage, $modelPlugins;
    public $controller, $content;

    /**
	 * @param stdClass $t
     * backend_controller_catalog constructor.
     */
    public function __construct($t = null)
    {
        $this->template = $t instanceof backend_model_template ? $t : new backend_model_template;
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $formClean = new form_inputEscape();
        $this->modelLanguage = new backend_model_language($this->template);
        $this->collectionLanguage = new component_collections_language();
        $this->modelPlugins = new backend_model_plugins();

        // --- GET
        if(http_request::isGet('controller')) $this->controller = $formClean->simpleClean($_GET['controller']);
        if (http_request::isGet('edit')) $this->edit = $formClean->numeric($_GET['edit']);
        if (http_request::isGet('action')) {
            $this->action = $formClean->simpleClean($_GET['action']);
        }
        elseif (http_request::isPost('action')) {
            $this->action = $formClean->simpleClean($_POST['action']);
        }
        if (http_request::isGet('tabs')) $this->tabs = $formClean->simpleClean($_GET['tabs']);

        if (http_request::isPost('content')) {
            $array = $_POST['content'];
            foreach($array as $key => $arr) {
                foreach($arr as $k => $v) {
                    $array[$key][$k] = ($k == 'catalog_content') ? $formClean->cleanQuote($v) : $formClean->simpleClean($v);
                }
            }
            $this->content = $array;
        }
        if (http_request::isGet('plugin')) $this->plugin = $formClean->simpleClean($_GET['plugin']);
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
     * @throws Exception
     */
    private function setItemsData(){
        $data = parent::fetchData(array('context'=>'all','type'=>'content'));
        $newArr = array();
        foreach ($data as $item) {
            $newArr[$item['id_lang']][$item['name_info']] = $item['value_info'];
        }
        return $newArr;
    }

    /**
     * save data
     */
    private function save(){
        if (isset($this->content)) {
            foreach ($this->content as $lang => $content) {
                if (parent::fetchData(array('context' => 'one', 'type' => 'content'), array('id_lang' => $lang)) != null) {
                    parent::update(array('type' => 'content'), array(
                            'name' => !empty($content['catalog_name']) ? $content['catalog_name'] : NULL,
                            'content' => !empty($content['catalog_content']) ? $content['catalog_content'] : NULL,
                            'seo_title' => !empty($content['seo_title']) ? $content['seo_title'] : NULL,
                            'seo_desc'  => !empty($content['seo_desc']) ? $content['seo_desc'] : NULL,
                            'id_lang' => $lang
                        )
                    );
                } else {
                    parent::insert(array('type' => 'newContent'), array(
                            'name' => !empty($content['catalog_name']) ? $content['catalog_name'] : NULL,
                            'content' => !empty($content['catalog_content']) ? $content['catalog_content'] : NULL,
                            'seo_title' => !empty($content['seo_title']) ? $content['seo_title'] : NULL,
                            'seo_desc'  => !empty($content['seo_desc']) ? $content['seo_desc'] : NULL,
                            'id_lang' => $lang
                        )
                    );
                }
            }
            $this->message->json_post_response(true, 'update', $this->content);
        }
    }

    /**
     *
     */
    public function run(){
        // Initialise l'API menu des plugins core
        $this->modelPlugins->getItems(
            array(
                'type'      =>  'tabs',
                'controller'=>  $this->controller
            )
        );

        if(isset($this->plugin)){
            // Execute un plugin core
            $this->modelLanguage->getLanguage();
            $this->modelPlugins->getCoreItem();
        }else{
            if(isset($this->action)) {
                switch ($this->action) {
                    case 'edit':
                        $this->save();
                        break;
                }
            }
            else {
                $this->modelLanguage->getLanguage();
                $this->template->assign('contentData',$this->setItemsData());
                $this->template->display('catalog/index.tpl');
            }
        }
    }
}