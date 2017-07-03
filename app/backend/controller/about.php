<?php
class backend_controller_about{

    public $edit, $action, $tabs, $search;

    protected $message, $template, $header, $data, $modelLanguage, $collectionLanguage;

    public function __construct()
    {
        $this->template = new backend_model_template();
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        //$this->data = new backend_model_data($this);
        $formClean = new form_inputEscape();
        $this->modelLanguage = new backend_model_language($this->template);
        $this->collectionLanguage = new component_collections_language();
    }
    public function run(){
        $this->template->display('about/index.tpl');
    }
}
?>