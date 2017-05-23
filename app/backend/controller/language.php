<?php
class backend_controller_language extends backend_db_language{
    public $edit, $action, $tabs;
    protected $message, $template, $header, $data;
    public $iso;
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
            $this->id = $formClean->numeric($_POST['id']);
        }
        if (http_request::isPost('default_lang')) {
            $this->default_lang = $formClean->numeric($_POST['default_lang']);
        }
        if (http_request::isPost('id_lang')) {
            $this->id_lang = $formClean->numeric($_POST['id_lang']);
        }
        if (http_request::isPost('active_lang')) {
            $this->active_lang = $formClean->numeric($_POST['active_lang']);
        }
        if (http_request::isPost('iso_lang')) {
            $this->iso_lang = $formClean->simpleClean($_POST['iso_lang']);
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

    private function setCollection(){
        return $this->arrayTools->defaultLanguage();
    }
    public function getCollection(){
        $data = $this->setCollection();
        $this->template->assign('getCollection',$data);
    }
    private function getItemsLanguage(){
        return $this->getItems('langs');
    }
    public function run(){
        if(isset($this->action)) {
            switch ($this->action) {
                case 'add':
                    $this->getCollection();
                    $this->template->display('language/add.tpl');
                    break;
            }
        }else{
            $this->getItemsLanguage();
            $this->template->display('language/index.tpl');
        }
    }
}
?>