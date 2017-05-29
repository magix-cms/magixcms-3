<?php
class backend_controller_language extends backend_db_language{
    public $edit, $action, $tabs;
    protected $message, $template, $header, $data;
    public $search,$default_lang,$id_lang,$active_lang,$iso_lang,$name_lang;
    public function __construct()
    {
        $this->template = new backend_model_template();
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
            $this->id_lang = $formClean->numeric($_POST['id']);
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
    private function setCollection(){
        return $this->arrayTools->defaultLanguage();
    }

    /**
     *
     */
    public function getCollection(){
        $data = $this->setCollection();
        $this->template->assign('getCollection',$data);
    }

    /**
     * @param null $id_lang
     */
    private function getItemsLanguage($id_lang = null){
        if($id_lang) {
            $data = parent::fetchData(array('context'=>'unique','type'=>'lang'),array('id' => $id_lang));
            $this->template->assign('lang',$data);
        }else{
            $this->getItems('langs');
        }
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
                $this->header->set_json_headers();
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
                        $this->header->set_json_headers();
                        $this->message->json_post_response(true,'update',$this->id_lang);
                    }else{
                        $this->getCollection();
                        $this->getItemsLanguage($this->edit);
                        $this->template->display('language/edit.tpl');
                    }
                    break;
            }
        }else{
            $this->getItemsLanguage();
            $this->template->display('language/index.tpl');
        }
    }
}
?>