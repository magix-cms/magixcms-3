<?php
class backend_controller_snippet extends backend_db_snippet {
    public $edit, $action, $tabs, $search, $controller;
    protected $message, $template, $header, $data, $modelLanguage, $collectionLanguage,
        $config, $routingUrl,$tableaction,$tableform;
    public $id_snippet, $content, $iso, $del_snippet, $ajax;

    public $tableconfig = array(
            'id_snippet',
            'title_sp' => ['title' => 'name'],
            'description_sp' => ['title' => 'name'],
            'content_sp' => ['type' => 'bin', 'input' => null],
            'date_register'
    );
    /**
     * backend_controller_logo constructor.
     * @param null|backend_model_template $t
     */
    public function __construct(backend_model_template $t = null) {
        $this->template = $t instanceof backend_model_template ? $t : new backend_model_template;
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $formClean = new form_inputEscape();
        $this->modelLanguage = new backend_model_language($this->template);
        $this->collectionLanguage = new component_collections_language();
        $this->routingUrl = new component_routing_url();

        // --- GET
        if(http_request::isGet('controller')) $this->controller = $formClean->simpleClean($_GET['controller']);
        if (http_request::isGet('edit')) $this->edit = $formClean->numeric($_GET['edit']);
        if (http_request::isGet('action')) $this->action = $formClean->simpleClean($_GET['action']);
        elseif (http_request::isPost('action')) $this->action = $formClean->simpleClean($_POST['action']);
        if (http_request::isGet('tabs')) $this->tabs = $formClean->simpleClean($_GET['tabs']);
        if (http_request::isGet('offset')) $this->offset = intval($formClean->simpleClean($_GET['offset']));

        if (http_request::isGet('tableaction')) {
            $this->tableaction = $formClean->simpleClean($_GET['tableaction']);
            $this->tableform = new backend_controller_tableform($this,$this->template);
        }

        // --- Search
        if (http_request::isGet('search')) {
            $this->search = $formClean->arrayClean($_GET['search']);
            $this->search = array_filter($this->search, function ($value) { return $value !== ''; });
        }

        // --- ADD or EDIT
        if (http_request::isGet('id')) $this->id_snippet = $formClean->simpleClean($_GET['id']);
        elseif (http_request::isPost('id')) $this->id_snippet = $formClean->simpleClean($_POST['id']);
        if (http_request::isPost('del_snippet')) $this->del_snippet = $formClean->simpleClean($_POST['del_snippet']);

        if (http_request::isPost('content')) {
            $array = $_POST['content'];
            foreach($array as $key => $arr) {
                $array[$key] = ($key == 'content_sp') ? $formClean->cleanQuote($arr) : $formClean->simpleClean($arr);
            }
            $this->content = $array;
        }
        # JSON LINK (TinyMCE)
        if (http_request::isGet('iso')) $this->iso = $formClean->simpleClean($_GET['iso']);
    }
    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param string|int|null $id
     * @param string $context
     * @param boolean $assign
     * @param boolean $pagination
     * @return mixed
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
        $params = array();

        $results = $this->getItems('pages', NULL, 'all',false,true);

        if($ajax) {
            $params['section'] = 'pages';
            $params['idcolumn'] = 'id_snippet';
            $params['activation'] = false;
            $params['sortable'] = false;
            $params['checkbox'] = true;
            $params['edit'] = true;
            $params['dlt'] = true;
            $params['readonly'] = array();
            $params['cClass'] = 'backend_controller_snippet';
        }

        $this->data->getScheme(
            array('mc_snippet'),
            array('id_snippet', 'title_sp', 'description_sp', 'content_sp', 'date_register'),
            $this->tableconfig);

        return array(
            'data' => $results,
            'var' => 'pages',
            'tpl' => 'snippet/index.tpl',
            'params' => $params
        );
    }

    /**
     * Insertion de données
     * @param $data
     */
    private function add($data){
        switch($data['type']){
            case 'page':
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
    private function upd($data)
    {
        switch ($data['type']) {
            case 'page':
                parent::update(
                    array(
                        'type' => $data['type']
                    ),
                    $data['data']
                );
                break;
        }
    }
    /**
     * Insertion de données
     * @param $data
     * @throws Exception
     */
    private function del($data)
    {
        switch($data['type']){
            case 'delPages':
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
     * @param $id
     * @return void
     * @throws Exception
     */
    private function saveContent($id = null)
    {
        //$this->content['id_snippet'] = $this->id_snippet;
        $this->content['title_sp'] = (!empty($this->content['title_sp']) ? $this->content['title_sp'] : NULL);
        $this->content['description_sp'] = (!empty($this->content['description_sp']) ? $this->content['description_sp'] : NULL);
        $this->content['content_sp'] = (!empty($this->content['content_sp']) ? $this->content['content_sp'] : NULL);

        if ($id != null) {
            $this->content['id_snippet'] = $this->id_snippet;
            $contentPage = $this->getItems('page', array('id_snippet' => $id), 'one', false);
            $this->upd(
                array(
                    'type' => 'page',
                    'data' => $this->content
                )
            );
        } else {
            $this->add(
                array(
                    'type' => 'page',
                    'data' => $this->content
                )
            );
        }
    }

    /**
     * @return array
     */
    public function getJsonData(){
        $stData = array();
        $data = $this->getItems('pages', NULL, 'all',false,true);
        if($data != null) {
            foreach ($data as $key) {
                $url = '/admin/index.php?controller=snippet&action=display&id=' . $key['id_snippet'];
                $stData[] = '{' . '"title":"' . $key['title_sp'] . '",' . '"description":"' . $key['description_sp'] . '",' . '"url":"' . $url . '"}';
            }
            return $stData;
        }
    }
    /**
     *
     */
    public function run()
    {
        if (isset($this->tableaction)) {
            $this->tableform->run();
        } elseif (isset($this->action)) {
            switch ($this->action) {
                case 'add':
                    if (isset($this->content)) {
                        $this->saveContent();
                        $this->message->json_post_response(true, 'add_redirect');
                    }
                    else {
                        $this->template->display('snippet/add.tpl');
                    }
                    break;
                case 'edit':
                    if (isset($this->id_snippet)) {
                        $this->saveContent($this->id_snippet);
                        $this->message->json_post_response(true, 'update', $this->content);
                    }
                    else {
                        $setEditData = $this->getItems('page', array('id'=>$this->edit),'one',false);

                        $this->template->assign('page',$setEditData);
                        $this->template->display('snippet/edit.tpl');
                    }
                    break;
                case 'delete':
                    if(isset($this->id_snippet)) {
                        $this->del(
                            array(
                                'type'=>'delPages',
                                'data'=>array(
                                    'id' => $this->id_snippet
                                )
                            )
                        );
                    }
                    break;
                case 'display':
                    if(isset($this->id_snippet)) {
                        $contentPage = $this->getItems('page', array('id' => $this->id_snippet), 'one', false);
                        if($contentPage['content_sp'] != null){
                            print $contentPage['content_sp'];
                        }
                    }
                    break;
            }
        }else{
            $this->getItems('pages',NULL,'all',true,true);
            $this->data->getScheme(
                array('mc_snippet'),
                array('id_snippet', 'title_sp', 'description_sp', 'content_sp', 'date_register'),
                $this->tableconfig);
            $this->template->display('snippet/index.tpl');
        }
    }
}