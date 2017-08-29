<?php
class backend_controller_seo extends backend_db_seo {
    public $edit, $action, $tabs;
    protected $message, $template, $header, $data, $dbPlugins,$modelLanguage,$collectionLanguage;
    public function __construct()
    {
        $this->template = new backend_model_template();
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $formClean = new form_inputEscape();
        $this->modelLanguage = new backend_model_language($this->template);
        $this->collectionLanguage = new component_collections_language();
        $this->dbPlugins = new backend_db_plugins();

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
    private function module(){
        $core =  array('catalog','news');
        $plugins = $this->dbPlugins->fetchData(array('context'=>'all','type'=>'seo'));
        foreach($plugins as $key){
            $newData[] = /*'plugins:'.*/$key['name'];
        }
        $newArray = array_merge($core,$newData);
        print '<pre>';
        print_r($newArray);
        print '</pre>';
    }
    public function run(){
        $this->modelLanguage->getLanguage();
        $defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'unique','type'=>'default'));
        $this->getItems('seo',array(':default_lang'=>$defaultLanguage['id_lang']),'all');

        $assign = array(
            'id_seo',
            'attribute_seo' => ['title' => 'name'],
            //'content_pages' => ['class' => 'fixed-td-lg', 'type' => 'bin', 'input' => null],
            'level_seo',
            'type_seo'
        );
        //$this->module();
        $this->data->getScheme(array('mc_seo','mc_seo_content'),array('id_seo','level_seo','attribute_seo','type_seo'),$assign);
        $this->template->display('seo/index.tpl');
    }
}
?>