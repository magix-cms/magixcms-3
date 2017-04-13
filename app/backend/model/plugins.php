<?php
class backend_model_plugins{
    protected $template, $controller_name, $dbPlugins;

    /**
     * backend_model_plugins constructor.
     */
    public function __construct()
    {
        $formClean = new form_inputEscape();
        if(http_request::isGet('controller')){
            $this->controller_name = $formClean->simpleClean($_GET['controller']);
        }
        $this->dbPlugins = new backend_db_plugins();
        //$this->data = new backend_model_data($this);
    }
    private function setItems(){
        return $this->dbPlugins->fetchAll(array('type'=>'list'));
    }
    public function getItems(){
        return $this->setItems();
    }
    /**
     * @param $routes
     * @param $template
     * @param $plugins
     */
    public function templateDir($routes, $template, $plugins){
        if(isset($this->controller_name)){
            $setTemplatePath = component_core_system::basePath().'/'.$routes.'/'.$this->controller_name.'/skin/'.$plugins.'/';
            if(file_exists($setTemplatePath)){
                $template->addTemplateDir($setTemplatePath);
            }
        }
    }
}
?>