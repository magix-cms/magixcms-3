<?php
class backend_controller_home extends backend_db_home{

    public $edit, $action, $tabs;
    protected $controller, $message, $template, $header, $data, $modelLanguage, $modelPlugins, $plugin;
    public $content;

	/**
	 * backend_controller_home constructor.
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
        $this->modelPlugins = new backend_model_plugins();

        // --- GET
        if(http_request::isGet('controller')) {
            $this->controller = $formClean->simpleClean($_GET['controller']);
        }
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
        if (http_request::isPost('content')) {
            $array = $_POST['content'];
            foreach($array as $key => $arr) {
                foreach($arr as $k => $v) {
                    $array[$key][$k] = ($k == 'content_page') ? $formClean->cleanQuote($v) : $formClean->simpleClean($v);
                }
            }
            $this->content = $array;
        }

        if(http_request::isGet('plugin')){
            $this->plugin = $formClean->simpleClean($_GET['plugin']);
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
     * @return mixed|null
     */
    private function setItemData(){
        return parent::fetchData(array('context'=>'one','type'=>'root'));
    }

    /**
     * @return array
     */
    private function setItemsData(){
        $data = parent::fetchData(array('context'=>'all','type'=>'pages'));
        $arr = array();
        foreach ($data as $page) {
            if (!array_key_exists($page['id_page'], $arr)) {
                $arr[$page['id_page']] = array();
                $arr[$page['id_page']]['id_page'] = $page['id_page'];
                $arr[$page['idpage']]['date_register'] = $page['date_register'];
            }
            $arr[$page['id_page']]['content'][$page['id_lang']] = array(
                'id_lang' => $page['id_lang'],
                'title_page' => $page['title_page'],
                'content_page' => $page['content_page'],
                'seo_title_page' => $page['seo_title_page'],
                'seo_desc_page' => $page['seo_desc_page'],
                'published' => $page['published']
            );
        }
        return $arr;
    }
    /**
     * Mise a jour des données
     */
    private function save()
    {
        $fetchRootData = parent::fetchData(array('context'=>'one','type'=>'root'));
        if($fetchRootData != null){
            $id_page = $fetchRootData['id_page'];
        }else{
            parent::insert(array('type'=>'newHome'));
            $newData = parent::fetchData(array('context'=>'one','type'=>'root'));
            $id_page = $newData['id_page'];
        }

        if($id_page) {
            foreach ($this->content as $lang => $content) {
                $content['published'] = (!isset($content['published']) ? 0 : 1);
                if (parent::fetchData(array('context' => 'one', 'type' => 'content'), array('id_page' => $id_page, 'id_lang' => $lang)) != null) {
                    parent::update(array('type' => 'content'), array(
                            'title_page'        => $content['title_page'],
                            'content_page'      => $content['content_page'],
                            'seo_title_page'    => $content['seo_title_page'],
                            'seo_desc_page'     => $content['seo_desc_page'],
                            'published'         => $content['published'],
                            'id_page'           => $id_page,
                            'id_lang'           => $lang
                        )
                    );
                } else {
                    parent::insert(array('type' => 'newContent'), array(
                            'title_page'        => $content['title_page'],
                            'content_page'      => $content['content_page'],
                            'seo_title_page'    => $content['seo_title_page'],
                            'seo_desc_page'     => $content['seo_desc_page'],
                            'published'         => $content['published'],
                            'id_page'           => $id_page,
                            'id_lang'           => $lang
                        )
                    );
                }
            }
            $this->message->json_post_response(true, 'update', $id_page);
        }
    }

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
            $this->modelPlugins->getCoreItem();
        }else{
            if(isset($this->action)) {
                switch ($this->action) {
                    case 'edit':
                        $this->save();
                        break;
                }
            }else{
                $this->modelLanguage->getLanguage();
                $last = $this->setItemData();
                $pages = $this->setItemsData();
                $this->template->assign('home',$last);
                $this->template->assign('page', $pages[$last['id_page']]);
                $this->template->display('home/edit.tpl');
            }
        }
    }

}
?>